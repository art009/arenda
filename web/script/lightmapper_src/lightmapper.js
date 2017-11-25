/*
 * Lightmapper, version 1.0
 *
 * (C) 2007 Dmitriy Khudorozhkov
 *
 * This software is provided "as-is", without any express or implied warranty.
 * In no event will the author be held liable for any damages arising from the
 * use of this software.
 *
 * Permission is granted to anyone to use this software for any purpose,
 * including commercial applications, and to alter it and redistribute it
 * freely, subject to the following restrictions:
 *
 * 1. The origin of this software must not be misrepresented; you must not
 *    claim that you wrote the original software. If you use this software
 *    in a product, an acknowledgment in the product documentation would be
 *    appreciated but is not required.
 *
 * 2. Altered source versions must be plainly marked as such, and must not be
 *    misrepresented as being the original software.
 *
 * 3. This notice may not be removed or altered from any source distribution.
 *
 *  - Dmitriy Khudorozhkov, kh_dmitry2001@mail.ru
 */

function Lightmapper(mapId,     // id of the MAP element,
                     imgId,     // id of the IMAGE element the map is bound to,
                     mouseOver, // global onMouseOver handler,
                     mouseOut,  // global onMouseOut handler
                     bindings)  // array of arrays of area/image/position/handler bindings:
                                // 
                                // [area_id, image_src, x_displacement, y_displacement, on_mouse_over, on_mouse_out, linked_area_id]
{
  this.mapId = mapId;
  this.imgId = imgId;

  this.binds = [];

  this.mouseOver = mouseOver || null;
  this.mouseOut  = mouseOut  || null;

  var img = new Image();

  for(var i = 0, l = bindings.length; i < l; i++)
  {
    var elem = this.binds[i] = [];
    var ref  = bindings[i];

    elem["area"] = ref[0];
    elem["isrc"] = img.src = ref[1];
    elem["xdsp"] = ref[2] || 0;
    elem["ydsp"] = ref[3] || 0;
    elem["musi"] = ref[4] || null;
    elem["muso"] = ref[5] || null;
    elem["link"] = ref[6] || null;
  }

  this._construct();
}

Lightmapper.prototype = {

  _idCount: 0,

  _construct: function()
  {
    var ct = document.getElementById(this.imgId);

    // Retrieve the position of the target image:
    var initX = 0, ct_ = ct;

    if(ct_.offsetParent)
    {
      while(ct_.offsetParent)
      {
        initX += ct_.offsetLeft;
        ct_ = ct_.offsetParent;
      }
    }
    else if(ct_.x)
      initX += ct_.x;

    var initY = 0; ct_ = ct;
    if(ct_.offsetParent)
    {
      while(ct_.offsetParent)
      {
        initY += ct_.offsetTop;
        ct_ = ct_.offsetParent;
      }
    }
    else if(ct_.y)
      initY += ct_.y;

    // create floting DIVs:
    for(var i = 0, l = this.binds.length; i < l; i++)
    {
      var elem = this.binds[i], area = document.getElementById(elem["area"]);

      // Calculate image position and dimensions:

      var img = document.createElement("IMG");
      img.src = elem["isrc"];
      img.border = "0";

      var div = elem.overlay = document.createElement("DIV");
      div.id = "ml_div_" + String(Lightmapper.prototype._idCount++);

      div.parentLightMapper = this;
      div.parentAreaId      = elem["area"];
      div.linkedAreaId      = elem["link"];

      div.appendChild(img);
      document.body.appendChild(div);

      // find area coords/width/height:
      var xmin = 0, xmax = 0, ymin = 0, ymax = 0;

      var coords = String(area.coords).split(",");
      xmin = xmax = parseInt(coords[0]);
      ymin = ymax = parseInt(coords[1]);

      var m = coords.length, k = 2;
      while(k < m)
      {
        var x = parseInt(coords[k++]);
        var y = parseInt(coords[k++]);

        if(x < xmin) xmin = x;
        if(x > xmax) xmax = x;

        if(y < ymin) ymin = y;
        if(y > ymax) ymax = y;
      }

      var dts = div.style;
      dts.position = "absolute";
      dts.top    = ymin + initY + elem["ydsp"];
      dts.left   = xmin + initX + elem["xdsp"];
      dts.width  = xmax - xmin;
      dts.height = ymax - ymin;
      dts.opacity = 0;
      dts.filter  = "alpha(opacity=0)";
      dts.display = "none";

      // create a map with a single area to bind to our div:
      var map = document.createElement("map");
      var mid = map.name = map.id = div.id + "_bound_map";

      img.useMap = "#" + mid;

      var area_new   = document.createElement("area");
      area_new.href  = area.href;
      area_new.shape = area.shape;

      k = 0;
      while(k < m)
      {
        coords[k++] -= xmin;
        coords[k++] -= ymin;
      }

      area_new.coords = coords.join(",");

      map.appendChild(area_new);
      document.body.appendChild(map);

      var mi = elem["musi"] || this.mouseOver;
      var mo = elem["muso"] || this.mouseOut;

      this._setup_event(area,    'mouseover', this._callLater(this._fade, div.id, 100, 50, 20, mi));
      this._setup_event(area_new, 'mouseout', this._callLater(this._fade, div.id,   0, 50, 20, mo));
    }
  },

  _callLater: function(func, param1, param2, param3, param4, param5)
  {
    return function() { func(param1, param2, param3, param4, param5); };
  },

  _setup_event: function(elem, eventType, handler)
  {    
    return (elem.attachEvent ? elem.attachEvent("on" + eventType, handler) :
      ((elem.addEventListener) ? elem.addEventListener(eventType, handler, false) : null));
  },

  _fade: function(id, destOp, rate, delta, callback)
  {
    var obj = document.getElementById(id);

    if(obj.timer) clearTimeout(obj.timer);

    var curOp = obj.filters ? obj.filters.alpha.opacity : (obj.style.opacity * 100.0);
    var direction = (curOp <= destOp) ? 1 : -1;
    var linked = null;

    var bindings = obj.parentLightMapper.binds;
    for(var i = 0, l = bindings.length; i < l; i++)
    {
      var elem = bindings[i].overlay;

      if(obj.linkedAreaId && (elem.parentAreaId == obj.linkedAreaId))
          linked = elem;
    }

    if((destOp < curOp) && (curOp == 100))
    {
      obj.style.zIndex  = 0;
    }
    else if((destOp > curOp) && (curOp == 0))
    {
      var bindings = obj.parentLightMapper.binds;
      for(var i = 0, l = bindings.length; i < l; i++)
      {
        var elem = bindings[i].overlay;

        if(elem != obj || elem != linked)
          Lightmapper.prototype._fade(elem.id, 0, 50, 20);
      }

      obj.style.display = "block";
      obj.style.zIndex  = 1;

      if(linked)
      {
        linked.style.display = "block";
        linked.style.zIndex  = 1;
      }
    }

    delta  = Math.min(direction * (destOp - curOp), delta);
    curOp += direction * delta;

    if(obj.filters)
    {
      obj.filters.alpha.opacity = curOp;

      if(linked) linked.filters.alpha.opacity = curOp;
    }
    else
    {
      obj.style.opacity = curOp / 100.0;

      if(linked) linked.style.opacity = curOp / 100.0;
    }

    if(curOp != destOp)
      obj.timer = setTimeout(function() { Lightmapper.prototype._fade(id, destOp, rate, delta, callback); }, rate);
    else
    {
      if(curOp == 0)
        obj.style.display = "none";

      if(callback)
        callback(obj.parentAreaId);
    }
  }
};