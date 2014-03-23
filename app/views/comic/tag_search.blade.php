<!doctype html>
<html>
<div class="welcome">
    @foreach ($comics as $comic)
        {{link_to_action('ComicController@show', $comic->path, ['id' => $comic->id])}}<br>
    @endforeach
</div>
</html>