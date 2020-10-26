<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function index()
    {
        $comics = \Comic::paginate(100);
        $comics->setPath(route('comicIndex'));
        $favoritesHash = \Favorite::favoritesHashByComics($comics);
        return \View::make('home.index')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function history()
    {
        $perPage = 100;
        $count = \History::where('user_id', '=', \Auth::user()->id)->count();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $histories = \History::with('comic')
            ->where('user_id', '=', \Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $comics = $histories->map(function (\History $history) {
            return $history->comic;
        })->filter(function ($comic) {
            return $comic instanceof \Comic;
        });
        $favoritesHash = \Favorite::favoritesHashByComics($comics);
        $pagination = new LengthAwarePaginator($histories, $count, $perPage, $page, [
            'path' => route('history'),
        ]);
        return \View::make('home.history')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash)
            ->with('pagination', $pagination);
    }
}
