<!DOCTYPE html>
<html>

<head>
    <title>New Ticket Submitted</title>
</head>

<body>
    <h1>New Ticket #{{ $ticket->ticket_number }}</h1>
    <p><strong>Department:</strong> {{ $ticket->department }}</p>
    <p><strong>User:</strong> {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
    <p><strong>Device:</strong> {{ $ticket->device_name }}</p>
    <p><strong>Description:</strong></p>
    <p>{{ $ticket->problem_description }}</p>
    <br>
    <a href="{{ route('tickets.show', $ticket->id) }}">Manage Ticket</a>
</body>

</html>