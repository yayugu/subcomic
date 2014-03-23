<!doctype html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">
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
</style>
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script>
    $(function() {
        $('.comic-page').on('click', function() {
            var count = parseInt($(this).attr('page-count'));
            var nextPageId = '#page' + (count + 1);
            var nextPage = $(nextPageId);
            $('html,body').animate({scrollTop: nextPage.offset().top}, 50);
            location.hash = nextPageId;
        });
    });
</script>
<div>
    @foreach ($comic->tags as $tag)
        {{link_to_action('ComicController@tagSearch', $tag->name, ['tag' => $tag->name])}}<br>
    @endforeach
</div>
<div>
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
</html>