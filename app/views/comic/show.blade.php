<!doctype html>
<html>
<div>
    @foreach ($pages as $page)
        {{link_to_action('BlobController@image', $page, ['archiveFileId' => $id, 'index' => $page])}}<br>
    @endforeach
</div>
</html>