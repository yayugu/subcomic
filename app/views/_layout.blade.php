<!doctype html>
<html>
    <head>
        <meta name="viewport"
               content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{asset('main.css')}}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        {{ HTML::script('main.js') }}
        {?
            $config = [
                'csrfToken' => csrf_token(),
                'urlBase' => action('home'),
                'urlFavorite' => action('favorite'),
                'urlFavoriteDelete' => action('favoriteDelete'),
            ];
        ?}
        <script>
            window.scConfig = {{ json_encode($config) }};
        </script>
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