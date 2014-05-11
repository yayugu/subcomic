$(function() {
    // this will send the headers on every ajax request you make via jquery...
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function updateStarred(elm, starred) {
        elm.dataset.starred = starred ? 'true' : 'false';
        elm.innerHTML = starred ? '★' : '☆';
    }
    function isStarred(elm) {
        return elm.dataset.starred === 'true';
    }

    $('.favorite-button').each(function() {
        updateStarred(this, isStarred(this));
        $(this).on('click', function() {
            var url = isStarred(this) ? '/fav/delete' : 'fav';
            var that = this;
            $.post(
                url,
                {comic_id: this.dataset.comicId},
                function() {
                    updateStarred(that, !isStarred(that));
                }
            );
        });
    });
});