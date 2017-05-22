<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

class FavoriteController extends Controller
{
    public function index()
    {
        $perPage = 200;
        $count = \Favorite::where('user_id', '=', \Auth::user()->id)->count();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $favorites = \Favorite::with('comic')
            ->where('user_id', '=', \Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $comics = $favorites->map(function(\Favorite $favorite) {
            return $favorite->comic;
        })->filter(function ($comic) {
            return $comic instanceof \Comic;
        });
        $favoritesHash = \Favorite::favoritesHashByComics($comics);
        $pagination = new LengthAwarePaginator($favorites, $count, $perPage, $page, [
            'path' => route('favorite'),
        ]);
        return \View::make('favorite.index')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash)
            ->with('pagination', $pagination);
    }

    public function store()
    {
        \Favorite::create([
            'user_id' => \Auth::user()->id,
            'comic_id' => \Input::get('comic_id'),
        ]);
        return \Response::make('');
    }

    public function delete()
    {
        \Favorite::where('user_id', '=', \Auth::user()->id)
            ->where('comic_id', '=', \Input::get('comic_id'))
            ->delete();
        return \Response::make('');
    }
}
