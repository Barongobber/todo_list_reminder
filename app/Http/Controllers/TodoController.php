<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TodoController extends Controller
{
    public function read() {
        $todo_lists = TodoList::where('user_id', Auth::user()->id)->get();
        return $todo_lists;
    }

    public function readDetail($id) {
        $todo_list = TodoList::where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->first();
        return $todo_list;
    }

    public function insert() {
        request()->validate([
            'title' => ['required', 'string'],
            'reminder' => ['required'],
            'notification' => ['required']
        ]);
        $notif = false;
        if(request('notification') == 'true'){
            $notif = true;
        }

        $check = TodoList::where('title', request('title'))->first();
        if ($check)
            abort(400, 'Sorry, cannot insert the same title as the existing one');
        
        $todoData = [
            'user_id' => Auth::user()->id,
            'title' => request('title'),
            'reminder' => Carbon::parse(request('reminder')),
            'notification' => $notif,
            'is_sent' => false
        ];
        $todo = TodoList::create($todoData);

        return $todo;
    }

    public function update($id) {
        request()->validate([
            'title' => [
                'required', 
                'string',
                Rule::unique('todo_lists')->ignore($id)
            ],
            'reminder' => ['required'],
            'notification' => ['required']
        ]);
        $notif = false;
        if(request('notification') == 'true'){
            $notif = true;
        }

        $todo = TodoList::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $todo->is_sent = false;
        $todo->notification = $notif;
        $todo->save();
        
        $todoData = request()->only([
            'title',
            'reminder'
        ]);

        $todo->update($todoData);

        $response = [
            "todo_list" => [
                'id' => $todo->id,
                'title' => $todo->title,
                'reminder' => Carbon::parse($todo->reminder),
                'notification' => $todo->notification,
                'is_sent' => $todo->is_sent
            ]
        ];

        return $response;
    }

    public function delete($id) {
        TodoList::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->delete();
        $msg = "Success to delete";
        return [
            'response' => $msg
        ];
    }

    public function test(){
        return "test";
    }

    public function sendReminder() {
        $todo_lists = TodoList::all();

        foreach($todo_lists as $val) {
            if ($val['is_sent'] != true || $val['notification'] != true) {
                if (Carbon::now()->greaterThan($val['reminder'])) {
                    $user = User::where('id', $val['user_id'])->first();
                    $todo = TodoList::where('id', $val['id'])->first();
                    $tasks = (new TaskController)->readTasks($val['id']);
                    $todo->is_sent = true;
                    $todo->save();
                    $details = [
                        'title' => $todo->title,
                        'id' => $todo->id,
                        'name' => $user->name,
                        'tasks' => $tasks
                    ];
                    (new MailController)->sendMail($user->email, $details);
                }
            }
        }
        return "email sent";
    }
}
