<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Error Analytics Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .table {
            background: #fff;
        }

        .table th {
            background: #0d6efd;
            color: #fff;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 14px;
        }

        .search-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
            margin-bottom: 25px;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <h2 class="text-center fw-bold mb-4">

            <i class="fa-solid fa-triangle-exclamation text-danger"></i>

            Error Analytics Dashboard

        </h2>

        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif

        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card text-center">

                    <div class="card-body">

                        <h5>Total Errors</h5>

                        <h2 class="text-primary">

                            {{ $totalErrors }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card text-center">

                    <div class="card-body">

                        <h5>404 Errors</h5>

                        <h2 class="text-danger">

                            {{ $error404 }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card text-center">

                    <div class="card-body">

                        <h5>403 Errors</h5>

                        <h2 class="text-warning">

                            {{ $error403 }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card text-center">

                    <div class="card-body">

                        <h5>500 Errors</h5>

                        <h2 class="text-success">

                            {{ $error500 }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>

        <div class="search-box">

            <form
                action="{{ route('error.dashboard') }}"
                method="GET">

                <div class="row">

                    <div class="col-md-5 mb-2">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search URL, IP or Status"
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-3 mb-2">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">

                                All Status

                            </option>

                            <option
                                value="403"
                                {{ request('status')=='403' ? 'selected' : '' }}>

                                403

                            </option>

                            <option
                                value="404"
                                {{ request('status')=='404' ? 'selected' : '' }}>

                                404

                            </option>

                            <option
                                value="500"
                                {{ request('status')=='500' ? 'selected' : '' }}>

                                500

                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <button
                            class="btn btn-primary">

                            <i class="fa fa-search"></i>

                            Search

                        </button>

                        <a
                            href="{{ route('error.dashboard') }}"
                            class="btn btn-secondary">

                            Reset

                        </a>

                        <a
                            href="{{ route('error.export', request()->query()) }}"
                            class="btn btn-success">

                            <i class="fa fa-download"></i>

                            Export CSV

                        </a>

                    </div>

                </div>

            </form>

            <form
                action="{{ route('error.delete.all') }}"
                method="POST"
                class="mt-3"
                onsubmit="return confirm('Delete all records?')">

                @csrf

                @method('DELETE')

                <button
                    class="btn btn-danger">

                    <i class="fa fa-trash"></i>

                    Delete All

                </button>

            </form>

        </div>

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Status</th>

                                <th>URL</th>

                                <th>IP Address</th>

                                <th>User Agent</th>

                                <th>Visited At</th>

                                <th width="120">

                                    Action

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($latestErrors as $error)

                            <tr>

                                <td>

                                    {{ $error->id }}

                                </td>

                                <td>

                                    @if($error->status_code == 404)

                                    <span class="badge bg-danger">

                                        404

                                    </span>

                                    @elseif($error->status_code == 403)

                                    <span class="badge bg-warning text-dark">

                                        403

                                    </span>

                                    @elseif($error->status_code == 500)

                                    <span class="badge bg-success">

                                        500

                                    </span>

                                    @else

                                    <span class="badge bg-secondary">

                                        {{ $error->status_code }}

                                    </span>

                                    @endif

                                </td>

                                <td>

                                    <small>

                                        {{ $error->url }}

                                    </small>

                                </td>

                                <td>

                                    {{ $error->ip_address }}

                                </td>

                                <td style="max-width:250px;word-break:break-word;">

                                    <small>

                                        {{ $error->user_agent }}

                                    </small>

                                </td>

                                <td>

                                    {{ $error->visited_at }}

                                </td>

                                <td>

                                    <form
                                        action="{{ route('error.delete', $error->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this record?')">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger">

                                            <i class="fa fa-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted py-4">

                                    <i class="fa fa-circle-info"></i>

                                    No error records found.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if ($latestErrors->lastPage() > 1)
                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        <ul class="pagination mb-0">

                            @for ($page = 1; $page <= $latestErrors->lastPage(); $page++)

                                <li class="page-item {{ $page == $latestErrors->currentPage() ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $latestErrors->url($page) }}">
                                        {{ $page }}
                                    </a>
                                </li>

                                @endfor

                        </ul>
                    </nav>
                </div>
                @endif

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>