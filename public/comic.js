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
            setIndex: function (i) { index = i; },
            current: function () {
                return getElementWithCacheAroundPage(index);
            },
            next: function() {
                return getElementWithCacheAroundPage(index++);
            }
        };
    })(page_urls);

    function hashedUrl(index) {
        var parser = document.createElement('a');
        parser.href = location.href;

        // modifiy hash
        parser.hash = 'p' + index;

        return parser.href;
    }

    function indexFromHash() {
        var match = (/p(\d+)/g).exec(location.hash);
        if (match === null) {
            return undefined;
        }
        return match[1]
    }

    function maximizeElement(element) {
        var b = document.body;
        var d = document.documentElement;
        element.width = Math.max(b.clientWidth, b.scrollWidth, d.scrollWidth, d.clientWidth);
        element.height = Math.max(b.clientHeight, b.scrollHeight, d.scrollHeight, d.clientHeight);
    }

    function show() {
        $main.html(book.current());
        location.replace(hashedUrl(book.index()));
    }

    var $main = $('#main');
    var main = $main[0];

    maximizeElement(main);
    if (indexFromHash() !== undefined) {
        book.setIndex(indexFromHash());
    }
    show();

    $main.on('click', function () {
        book.next();
        show();
    });
});
