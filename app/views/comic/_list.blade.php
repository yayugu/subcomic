@foreach ($comics as $comic)
    <div>
        {{link_to_action('comicInfo', $comic->getFileName(), ['id' => $comic->id])}}
        <a href="{{$comic->getUrlToShow()}}" class="btn btn-default btn-xs">View</a>
    </div>
@endforeach