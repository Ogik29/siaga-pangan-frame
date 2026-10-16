<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AnalysisController extends Controller
{
    public function index(): View
    {
        return view('analysis.index');
    }
}
