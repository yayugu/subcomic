$(function() {
    // this will send the headers on every ajax request you make via jquery...
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': scConfig.csrfToken
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
            var url = isStarred(this) ? scConfig.urlFavoriteDelete : scConfig.urlFavorite;
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