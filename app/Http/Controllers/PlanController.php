<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
                    ->orderBy('price', 'asc')
                    ->get();
                    
        return view('plans.index', compact('plans'));
    }
}
