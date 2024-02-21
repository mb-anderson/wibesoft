<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $operation }}</title>
</head>
<body>
    <h1>Task {{ $operation }}</h1>
    <p>Task {{ $operation }} with the following details:</p>
    <ul>
        <li><strong>Title:</strong> {{ $task->title }}</li>
        <li><strong>Subject:</strong> {{ $task->subject }}</li>
        <li><strong>Task Date:</strong> {{ $task->task_date }}</li>
        <li><strong>Status:</strong> {{ $task->status }}</li>
        <li>
            <strong>Assigned Users:</strong>
            <ul>
                @foreach($task->users as $user)
                    <li>{{ $user->name }}</li>
                @endforeach
            </ul>
        </li>
    </ul>
</body>
</html>