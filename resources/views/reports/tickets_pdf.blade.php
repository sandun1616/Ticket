<!DOCTYPE html>
<html>

<head>
    <title>Ticket Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            bg-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .status-open {
            color: red;
        }

        .status-progress {
            color: orange;
        }

        .status-closed {
            color: green;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Ticket Dashboard Report</h1>
        <p>Generated on: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ticket #</th>
                <th>Department</th>
                <th>Fault</th>
                <th>Submitter</th>
                <th>Device</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->department }}</td>
                        <td>{{ $ticket->fault }}</td>
                        <td>{{ $ticket->username }}<br><small>{{ $ticket->email }}</small></td>
                        <td>{{ $ticket->device_name }}</td>
                        <td
                            class="{{ $ticket->status === 'Open' ? 'status-open' : ($ticket->status === 'In Progress' ? 'status-progress' : 'status-closed') }}">
                            {{ $ticket->status }}
                        </td>
                        <td>{{ $ticket->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            {{ ($ticket->status === 'Closed' && $ticket->closed_at)
                ? $ticket->created_at->diffForHumans($ticket->closed_at, ['parts' => 2, 'join' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE])
                : 'N/A' }}
                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>