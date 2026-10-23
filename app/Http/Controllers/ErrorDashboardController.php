<?php

namespace App\Http\Controllers;

use App\Models\BlockedIp;
use App\Models\ErrorReport;
use App\Models\ErrorVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ErrorDashboardController extends Controller
{
    /**
     * Display dashboard with search, filter, analytics charts, and security telemetry.
     */
    public function index(Request $request)
    {
        $query = ErrorVisit::query();

        // 1. Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%")
                    ->orWhere('threat_category', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('status_code', $search);
                }
            });
        }

        // 2. Status Code Filter
        if ($request->filled('status')) {
            $query->where('status_code', $request->status);
        }

        // 3. Threat Level Filter
        if ($request->filled('threat')) {
            $query->where('threat_level', $request->threat);
        }

        // 4. Device Type Filter
        if ($request->filled('device')) {
            $query->where('device_type', $request->device);
        }

        $latestErrors = $query
            ->latest('visited_at')
            ->paginate(8)
            ->withQueryString();

        // Summary KPI Metrics
        $totalErrors = ErrorVisit::count();
        $error404 = ErrorVisit::where('status_code', 404)->count();
        $error403 = ErrorVisit::where('status_code', 403)->count();
        $error500 = ErrorVisit::where('status_code', 500)->count();

        // Security Radar Metrics
        $threatCount = ErrorVisit::whereIn('threat_level', ['suspicious', 'critical'])->count();
        $blockedIpsCount = BlockedIp::count();
        $userReportsCount = ErrorReport::count();

        // Analytics Breakdown: Devices
        $deviceStats = ErrorVisit::selectRaw('COALESCE(device_type, "Desktop") as device, count(*) as count')
            ->groupBy('device')
            ->pluck('count', 'device')
            ->toArray();

        // Analytics Breakdown: Browsers
        $browserStats = ErrorVisit::selectRaw('COALESCE(browser, "Chrome") as browser, count(*) as count')
            ->groupBy('browser')
            ->pluck('count', 'browser')
            ->toArray();

        // Analytics Breakdown: Operating Systems
        $osStats = ErrorVisit::selectRaw('COALESCE(os, "Windows") as os_name, count(*) as count')
            ->groupBy('os_name')
            ->pluck('count', 'os_name')
            ->toArray();

        // Analytics Breakdown: Top Countries
        $countryStats = ErrorVisit::selectRaw('COALESCE(country, "Localhost") as country_name, COALESCE(country_code, "US") as code, count(*) as count')
            ->groupBy('country_name', 'code')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Blocked IPs List
        $blockedIps = BlockedIp::latest('blocked_at')->limit(10)->get();

        // User Incident Reports List
        $userReports = ErrorReport::latest()->limit(10)->get();

        return view('error-dashboard', [
            'latestErrors' => $latestErrors,
            'totalErrors' => $totalErrors,
            'error404' => $error404,
            'error403' => $error403,
            'error500' => $error500,
            'threatCount' => $threatCount,
            'blockedIpsCount' => $blockedIpsCount,
            'userReportsCount' => $userReportsCount,
            'deviceStats' => $deviceStats,
            'browserStats' => $browserStats,
            'osStats' => $osStats,
            'countryStats' => $countryStats,
            'blockedIps' => $blockedIps,
            'userReports' => $userReports,
        ]);
    }

    /**
     * Real-Time Telemetry Live Feed API for real-time polling.
     */
    public function liveFeed(Request $request): JsonResponse
    {
        $afterId = $request->query('after_id', 0);

        $newErrors = ErrorVisit::where('id', '>', $afterId)
            ->latest('id')
            ->limit(10)
            ->get();

        return response()->json([
            'new_count' => $newErrors->count(),
            'latest_id' => ErrorVisit::max('id') ?? 0,
            'total' => ErrorVisit::count(),
            'threats' => ErrorVisit::whereIn('threat_level', ['suspicious', 'critical'])->count(),
            'records' => $newErrors,
        ]);
    }

    /**
     * 1-Click User Incident Report Submission Endpoint (React modal call).
     */
    public function reportBrokenPage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status_code' => 'required|integer',
            'url' => 'required|string',
            'user_message' => 'required|string|max:1000',
            'user_email' => 'nullable|email|max:255',
            'diagnostic_data' => 'nullable|array',
        ]);

        $ticketId = 'ERR-' . strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 6));

        // Find matching recent visit if available
        $recentVisit = ErrorVisit::where('url', $validated['url'])
            ->latest()
            ->first();

        $report = ErrorReport::create([
            'ticket_id' => $ticketId,
            'error_visit_id' => $recentVisit?->id,
            'status_code' => $validated['status_code'],
            'url' => $validated['url'],
            'user_message' => $validated['user_message'],
            'user_email' => $validated['user_email'] ?? null,
            'diagnostic_data' => $validated['diagnostic_data'] ?? [],
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'ticket_id' => $ticketId,
            'message' => 'Your incident report has been registered with ticket ID: ' . $ticketId,
        ]);
    }

    /**
     * Blacklist an IP address from the Security Radar.
     */
    public function blacklistIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|string',
            'reason' => 'nullable|string|max:255',
            'threat_category' => 'nullable|string|max:255',
        ]);

        BlockedIp::updateOrCreate(
            ['ip_address' => $request->ip_address],
            [
                'reason' => $request->reason ?? 'Manual security block from dashboard',
                'threat_category' => $request->threat_category ?? 'Malicious Probe',
                'blocked_by' => 'Security Admin',
                'blocked_at' => now(),
            ]
        );

        return redirect()
            ->route('error.dashboard')
            ->with('success', "IP address {$request->ip_address} has been successfully added to the security blacklist.");
    }

    /**
     * Unblock an IP address.
     */
    public function unblockIp($id)
    {
        $blocked = BlockedIp::findOrFail($id);
        $ip = $blocked->ip_address;
        $blocked->delete();

        return redirect()
            ->route('error.dashboard')
            ->with('success', "IP address {$ip} has been unblocked.");
    }

    /**
     * Mark an incident report as resolved or in-progress.
     */
    public function updateReportStatus(Request $request, $id)
    {
        $report = ErrorReport::findOrFail($id);
        $status = $request->input('status', 'resolved');
        $report->update(['status' => $status]);

        return redirect()
            ->route('error.dashboard')
            ->with('success', "Report #{$report->ticket_id} updated to {$status}.");
    }

    /**
     * Statistics API
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total' => ErrorVisit::count(),
            'error404' => ErrorVisit::where('status_code', 404)->count(),
            'error403' => ErrorVisit::where('status_code', 403)->count(),
            'error500' => ErrorVisit::where('status_code', 500)->count(),
            'threats' => ErrorVisit::whereIn('threat_level', ['suspicious', 'critical'])->count(),
            'blocked' => BlockedIp::count(),
        ]);
    }

    /**
     * Export CSV with enriched Security and Device Telemetry.
     */
    public function export(): StreamedResponse
    {
        $fileName = 'error_telemetry_logs_' . date('Y-m-d_His') . '.csv';

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
                'Threat Level',
                'Threat Category',
                'Device Type',
                'Browser',
                'Operating System',
                'Country',
                'User Agent',
                'Visited At',
            ]);

            ErrorVisit::latest('visited_at')
                ->chunk(100, function ($errors) use ($file) {
                    foreach ($errors as $error) {
                        fputcsv($file, [
                            $error->id,
                            $error->status_code,
                            $error->url,
                            $error->ip_address,
                            strtoupper($error->threat_level ?? 'SAFE'),
                            $error->threat_category ?? 'Normal Mistake',
                            $error->device_type ?? 'Desktop',
                            $error->browser ?? 'Chrome',
                            $error->os ?? 'Windows',
                            $error->country ?? 'Localhost',
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
            ->with('success', 'All error telemetry records deleted successfully.');
    }
}
