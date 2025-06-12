<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')"/>
    <meta name="keywords" content="@yield('keywords')"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <link rel="stylesheet" href="/lib/fontawesome/css/all.css" type="text/css"/>
    <script src="/lib/fontawesome/js/all.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div  id="app_vue">
    @yield('content')
</div>
<div class="background__overlay"></div>
</body>
</html>

