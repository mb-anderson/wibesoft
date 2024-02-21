<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskMail extends Mailable
{
    use Queueable, SerializesModels;

    public const CREATED = "Created";

    public const UPDATED = "Updated";

    public const DESTROYED = "Destroyed";

    public $task;

    public $operation;

    public function __construct(Task $task, string $operation = TaskMail::CREATED)
    {
        $this->task = $task;
        $this->operation = $operation;
    }

    public function build()
    {
        return $this->subject('Task ' . $this->operation)->view('mail.task-mail');
    }
}