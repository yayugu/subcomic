$(function() {
    var book = (function(page_urls) {
        var index = 0;
        var elements = [];

        function getElement(i) {
            var img;
            if (i < 0 || page_urls.length <= i) return undefined;
            if (elements[i] === undefined) {
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
            length: function() {
                return page_urls.length
            },
            index: function() {
                return index;
            },
            setIndex: function(i) {
                index = i;
            },
            previous: function() {
                index = (index <= 0)
                    ? indexLast()
                    : index - 1;
                return getElementWithCacheAroundPage(index);
            },
            current: function() {
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
        if (!element.complete) {
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
        s.overflow = "hidden";

        var b = document.body;
        var d = document.documentElement;
        var maxWidth = Math.max(b.clientWidth, d.clientWidth);
        var maxHeight = Math.max(b.clientHeight, d.clientHeight);
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
        resetTransform();
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

    $(document).on('keydown', function(event) {
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

    function transform(translateX, translateY, scale) {
        var t = "translate(" + translateX + 'px,' + translateY + "px) " +
            "scale(" + scale + ',' + scale + ")";
        $(book.current()).css({
            "transform-origin": "0 0",
            "-moz-transform-origin": "0 0",
            "-webkit-transform-origin": "0 0",
            "-ms-transform-origin": "0 0",
            "transform": t,
            "-webkit-transform": t,
            "-ms-transform": t
        });
    }

    function resetTransform() {
        scale = 1;
        deltaX = 0;
        deltaY = 0;
        transform(deltaX, deltaY, scale);
    }

    var hammertime = Hammer(main, {
        transformMinScale: 0.01,
        transformMinRotation: 360
    });

    var scale = 1;
    var deltaX = 0;
    var deltaY = 0;
    var scaleTmp, deltaXTmp, deltaYTmp;
    var lx, ly;
    var pinched = false, dragged = false;
    hammertime.on('tap pinch transformend dragstart drag dragend', function(ev) {
        ev.gesture.preventDefault();
        switch (ev.type) {
            case "tap":
                //var y = event.pageY;
                //var height = window.innerHeight;

                if (ev.gesture.center.clientY < window.innerHeight / 2) {
                    showPrevious();
                    return;
                }
                showNext();
                break;
            case "pinch":
                console.log("pinch");
                scaleTmp = scale * ev.gesture.scale;
                //deltaXTmp = deltaX - ev.gesture.deltaX * scaleTmp;
                //deltaYTmp = deltaY - ev.gesture.deltaY * scaleTmp;
                lx = (ev.gesture.startEvent.center.clientX - deltaX) / scale;
                ly = (ev.gesture.startEvent.center.clientY - deltaY) / scale;
                deltaXTmp = ev.gesture.center.clientX - lx * scaleTmp;
                deltaYTmp = ev.gesture.center.clientY - ly * scaleTmp;
                transform(deltaXTmp, deltaYTmp, scaleTmp);
                pinched = true;
                break;
            case "transformend":
                if (!pinched) break;
                pinched = false;
                console.log("transformend");
                if (scaleTmp < 1) {
                    scale = 1.0;
                    deltaX = 0;
                    deltaY = 0;
                } else {
                    scale = scaleTmp;
                    deltaX = deltaXTmp;
                    deltaY = deltaYTmp;
                }
                transform(deltaX, deltaY, scale);
                break;
            case "dragstart":
                console.log("dragstart");
                break;
            case "drag":
                console.log("drag");
                lx = (ev.gesture.startEvent.center.clientX - ev.gesture.center.clientX);// / scale;
                ly = (ev.gesture.startEvent.center.clientY - ev.gesture.center.clientY);// / scale;
                deltaXTmp = deltaX - lx;
                deltaYTmp = deltaY - ly;
                scaleTmp = scale;
                console.log([lx, deltaXTmp]);
                transform(deltaXTmp, deltaYTmp, scale);
                dragged = true;
                break;
            case "dragend":
                if (!dragged) break;
                dragged = false;
                console.log("dragend");

                scale = scaleTmp;
                deltaX = deltaXTmp;
                deltaY = deltaYTmp;
                transform(deltaX, deltaY, scale);
                break;
        }

    });
});
