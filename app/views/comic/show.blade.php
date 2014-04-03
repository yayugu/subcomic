<!doctype html>
<html>
<meta name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">
<style type="text/css">
    .block {
        display: block;
        height: 100%;
    }


    .comic-page {
        max-width: 100%;
        max-height: 100%;
    }
    body {
        margin: 0px;
        height: 100%;
        position: fixed;
    }
    #main {
        margin: 0px;
        padding: 0px;
    }
</style>
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
{{ HTML::script('comic.js') }}
<script>
    var page_urls = {{
        json_encode(array_map(function($page) use($comic) {
            return action('BlobController@image', ['archiveFileId' => $comic->id, 'index' => $page]);
        }, $pages));
    }}
</script>
<div id="main"></div>
</html>