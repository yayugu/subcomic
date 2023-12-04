<?php

namespace App\Http\Controllers;

use App\Jobs\Sync;

class ComicController extends Controller
{
    public function index()
    {
        $comics = \Comic::paginate(100);
        $favoritesHash = \Favorite::favoritesHashByComics($comics);
        return \View::make('comic.index')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function info($archiveFileId)
    {
        $comic = \Comic::find($archiveFileId);
        return \View::make('comic.info')
            ->with('comic', $comic);
    }

    public function search()
    {
        $comics = \Comic::search(\Request::get('q'))->get();
        $favoritesHash = \Favorite::favoritesHashByComics($comics);
        return \View::make('comic.search')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash)
            ->with('comic_search_query', \Request::get('q'));
    }

    public function show($archiveFileId)
    {
        $comic = \Comic::find($archiveFileId);
        if (!$comic) {
            \App::abort(404);
        }
        \History::create([
            'user_id' => \Auth::user()->id,
            'comic_id' => $comic->id,
        ]);
        if ($comic->isPDF()) {
            return \Response::make(file_get_contents($comic->getAbsolutePath()))
                ->header('Content-Type', 'application/pdf');
        }
        return \View::make('comic.show')
            ->with('comic', $comic);
    }

    public function tagSearch($tag_name)
    {
        $comics = \Comic::findByTagName($tag_name);
        $favoritesHash = \Favorite::favoritesHashByComics($comics);
        return \View::make('comic.tag_search')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function sync()
    {
        dispatch(new Sync());
        return \Redirect::home();
    }
}
