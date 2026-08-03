<?php

namespace App\Http\Controllers;

use App\Models\ErrorVisit;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ErrorDashboardController extends Controller
{
    /**
     * Display dashboard with search, filter and pagination.
     */
    public function index(Request $request)
    {
        $query = ErrorVisit::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('url', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('status_code', $search);
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by Status Code
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where('status_code', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Latest Records with Pagination
        |--------------------------------------------------------------------------
        */

        $latestErrors = $query
            ->oldest('visited_at')
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        return view('error-dashboard', [

            'latestErrors' => $latestErrors,

            'totalErrors' => ErrorVisit::count(),

            'error404' => ErrorVisit::where('status_code', 404)->count(),

            'error403' => ErrorVisit::where('status_code', 403)->count(),

            'error500' => ErrorVisit::where('status_code', 500)->count(),

        ]);
    }

    /**
     * Statistics API
     */
    public function stats()
    {
        return response()->json([

            'total' => ErrorVisit::count(),

            'error404' => ErrorVisit::where('status_code', 404)->count(),

            'error403' => ErrorVisit::where('status_code', 403)->count(),

            'error500' => ErrorVisit::where('status_code', 500)->count(),

        ]);
    }

    /**
     * Export CSV
     */
    public function export()
    {
        $fileName = 'error_logs.csv';

        $headers = [

            'Content-Type' => 'text/csv',

            'Content-Disposition' => "attachment; filename={$fileName}",

        ];

        $callback = function () {

            $file = fopen('php://output', 'w');

            fputcsv($file, [

                'ID',
                'Status Code',
                'URL',
                'IP Address',
                'User Agent',
                'Visited At'

            ]);

            ErrorVisit::latest('visited_at')
                ->chunk(100, function ($errors) use ($file) {

                    foreach ($errors as $error) {

                        fputcsv($file, [

                            $error->id,
                            $error->status_code,
                            $error->url,
                            $error->ip_address,
                            $error->user_agent,
                            $error->visited_at,

                        ]);
                    }
                });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Delete Single Record
     */
    public function destroy($id)
    {
        $error = ErrorVisit::findOrFail($id);

        $error->delete();

        return redirect()
            ->route('error.dashboard')
            ->with('success', 'Error record deleted successfully.');
    }

    /**
     * Delete All Records
     */
    public function deleteAll()
    {
        ErrorVisit::truncate();

        return redirect()
            ->route('error.dashboard')
            ->with('success', 'All error records deleted successfully.');
    }
}
