@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <div class="row" style="padding: 5px;">
        <div class="d-sm-flex justify-content-between align-items-center">
            <h3 class="text-dark">Todo-List Info</h3>
            <div class="d-none d-sm-inline-block">
                <button class="btn btn-primary btn-sm" type="button" id="update_button">Update Todo-List</button>
                {{-- <button class="btn btn-success btn-sm" id="pdf_button">Generate Todo-List (PDF)</button>
                <button class="btn btn-success btn-sm" id="excel_button">Generate Todo-List (Excel)</button> --}}
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
                        <input id="notif" type="checkbox" for='notif'>
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
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var isSet = false;
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results==null) {
            return null;
            }
            return decodeURI(results[1]) || 0;
        }
        var idParam = $.urlParam('id');

        $("#notif").change(function (e) { 
            e.preventDefault();
            if (isSet == true) {
                isSet = false;
            } else {
                isSet = true;
            }
        });

        $.ajax({
            type: "GET",
            url: "../api/todo-list/readDetail/" + idParam,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization',
                    'Bearer {{ Auth::user()->api_token }}');
            },
            success: function (response) {
                $('#todo_title').val(response['title']);
                var textToReplace = response['reminder'];
                var replaced = textToReplace.replace(" ", "T");
                $('#todo_reminder').val(replaced);
                if(response['notification']) {
                    $('#notif').prop('checked', true);
                    isSet = true;
                }
            }
        });
        $.ajax({
            type: "GET",
            url: "../api/task/readTasks/" + idParam,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization',
                    'Bearer {{ Auth::user()->api_token }}');
            },
            success: function (response) {
                var i=1;
                $.each(response, function (key, val) { 
                    $('tbody').append(
                        '<tr>' +
                            '<td>'+i+'</td>' +
                            "<td><input id='"+ i +"' name='task[]' class='form-control' type='text' value='" + val['title'] +"''></td>" +
                        '</tr>'
                    );
                    i++;
                });
            }
        });
        
        $("#update_button").click(function (e) { 
            var todo_list_title = $('#todo_title').val();
            var todo_list_reminder = $('#todo_reminder').val();
            var tasks = $("input[name='task[]']").map(function()
            {
                return $(this).val();
            }).get();

            $.ajax({
                type: "POST",
                url: "../api/todo-list/update/" + idParam,
                data: {
                    title : todo_list_title,
                    reminder : todo_list_reminder,
                    notification: isSet
                },
                dataType: "json",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization',
                        'Bearer {{ Auth::user()->api_token }}');
                },
                success: function (response) {
                    $.ajax({
                        type: "POST",
                        url: "../api/task/delete/" + idParam,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('Authorization',
                                'Bearer {{ Auth::user()->api_token }}');
                        },
                        success: function (response) {
                            $.each(tasks, function (key, val) { 
                                $.ajax({
                                    type: "POST",
                                    url: "../api/task/insert",
                                    data: {
                                        todo_list_id : idParam,
                                        title : val
                                    },
                                    dataType: "json",
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
                }
            });
        });
    });
</script>
@endsection