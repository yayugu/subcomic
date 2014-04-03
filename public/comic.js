$(function () {
    var book = (function(page_urls) {
        var index = 0;
        var elements = [];

        function getElement(i) {
            var img;
            if (i < 0 || page_urls.count <= i) return undefined;
            if (elements[i] == undefined) {
                img = new Image();
                img.src = page_urls[i];
                img.className = 'comic-page';
                elements[i] = img;
            }
            return elements[i];
        }
        function getElementWithCacheAroundPage(i) {
            getElement(i);
            getElement(i + 1);
            getElement(i - 1);
            getElement(i + 2);
            getElement(i + 3);
            getElement(i + 4);
            return getElement(i);
        }

        return {
            length: function() { return page_urls.length },
            index: function() { return index; },
            next: function() {
                return getElementWithCacheAroundPage(index++);
            }
        };
    })(page_urls);

    function maximizeElement(element) {
        var b = document.body;
        var d = document.documentElement;
        element.width = Math.max(b.clientWidth, b.scrollWidth, d.scrollWidth, d.clientWidth);
        element.height = Math.max(b.clientHeight, b.scrollHeight, d.scrollHeight, d.clientHeight);
    }

    function showNext() {
        $main.html(book.next());
    }
    var $main = $('#main');
    var main = $main[0];

    maximizeElement(main);
    showNext();

    $main.on('click', function () {
        showNext();
    });
});
