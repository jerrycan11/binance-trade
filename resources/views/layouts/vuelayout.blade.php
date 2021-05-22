<!doctype html>
<html lang="en">
<head>
    <title>My Trade</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    @if (app()->environment('local'))
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif

    @yield('style')
</head>
<body>
<div id="app">
   @yield('content')
</div>
@if (app()->environment('local'))
    <script src="{{ mix('js/app.js') }}?_t={{time()}}" defer></script>
@else
    <script src="{{ asset('js/app.js') }}" defer></script>
@endif
</body>
</html>
