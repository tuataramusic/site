var ModularGrid={};ModularGrid.Utils={};ModularGrid.Utils.EventProvider=function(a,c,b){this.eventName=a;this.prepareParams=c;this.target=b||"document";this.handlers=null;return this;};ModularGrid.Utils.EventProvider.prototype.genericHandler=function(c){var d=(this.prepareParams?this.prepareParams(c):c);for(var a=0,b=this.handlers.length;a<b;a++){this.handlers[a](d);}};ModularGrid.Utils.EventProvider.prototype.initHandlers=function(){this.handlers=[];var code=this.target+".on"+this.eventName.toLowerCase()+" = function (event) { self.genericHandler(event); };";var self=this;eval(code);};ModularGrid.Utils.EventProvider.prototype.addHandler=function(a){if(this.handlers==null){this.initHandlers();}this.handlers.push(a);};ModularGrid.Utils.StateChanger=function(c,b,a){c.addHandler(function(d){if(b(d)){a();}});return this;};ModularGrid.Utils.getClientHeight=function(){var a=Math.max(document.documentElement.clientHeight,this.getDocumentBodyElement().offsetHeight);if(window.scrollMaxY){a=Math.max(a,window.scrollMaxY);}if(document.documentElement.scrollHeight){a=Math.max(a,document.documentElement.scrollHeight);}return a;};ModularGrid.Utils.getClientWidth=function(){var a=document.documentElement.clientWidth;return a;};ModularGrid.Utils.documentBodyElement=null;ModularGrid.Utils.getDocumentBodyElement=function(){if(this.documentBodyElement==null){this.documentBodyElement=document.getElementsByTagName("body")[0];}return this.documentBodyElement;};ModularGrid.Utils.createParams=function(c,d){var a={};for(var b in c){a[b]=c[b];}for(var b in d){a[b]=d[b];}return a;};ModularGrid.Utils.defaultStyleValueParams={display:"block",width:"100%",height:"100%",opacity:1,background:"transparent","float":"none",visibility:"visible",border:"0"};ModularGrid.Utils.createStyleValue=function(d){var c=ModularGrid.Utils.createParams(ModularGrid.Utils.defaultStyleValueParams,d);var a="";for(var b in c){if(c[b]){a+=b+":"+c[b]+";";}if(c[b]=="opacity"){a+="-khtml-opacity:"+c[b]+";-moz-opacity:"+c[b]+";filter:progid:DXImageTransform.Microsoft.Alpha(opacity="+(c[b]*100)+");";}}return a;};ModularGrid.OpacityChanger={};ModularGrid.OpacityChanger.defaults={shouldStepUpOpacity:function(b){var a=!b.occured_in_form&&(b.shiftKey&&b.keyCode==221);return a;},shouldStepDownOpacity:function(b){var a=!b.occured_in_form&&(b.shiftKey&&b.keyCode==219);return a;},opacity:0.25,opacityStep:0.05};ModularGrid.OpacityChanger.params=null;ModularGrid.OpacityChanger.handlers=null;ModularGrid.OpacityChanger.init=function(a){this.params=ModularGrid.Utils.createParams(this.defaults,a);this.handlers=[];};ModularGrid.OpacityChanger.stepDownOpacity=function(){this.params.opacity-=this.params.opacityStep;this.params.opacity=(this.params.opacity<0?0:this.params.opacity);this.updateOpacity(this.params.opacity);};ModularGrid.OpacityChanger.stepUpOpacity=function(){this.params.opacity+=this.params.opacityStep;this.params.opacity=(this.params.opacity>1?1:this.params.opacity);this.updateOpacity(this.params.opacity);};ModularGrid.OpacityChanger.addHandler=function(a){this.handlers.push(a);};ModularGrid.OpacityChanger.updateOpacity=function(a){for(var b=0,c=this.handlers.length;b<c;b++){this.handlers[b]();}};ModularGrid.OpacityChanger.changeElementOpacity=function(a){if(a){a.style.opacity=this.params.opacity;}};ModularGrid.Image={};ModularGrid.Image.defaults={shouldToggleVisibility:function(b){var a=!b.occured_in_form&&(b.ctrlKey&&(b.character=="\\"||b.keyCode==28||b.keyCode==220));return a;},"z-index":255,centered:false,marginTop:0,marginLeft:"0px",marginRight:"0px",src:"",width:100,height:100};ModularGrid.Image.showing=false;ModularGrid.Image.parentElement=null;ModularGrid.Image.params=null;ModularGrid.Image.imgElement=null;ModularGrid.Image.init=function(a){this.params=ModularGrid.Utils.createParams(this.defaults,a);};ModularGrid.Image.createParentElement=function(c){var b=document.createElement("div");var a={position:"absolute",left:"0",top:"0",width:"100%",height:c.height+"px",opacity:1,"z-index":c["z-index"]};b.setAttribute("style",ModularGrid.Utils.createStyleValue(a));b.appendChild(this.createImageDOM(c));ModularGrid.Utils.getDocumentBodyElement().appendChild(b);return b;};ModularGrid.Image.createImageDOM=function(d){var a={width:"auto",height:"auto",opacity:ModularGrid.OpacityChanger.params.opacity};var c={"padding-top":d.marginTop+"px",width:"auto",height:"auto"};if(d.centered){c["text-align"]="center";a.margin="0 auto";}else{c["padding-left"]=d.marginLeft,c["padding-right"]=d.marginRight;}var b=document.createElement("div");b.setAttribute("style",ModularGrid.Utils.createStyleValue(c));this.imgElement=document.createElement("img");this.imgElement.setAttribute("src",d.src);this.imgElement.setAttribute("width",d.width);this.imgElement.setAttribute("height",d.height);this.imgElement.setAttribute("style",ModularGrid.Utils.createStyleValue(a));b.appendChild(this.imgElement);return b;};ModularGrid.Image.opacityHandler=function(){ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Image.imgElement);};ModularGrid.Image.toggleVisibility=function(){this.showing=!this.showing;if(this.showing&&this.parentElement==null){this.parentElement=this.createParentElement(this.params);}if(this.parentElement){this.parentElement.style.display=(this.showing?"block":"none");}};ModularGrid.Guides={};ModularGrid.Guides.defaults={shouldToggleVisibility:function(b){var a=!b.occured_in_form&&(b.ctrlKey&&(b.character==";"||b.keyCode==186));return a;},lineStyle:"solid",lineColor:"#9dffff",lineWidth:"1px","z-index":255,items:[]};ModularGrid.Guides.showing=false;ModularGrid.Guides.parentElement=null;ModularGrid.Guides.params=null;ModularGrid.Guides.init=function(a){this.params=ModularGrid.Utils.createParams(this.defaults,a);};ModularGrid.Guides.createParentElement=function(b){var a=document.createElement("div");var c=ModularGrid.Utils.createStyleValue({position:"absolute",left:"0",top:"0",height:"100%",width:"100%","text-align":"center","z-index":b["z-index"]});a.setAttribute("style",c);a.innerHTML=this.createGuidesHTML(b.items);ModularGrid.Utils.getDocumentBodyElement().appendChild(a);return a;};ModularGrid.Guides.createGuidesHTML=function(b){var e="";if(b){var d,g,a=this.params.lineWidth+" "+this.params.lineStyle+" "+this.params.lineColor+" !important";for(var c=0,f=b.length;c<f;c++){d=b[c];g={position:"absolute"};switch(d.type){case"center":g.width="100%";g.height="100%";var h={width:d.width,height:"100%",margin:"0 auto","border-left":a,"border-right":a};e+='<div style="'+ModularGrid.Utils.createStyleValue(g)+'"><div style="'+ModularGrid.Utils.createStyleValue(h)+'"></div></div>';break;case"vertical":g.width="0px";g.height="100%";if(d.left!=null){g.left=d.left;g["border-right"]=a;}if(d.right!=null){g.right=d.right;g["border-left"]=a;}e+='<div style="'+ModularGrid.Utils.createStyleValue(g)+'"></div>';break;case"horizontal":g.width="100%";g.height="0px";if(d.top!=null){g.top=d.top;g["border-bottom"]=a;}if(d.bottom!=null){g.bottom=d.bottom;g["border-top"]=a;}e+='<div style="'+ModularGrid.Utils.createStyleValue(g)+'"></div>';break;}}}return e;};ModularGrid.Guides.toggleVisibility=function(){this.showing=!this.showing;if(this.showing&&this.parentElement==null){this.parentElement=this.createParentElement(this.params);}if(this.parentElement){this.parentElement.style.display=(this.showing?"block":"none");}};ModularGrid.Grid={};ModularGrid.Grid.defaults={shouldToggleVerticalGridVisibility:function(b){var a=!b.occured_in_form&&(b.shiftKey&&b.character=="v");return a;},shouldToggleHorizontalGridVisibility:function(b){var a=!b.occured_in_form&&(b.shiftKey&&b.character=="h");return a;},shouldToggleFontGridVisibility:function(b){var a=!b.occured_in_form&&(b.shiftKey&&b.character=="f");return a;},shouldToggleVisibility:function(b){var a=!b.occured_in_form&&(b.ctrlKey&&(b.character=="'"||b.keyCode==222));return a;},"z-index":255,color:"#F00",centered:true,prependGutter:false,appendGutter:false,gutter:16,vDivisions:6,hDivisions:4,marginTop:0,marginLeft:"18px",marginRight:"18px",width:464,minWidth:464,maxWidth:null,lineHeight:16,lineStyle:"solid",lineWidth:"1px",lineColor:"#555"};ModularGrid.Grid.showing=false;ModularGrid.Grid.fontGridShowing=false;ModularGrid.Grid.fontGridParentElement=null;ModularGrid.Grid.horizontalGridShowing=false;ModularGrid.Grid.horizontalGridParentElement=null;ModularGrid.Grid.verticalGridShowing=false;ModularGrid.Grid.verticalGridParentElement=null;ModularGrid.Grid.params=null;ModularGrid.Grid.init=function(a){this.params=ModularGrid.Utils.createParams(this.defaults,a);};ModularGrid.Grid.createParentElement=function(b){var a=ModularGrid.Utils.getDocumentBodyElement();a.appendChild(this.createVerticalGridParentElement(b));a.appendChild(this.createHorizontalGridParentElement(b));a.appendChild(this.createFontGridParentElement(b));return a;};ModularGrid.Grid.opacityHandler=function(){ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Grid.fontGridParentElement);ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Grid.verticalGridParentElement);ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Grid.horizontalGridParentElement);};ModularGrid.Grid.createVerticalGridParentElement=function(a){this.verticalGridParentElement=document.createElement("div");this.verticalGridParentElement.setAttribute("style",ModularGrid.Utils.createStyleValue({position:"absolute",left:"0",top:"0",display:"none",height:"100%",width:"100%",opacity:ModularGrid.OpacityChanger.params.opacity,"z-index":a["z-index"]}));this.verticalGridParentElement.innerHTML=this.createVerticalGridHTML(a);return this.verticalGridParentElement;};ModularGrid.Grid.createVerticalGridHTML=function(g){var k="";var l=(typeof(g.width)=="string"&&g.width.substr(g.width.length-1)=="%");var b=(l?g.minWidth:g.width);var j=g.vDivisions-1;(g.prependGutter?j++:null);(g.appendGutter?j++:null);var a=(g.gutter/b)*100;var f=(100-j*a)/g.vDivisions;var n=(g.prependGutter?a:0);var m={position:"relative","float":"left","margin-right":"-"+f+"%",width:f+"%",height:"100%",background:g.color,opacity:g.opacity};for(var h=0,c=g.vDivisions;h<c;h++){m.left=n+"%";k+='<div style="'+ModularGrid.Utils.createStyleValue(m)+'"></div>';n+=a+f;}var e={width:(l?g.width:b+"px")};if(g.centered){var o={"text-align":"center"};e.margin="0 auto";k='<div style="'+ModularGrid.Utils.createStyleValue(o)+'"><div style="'+ModularGrid.Utils.createStyleValue(e)+'">'+k+"</div></div>";}else{k='<div style="'+ModularGrid.Utils.createStyleValue(e)+'">'+k+"</div>";}var d={width:"auto",padding:"0 "+g.marginRight+" 0 "+g.marginLeft};k='<div style="'+ModularGrid.Utils.createStyleValue(d)+'">'+k+"</div>";return k;};ModularGrid.Grid.createHorizontalGridParentElement=function(a){this.horizontalGridParentElement=document.createElement("div");var b=ModularGrid.Utils.createStyleValue({position:"absolute",left:"0",top:"0",display:"none",height:ModularGrid.Utils.getClientHeight()+"px",width:"100%",opacity:ModularGrid.OpacityChanger.params.opacity,"z-index":a["z-index"]});this.horizontalGridParentElement.setAttribute("style",b);this.horizontalGridParentElement.innerHTML=this.createHorizontalGridHTML(a);return this.horizontalGridParentElement;};ModularGrid.Grid.createHorizontalGridHTML=function(g){var d="";var a=ModularGrid.Utils.getClientHeight();var h=g.marginTop;var c=0;var b=g.hDivisions+1;var f=g.lineHeight*g.hDivisions;var e={position:"absolute",width:"auto",left:g.marginLeft,right:g.marginRight,height:f+"px",background:g.color,opacity:g.opacity};while(h<a){if(c==0&&(h+f)<a){e.top=h+"px";d+='<div style="'+ModularGrid.Utils.createStyleValue(e)+'"></div>';}h+=g.lineHeight;c++;if(c==b){c=0;}}return d;};ModularGrid.Grid.createFontGridParentElement=function(a){this.fontGridParentElement=document.createElement("div");var b=ModularGrid.Utils.createStyleValue({position:"absolute",left:"0",top:"0",display:"none",height:ModularGrid.Utils.getClientHeight()+"px",width:"100%",opacity:ModularGrid.OpacityChanger.params.opacity,"z-index":a["z-index"]});this.fontGridParentElement.setAttribute("style",b);this.fontGridParentElement.innerHTML=this.createFontGridHTML(a);return this.fontGridParentElement;};ModularGrid.Grid.createFontGridHTML=function(d){var c="";var a=ModularGrid.Utils.getClientHeight();var e=d.marginTop+d.lineHeight;var b={position:"absolute",height:0,opacity:d.opacity,"border-bottom":d.lineWidth+" "+d.lineStyle+" "+d.lineColor+" !important"};while(e<a){b.top=(e+"px");c+='<div style="'+ModularGrid.Utils.createStyleValue(b)+'"></div>';e+=d.lineHeight;}return c;};ModularGrid.Grid.toggleVisibility=function(){this.showing=!this.showing;this.fontGridShowing=this.showing;this.horizontalGridShowing=this.showing;this.verticalGridShowing=this.showing;this.updateFontGridVisibility();this.updateHorizontalGridVisibility();this.updateVerticalGridVisibility();};ModularGrid.Grid.updateFontGridVisibility=function(){if(this.fontGridShowing&&this.fontGridParentElement==null){this.createParentElement(this.params);}if(this.fontGridParentElement){this.fontGridParentElement.style.display=(this.fontGridShowing?"block":"none");}};ModularGrid.Grid.updateHorizontalGridVisibility=function(){if(this.horizontalGridShowing&&this.horizontalGridParentElement==null){this.createParentElement(this.params);}if(this.horizontalGridParentElement){this.horizontalGridParentElement.style.display=(this.horizontalGridShowing?"block":"none");}};ModularGrid.Grid.updateVerticalGridVisibility=function(){if(this.verticalGridShowing&&this.verticalGridParentElement==null){this.createParentElement(this.params);}if(this.verticalGridParentElement){this.verticalGridParentElement.style.display=(this.verticalGridShowing?"block":"none");}};ModularGrid.Grid.toggleHorizontalGridVisibility=function(){this.horizontalGridShowing=!this.horizontalGridShowing;this.updateShowing();this.updateHorizontalGridVisibility();};ModularGrid.Grid.toggleVerticalGridVisibility=function(){this.verticalGridShowing=!this.verticalGridShowing;this.updateShowing();this.updateVerticalGridVisibility();};ModularGrid.Grid.toggleFontGridVisibility=function(){this.fontGridShowing=!this.fontGridShowing;this.updateShowing();this.updateFontGridVisibility();};ModularGrid.Grid.updateShowing=function(){this.showing=this.fontGridShowing||this.horizontalGridShowing||this.verticalGridShowing;};ModularGrid.Resizer={};ModularGrid.Resizer.defaults={shouldToggleSize:function(b){var a=!b.occured_in_form&&(b.shiftKey&&b.character=="r");return a;},changeTitle:true,sizes:[]};ModularGrid.Resizer.params=null;ModularGrid.Resizer.sizes=null;ModularGrid.Resizer.currentSizeIndex=null;ModularGrid.Resizer.title=null;ModularGrid.Resizer.detectDefaultSize=function(){var a=null;if(typeof(window.innerWidth)=="number"&&typeof(window.innerHeight)=="number"){a={width:window.innerWidth,height:window.innerHeight};}else{if(document.documentElement&&(document.documentElement.clientWidth||document.documentElement.clientHeight)){a={width:document.documentElement.clientWidth,height:document.documentElement.clientHeight};}else{if(document.body&&(document.body.clientWidth||document.body.clientHeight)){a={width:document.body.clientWidth,height:document.body.clientHeight};}}}return a;};ModularGrid.Resizer.getDefaultSize=function(){return this.sizes[0];};ModularGrid.Resizer.getCurrentSize=function(){return this.sizes[this.currentSizeIndex];};ModularGrid.Resizer.init=function(f,b){this.params=ModularGrid.Utils.createParams(this.defaults,f);var e=this.detectDefaultSize();if(e){var d=[e];if(this.params.sizes.length){for(var a=0,c=this.params.sizes.length;a<c;a++){d.push(this.params.sizes[a]);}}else{if(b.params.minWidth){d.push({width:b.params.minWidth});}}if(d.length>1){if(this.params.changeTitle){this.title=document.title;}this.sizes=d;this.currentSizeIndex=0;}}};ModularGrid.Resizer.toggleSize=function(){if(this.currentSizeIndex!=null){this.currentSizeIndex++;this.currentSizeIndex=(this.currentSizeIndex==this.sizes.length?0:this.currentSizeIndex);var c=(this.getCurrentSize().width?this.getCurrentSize().width:this.getDefaultSize().width);var a=(this.getCurrentSize().height?this.getCurrentSize().height:this.getDefaultSize().height);window.resizeTo(c,a);if(this.params.changeTitle){var b=(this.currentSizeIndex?this.title+" ("+c+"×"+a+")":this.title);if(this.getCurrentSize().title){b=this.getCurrentSize().title;}document.title=b;}}};ModularGrid.keyDownEventProvider=null;ModularGrid.resizeEventProvider=null;ModularGrid.getResizeEventProvider=function(){if(this.resizeEventProvider==null){this.resizeEventProvider=new ModularGrid.Utils.EventProvider("resize",function(a){return{event:a};},"window");}return this.resizeEventProvider;};ModularGrid.getKeyDownEventProvider=function(){if(this.keyDownEventProvider==null){this.keyDownEventProvider=new ModularGrid.Utils.EventProvider("keydown",function(c){var b=(c||window.event);var e=(b.keyCode?b.keyCode:(b.which?b.which:b.keyChar));var d=String.fromCharCode(e).toLowerCase();var f={"`":"~","1":"!","2":"@","3":"#","4":"$","5":"%","6":"^","7":"&","8":"*","9":"(","0":")","-":"_","=":"+",";":":","'":'"',",":"<",".":">","/":"?","\\":"|"};if(b.shiftKey&&f[d]){d=f[d];}var a=(b.target?b.target:b.srcElement);if(a&&a.nodeType==3){a=a.parentNode;}var g=(a&&(a.tagName=="INPUT"||a.tagName=="TEXTAREA"));return{occured_in_form:g,character:d,keyCode:e,altKey:b.altKey,shiftKey:b.shiftKey,ctrlKey:b.ctrlKey,event:b};});}return this.keyDownEventProvider;};ModularGrid.init=function(e){var k=this;this.OpacityChanger.init(e.opacity);var h=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.OpacityChanger.params.shouldStepUpOpacity,function(){k.OpacityChanger.stepUpOpacity();});var i=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.OpacityChanger.params.shouldStepDownOpacity,function(){k.OpacityChanger.stepDownOpacity();});this.Image.init(e.image);this.OpacityChanger.addHandler(this.Image.opacityHandler);var b=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Image.params.shouldToggleVisibility,function(){k.Image.toggleVisibility();});this.Guides.init(e.guides);var f=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Guides.params.shouldToggleVisibility,function(){k.Guides.toggleVisibility();});this.Grid.init(e.grid);this.OpacityChanger.addHandler(this.Grid.opacityHandler);var a=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Grid.params.shouldToggleVisibility,function(){k.Grid.toggleVisibility();});var d=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Grid.params.shouldToggleFontGridVisibility,function(){k.Grid.toggleFontGridVisibility();});var c=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Grid.params.shouldToggleHorizontalGridVisibility,function(){k.Grid.toggleHorizontalGridVisibility();});var g=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Grid.params.shouldToggleVerticalGridVisibility,function(){k.Grid.toggleVerticalGridVisibility();});this.Resizer.init(e.resizer,this.Grid);var j=new ModularGrid.Utils.StateChanger(this.getKeyDownEventProvider(),this.Resizer.params.shouldToggleSize,function(){k.Resizer.toggleSize();});};/** @include "index.js" */

/**
 * Настройки.
 *
 * Любые настройки ниже - настройки по-умолчанию, вы можете удалить их,
 * если они вам не нужны.
 */
ModularGrid.init(
	{
		// настройки гайдов
		guides: {
			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно показать/скрыть направляющие
			 */
			shouldToggleVisibility:
				function (params) {
					// Ctrl + ;
					var result = !params.occured_in_form && (params.ctrlKey && (params.character == ';' || params.keyCode == 186));
					return result;
				},

			/**
			 * Стиль линий-направляющих.
			 * Значения аналогичны значениям CSS-свойства border-style.
			 * @type String
			 */
			lineStyle: 'solid',
			/**
			 * Цвет линий-направляющих.
			 * Значения аналогичны значениям CSS-свойства border-color.
			 * @type String
			 */
			lineColor: '#9dffff',
			/**
			 * Толщина линий-направляющих.
			 * Значения аналогичны значениям CSS-свойства border-width.
			 * @type String
			 */
			lineWidth: '1px',

			/**
			 * значения CSS-свойства z-index HTML-контейнера всех направляющих
			 * @type Number
			 */
			'z-index': 255,

			/**
			 * Массив настроек направляющих (задается в формате items:[{настройки-1},{настройки-2},...,{настройки-N}]).
			 * @type Array
			 */
			items: [
				{
					/**
					 * Две центрированные направляющие
					 *
					 * Ширина задается параметром width (значения аналогичны значениям CSS-свойства width),
					 * две направляющие рисуются слева и справа от центрированной области заданной ширины.
					 */
					type: 'center',
					width: '600px'
				},
				{
					/**
					 * Одна вертикальная направляющая
					 *
					 * Можно задать либо отступ от левого края рабочей области браузера параметром left,
					 * либо отступ от правого края рабочей области браузера параметром right.
					 * Значения параметров аналогичны значениям CSS-свойства left.
					 */
					type: 'vertical',
					left: '33%'
				},
				{
					/**
					 * Одна горизонтальная направляющая
					 *
					 * Можно задать либо отступ от верхнего края рабочей области браузера параметром top,
					 * либо отступ от нижнего края рабочей области браузера параметром bottom.
					 * Значения параметров аналогичны значениям CSS-свойства top.
					 */
					type: 'horizontal',
					top: '48px'
				}
			]
		},

		grid: {
			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно показать/скрыть вертикальную сетку
			 */
			shouldToggleVerticalGridVisibility:
				function (params) {
					// Shift + v
					var result = !params.occured_in_form && (params.shiftKey && params.character == 'v' );
					return result;
				},

			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно показать/скрыть горизонтальную сетку
			 */
			shouldToggleHorizontalGridVisibility:
				function (params) {
					// Shift + h
					var result = !params.occured_in_form && (params.shiftKey && params.character == 'h' );
					return result;
				},

			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно показать/скыть шрифтовую сетку
			 */
			shouldToggleFontGridVisibility:
				function (params) {
					// Shift + f
					var result = !params.occured_in_form && (params.shiftKey && params.character == 'f' );
					return result;
				},

			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно показать/скрыть сетку целиком
			 */
			shouldToggleVisibility:
				function (params) {
					// Ctrl + '
					// скрывает если хотя бы один из элементов сетки показан (шрифтовая, колонки или строки)
					var result = !params.occured_in_form && (params.ctrlKey && (params.character == "'" || params.keyCode == 222));
					return result;
				},

			'z-index': 255,

			/**
			 * Цвет фона колонок и строк модульной сетки.
			 * Цвет линий шрифтовой сетки задаётся отдельно.
			 * @see lineColor
			 * @type String
			 */
			color: "#F00",

			/**
			 * Центрировать ли сетку
			 * @type Boolean
			 */
			centered: true,

			prependGutter: false,
			appendGutter: false,

			gutter: 16,

			/**
			 * Количество вертикальных модулей (столбцов сетки)
			 * @see lineHeight
			 * @type Number
			 */
			vDivisions: 3,

			/**
			 * Высота строки модульной сетки в строках модульной сетки.
			 * @see lineHeight
			 * @type Number
			 */
			hDivisions: 3,

			/**
			 * Отступ от верхнего края рабочей области браузера до шрифтовой и горизонтальной сетки в пикселах.
			 * @type Number
			 */
			marginTop: 0,
			/**
			 * Отступ от левого края рабочей области браузера до сетки.
			 * Значения аналогичны значениям CSS-свойства margin-left
			 * @type Number
			 */
			marginLeft: '0px',
			/**
			 * Отступ от правого края рабочей области браузера до сетки.
			 * Значения аналогичны значениям CSS-свойства margin-right
			 * @type Number
			 */
			marginRight: '0px',

			width: 600,
			minWidth: null,
			maxWidth: null,

			/**
			 * Высота строки в пикселах.
			 * Используется для рисования шрифтовой сетки.
			 * Сама линия сетки начинает рисоваться на (lineHeight + 1) пикселе
			 * @type Number
			 */
			lineHeight: 16,

			// стиль линий шрифтовой сетки
			/**
			 * Стиль линий шрифтовой сетки.
			 * Значения аналогичны значениям CSS-свойства border-style
			 * @type String
			 */
			lineStyle: 'solid',
			/**
			 * Толщина линий шрифтовой сетки.
			 * Значения аналогичны значениям CSS-свойства border-width
			 * @type String
			 */
			lineWidth: '1px',
			/**
			 * Цвет линий шрифтовой сетки.
			 * Значения аналогичны значениям CSS-свойства border-color
			 * @type String
			 */
			lineColor: "#555"
		},

		resizer: {
			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно изменить размер на следующий из заданных
			 */
			shouldToggleSize:
				function (params) {
					// Shift + r
					var result = !params.occured_in_form && (params.shiftKey && params.character == 'r');
					return result;
				},

			/**
			 * Нужно ли в title окна указывать разрешение
			 * @type Boolean
			 */
			changeTitle: true,

			sizes:
				[
					{
						width: 640,
						height: 480
					},
					{
						width: 800,
						height: 600
					},
					{
						width: 1024,
						height: 768
					}
				]
		},

		// настройки макета-изображения
		image: {
			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно показать/скрыть изображение
			 */
			shouldToggleVisibility:
				function (params) {
					// Ctrl + \
					var result = !params.occured_in_form && (params.ctrlKey && (params.character == '\\' || params.keyCode == 28 || params.keyCode == 220));
					return result;
				},

			/**
			 * Значения CSS-свойства z-index HTML-контейнера изображения
			 * @type Number
			 */
			'z-index': 255,

			/**
			 * Начальное значение прозрачности изображения от 0 до 1 (0 - абсолютно прозрачное, 1 - абсолютно непрозрачное)
			 * @type Number
			 */
			opacity: 0.85,
			/**
			 * Шаг изменения значения прозрачности для изображения от 0 до 1
			 * @type Number
			 */
			opacityStep: 0.05,

			/**
			 * Центрировать ли изображение относительно ширины рабочей области браузера
			 * @type Boolean
			 */
			centered: true,

			/**
			 * Отступ от верхнего края рабочей области браузера до изображения в пикселах
			 * @type Number
			 */
			marginTop: 100,
			/**
			 * Отступ от левого края рабочей области браузера до изображения.
			 * Возможные значения аналогичны значениям CSS-свойства margin-left
			 * @type Number
			 */
			marginLeft: '0px',
			/**
			 * Отступ от правого края рабочей области браузера до изображения.
			 * Возможные значения аналогичны значениям CSS-свойства margin-left
			 * @type Number
			 */
			marginRight: '0px',

			/**
			 * URL файла изображения
			 * @type String
			 */
			src: 'design.png',

			/**
			 * Ширина изображения в пикселах
			 * @type Number
			 */
			width: 300,
			/**
			 * Высота изображения в пикселах
			 * @type Number
			 */
			height: 356
		},

		opacity: {
			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно сделать изображение менее прозрачным на opacityStep процентов
			 */
			shouldStepUpOpacity:
				function (params) {
					// Shift + ]
					var result = !params.occured_in_form && (params.shiftKey && params.keyCode == 221);
					return result;
				},
			/**
			 * Функция вызывается каждый раз при нажатии клавиш в браузере.
			 * @param {Object} params информация о нажатой комбинации клавиш (params.ctrlKey, params.altKey, params.keyCode)
			 * @return {Boolean} true, если нужно сделать изображение более прозрачным на opacityStep процентов
			 */
			shouldStepDownOpacity:
				function (params) {
					// Shift + [
					var result = !params.occured_in_form && (params.shiftKey && params.keyCode == 219);
					return result;
				},

			/**
			 * Начальное значение прозрачности изображения от 0 до 1 (0 - абсолютно прозрачное, 1 - абсолютно непрозрачное)
			 * @type Number
			 */
			opacity: 0.25,
			/**
			 * Шаг изменения значения прозрачности для изображения от 0 до 1
			 * @type Number
			 */
			opacityStep: 0.05
		}

	}
);