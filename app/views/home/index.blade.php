<!doctype html>
<html>
<div class="welcome">
    @foreach ($Comics as $comic)
        {{link_to_action('ComicController@show', $comic->path, ['id' => $comic->id])}}<br>
    @endforeach
</div>
</html>