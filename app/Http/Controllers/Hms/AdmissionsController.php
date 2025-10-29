<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdmissionsController extends Controller
{
    public function index(): View
    {
        return view('hms.admissions.index');
    }
}


