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
    }
    #main {
        margin: 0px;
        padding: 0px;
    }
</style>
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script>
    /*
     $(function() {
     $('.comic-page').on('click', function() {
     var count = parseInt($(this).attr('page-count'));
     var nextPageId = '#page' + (count + 1);
     var nextPage = $(nextPageId);
     $('html,body').animate({scrollTop: nextPage.offset().top}, 50);
     location.hash = nextPageId;
     });
     });
     */
    $(function () {
        function maximizeElement(element) {
            var b = document.body;
            var d = document.documentElement;
            element.width = Math.max(b.clientWidth , b.scrollWidth, d.scrollWidth, d.clientWidth);
            element.height = Math.max(b.clientHeight , b.scrollHeight, d.scrollHeight, d.clientHeight);
        }
        function showNext() {
            $(current).replaceWith(imgs[index]);
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
<!--
<div>
    @foreach ($comic->tags as $tag)
    {{link_to_action('ComicController@tagSearch', $tag->name, ['tag' => $tag->name])}}<br>
    @endforeach
</div>
<div id="wrapper">
    {? $count = 0 ?}
    @foreach ($pages as $page)
    {? $img_url = action('BlobController@image', ['archiveFileId' => $comic->id, 'index' => $page]); ?}
    <div class="block">
        <img id="page{{{$count}}}" class="comic-page" src="{{{$img_url}}}" page-count="{{{$count}}}">
    </div>
    <br>
    {? $count++ ?}
    @endforeach
</div>
-->
<div id="main"></div>
</html>