<!doctype html>
<html>
    <head>
　       <meta name="viewport"
               content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">
         <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
         <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            @if (Auth::check())
                @include('layout._navbar')
            @endif
            @yield('content')
        </div>
    </body>
</html>