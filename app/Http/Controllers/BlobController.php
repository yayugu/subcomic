<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Subcomic\Image;
use Subcomic\Rect;

class BlobController extends Controller
{

    /**
     * @param $archiveFileId
     * @param $index
     * @return Response
     */
    public function image($archiveFileId, $index)
    {
        $comic = \Comic::find($archiveFileId);
        $imageBlob = $comic->getArchive()->getFromIndex($index);
        if (\Request::has('width')) {
            $pixel = new Rect;
            $pixel->width = (int)\Request::input('width');
            $pixel->height = 0;
            $image = new Image($pixel);
            $image->loadBlob($imageBlob);
            $optimizedImageBlob = $image->getBlob();
        } else {
            $optimizedImageBlob = \Subcomic\ImageOptimizer::optimizeWithUserAgent($imageBlob);
        }
        return \Response::make($optimizedImageBlob)
            ->header('Content-Type', 'image/jpeg');
    }

}
