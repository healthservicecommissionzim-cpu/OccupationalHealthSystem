<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemRestorationController extends Controller
{
    /**
     * Display the system restoration page
     */
    public function index()
    {
        return view('system.restoration');
    }
}
