<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Display revenue reports.
     */
    public function revenue()
    {
        return view('admin.reports.revenue');
    }

    /**
     * Display users reports.
     */
    public function users()
    {
        return view('admin.reports.users');
    }

    /**
     * Display churn reports.
     */
    public function churn()
    {
        return view('admin.reports.churn');
    }

    /**
     * Display LTV reports.
     */
    public function ltv()
    {
        return view('admin.reports.ltv');
    }

    /**
     * Display MRR reports.
     */
    public function mrr()
    {
        return view('admin.reports.mrr');
    }

    /**
     * Display conversion reports.
     */
    public function conversion()
    {
        return view('admin.reports.conversion');
    }

    /**
     * Export reports.
     */
    public function export($type)
    {
        return response()->download('path/to/export.csv');
    }
}
