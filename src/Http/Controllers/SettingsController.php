<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Transave\ScolaCbt\Helpers\ResponseHelper;

class SettingsController extends Controller
{
    use ResponseHelper;
    private $config;
    
    public function __construct()
    {
        $this->config = config('scola-cbt');
    }
    
    public function difficultyLevel()
    {
        $diffLevels = $this->config['difficulty_level'];
        return $this->sendSuccess($diffLevels, 'difficulty levels returned');
    }
}