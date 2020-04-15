$(document).ready(function () {
    var show_window = document.getElementById('show_window');
    var slides = document.getElementById('slides');
    var first = document.getElementById('first');
    var area = document.getElementById('area');
    var link = document.getElementById('page_url').value;
    

    $.ajax({
        url : 'http://official.org.ua/www/orders/popular_offers.php',
        type : "POST",
        dataType : 'json',
        data : {url : link},
        success : function(data){
            var i = 0;
            for (var key in data){
                i++;
                $('.show_window').append('<a href="' + data[key]["url"] + '"><div class="product" data-toggle="popover" data-placement="top" data-trigger="hover" data-html="true" title="Описание товара" data-content="' + data[key]["small_descr"] + '"><img src="' + data[key]["image"] + '"><div class="name"><span class="test">' + data[key]["name"] +'</span></div></div></a>');
                if (i==1){
                    $('.product').attr("id", "first")
                }
            }

            $('.product').popover({
                placement : 'top'
            });

            timerId();

        }
    });


    var timerId = function slide() {
        var res = show_window.scrollWidth - show_window.clientWidth;
        slides.onmouseenter = function () {
            clearInterval(forward);
        };

        if (show_window.scrollLeft == 0){
            var forward = setInterval(function () {
                if (show_window.scrollLeft == res){
                    clearInterval(forward);
                    var back = setInterval(function () {
                        slides.onmouseenter = function () {
                            clearInterval(back);
                        };
                        if (show_window.scrollLeft == 0){
                            clearInterval(back);
                            timerId = setTimeout(slide, 0)
                        }
                        show_window.scrollLeft--;
                    }, 20);
                }

                show_window.scrollLeft++;
            }, 20);

        }

    };


    function addOnWheel(elem, handler) {
        if (elem.addEventListener) {
            if ('onwheel' in document) {
                // IE9+, FF17+
                elem.addEventListener("wheel", handler);
            } else if ('onmousewheel' in document) {
                // устаревший вариант события
                elem.addEventListener("mousewheel", handler);
            } else {
                // 3.5 <= Firefox < 17, более старое событие DOMMouseScroll пропустим
                elem.addEventListener("MozMousePixelScroll", handler);
            }
        } else { // IE8-
            slides.attachEvent("onmousewheel", handler);
        }
    }

    var scale = 0;

    addOnWheel(slides, function(e) {

        var delta = e.deltaY || e.detail || e.wheelDelta;

        if (delta > 0) scale += 40;
        else scale -= 40;

        show_window.scrollLeft = scale;

        e.preventDefault();
    });

});