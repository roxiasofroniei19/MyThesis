<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use App\Models\Schedule;

class PageController extends Controller
{
    public function index() {
        return Inertia::render('Dashboard');
    }

    public function customers() {
        return Inertia::render('Customers');
    }

    public function schedules() {
        return Inertia::render('Schedules');
    }

    public function admin() {
        if (Auth::user()->isAdmin)
            return Inertia::render('Admin');
        else 
            return Inertia::render('Dashboard');
    }
}
