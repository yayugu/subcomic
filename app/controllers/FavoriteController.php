<?php

class FavoriteController extends BaseController
{
    public function index()
    {
        $pagination = App::make('paginator');
        $pagination->setBaseUrl(action('history'));
        $perPage = 200;
        $count = Favorite::where('user_id', '=', Auth::user()->id)->count();
        $page = $pagination->getCurrentPage($count);
        $favorites = Favorite::with('comic')
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $comics = $favorites->map(function(Favorite $favorite) {
            return $favorite->comic;
        });
        $favoritesHash = Favorite::favoritesHashByComics($comics);
        $pagination = $pagination->make($comics->toArray(), $count, $perPage);
        return View::make('home.history')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash)
            ->with('pagination', $pagination);
    }

    public function store()
    {
        Favorite::create([
            'user_id' => Auth::user()->id,
            'comic_id' => Input::get('comic_id'),
        ]);
        return Response::make('');
    }

    public function delete()
    {
        Favorite::where('user_id', '=', Auth::user()->id)
            ->where('comic_id', '=', Input::get('comic_id'))
            ->delete();
        return Response::make('');
    }
}