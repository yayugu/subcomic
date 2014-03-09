<?php

class ComicController extends \BaseController
{
    public function show($archiveFileId)
    {
        $archiveFile = Comic::find($archiveFileId);
        return View::make('comic.show')
            ->with('id', $archiveFileId)
            ->with('pages', $archiveFile->pages());
    }
}