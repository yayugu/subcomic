$(function () {
    var book = (function(page_urls) {
        var index = 0;
        var elements = [];

        function getElement(i) {
            var img;
            if (i < 0 || page_urls.count <= i) return undefined;
            if (elements[i] == undefined) {
                img = new Image();
                $(img).on('error', function() {
                   img.src = this.src; // reload
                });
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

        function indexLast() {
            return page_urls.length - 1;
        }

        return {
            length: function() { return page_urls.length },
            index: function() { return index; },
            setIndex: function (i) { index = i; },
            previous: function() {
                index = (index <= 0)
                    ? indexLast()
                    : index - 1;
                return getElementWithCacheAroundPage(index);
            },
            current: function () {
                return getElementWithCacheAroundPage(index);
            },
            next: function() {
                index = (index >= indexLast())
                    ? 0
                    : index + 1;
                return getElementWithCacheAroundPage(index);
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
        return parseInt(match[1], 10);
    }

    function fitToWindow(element) {
        if (!element.complete)  {
            $(element).on('load', function() {
                fitToWindow(element);
                element.style.visibility = 'visible';
            });
            element.style.visibility = 'hidden';
            return;
        }

        var s = element.style;

        s.position = "absolute";
        s.margin = "auto";
        s.left = "0px";
        s.top = "0px";
        s.bottom = "0px";
        s.right = "0px";

        var b = document.body;
        var d = document.documentElement;
        var maxWidth = Math.max(b.clientWidth, b.scrollWidth, d.scrollWidth, d.clientWidth);
        var maxHeight = Math.max(b.clientHeight, b.scrollHeight, d.scrollHeight, d.clientHeight);
        var rateWidth = element.width / maxWidth;
        var rateHeight = element.height / maxHeight;
        var rate = element.height / element.width;

        if (rateWidth > rateHeight) {
            s.width = maxWidth + "px";
            s.height = maxWidth * rate + "px";
        }
        else {
            s.width = maxHeight / rate + "px";
            s.height = maxHeight + "px";
        }
    }

    function show() {
        fitToWindow(book.current());
        $main.html(book.current());
        location.replace(hashedUrl(book.index()));
    }

    function showNext() {
        book.next();
        show();
    }

    function showPrevious() {
        book.previous();
        show();
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
            showPrevious();
            return;
        }
        showNext();
    });

    $(document).on('keydown', function (event) {
        var keyCodeUp = 38;
        var keyCodeDown = 40;
        if (event.keyCode == keyCodeUp) {
            showPrevious();
            return;
        }
        if (event.keyCode == keyCodeDown) {
            showNext();
            return;
        }
    });
});
