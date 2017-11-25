function selectBuilding(th)
{
    var url = $(th).attr('data-url'),
        wrap = $('#level-building');
    $(th).parent().find('tr.active').each(function(){
        $(this).removeClass('active');
    });
    $(th).addClass('active');

    $.post(
        url,
        {},
        function(data){
            wrap.empty().append(data);
        }
    );
}