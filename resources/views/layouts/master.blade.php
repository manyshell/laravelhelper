<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="grey"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="format-detection" content="telephone=no"/>
    <title>@yield('title')</title>

@if (env('APP_DEBUG','false'))
    <link rel="stylesheet" type="text/css" href="/css/master.css?{{ mt_rand(1000,9999) }}">
@else
    <link rel="stylesheet" type="text/css" href="/css/master.css?{{ filemtime(realpath(public_path('css/style.css'))) }}">
@endif

    <script type="text/javascript" src="/components/requirejs/require.js"></script>
    <script type="text/javascript" src="/requirec.js"></script>

    @yield('head')

</head>
<body>
@yield('body')
@yield('footer')

@yield("script")
</body>
</html>

