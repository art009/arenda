/**
 * Map Area Draw
 * 
 * @author Serhio Magpie <serdidg@gmail.com> http://screensider.com
 * @fork Sergey Glazun <t4gr1m@gmail.com> http://tagrim.ru
 * 
 */

/**
 * Painter
 * 
 * @param {object} buttons
 * @returns {object}
 */
painter = function(buttons, raw_data) {
  var nodes,
      context,
      that          = this,
      isCanvas      = false,
      points        = [],
      areas         = [],
      default_clear = {
        type  : 'button',
        //value : '<span class="glyphicon glyphicon-trash"></span> Очистить',
        value : 'Очистить',
        class : 'btn btn-default'
        //style : 'margin-right: 5px;'
      },
      default_add   = {
        type  : 'button',
        //value : '<span class="glyphicon glyphicon-plus"></span>',
        value : 'Добавить',
        class : 'btn btn-default'
      },
      default_submit = {
        type  : 'button',
        //value : '<span class="glyphicon glyphicon-floppy-save"></span>',
        value : 'Записать',
        class : 'btn btn-default'
      },
      default_save    = {
        type  : 'button',
        //value : '<span class="glyphicon glyphicon-floppy-saved"></span>',
        value : 'Закрыть',
        class : 'btn btn-default'
      };

  var checkCanvas = function() {
    if (nodes['canvas'].getContext) {
      isCanvas               = true;
      nodes['canvas'].width  = nodes['draw'].offsetWidth;
      nodes['canvas'].height = nodes['draw'].offsetHeight;
      context                = nodes['canvas'].getContext('2d');
    } else {
      _.remove(nodes['canvas']);
    }
  };

  var clearAllBtn = function() {
    var options                = (buttons && buttons.clear) ? Object.extend(default_clear, buttons.clear) : default_clear;

    if (_.isHTML(options['value'])) {
      var label = options['value'];
      options['value'] = '';
      var nodeEl = _.node('input', options);
      nodeEl.innerHTML = label;
    } else {
      var nodeEl = _.node('input', options);
    }
    nodes['clear_all']         = nodes['buttons'].appendChild(nodeEl);

    nodes['clear_all'].onclick = clearAll;
  };

  var addBtn = function() {
    var options          = (buttons && buttons.add) ? Object.extend(default_add, buttons.add) : default_add;
    nodes['add']         = nodes['buttons'].appendChild(_.node('input', options));
    nodes['add'].onclick = add;
  };

  var saveBtn = function() {
    var options           = (buttons && buttons.save) ? Object.extend(default_save, buttons.save) : default_save;
    nodes['save']         = nodes['buttons'].appendChild(_.node('input', options));
    nodes['save'].onclick = save;
  };

  var submitBtn = function() {
    var options           = (buttons && buttons.submit) ? Object.extend(default_submit, buttons.submit) : default_submit;
    nodes['sbmt']         = nodes['buttons'].appendChild(_.node('input', options));
    nodes['sbmt'].onclick = sbmt;
  };

  var sbmt = function() {
    var url = nodes['info'].getAttribute('data-href'),
      xhr = new XMLHttpRequest(),
        c_p = document.getElementsByName('csrf-param')[0].getAttribute('content'),
        c_v = document.getElementsByName('csrf-token')[0].getAttribute('content');

    var body = 'coordinates=' + encodeURIComponent(nodes['info'].value) + '&' + encodeURIComponent(c_p) + '=' + encodeURIComponent(c_v);

    xhr.open("POST", url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
      if (this.readyState != 4) return;

      location.reload();
    };

    xhr.send(body);
  };
// полная очистка от координат
  var clearAll = function() {
    clear();
    _.addClass(nodes['canvas'],'hidden');
    if (raw_data) {
      nodes['info'].value = '';
    } else {
      _.clearNode(nodes['info']);
    }
    points = [];
    areas  = [];

    // Clear preview from points or canvas
    if (isCanvas) {
      clearCanvas();
    } else {
      clearPoints();
    }
  };

  var add = function() {
    _.removeClass(nodes['canvas'],'hidden');
    nodes['info'].setAttribute('readonly', 'readonly');
    //
    if (raw_data){
      points = []; //hidden зачистим точки
      areas  = []; // зачистим старый массив элементов
      areasFromInfo();
    }
    _.remove(nodes['sbmt']);
    _.remove(nodes['add']);
    _.addClass(nodes['preview'], 'draw');
    _.addEvent(nodes['draw'], 'mousedown', addPoint);
    saveBtn();
  };
  // данный по координатам из textarea
  var areasFromInfo = function() {
    var text = nodes['info'].value,
        str_areas = text.match(/[^\r\n]+/g);
    if (text == '') return ;
    for (var i = 0, l = str_areas.length; i < l; i++){
      var coord_array = str_areas[i].split(',');
      for (var j = 0, lc = coord_array.length; j < lc; j = j+2){
        points.push({'x' : _.toNumber(coord_array[j]), 'y' : _.toNumber(coord_array[j+1])});
      }
      areas.push(_.clone(points));
      points = [];
      drawCanvasAll(); // перерисовка координат
    }
  };

  // событие на копке "сохранить"
  var save = function() {
    clear();
    areas.push(_.clone(points));
    points = [];
    _.addClass(nodes['canvas'],'hidden');
    renderInfo();
    nodes['info'].removeAttribute('readonly');
    if (nodes['info'].value)
      submitBtn();
  };

  var clear = function() {
    _.remove(nodes['add']);
    _.remove(nodes['save']);
    _.remove(nodes['sbmt']);
    _.removeClass(nodes['preview'], 'draw');
    _.removeEvent(nodes['draw'], 'mousedown', addPoint);
    addBtn();
  };

  var clearCanvas = function() {
    context.clearRect(0, 0, nodes['canvas'].width, nodes['canvas'].height);
  };

  var clearPoints = function() {
    _.clearNode(nodes['points']);
  };

  var addPoint = function(e) {
    var e      = _.getEvent(e),
        offset = _.getOffset(nodes['draw']),
        x      = e.clientX + _.getDocScrollLeft() - offset[0],
        y      = e.clientY + _.getDocScrollTop() - offset[1];

    points.push({'x' : x, 'y' : y});

    if (isCanvas) {
      drawCanvasAll();
    } else {
      drawHtmlPoint(x,y);
    }
    // Prevent drag event
    e.preventDefault && e.preventDefault();
    return false;
  };

  var drawHtmlPoint = function(x, y) {
    var node        = nodes['points'].appendChild(_.node('div', { class: 'point' }));
    node.style.top  = y - 1 + 'px';
    node.style.left = x - 1 + 'px';
  };

  var drawCanvasPoints = function(o) {
    // Draw lines
    context.fillStyle   = 'rgba(0, 172, 239, 0.2)';
    context.lineWidth   = 1;
    context.strokeStyle = 'rgba(0, 172, 239, 0.8)';
    context.beginPath();
    for (var i = 0, l = o.length; i < l; i++) {
      if (i === 0) {
        context.moveTo(o[i]['x'], o[i]['y']);
      } else {
        context.lineTo(o[i]['x'], o[i]['y']);
      }
    }
    context.closePath();
    context.fill();
    context.stroke();

    // Draw points
    context.fillStyle = 'rgba(0, 139, 191, 0.8)';
    for (var i = 0, l = o.length; i < l; i++) {
      context.fillRect(o[i]['x']- 2, o[i]['y']- 2, 4, 4);
    }
  };
// отрисовка всех объектов по координатам
  var drawCanvasAll = function() {
    clearCanvas();
    // Draw saved areas
    for (var i = 0, l = areas.length; i < l; i++) {
      drawCanvasPoints(areas[i]);
    }
    // Draw current area
    drawCanvasPoints(points);
  };
// отразить массив
  var renderInfo = function() {
    var text;

    _.clearNode(nodes['info']);

    if (!raw_data) {
      nodes['info'].appendChild(_.node('span', '<map>'));
      nodes['info'].appendChild(_.node('br'));
    } else {
      nodes['info'].value = '';
    }

    for (var i = 0, l = areas.length; i < l; i++) {
      if (areas[i].length > 0) {
        text = (raw_data) ? '' : '<area shape="poly" coords="';
        for (var i2 = 0, l2 = areas[i].length; i2 < l2; i2++) {
          if (i2 > 0) {
            text += ',';
          }
          text += areas[i][i2]['x'] + ',' + areas[i][i2]['y'];
        }
        text += (raw_data) ? '' : '">';
        if (raw_data) {
          nodes['info'].value = nodes['info'].value + text;
          nodes['info'].value = nodes['info'].value + '\r\n';
        } else {
          nodes['info'].appendChild(_.node('span', text));
          nodes['info'].appendChild(_.node('br'));
        }
      }
    }

    if (!raw_data) {
      nodes['info'].appendChild(_.node('span', '</map>'));
    }
  };

  that.init = function(options) {
    if (options) {
      if (options.length < 6) {
        // If options count less that 6, then merge with defaults
        nodes = Object.extend(options, nodes);
      } else {
        // Override options
        nodes = options;
      }
    } else {
      nodes = {
        preview: _.getEl('preview'),
        draw   : _.getEl('draw'),
        canvas : _.getEl('canvas'),
        points : _.getEl('points'),
        buttons: _.getEl('bar'),
        info   : _.getEl('info')
      };
    }

     checkCanvas();
    clearAllBtn();
    addBtn();
  };
};

window.onload = function() {
  var map_area = new painter({},true);
  map_area.init();
};