<!doctype html>
<html>
<div class="welcome">
    @foreach ($archiveFiles as $archive)
        {{{$archive->path}}}<br>
    @endforeach
</div>
</html>