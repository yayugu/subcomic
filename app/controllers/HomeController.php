<?php

class HomeController extends BaseController
{
    public function index()
    {
        $comics = Comic::paginate(200);
        $comics->setBaseUrl(action('comicIndex'));
        $favoritesHash = Favorite::favoritesHashByComics($comics);
        return View::make('home.index')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function history()
    {
        $pagination = App::make('paginator');
        $pagination->setBaseUrl(action('history'));
        $perPage = 200;
        $count = History::where('user_id', '=', Auth::user()->id)->count();
        $page = $pagination->getCurrentPage($count);
        $histories = History::with('comic')
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $comics = $histories->map(function (History $history) {
            return $history->comic;
        })->filter(function ($comic) {
            return $comic instanceof Comic;
        });
        $favoritesHash = Favorite::favoritesHashByComics($comics);
        $pagination = $pagination->make($comics->toArray(), $count, $perPage);
        return View::make('home.history')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash)
            ->with('pagination', $pagination);
    }
}