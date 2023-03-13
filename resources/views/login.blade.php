<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Todo List</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome5-overrides.min.css') }}">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col offset-xl-0">
                                <div class="p-5">
                                    <div class="text-center">
                                        @if($errors->any())
                                        <h4>{{$errors->first()}}</h4>
                                        @endif
                                        <h4 class="text-dark mb-4">Welcome!</h4>
                                    </div>
                                    <form id="login_form">
                                        {{-- @csrf --}}
                                        <div class="mb-3"><input class="form-control form-control-user" type="email" id="email" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email"></div>
                                        <div class="mb-3"><input class="form-control form-control-user" type="password" id="password" placeholder="Password" name="password"></div>
                                        <input class="btn btn-primary d-block btn-user w-100" type="submit" value="Login">
                                        <hr>
                                    </form>
                                    <div class="text-center"></div>
                                    <div class="text-center"><a class="small" href="{{ url('register') }}">Create an Account!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
</body>
<script>
    $("#login_form").submit(function (e) { 
        e.preventDefault();
        var form = $("#login_form");
        $.ajax({
            type: "POST",
            url: "{{ route('login-attempt') }}",
            data: form.serialize(),
            success: function (response) {
                location.reload();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                var data=XMLHttpRequest.responseText;
                var jsonResponse = JSON.parse(data);
                alert(jsonResponse['message']);
            }
        });
    });
</script>
</html>