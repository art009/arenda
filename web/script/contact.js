//показать модальное окно
function showForm(th, e)
{
    e.preventDefault();
    e.stopPropagation();
    var url = $(th).attr('href'),
        module = $('#modal-modif');
    $.post(url,{},function(data){
        module.find('.modal-body').empty().append(data);
        module.modal('show');
    });
}
// сохранение формы
function saveForm(th,e)
{
    e.preventDefault();
    e.stopPropagation();
    var url = $(th).attr('href'),
        form = $(th).parents('form'),
        dataSend = $(form).serialize(),
        module = $('#modal-modif');
    $.post(url,dataSend,function(data){
        module.find('.modal-body').empty().append(data);
        updateTable();
    });
}
// удаление записи
function rmContact(th,e)
{
    e.preventDefault();
    e.stopPropagation();
    var url = $(th).attr('href');
    $.post(
        url,
        {},
        function(data){
            updateTable();
        }
    );
}
// обновить таблицу
function updateTable()
{
    var wrap = $('#contact-table'),
        table = wrap.find('table'),
        url = $(table).attr('data-url');
    $.post(url,{},function(data){
        wrap.empty().append(data);
    });

}
