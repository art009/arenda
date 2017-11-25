/* ******* Simple Poly Draw   ******* */
/* ******* @ Serhio Magpie    ******* */

/* serdidg@gmail.com      */
/* http://screensider.com */

var Painter = new function(){
	var that = this,
		nodes,
		context,
		points = [];
		
	
	var clearBtn = function(){
		var node = nodes['buttons'].appendChild(_.node('input', {'type':'button', 'value':'Clear'}));
		node.onclick = clear;
	};
		
	var renderBtn = function(){
		var node = nodes['buttons'].appendChild(_.node('input', {'type':'button', 'value':'Render'}));
		node.onclick = render;
	};
	
	var clear = function(){
		points = [];
		context.clearRect(0, 0, nodes['canvas'].width, nodes['canvas'].height);
	};
	
	var render = function(){
		points = JSON.parse(nodes['text'].value);
		// Draw canvas
		points.forEach(function(item){
			drawCanvasPoints(item);
		});
	};

	var drawCanvasPoints = function(o){
		// Draw lines
		context.fillStyle = 'rgba(0,172,239,0.2)';
		context.lineWidth = 1;
		context.strokeStyle = 'rgba(0,172,239,0.8)';
		context.beginPath();
		o.forEach(function(item, i){
			if(i == 0){
				context.moveTo(item[0], item[1]);
			}else{
				context.lineTo(item[0], item[1]);
			}
		});
		context.closePath();
		context.fill();
		context.stroke();
		// Draw points
		context.fillStyle = 'rgba(0,139,191,0.8)';
		o.forEach(function(item){
			context.fillRect(item[0] - 2, item[1] - 2, 4, 4);
		});
	};
	
	that.init = function(){
		nodes = {
			'draw' : _.getEl('draw'),
			'canvas' : _.getEl('canvas'),
			'buttons' : _.getEl('bar'),
			'text' : _.getEl('text')
		};
		// Set canvas
		nodes['canvas'].width = nodes['draw'].offsetWidth;
		nodes['canvas'].height = nodes['draw'].offsetHeight;
		context = nodes['canvas'].getContext('2d');
		// Render buttons
		clearBtn();
		renderBtn();
	};
};

_.addEvent(window, 'load', Painter.init);