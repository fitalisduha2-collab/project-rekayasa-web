<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //

    public function index()
    {
        $projects = project::paginate(6);

        return view('page.project', compact('projects'));
    }

    public function show($id)
    {
        $project = project::find0rfail($id);

        return view('page.project-detail', compact('project'));
    }


}