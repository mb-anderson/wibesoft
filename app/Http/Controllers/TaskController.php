<?php

namespace App\Http\Controllers;

use App\Mail\TaskMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:tasks|max:255',
            'subject' => 'required|max:1000',
            'task_date' => 'date',
            "status" => "string",
            'users' => 'required|array',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();
        if ($user?->hasRole('Admin')) {
            $task = Task::create($request->all());
            $task->users()->sync($request->users);
            Mail::to($task->getUserEmails())->send(new TaskMail($task, TaskMail::CREATED));
            return response()->json([
                'message' => 'Task created successfully',
                'task' => $task,
                "task_user" => $task->users()->pluck('users.id')
            ], 201);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function update(Request $request, $task_id)
    {

        $request->validate([
            'title' => 'required|max:255',
            'subject' => 'required|max:1000',
            'task_date' => 'date',
            "status" => "string",
            'users' => 'required|array',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();
        $task = Task::find($task_id);
        if ($user?->hasRole('Admin')) {
            if ($task) {
                $task->update($request->all());
                $task->users()->sync($request->users);
                Mail::to($task->getUserEmails())->send(new TaskMail($task, TaskMail::UPDATED));
                return response()->json([
                    'message' => 'Task updated successfully',
                    'task' => $task,
                    "task_user" => $task->users()->pluck('users.id')
                ], 201);
            } else {
                return response()->json(['error' => 'Task Not Found'], 404);
            }
        } elseif ($user->hasRole('User')) {
            $updatedData = $request->all();
            unset($updatedData["status"]);
            $changedData = array_diff_assoc($updatedData, $task->toArray());
            if (!empty($changedData) && count($changedData) < 2) {
                $task->update(["status" => $request->status]);
                Mail::to($task->getUserEmails())->send(new TaskMail($task, TaskMail::UPDATED));
                return response()->json(['message' => 'Status has been updated'], 200);
            }
            return response()->json(['error' => 'You can update just STATUS, Unauthorized'], 403);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function destroy($task_id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        if ($user?->hasRole('Admin')) {
            $task = Task::find($task_id);
            if ($task) {
                $task_temp = $task;
                $task->delete();
                Mail::to($task->getUserEmails())->send(new TaskMail($task, TaskMail::DESTROYED));
                return response()->json(['message' => 'Task destroyed successfully', "task" => $task], 200);
            } else {
                return response()->json(['error' => 'Task Not Found'], 404);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}
