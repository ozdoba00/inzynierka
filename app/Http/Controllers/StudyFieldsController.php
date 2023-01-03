<?php

namespace App\Http\Controllers;

use App\Models\FieldOfStudy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudyFieldsController extends Controller
{
    public function index()
    {
        $fields = FieldOfStudy::getStudyFieldsByUser(Auth::user()->id);

        return $fields;
    }
}
