jQuery( document ).ready(function( $ ) {
    $("input[id$='highlight-team']").each(function(){
        if($(this).is(':checked')) {
            $(this).parent().next('p#changeTeam').show();
            $(this).parent().next('p#your-team').hide();
        }
        else {
            $(this).parent().next('p#changeTeam').hide();
            $(this).parent().next('p#your-team').hide();
        }
    })
    
    $("input[id$='highlight-team']").live('click',function(){
        if($(this).is(':checked')) {
            $(this).parent().next('p#changeTeam').show();
        }
        else {
            $(this).parent().next('p#changeTeam').hide();
            $(this).parent().next('p#your-team').hide();
        }
    });
    $('p#changeTeam span').live('click',function(){
        $(this).parent().append('&nbsp;<img src='+$('#plugin-url').val()+'ajax-loader.gif'+' alt="" />')
        _this_ = $(this);
        $.ajax({
            url: $('#plugin-url').val()+'getteams.php',
            data: {theteam: $(this).parent().prev('p').find("input[id$='highlight-team']").attr('class'), league: $(this).parent().parent().find("select[id$='-league']").val()},
            success: function (response) {
                console.log(response);
                $(_this_).parent().find('img').remove();
                $(_this_).parent().next('p').find("select[id$='your-team']").prepend(response);
            },
            complete: function(response) {
                $(_this_).parent().next('p#your-team').show();
            }
        });
    });
    
});
