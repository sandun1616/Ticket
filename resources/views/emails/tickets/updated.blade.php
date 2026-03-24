<!DOCTYPE html>
<html>

<head>
    <title>Ticket Status Update</title>
</head>

<body>
    <h1>Ticket #{{ $ticket->ticket_number }} Update</h1>
    <p>Dear {{ $ticket->user->name }},</p>
    <p>The status of your ticket has been updated to: <strong>{{ $ticket->status }}</strong></p>

    @if($ticket->admin_reply)
        <p><strong>Admin Note:</strong></p>
        <p>{{ $ticket->admin_reply }}</p>
    @endif

    <br>
    <p>Thank you,</p>
    <p>ICT Support Team</p>
</body>

</html>