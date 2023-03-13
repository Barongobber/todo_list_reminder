@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <div class="row" style="padding: 5px;">
        <div class="d-sm-flex justify-content-between align-items-center">
            <h3 class="text-dark">Todo-List Info</h3>
            <div class="d-none d-sm-inline-block">
                <button class="btn btn-primary btn-sm" type="button" id="create_button">Create Todo-List</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">

                <h2 class="col-md-6">Build your Todo-List</h2>
                <h2 class="col-md-6 text-end">
                    <i class='fas fa-bell' style='font-size:35px;color:rgb(44, 44, 146)'></i>
                    <label class="switch">
                        <input id="notif" type="checkbox" for='notif' value="true">
                        <span class="slider round"></span>
                    </label>
                </h2>
            </div>
            <hr>
            <form id="create_todo">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control col-md-4" name="todo_title" id="todo_title" width="10%" placeholder="Insert your todo list title here">
                    </div>
                    <div class="col-md-6">
                        <input type="datetime-local" class="form-control col-md-4" name="todo_reminder" id="todo_reminder" width="10%">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Milestone/Task</th>
                            </tr>
                        </thead>
                        <tbody id="task_list">
                            <tr>
                                <td>1</td>
                                <td><input name="task[]" class="form-control" type="text"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input name="task[]" class="form-control" type="text"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="text-end">
                <button class="btn btn-danger btn-sm p-2" id="add_row" ><i class='fas fa-plus'></i> Task Row</button>
            </div>
        </div>
    </div>
</div>
<script> 
    var i = 3;
    var isSet = false;
    $("#add_row").click(function (e) { 
        $("#task_list").append(
            '<tr>' +
                '<td>' + i + '</td>' +
                '<td><input name="task[]" class="form-control" type="text"></td>' +
            '</tr>'
        );
        i++;
    });

    $("#notif").change(function (e) { 
        e.preventDefault();
        if (isSet == true) {
            isSet = false;
        } else {
            isSet = true;
        }
    });

    $('#create_button').click(function (e) { 
        var todo_list_title = $('#todo_title').val();
        var todo_list_reminder = $('#todo_reminder').val();
        var tasks = $("input[name='task[]']").map(function()
        {
            return $(this).val();
        }).get();
        $.ajax({
            type: "POST",
            url: "../api/todo-list/insert",
            data: {
                title: todo_list_title,
                reminder : todo_list_reminder,
                notification : isSet
            },
            dataType: "json",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization',
                    'Bearer {{ Auth::user()->api_token }}');
            },
            success: function (response) {
                $.each(tasks, function (key, value) { 
                    $.ajax({
                        type: "POST",
                        url: "../api/task/insert",
                        data: {
                            todo_list_id : response['id'],
                            title : value
                        },
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('Authorization',
                                'Bearer {{ Auth::user()->api_token }}');
                        },
                        success: function (response) {
                            location.href= "{{ url('dashboard') }}"
                        }
                    });
                });
            }
        });

    });

    
</script>
@endsection