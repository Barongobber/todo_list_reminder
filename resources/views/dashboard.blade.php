@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row" style="padding: 5px;">
        <div class="d-sm-flex justify-content-between align-items-center">
            <h3 class="text-dark">Todo List</h3>
            <div class="d-none d-sm-inline-block">
                <form id="import_form" enctype="multipart/form-data">
                    <button class="btn btn-primary" onclick="window.location.href='{{ url('generator') }}'" type="button"><i class="fa fa-plus fa-sm"></i>&nbsp;Create Todo-List</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card shadow ">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Todo-List Info</p>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                <table class="table my-0 display" id="myTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Title of Todo-List</th>
                            <th>Reminder Time</th>
                            <th>Action Button</th>
                        </tr>
                    </thead>
                    <tbody id="content">
                        {{-- todo list goes here --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_todo(id)
    {
        $.ajax({
            type: "POST",
            url: `api/todo-list/delete/${id}`,
            data: "data",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization',
                    'Bearer {{ Auth::user()->api_token }}');
            },
            success: function (response) {
                alert('success to delete timetable');
                location.href= "{{ url('dashboard') }}"
            }
        });
    }
    $(document).ready(function () {
        $.ajax({
            type: "GET",
            url: "../api/todo-list/read",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization',
                    'Bearer {{ Auth::user()->api_token }}');
            },
            success: function (response) {
                $.each(response, function (key, value) {
                    $('tbody').append(
                        '<tr>' +
                            '<td>'+ value['title'] + '</td>' +
                            '<td>'+ value['reminder'] + '</td>' +
                            "<td><button onclick='window.location.href=`info?id="+ value['id'] +"`' class='btn btn-info' type='button'><i class='fa fa-info-circle'></i></button> <button class='btn btn-danger' onclick='delete_todo(`"+ value['id'] +"`)' type='button'><i class='fa fa-trash'></i></button></td>" +
                        '</tr>'
                    );
                });
                $('#myTable').DataTable();
            }           
        });
    });
</script>
@endsection