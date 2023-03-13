@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h3 class="text-dark mb-4">Profile</h3>
    <div class="row mb-3">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center shadow" style="height: 308px;"><img class="rounded-circle mb-3 mt-4" src="../../storage/img/user/{{ Auth::user()->id }}/{{ Auth::user()->picture }}" width="160" height="160">
                    <div class="mb-3"><input class="btn btn-primary btn-sm" type="file" id="photo" accept="image/*"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row mb-3 d-none">
                <div class="col">
                    <div class="card textwhite bg-primary text-white shadow">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <p class="m-0">Peformance</p>
                                    <p class="m-0"><strong>65.2%</strong></p>
                                </div>
                                <div class="col-auto"><i class="fas fa-rocket fa-2x"></i></div>
                            </div>
                            <p class="text-white-50 small m-0"><i class="fas fa-arrow-up"></i>&nbsp;5% since last month</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card textwhite bg-success text-white shadow">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <p class="m-0">Peformance</p>
                                    <p class="m-0"><strong>65.2%</strong></p>
                                </div>
                                <div class="col-auto"><i class="fas fa-rocket fa-2x"></i></div>
                            </div>
                            <p class="text-white-50 small m-0"><i class="fas fa-arrow-up"></i>&nbsp;5% since last month</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-3">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">User Settings</p>
                        </div>
                        <div class="card-body">
                            <form id="profile_form">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3"><label class="form-label" for="username"><strong>Username</strong></label><input class="form-control" type="text" id="username" name="username"></div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3"><label class="form-label" for="email"><strong>Email Address</strong></label><input class="form-control" type="email" id="email" name="email"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3"><label class="form-label" for="name"><strong>Name</strong></label><input class="form-control" type="text" id="name" name="name"></div>
                                    </div>
                                </div>
                                <div class="mb-3"><button class="btn btn-primary btn-sm" type="submit">Update Profile</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#username').val('{{Auth::user()->username}}');
        $('#email').val('{{Auth::user()->email}}');
        $('#name').val('{{Auth::user()->name}}');
        var form = $("#profile_form");

        $("#profile_form").submit(function (e) { 
            e.preventDefault();
            
            var formData = new FormData();
            var file = $('#photo')[0].files[0];
            formData.append('picture', file);

            $.ajax({
                type: "POST",
                url: "../api/user/update",
                data: form.serialize(),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization',
                        'Bearer {{ Auth::user()->api_token }}');
                },
                success: function (response) {
                    $.ajax({
                        type: "POST",
                        url: "../api/user/update-image",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('Authorization',
                                'Bearer {{ Auth::user()->api_token }}');
                        },
                        success: function (response) {
                            location.reload();
                            alert('successful to update photo and profile');
                        },
                        error: function (response) {
                            location.reload();
                            alert('successful upadate a profile without photo');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection