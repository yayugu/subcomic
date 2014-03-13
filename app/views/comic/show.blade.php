<!doctype html>
<html>
<div>
    @foreach ($pages as $page)
        <?php $img_url = action('BlobController@image', ['archiveFileId' => $id, 'index' => $page]); ?>
        <a href="{{$img_url}}">
          <img src="{{$img_url}}">
        </a>
        <br>
    @endforeach
</div>
</html>