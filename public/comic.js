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
            previous: function() {
                return getElementWithCacheAroundPage(index--);
            },
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

    function fitToWindow(element) {
        var s = element.style;

        s.position = "absolute";
        s.margin = "auto";
        s.left = "0px";
        s.top = "0px";
        s.bottom = "0px";
        s.right = "0px";

        var rateWidth = element.width / window.innerWidth;
        var rateHeight = element.height / window.innerHeight;
        var rate = element.height / element.width;

        if (rateWidth > rateHeight) {
            s.width = innerWidth + "px";
            s.height = innerWidth * rate + "px";
        }
        else {
            s.width = innerHeight / rate + "px";
            s.height = innerHeight + "px";
        }
    }

    function show() {
        fitToWindow(book.current());
        $main.html(book.current());
        location.replace(hashedUrl(book.index()));
    }

    var $main = $('#main');
    var main = $main[0];

    if (indexFromHash() !== undefined) {
        book.setIndex(indexFromHash());
    }
    show();

    $main.on('click', function (event) {
        var y = event.pageY;
        var height = window.innerHeight;

        if (y < height / 2) {
            book.previous();
            show();
            return;
        }
        book.next();
        show();
    });
});
