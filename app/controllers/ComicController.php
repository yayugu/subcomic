<?php

class ComicController extends \BaseController
{
    public function info($archiveFileId)
    {
        $comic = Comic::find($archiveFileId);
        return View::make('comic.info')
            ->with('comic', $comic);
    }

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

    public function tagSearch($tag_name)
    {
        return View::make('comic.tag_search')
            ->with('comics', Comic::findByTagName($tag_name));
    }
}