@foreach ($comics as $comic)
    {{link_to_action('comicInfo', $comic->path, ['id' => $comic->id])}}
    {{link_to_action('comicShow', 'View', ['id' => $comic->id])}}<br>
@endforeach