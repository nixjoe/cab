    function isScrolledIntoView(elem)
    {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }
    $('.unfolder').live('click',function(){
        element = $(this);
        a = element.hasClass('active');
        if (a) {} else {
            // Deactivate any other active ones
            element.parent().siblings().children('.foldable').slideUp('fast', function() {
                element.parent().siblings().children('.unfolder').removeClass('active');
            });
        }
        $(this).siblings('.foldable').slideToggle(300, function() {
            if (a) {} else {
                if (!isScrolledIntoView(element.parent())) $("html, body").animate({ scrollTop: (element.parent().offset().top + 'px') });
            }
        });
        element.toggleClass('active');
    });  