<!doctype html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">
<style type="text/css">
    .block {
        display: block;
    }
    .comic-page {
        width: 100%;
    }
    body {
        margin: 0px;
    }
</style>
<div>
    @foreach ($pages as $page)
        <?php $img_url = action('BlobController@image', ['archiveFileId' => $id, 'index' => $page]); ?>
        <a class="block" href="{{$img_url}}">
          <img class="comic-page" src="{{$img_url}}">
        </a>
        <br>
    @endforeach
</div>
</html>