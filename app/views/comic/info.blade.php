<!doctype html>
<html>
<meta name="viewport"
      content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=4, user-scalable=yes">

{{link_to_action('ComicController@show', $comic->path, ['id' => $comic->id])}}<br><br>

@foreach ($comic->tags as $tag)
    {{link_to_action('ComicController@tagSearch', $tag->name, ['tag' => $tag->name])}}<br>
@endforeach

</html>