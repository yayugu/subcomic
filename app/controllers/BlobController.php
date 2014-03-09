<?php

class BlobController extends \BaseController
{

    /**
     * @param $archiveFileId
     * @param $index
     * @return Response
     */
    public function image($archiveFileId, $index)
    {
        $comic = Comic::find($archiveFileId);
        $imageBlob = $comic->getArchive()->getFromIndex($index);
        return Response::make($imageBlob)
            ->header('Content-Type', 'image/jpeg');
    }

}