<?php

class ComicController extends \BaseController
{
    public function show($archiveFileId)
    {
        $archiveFile = Comic::find($archiveFileId);
        if ($archiveFile->isPDF()) {
            return Response::make(file_get_contents($archiveFile->getAbsolutePath()))
                ->header('Content-Type', 'application/pdf');
        }
        return View::make('comic.show')
            ->with('id', $archiveFileId)
            ->with('pages', $archiveFile->getArchive()->getImageList());
    }
}