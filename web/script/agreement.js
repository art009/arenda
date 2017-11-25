function showInform(th,e)
{
    e.preventDefault();
    e.stopPropagation();
    var modal = $("#modal-modif"),
        url = $(th).attr("href");
    $.post(url,{},function(data){
        modal.find('.modal-body').empty().append(data);
        modal.modal('show');
    });
}
// кнопка показать/скрыть форму добавления договора
function addAgreement(th,e)
{
    e.preventDefault();
    e.stopPropagation();
    $('#form-add-agreement').toggle();
}
// добавим помещение к договору
function saveAddAgreement(th)
{
    var url = $(th).attr('data-href'),
        agreement = $('[name=agreement]').val();
    $.post(
        url,
        {
            agreement: agreement
        },
        function (){
            location.reload();
        }
    )
}

function rmQuarter(th,e) {
    e.preventDefault();
    e.stopPropagation();
    if (confirm("Удалить помещение из договора?")) {
        var url = $(th).attr('href');
        $.post(
            url,
            {},
            function () {
                $(th).parents('tr').remove();
            }
        )
    }
}
