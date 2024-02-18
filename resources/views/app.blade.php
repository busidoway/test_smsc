<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <title>Daily Grow</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/favicon/favicon.ico') }}" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        @vite(['resources/css/core.css'])
        @vite(['resources/css/theme-default.css'])
        @vite(['resources/css/app.css'])
    </head>
    <body>
        <div id="app"></div>
        @vite(['resources/js/app.js'])
    </body>
</html>
