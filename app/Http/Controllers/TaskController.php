<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function readTasks($todo_list_id) {
        $tasks = Task::where('todo_list_id', $todo_list_id)->get();
        return $tasks;
    }
    
    public function insert() {
        request()->validate([
            'title' => ['required', 'string'],
            'todo_list_id' => ['required']
        ]);

        $taskData = [
            'todo_list_id' => request('todo_list_id'),
            'title' => request('title')
        ];

        $task = Task::create($taskData);
        return $task;
    }

    public function delete($todo_list_id) {
        Task::where('todo_list_id', $todo_list_id)->delete();
        $msg = "Success to delete";
        return [
            'response' => $msg
        ];
    }
}
