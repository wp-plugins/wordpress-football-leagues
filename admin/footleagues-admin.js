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
            data: {theteam: $(this).parent().prev('p').find("input[id$='highlight-team']").attr('class'), country: $(this).parent().parent().find("select[id$='-country']").val(), league: $(this).parent().parent().find("select[id$='-league']").val()},
            success: function (response) {
                console.log(response);
                $(_this_).parent().find('img').remove();
                $(_this_).parent().next('p').find("select[id$='your-team']").html(response);
            },
            complete: function(response) {
                $(_this_).parent().next('p#your-team').show();
            }
        });
    });
    
    $("select[id$='-country']").live('change', function() {
        $eng  = '<option value="pl">Premier League</option>';
		$eng += '<option value="ch">Championship</option>';
		$eng += '<option value="l1">League 1</option>';
		$eng += '<option value="l2">League 2</option>';
        
        $sco  = '<option value="spl">Premiership</option>';
        $sco += '<option value="sch">Championship</option>';
		$sco += '<option value="sl1">League 1</option>';
		$sco += '<option value="sl2">League 2</option>';
        
        $nor = '<option value="nor1">Tippeligaen</option>';

        if ($(this).val() == 'eng') {
            $(this).parent().next('p').find('select').html($eng);
        }
        else if ($(this).val() == 'sco') {
            $(this).parent().next('p').find('select').html($sco);
        }
        else if ($(this).val() == 'nor') {
            $(this).parent().next('p').find('select').html($nor);
        }
        
    });
    
});
