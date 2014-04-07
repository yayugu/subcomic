<!doctype html>
<html>
<div class="welcome">
    @foreach ($Comics as $comic)
        {{link_to_action('ComicController@info', $comic->path, ['id' => $comic->id])}}
        {{link_to_action('ComicController@show', 'View', ['id' => $comic->id])}}<br>
    @endforeach
</div>
</html>