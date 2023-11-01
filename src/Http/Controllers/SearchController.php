<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Transave\ScolaCbt\Actions\Search\SearchCourses;
use Transave\ScolaCbt\Actions\Search\SearchDepartments;
use Transave\ScolaCbt\Actions\Search\SearchFaculties;
use Transave\ScolaCbt\Actions\Search\SearchOptions;
use Transave\ScolaCbt\Actions\Search\SearchSessions;
use Transave\ScolaCbt\Actions\Search\SearchStudentExams;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\StudentExam;

class SearchController extends Controller
{
    public function __construct()
    {

    }

    public function indexSessions()
    {
        return (new SearchSessions(Session::class, []))->execute();
    }

    public function indexFaculties()
    {
        return (new SearchFaculties(Faculty::class, []))->execute();
    }

    public function indexDepartments()
    {
        return (new SearchDepartments(Department::class, ['faculty']))->execute();
    }

    public function indexCourses()
    {
        return (new SearchCourses(Course::class, []))->execute();
    }

    public function indexQuestionOptions()
    {
        return (new SearchOptions(Option::class, ['question']))->execute();
    }

    public function indexStudentExams()
    {
        return (new SearchStudentExams(StudentExam::class, ['student', 'exam']))->execute();
    }
}