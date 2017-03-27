        
$('.overlayed').live('focus',function(){
    $(this).siblings('.label-overlay').hide();
});
$('.overlayed').live('blur',function(){
    if ($(this).val()=='') $(this).siblings('label.label-overlay').show();
});
$('.label-overlay').click(function(){
    $(this).hide();
    $(this).siblings('.ui-autocomplete').focus();
});
if ($('input.overlayed').val()!=='') $('input.overlayed').siblings('label').hide();