@foreach ($comics as $comic)
    <div>
        {{link_to_action('comicInfo', $comic->getFileNameToShow(), ['id' => $comic->id])}}
        <a href="{{$comic->getUrlToShow()}}" class="btn btn-default btn-xs">View</a>
        <span
            class="favorite-button"
            data-comic-id="{{$comic->id}}"
            data-starred="{{ array_key_exists($comic->id, $favoritesHash) ? 'true' : 'false' }}"></span>
    </div>
@endforeach