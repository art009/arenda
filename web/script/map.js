$(document).ready(function () {
    init();
    setLabelToArea();
});

function init(){
    $(".map").maphilight({
        fillColor: "008800"
    });
}

function modifRoom(th,e)
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
        //updateTable();
    });
}
// удаление координат
function removeCoords(th,e)
{
    e.preventDefault();
    e.stopPropagation();
    if (confirm("Вы хотите удалить координаты?")){
        var url = $(th).attr('href');
        $.post(url,{},function($data){
            location.reload();
        });
    } else {
        return false;
    }
}

function setLabelToArea()
{
    drawCanvas();
}

function drawCanvas()
{
    var canvas = $('<canvas>'),
        image = $("img.map");
    canvas.attr({
        'height' : image.height(),
        'width' : image.width(),
        'id' : 'canvas_map'
    }).css({
        'width'     : image.width() + 'px',
        'height'    : image.height() + 'px',
        'position'  : 'absolute',
        'left'      : '0px',
        'top'       : '0px',
        'padding'   : '0px',
        'border'    : '0px none'
    });
    image.parent().prepend(canvas);
//console.log(image.parent());
    var coords = coordPoint();
    ctx = $('#canvas_map').get(0).getContext('2d');

    for ( var i = 0, l = coords.length ; i < l ; i++){
        ctx.beginPath();
        ctx.fillStyle = "black";
        ctx.arc(coords[i][0] + 4, coords[i][1] + 10, 5, 0, Math.PI*2, true); // рисуем точки
        ctx.fill(); // закрашиваем
        ctx.fillStyle = "orange";
        ctx.arc(coords[i][0] + 4, coords[i][1] + 10, 4, 0, Math.PI*2, true); // рисуем точки
        ctx.fill(); // закрашиваем
        ctx.closePath();
    }

}
// список координат клевый - верх у area
function coordPoint()
{
    var elsHasLegal = $('[data-legal]'),
        resultPoints = new Array();
    for (var i = 0, l = elsHasLegal.length ; i < l ; i++){
        var coordinate = searchLeftTopPoint($(elsHasLegal[i]).attr('coords')),
            j = $(elsHasLegal[i]).attr('data-legal');
        while (j > 0){
            var mod_c = [
                coordinate[0] + 6 * j,
                coordinate[1]
            ];
            resultPoints.push(mod_c);
            j--;
        }

    }
    return resultPoints;
}
// поиск координат левый - верх
function searchLeftTopPoint(str)
{
    var a_point = str.split(','),
        a_x = new Array,
        a_y = new Array;
    a_point = a_point.map(function(point) {
        return toNumber(point);
    });
    for (var i = 0, l = a_point.length ; i<l ; i = i+2){
        a_x.push(a_point[i]);
    }
    var leftX = Array.min(a_x); // крайний левый х
    for (var i = 1, l = a_point.length ; i<l ; i = i+2){
        if (a_point[i-1] == leftX)
            a_y.push(a_point[i]);
    }
    var leftY = Array.min(a_y);
    return [leftX,leftY];
}
// поиск минимального значения в массиве
Array.min = function( array ){
    return Math.min.apply( Math, array );
};
// из строки делает целое число
function toNumber(str) { return parseInt(str.replace(/\s+/, '')); };