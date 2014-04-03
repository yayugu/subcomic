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
<script>
    $(function () {
        function maximizeElement(element) {
            var b = document.body;
            var d = document.documentElement;
            element.width = Math.max(b.clientWidth , b.scrollWidth, d.scrollWidth, d.clientWidth);
            element.height = Math.max(b.clientHeight , b.scrollHeight, d.scrollHeight, d.clientHeight);
        }
        function showNext() {
            $main.html(imgs[index]);
            current = imgs[index];
            index++;
        }

        var imgs = page_urls.map(function(page_url) {
            var img = new Image();
            img.src = page_url;
            img.className = 'comic-page';
            return img;
        });
        var $main = $('#main');
        var main = $main[0];
        var current = imgs[0];
        var index = 0;

        maximizeElement(main);
        $main.append(imgs[0]);

        $main.on('click', function() {
            showNext();
        });
    });
</script>
<script>
    var page_urls = {{
        json_encode(array_map(function($page) use($comic) {
            return action('BlobController@image', ['archiveFileId' => $comic->id, 'index' => $page]);
        }, $pages));
    }}
</script>
<div id="main"></div>
</html>