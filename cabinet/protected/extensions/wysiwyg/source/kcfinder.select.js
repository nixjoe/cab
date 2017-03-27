function openKCFinder(div) {
    window.KCFinder = {
        callBack: function (url) {
            window.KCFinder = null;
            var $div = $(div);
            $div.children('.preload, img').remove();
            $div.append('<div style="margin:5px" class="preload">Loading...</div>');
            var img = new Image();
            img.src = url;
            $div.children('input:hidden').val(url);
            img.onload = function () {
                $div.children('.preload').remove();
                $div.append('<img id="img" src="' + url + '" style="visibility:visible" />');
            }
        }
    };
    window.open($(div).data('url'),
        'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'directories=0, resizable=1, scrollbars=0, width=800, height=600'
    );
}

function openKCFinderInput(btn) {
    window.KCFinder = {
        callBack: function (url) {
            window.KCFinder = null;
            var $input = $(btn).siblings('input');
            $input.val(url);
        }
    };
    window.open($(btn).data('url'),
        'kcfinder_files', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'resizable=1, scrollbars=0, width=800, height=600'
    );
}