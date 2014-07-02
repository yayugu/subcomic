<?php

class ComicController extends \BaseController
{
    public function index()
    {
        $comics = Comic::paginate(200);
        $favoritesHash = Favorite::favoritesHashByComics($comics);
        return View::make('comic.index')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function info($archiveFileId)
    {
        $comic = Comic::find($archiveFileId);
        return View::make('comic.info')
            ->with('comic', $comic);
    }

    public function search()
    {
        $comics = Comic::where('path', 'like', '%'.Input::get('q').'%')->get();
        $favoritesHash = Favorite::favoritesHashByComics($comics);
        return View::make('comic.search')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function show($archiveFileId)
    {
        $comic = Comic::find($archiveFileId);
        if (!$comic) {
            App::abort(404);
        }
        History::create([
            'user_id' => Auth::user()->id,
            'comic_id' => $comic->id,
        ]);
        if ($comic->isPDF()) {
            return Response::make(file_get_contents($comic->getAbsolutePath()))
                ->header('Content-Type', 'application/pdf');
        }
        return View::make('comic.show')
            ->with('comic', $comic)
            ->with('pages', $comic->getArchive()->getImageList());
    }

    public function tagSearch($tag_name)
    {
        $comics = Comic::findByTagName($tag_name);
        $favoritesHash = Favorite::favoritesHashByComics($comics);
        return View::make('comic.tag_search')
            ->with('comics', $comics)
            ->with('favoritesHash', $favoritesHash);
    }

    public function sync()
    {
        (new SyncFilesAndDB)->exec();
        return Redirect::action('home');
    }
}
