@foreach ($comics as $comic)
    {{link_to_action('comicInfo', $comic->getFileName(), ['id' => $comic->id])}}
    <a href="{{$comic->getUrlToShow()}}">View</a><br>
@endforeach