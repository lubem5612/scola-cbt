<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Transave\ScolaCbt\Actions\Analytics\GenerateReport;

class AnalyticController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function report()
    {
        return (new GenerateReport())->execute();
    }
}