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
        $archiveFile = ArchiveFile::get((int)$archiveFileId);
        $imageBlob = $archiveFile->getFromIndex((int)$index);
        return Response::make($imageBlob)
            ->header('Content-Type', 'image/jpeg');
    }

}