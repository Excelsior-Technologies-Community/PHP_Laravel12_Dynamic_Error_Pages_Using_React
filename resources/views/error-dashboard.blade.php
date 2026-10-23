<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Error Analytics & Security Radar Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #0f172a;
            color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .card {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            background: #1e293b;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            color: #f8fafc;
        }

        .table-dark-custom {
            background: #1e293b;
            color: #e2e8f0;
        }

        .table-dark-custom th {
            background: #0f172a;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.08);
        }

        .table-dark-custom td {
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13px;
        }

        .nav-tabs .nav-link {
            color: #94a3b8;
            border: none;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            color: #38bdf8;
            background: transparent;
            border-bottom: 3px solid #38bdf8;
        }

        .pulse-live {
            width: 10px;
            height: 10px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse-ring 1.8s infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid px-4 py-4">

        {{-- Top Header & Simulation Controls --}}
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="pulse-live"></span>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">
                        LIVE TELEMETRY STREAM
                    </span>
                    <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-50">
                        Laravel 12 + React 18
                    </span>
                </div>
                <h3 class="fw-bold mb-0 text-white">
                    <i class="fa-solid fa-radar text-info me-2"></i> Error Telemetry & Security Radar
                </h3>
            </div>

            {{-- Quick Simulation & Action Buttons --}}
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-info dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-flask me-1"></i> Trigger Test Error
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark shadow">
                        <li><a class="dropdown-item" href="{{ route('test.404') }}" target="_blank"><i class="fa-solid fa-link-slash text-danger me-2"></i> Test 404 (Missing)</a></li>
                        <li><a class="dropdown-item" href="{{ route('test.403') }}" target="_blank"><i class="fa-solid fa-shield-halved text-warning me-2"></i> Test 403 (Forbidden)</a></li>
                        <li><a class="dropdown-item" href="{{ route('test.500') }}" target="_blank"><i class="fa-solid fa-server text-danger me-2"></i> Test 500 (Exception)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/.env" target="_blank"><i class="fa-solid fa-bug text-danger me-2"></i> Simulate Malicious .env Probe</a></li>
                        <li><a class="dropdown-item" href="/wp-admin" target="_blank"><i class="fa-solid fa-skull text-danger me-2"></i> Simulate Bot wp-admin Scan</a></li>
                    </ul>
                </div>

                <a href="{{ route('error.export') }}" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-csv me-1"></i> Export Telemetry CSV
                </a>

                <form action="{{ route('error.delete.all') }}" method="POST" class="d-inline" onsubmit="return confirm('Clear all error telemetry records?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fa-solid fa-trash-can me-1"></i> Truncate Logs
                    </button>
                </form>

                <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-home me-1"></i> Home
                </a>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-25 text-white border-success">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Real-Time Dynamic Toast Container --}}
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
            <div id="liveTelemetryToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid fa-bell me-2"></i> <span id="toastMessage">New incoming error detected!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        {{-- KPI Cards Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-bold">TOTAL VISITS</span>
                        <i class="fa-solid fa-globe text-primary"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0 text-white" id="kpiTotal">{{ $totalErrors }}</h3>
                    <span class="text-secondary small" style="font-size: 11px;">Tracked HTTP events</span>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-bold">404 NOT FOUND</span>
                        <i class="fa-solid fa-compass text-info"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0 text-info">{{ $error404 }}</h3>
                    <span class="text-secondary small" style="font-size: 11px;">Broken / Missing URLs</span>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-bold">403 FORBIDDEN</span>
                        <i class="fa-solid fa-lock text-warning"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0 text-warning">{{ $error403 }}</h3>
                    <span class="text-secondary small" style="font-size: 11px;">Access restrictions</span>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-bold">500 EXCEPTIONS</span>
                        <i class="fa-solid fa-server text-danger"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0 text-danger">{{ $error500 }}</h3>
                    <span class="text-secondary small" style="font-size: 11px;">Server crashes</span>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="card p-3 h-100" style="background: linear-gradient(135deg, #1e293b, #2e1065);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-warning small fw-bold">SECURITY THREATS</span>
                        <i class="fa-solid fa-shield-virus text-danger"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0 text-warning" id="kpiThreats">{{ $threatCount }}</h3>
                    <span class="text-warning text-opacity-75 small" style="font-size: 11px;">Exploit probes intercepted</span>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="card p-3 h-100" style="background: linear-gradient(135deg, #1e293b, #042f2e);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success small fw-bold">USER REPORTS</span>
                        <i class="fa-solid fa-envelope-open-text text-success"></i>
                    </div>
                    <h3 class="fw-bold mt-2 mb-0 text-success">{{ $userReportsCount }}</h3>
                    <span class="text-success text-opacity-75 small" style="font-size: 11px;">User feedback tickets</span>
                </div>
            </div>
        </div>

        {{-- Analytics & Charts Row --}}
        <div class="row g-3 mb-4">
            {{-- Device Breakdown Chart --}}
            <div class="col-lg-4">
                <div class="card p-3 h-100">
                    <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-mobile-screen me-2 text-info"></i> Device Distribution
                    </h6>
                    <div style="height: 180px; position: relative;">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Browser Breakdown Chart --}}
            <div class="col-lg-4">
                <div class="card p-3 h-100">
                    <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-window-restore me-2 text-primary"></i> Browser Analytics
                    </h6>
                    <div style="height: 180px; position: relative;">
                        <canvas id="browserChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top Geo Origins --}}
            <div class="col-lg-4">
                <div class="card p-3 h-100">
                    <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-earth-americas me-2 text-success"></i> Geo Origins & Intrusion Radar
                    </h6>
                    <div class="list-group list-group-flush bg-transparent">
                        @forelse($countryStats as $country)
                        <div class="list-group-item bg-transparent text-white px-0 py-2 d-flex justify-content-between align-items-center border-secondary border-opacity-25">
                            <div>
                                <span class="badge bg-secondary me-2">{{ $country->code }}</span>
                                <span class="fw-medium">{{ $country->country_name }}</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $country->count }}</span>
                        </div>
                        @empty
                        <div class="text-secondary small text-center py-4">No geographic records yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav Tabs: Error Visits Log | Blacklisted IPs | User Incident Reports --}}
        <ul class="nav nav-tabs mb-3 border-secondary border-opacity-25" id="dashboardTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logsTabPane">
                    <i class="fa-solid fa-list-check me-2"></i> Telemetry Logs ({{ $latestErrors->total() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reportsTabPane">
                    <i class="fa-solid fa-headset me-2"></i> User Incident Tickets ({{ $userReportsCount }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="blacklist-tab" data-bs-toggle="tab" data-bs-target="#blacklistTabPane">
                    <i class="fa-solid fa-ban me-2 text-danger"></i> Blacklisted IPs ({{ $blockedIpsCount }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="dashboardTabsContent">

            {{-- Tab 1: Telemetry Logs --}}
            <div class="tab-pane fade show active" id="logsTabPane">
                
                {{-- Search & Filter Toolbar --}}
                <div class="card p-3 mb-3">
                    <form method="GET" action="{{ route('error.dashboard') }}" class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Search URL, IP, Threat Category...">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary">
                                <option value="">Status: All</option>
                                <option value="404" {{ request('status') == '404' ? 'selected' : '' }}>404 Not Found</option>
                                <option value="403" {{ request('status') == '403' ? 'selected' : '' }}>403 Forbidden</option>
                                <option value="500" {{ request('status') == '500' ? 'selected' : '' }}>500 Server Error</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="threat" class="form-select form-select-sm bg-dark text-white border-secondary">
                                <option value="">Threat: All</option>
                                <option value="safe" {{ request('threat') == 'safe' ? 'selected' : '' }}>Safe (Normal)</option>
                                <option value="suspicious" {{ request('threat') == 'suspicious' ? 'selected' : '' }}>Suspicious Probe</option>
                                <option value="critical" {{ request('threat') == 'critical' ? 'selected' : '' }}>Critical Attack</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="device" class="form-select form-select-sm bg-dark text-white border-secondary">
                                <option value="">Device: All</option>
                                <option value="Desktop" {{ request('device') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="Mobile" {{ request('device') == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="Tablet" {{ request('device') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Bot" {{ request('device') == 'Bot' ? 'selected' : '' }}>Bot / Scanner</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Filter</button>
                            <a href="{{ route('error.dashboard') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="card overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0" id="errorsTable">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Target URL</th>
                                    <th>Security Threat Radar</th>
                                    <th>Client Telemetry</th>
                                    <th>IP Address & Location</th>
                                    <th>Timestamp</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="telemetryTableBody">
                                @forelse($latestErrors as $error)
                                <tr id="row-{{ $error->id }}">
                                    <td>
                                        @if($error->status_code == 404)
                                            <span class="badge bg-info text-dark fw-bold">404</span>
                                        @elseif($error->status_code == 403)
                                            <span class="badge bg-warning text-dark fw-bold">403</span>
                                        @elseif($error->status_code == 500)
                                            <span class="badge bg-danger fw-bold">500</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $error->status_code }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 280px;" title="{{ $error->url }}">
                                            <code>{{ $error->url }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        @if($error->threat_level === 'critical')
                                            <span class="badge bg-danger text-white">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i> CRITICAL
                                            </span>
                                            <div class="small text-danger fw-semibold mt-1">{{ $error->threat_category ?? 'Attack Signature' }}</div>
                                        @elseif($error->threat_level === 'suspicious')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fa-solid fa-eye me-1"></i> SUSPICIOUS
                                            </span>
                                            <div class="small text-warning mt-1">{{ $error->threat_category ?? 'Probe Attempt' }}</div>
                                        @else
                                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">
                                                <i class="fa-solid fa-check me-1"></i> SAFE
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <i class="fa-solid {{ $error->device_type === 'Mobile' ? 'fa-mobile-screen' : ($error->device_type === 'Bot' ? 'fa-robot text-danger' : 'fa-laptop') }} me-1 text-secondary"></i>
                                            {{ $error->device_type ?? 'Desktop' }}
                                        </div>
                                        <div class="small text-secondary">
                                            {{ $error->browser ?? 'Browser' }} on {{ $error->os ?? 'OS' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-info">{{ $error->ip_address ?? '127.0.0.1' }}</span>
                                        <div class="small text-secondary">
                                            <span class="badge bg-secondary bg-opacity-50 me-1">{{ $error->country_code ?? 'US' }}</span>
                                            {{ $error->country ?? 'Localhost' }}
                                        </div>
                                    </td>
                                    <td class="small text-secondary">
                                        {{ $error->visited_at ? $error->visited_at->diffForHumans() : 'Just now' }}
                                    </td>
                                    <td class="text-end">
                                        {{-- Blacklist button trigger modal --}}
                                        <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2" title="Blacklist IP" onclick="openBlacklistModal('{{ $error->ip_address }}', '{{ $error->threat_category }}')">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>

                                        <form action="{{ route('error.delete', $error->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm p-1 px-2">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-secondary">No error telemetry logs recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($latestErrors->hasPages())
                    <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                        {{ $latestErrors->links() }}
                    </div>
                    @endif
                </div>

            </div>

            {{-- Tab 2: User Incident Tickets --}}
            <div class="tab-pane fade" id="reportsTabPane">
                <div class="card overflow-hidden">
                    <div class="card-header bg-dark border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                        <span class="fw-bold">🚨 1-Click User Incident Reports</span>
                        <span class="badge bg-primary">{{ count($userReports) }} Recent Tickets</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Status Code</th>
                                    <th>Failing URL</th>
                                    <th>User Feedback Description</th>
                                    <th>Reporter Email</th>
                                    <th>Diagnostics</th>
                                    <th>Resolution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userReports as $report)
                                <tr>
                                    <td>
                                        <span class="badge bg-info text-dark fw-bold">{{ $report->ticket_id }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $report->status_code }}</span>
                                    </td>
                                    <td>
                                        <code class="text-truncate d-inline-block" style="max-width: 200px;">{{ $report->url }}</code>
                                    </td>
                                    <td>
                                        <div class="text-white">{{ $report->user_message }}</div>
                                    </td>
                                    <td>
                                        <span class="text-secondary small">{{ $report->user_email ?: 'Anonymous User' }}</span>
                                    </td>
                                    <td>
                                        @if($report->diagnostic_data)
                                            <span class="badge bg-dark border border-secondary text-secondary" style="font-size: 11px;">
                                                Viewport: {{ $report->diagnostic_data['viewport'] ?? 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->status === 'resolved')
                                            <span class="badge bg-success">Resolved</span>
                                        @else
                                            <form action="{{ route('error.report.status', $report->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fa-solid fa-check me-1"></i> Resolve
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-secondary">No user incident reports submitted yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Blacklisted IPs --}}
            <div class="tab-pane fade" id="blacklistTabPane">
                <div class="card overflow-hidden">
                    <div class="card-header bg-dark border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-ban text-danger me-2"></i> Active Security Blacklist</span>
                        <button type="button" class="btn btn-sm btn-danger fw-bold" onclick="openBlacklistModal('', '')">
                            <i class="fa-solid fa-plus me-1"></i> Manual IP Ban
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Threat Category</th>
                                    <th>Reason</th>
                                    <th>Blocked By</th>
                                    <th>Blocked At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blockedIps as $blocked)
                                <tr>
                                    <td><span class="fw-bold text-danger">{{ $blocked->ip_address }}</span></td>
                                    <td><span class="badge bg-warning text-dark">{{ $blocked->threat_category ?: 'Malicious Probe' }}</span></td>
                                    <td class="text-secondary small">{{ $blocked->reason }}</td>
                                    <td class="text-secondary small">{{ $blocked->blocked_by }}</td>
                                    <td class="text-secondary small">{{ $blocked->blocked_at ? $blocked->blocked_at->diffForHumans() : 'N/A' }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('error.unblock', $blocked->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-success btn-sm">
                                                <i class="fa-solid fa-unlock me-1"></i> Unblock
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-secondary">No IP addresses currently blacklisted.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Blacklist IP Modal --}}
    <div class="modal fade" id="blacklistModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-danger">
                        <i class="fa-solid fa-ban me-2"></i> Blacklist IP Address
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('error.blacklist') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">IP Address</label>
                            <input type="text" id="modalIpInput" name="ip_address" class="form-control bg-secondary bg-opacity-25 text-white border-secondary" required placeholder="e.g. 192.168.1.100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Threat Category</label>
                            <input type="text" id="modalCategoryInput" name="threat_category" class="form-control bg-secondary bg-opacity-25 text-white border-secondary" placeholder="e.g. Admin / Backdoor Probe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Block Reason</label>
                            <textarea name="reason" rows="2" class="form-control bg-secondary bg-opacity-25 text-white border-secondary" placeholder="Explain the security violation..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">
                            <i class="fa-solid fa-shield-halved me-1"></i> Confirm IP Blacklist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Blacklist Modal Helper
        function openBlacklistModal(ip, category) {
            document.getElementById('modalIpInput').value = ip;
            document.getElementById('modalCategoryInput').value = category || 'Automated Vulnerability Scanner';
            const modal = new bootstrap.Modal(document.getElementById('blacklistModal'));
            modal.show();
        }

        // 2. Chart.js Device Breakdown Donut
        const deviceData = @json($deviceStats);
        const deviceLabels = Object.keys(deviceData).length > 0 ? Object.keys(deviceData) : ['Desktop', 'Mobile'];
        const deviceValues = Object.keys(deviceData).length > 0 ? Object.values(deviceData) : [1, 0];

        new Chart(document.getElementById('deviceChart'), {
            type: 'doughnut',
            data: {
                labels: deviceLabels,
                datasets: [{
                    data: deviceValues,
                    backgroundColor: ['#38bdf8', '#818cf8', '#f43f5e', '#facc15'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#94a3b8', boxWidth: 12 } }
                }
            }
        });

        // 3. Chart.js Browser Breakdown Donut
        const browserData = @json($browserStats);
        const browserLabels = Object.keys(browserData).length > 0 ? Object.keys(browserData) : ['Chrome', 'Firefox', 'Safari'];
        const browserValues = Object.keys(browserData).length > 0 ? Object.values(browserData) : [1, 0, 0];

        new Chart(document.getElementById('browserChart'), {
            type: 'doughnut',
            data: {
                labels: browserLabels,
                datasets: [{
                    data: browserValues,
                    backgroundColor: ['#22c55e', '#3b82f6', '#ec4899', '#f97316', '#a855f7'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#94a3b8', boxWidth: 12 } }
                }
            }
        });

        // 4. Real-Time Telemetry Stream Polling (every 4 seconds)
        let lastErrorId = {{ $latestErrors->first()?->id ?? 0 }};

        setInterval(async () => {
            try {
                const response = await fetch(`/error-live-feed?after_id=${lastErrorId}`);
                if (!response.ok) return;

                const data = await response.json();

                if (data.new_count > 0) {
                    lastErrorId = data.latest_id;

                    // Update KPI counters
                    const totalEl = document.getElementById('kpiTotal');
                    if (totalEl) totalEl.textContent = data.total;

                    const threatsEl = document.getElementById('kpiThreats');
                    if (threatsEl) threatsEl.textContent = data.threats;

                    // Trigger toast notification
                    const toastMsg = document.getElementById('toastMessage');
                    if (toastMsg) {
                        toastMsg.textContent = `🚨 ${data.new_count} new error visit(s) captured by Radar!`;
                    }
                    const toast = new bootstrap.Toast(document.getElementById('liveTelemetryToast'));
                    toast.show();
                }
            } catch (err) {
                // Ignore transient network errors
            }
        }, 4000);
    </script>

</body>

</html>
