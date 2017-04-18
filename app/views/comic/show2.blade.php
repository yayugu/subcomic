<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{{$comic->getFileNameToShow()}}}</title>
</head>
<body>
<script>
    var pageUrls = {{
        json_encode(array_map(function($page) use($comic) {
            return action('comicImage', ['archiveFileId' => $comic->id, 'index' => $page]);
        }, $pages));
    }}
</script>
<div id="app"></div>
<script src="/bundle.js"></script>
</body>
</html>