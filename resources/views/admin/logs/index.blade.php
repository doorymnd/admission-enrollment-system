@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <div class="header-inline">
        <h2>Admin Activity Logs</h2>
    </div>

    <hr>

    <!-- SEARCH -->
    <form method="GET" action="{{ route('admin.logs') }}" id="searchForm" style="margin-bottom: 15px;">
        
        <input type="text"
               id="logSearch"
               name="search"
               placeholder="Search logs..."
               value="{{ request('search') }}"
               style="padding:8px; width:300px;">

        @if(request('search'))
            <a href="{{ route('admin.logs') }}" style="padding:8px 12px; background:#ccc; border-radius:5px; text-decoration:none; margin-left:10px;">
                Clear
            </a>
        @endif

    </form>

    <div style="background:#fff; padding:15px; border-radius:8px;">

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            {{ $log->admin->first_name ?? 'System' }}
                            {{ $log->admin->last_name ?? '' }}
                        </td>

                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ optional($log->created_at)->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">No logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection

{{-- ✅ AUTO SEARCH SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    let input = document.getElementById("logSearch");
    let form = document.getElementById("searchForm");

    let timeout = null;

    input.addEventListener("keyup", function () {

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            form.submit();
        }, 500); // wait 500ms after typing
    });

});
</script>