<!doctype html>
<html>
<title>{!! $comic->getFileNameToShow() !!}</title>
<meta name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<style type="text/css">
    .block {
        display: block;
        height: 100%;
    }

    body {
        margin: 0px;
        height: 100%;
        position: fixed;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
    #main {
        margin: 0px;
        padding: 0px;
        position: fixed;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
</style>
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="{{url('hammer.js')}}"></script>
<script src="{{url('comic.js')}}"></script>

<script>
    var page_urls = {!!
        json_encode(array_map(function($page) use($comic) {
            return route('comicImage', ['archiveFileId' => $comic->id, 'index' => $page]);
        }, $pages))
    !!}
</script>
<div id="main"></div>
</html>