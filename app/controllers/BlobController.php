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
        $optimizedImageBlob = \Subcomic\ImageOptimizer::optimizeWithUserAgent($imageBlob);
        return Response::make($optimizedImageBlob)
            ->header('Content-Type', 'image/jpeg');
    }

}