<?php


namespace Transave\ScolaCbt\Helpers;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHelper
{
    public static $FILE_SIZE = 0;
    public static $UPLOADED_PATH = "";
    public static $MIME_TYPE = "";
    public static $IS_UPLOADED = false;
    public static $MESSAGE = "";
    public static $ERROR = null;
    private static $DRIVER = 'azure';
    private static $DEFAULTCONFIG = [];
    private static $BASE_STORAGE_PATH = '';
    private static $STORAGE_ID = '';

    public static function UploadFile(UploadedFile $file, $folder)
    {
        try{
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid().'.'.$extension;
            self::setDefaultConfigurations();

            $path = $file->storePubliclyAs($folder, $filename, self::$DRIVER);
            if ($path) {
                $uploadUrl = self::$BASE_STORAGE_PATH;
                if (self::$DRIVER == 'azure' && env('AZURE_STORAGE_PREFIX')) {
                    $uploadUrl = $uploadUrl.'/'.env('AZURE_STORAGE_PREFIX');
                }
                $uploadUrl = $uploadUrl.'/'.$path;
                
                self::$FILE_SIZE = $file->getSize();
                self::$MIME_TYPE = $extension;
                self::$UPLOADED_PATH = $uploadUrl;
                self::$IS_UPLOADED = true;
                self::$MESSAGE = "upload successful";
            }
        }catch (\Exception $exception) {
            self::$MESSAGE = $exception->getMessage();
            self::$ERROR = $exception->getTrace();
        }
        return self::response();
    }

    public static function UploadOrReplaceFile(UploadedFile $file,  $folder, $model,  $column)
    {
        try{
            if($model->$column) {
                $deleteAction = self::DeleteFile($model->$column);
                if (!$deleteAction["success"]) {
                    self::$MESSAGE = "unable to delete existing file";
                    return self::response();
                }
            }

            self::UploadFile($file, $folder);
            self::$MESSAGE = $model->$column? "file replaced successfully" : "file upload successful";

        }catch (\Exception $exception) {
            self::$MESSAGE = $exception->getMessage();
            self::$ERROR = $exception->getTrace();
        }
        return self::response();
    }

    public static function DeleteFile($file_url)
    {
        try{
            self::setDefaultConfigurations();
    
            Storage::disk(self::$DRIVER)->delete(self::getFilePath($file_url));
            self::$IS_UPLOADED = true;

        }catch (\Exception $exception) {
            self::$IS_UPLOADED = false;
            self::$MESSAGE = $exception->getMessage();
            self::$ERROR = $exception->getTrace();
        }
        return self::response();
    }

    private static function getFilePath($file_url)
    {
        if (self::$DRIVER == 'azure' && env('AZURE_STORAGE_PREFIX')) {
            return Str::after($file_url, env('AZURE_STORAGE_PREFIX').'/');
        }
        return Str::after($file_url, self::$BASE_STORAGE_PATH);
    }
    
    private static function setDefaultConfigurations ()
    {
        $driver = config('scola-cbt.file_storage.default_disk');
        self::$DRIVER = $driver;
        self::$DEFAULTCONFIG = config("scola-cbt.file_storage.disks.${$driver}");
        self::$BASE_STORAGE_PATH = self::$DEFAULTCONFIG['storage_url'];
        self::$STORAGE_ID = self::$DEFAULTCONFIG['id'];
    }

    private static function response()
    {
        return [
            "success"       => self::$IS_UPLOADED,
            "upload_url"    => self::$UPLOADED_PATH,
            "mime_type"     => self::$MIME_TYPE,
            "size"          => self::$FILE_SIZE,
            "message"       => self::$MESSAGE,
            "errors"        => self::$ERROR,
        ];
    }

}