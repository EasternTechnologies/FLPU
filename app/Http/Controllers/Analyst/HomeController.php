<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\models\analyst\monthly\Monthlyreport;
use App\models\analyst\weekly\Weeklyreport;
use App\ReportType;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct () {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
        $title          = ReportType::find(1)->title;
        $week_articles  = Weeklyreport::latest()->take(3);
        $month_articles = Monthlyreport::latest()->take(3);

        return view('user.home', compact('title', 'week_articles', 'month_articles'));
    }

}
