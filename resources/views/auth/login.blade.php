<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Daily Grow</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/favicon/favicon.ico') }}" />
    @vite(['resources/css/core.css'])
    @vite(['resources/css/theme-default.css'])
    @vite(['resources/css/page-auth.css'])
</head>
<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <span class="app-brand-text demo text-body fw-bold">Daily Grow</span>
                        </div>

                        <form id="formAuthentication" class="mb-3" action="{{ route('authenticate') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email или логин</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Enter your email or username"
                                    autofocus />
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Пароль</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
{{--                            <div class="mb-3">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" id="remember-me" />--}}
{{--                                    <label class="form-check-label" for="remember-me"> Remember Me </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Войти</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
</body>
</html>
