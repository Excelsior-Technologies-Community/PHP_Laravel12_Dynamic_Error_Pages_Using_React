<?php

namespace App\Http\Controllers;

use App\Models\ErrorVisit;

class ErrorDashboardController extends Controller
{
    public function index()
    {
        return view('error-dashboard', [

            'totalErrors' => ErrorVisit::count(),

            'error404' => ErrorVisit::where('status_code', 404)->count(),

            'error403' => ErrorVisit::where('status_code', 403)->count(),

            'error500' => ErrorVisit::where('status_code', 500)->count(),

            'latestErrors' => ErrorVisit::latest('visited_at')
                ->take(15)
                ->get(),

        ]);
    }

    public function stats()
    {
        return response()->json([
            'total' => ErrorVisit::count(),

            'error404' => ErrorVisit::where('status_code', 404)->count(),

            'error403' => ErrorVisit::where('status_code', 403)->count(),

            'error500' => ErrorVisit::where('status_code', 500)->count(),
        ]);
    }
}
