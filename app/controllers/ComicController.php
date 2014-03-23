<?php

class ComicController extends \BaseController
{
    public function show($archiveFileId)
    {
        $comic = Comic::find($archiveFileId);
        if ($comic->isPDF()) {
            return Response::make(file_get_contents($comic->getAbsolutePath()))
                ->header('Content-Type', 'application/pdf');
        }
        return View::make('comic.show')
            ->with('comic', $comic)
            ->with('pages', $comic->getArchive()->getImageList());
    }

    public function tagSearch($tagName)
    {
        $tag = Tag::where('name', '=', $tagName)->first();
        $comics = $tag->comics()->get();
        return View::make('comic.tag_search')
            ->with('comics', $comics);
    }
}