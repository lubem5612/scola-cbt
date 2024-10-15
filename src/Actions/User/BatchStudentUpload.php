<?php


namespace Transave\ScolaCbt\Actions\User;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Imports\StudentImport;

class BatchStudentUpload
{
    use ResponseHelper, ValidationHelper;
    private $request, $validatedData, $records = [], $successful = [], $failed = [];

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->getExcelData()
                ->batchCreation();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function getExcelData()
    {
        $this->records = Excel::toArray(new StudentImport(), $this->validatedData['file'])[0];
        return $this;
    }

    private function createUser($record)
    {
        $this->validate($record, [
            'email' => 'sometimes|required|unique:fc_users,email',
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'phone' => 'required|string|max:16|min:9',
        ]);
        
        $email = $this->setUserEmail($record);
        
        return config('scola-cbt.auth_model')::query()->create([
            'email' => $email,
            'first_name' => $record['first_name'],
            'last_name' => $record['last_name'],
            'telephone' => $record['phone'],
            'password' => bcrypt('secret'),
            'is_verified' => 1,
            'role' => 'student',
        ]);
    }

    private function createStudent($record)
    {
        $record['registration_number'] = $this->setRegistrationNumber($record);
        if (Arr::exists($this->validatedData, 'department_id') && $this->validatedData['department_id']) {
            $record['department_id'] = $this->validatedData['department_id'];
        }
        return Student::query()->create($record);
    }

    private function batchCreation()
    {
        foreach ($this->records as $record) {
            DB::beginTransaction();
            $user = $this->createUser($record);
            if (!empty($user)) {
                $record['user_id'] = $user->id;
                $this->createStudent($record);
                array_push($this->successful, $record);
            }else {
                array_push($this->failed, $record);
                continue;
            }
            DB::commit();
        }

        return $this->sendSuccess(['uploaded'=>$this->successful, 'failed'=>$this->failed], 'batch upload executed');
    }
    
    private function setRegistrationNumber($record)
    {
        if (!Arr::exists($record, 'registration_number')) {
            return 'SCBT-'.rand(200000, 999999);
        }
        return $record['registration_number'];
    }
    
    private function setUserEmail($record)
    {
        if (!Arr::exists($record, 'email')) {
            return $record['first_name'].strtolower(Str::random(5)).'@scolacbt.com';
        }
        return $record['email'];
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            "department_id" => "sometimes|required|exists:cbt_departments,id",
            "file" => "required|file|max:5000"
        ]);
        return $this;
    }
}