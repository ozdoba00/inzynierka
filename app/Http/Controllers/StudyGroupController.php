<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Auth;

class StudyGroupController extends Controller
{
    

    public function index()
    {
        $groups= StudyGroup::getUserGroups(Auth::user()->id);

        return $groups;
    }
}
