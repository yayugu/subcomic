<!doctype html>
<html>
    <head>
        <meta name="viewport"
               content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{asset('main.css')}}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="{{url('main.js')}}"></script>
        @php
            $config = [
                'csrfToken' => csrf_token(),
                'urlBase' => route('home'),
                'urlFavorite' => route('favorite'),
                'urlFavoriteDelete' => route('favoriteDelete'),
            ];
        @endphp
        <script>
            window.scConfig = {!! json_encode($config) !!};
        </script>
        <title>Subcomic</title>
    </head>
    <body>
        <div class="container">
            @if (\Auth::check())
                @include('layout._navbar')
            @endif
            @yield('content')
        </div>
    </body>
</html>