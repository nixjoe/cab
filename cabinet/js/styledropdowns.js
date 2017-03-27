$('.select-container .valuebox:not(.active)').live('click',function(){
    a = $('.select-container .valuebox.active');
    var self = $(this);
    if (a.length >0)
    a.siblings('.radiobox').slideUp(175, 'swing', function() {
        a.removeClass('active');            
        self.addClass('active');
        self.siblings('.radiobox').slideDown(175, 'swing');            

    })
    else {
        self.addClass('active');
        self.siblings('.radiobox').slideDown(175, 'swing');                        
    };    
});
$('body').live('click',function(){
    if ($('.select-container .valuebox:hover, .jspTrack:hover').length ==0) {
        a = $('.select-container .valuebox.active');
        a.siblings('.radiobox').slideUp(175, 'swing', function(){
            a.removeClass('active');
        })

    }
});
$('.select-container .valuebox.active').live('click',function(){
    a = $(this);
    a.siblings('.radiobox').slideUp(175, 'swing', function() {
        a.removeClass('active');            
    })
});
$('.trow input:radio').change(function(){
	//if ($(this).parents('.padtable').find('input.on').length > 0){
		$(this).parents('.padtable').find('.trow label').css({"background-color":"inherit"});
	//}
    
    $(this).next().css({"background-color":"#2c2d2f"});	
});
$('.select-container .radiobox :radio').live('change',function(){
    $('.select-container .radiobox label').css("background-color","inherit");
    $(this).next().css("background-color","#585b61");
    $(this).parents('.radiobox').siblings('.valuebox').children('p').html($(this).siblings(':checked+label').text());
    if( $(this).parents('.select-container').hasClass('langs')){
	var langPart = document.location.href.split('//')[1].split('/')[1];
	var url = '/'+$(this).val();	

	var p1 = document.location.href.split('//')[1].split('/')[1];
	var p2 = document.location.href.split('//')[1].split('/')[2];

	if(langPart.search(/^[A-Za-z]{2}$/) != -1) {
		for(var i = 2; i < document.location.href.split('//')[1].split('/').length; i++) {
				url += '/'+document.location.href.split('//')[1].split('/')[i];
		}

	} else if(p1 == 'payment' && p2 == 'payment') {

		url = '/'+document.location.href.split('//')[1].split('/').splice(1).join('/')+'&language='+$(this).val();
			
	} else {
		for(var i = 1; i < document.location.href.split('//')[1].split('/').length; i++) {
			if(document.location.href.split('//')[1].split('/')[i] != 'index.php')
			url += '/'+document.location.href.split('//')[1].split('/')[i];
		}
	}

	 window.location = url;
    }

})

    $('#select-country .scrollable ').jScrollPane({
        mouseWheelSpeed : 145,
        trackClickSpeed : 145,
        arrowButtonSpeed : 145,
        contentWidth : '100%',
        verticalGutter : -5,
        verticalDragMinHeight : 58,
        verticalDragMaxHeight : 58,
        animateScroll : true,
        animateDuration : 150,
        animateEase : 'swing'
    });
    $('#select-birthYear .scrollable ').jScrollPane({
        mouseWheelSpeed : 145,
        trackClickSpeed : 145,
        arrowButtonSpeed : 145,
        contentWidth : '100%',
        verticalGutter : -5,
        verticalDragMinHeight : 58,
        verticalDragMaxHeight : 58,
        animateScroll : true,
        animateDuration : 150,
        animateEase : 'swing'
    });
    $('.select-container .radiobox').css("visibility", "visible").hide();
    $('.optcontainer :checked+label').each(function(){
        $(this).closest('.radiobox').siblings('.valuebox').children('p').text($(this).text())
    });