<table class="table table-striped">
    @foreach ($comics as $comic)
        <tr>
            <td>
                <div class="comic-list-row-wrapper">
                    @php
                      $thumb = $comic->getThumbnailURL();
                    @endphp
                    @if ($thumb)
                      <img src="{{$thumb}}" width="100px">
                    @endif
                </div>
            </td>

            <td>
                <a href="{{route('comicInfo', ['id' => $comic->id])}}">{{$comic->getFileNameToShow()}}</a>
                <a href="{{$comic->getUrlToShow()}}" class="btn btn-default btn-xs">View</a>
                <span
                    class="favorite-button"
                    data-comic-id="{{$comic->id}}"
                    data-starred="{{ array_key_exists($comic->id, $favoritesHash) ? 'true' : 'false' }}"></span>
            </td>
        </tr>
    @endforeach
</table>
