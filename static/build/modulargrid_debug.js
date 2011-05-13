/** @namespace */
var ModularGrid = {};/** @include "../index.js" */
ModularGrid.Utils = {};/** @include "index.js" */

/**
 * Обертка события от браузера.
 * @constructor
 * @param {String} eventName название события, например "keydown"
 * @param {Function} prepareParams преобразователь event браузера в хеш для обработчика
 * @return {ModularGrid.EventProvider}
 */
ModularGrid.Utils.EventProvider = function(eventName, prepareParams, target) {
	this.eventName = eventName;
	this.prepareParams = prepareParams;
	this.target = target || 'document';

	this.handlers = null;

	return this;
};

/**
 * Формирует хеш параметоров с помощью this.prepareParams и вызывает все обработчики
 * @private
 * @param {Object} event
 */
ModularGrid.Utils.EventProvider.prototype.genericHandler = function(event) {
	var params = (this.prepareParams ? this.prepareParams(event) : event);

	for(var i = 0, length = this.handlers.length; i < length; i++)
		this.handlers[i](params);
};

/**
 * Создает массив обработчиков, вешает обработчик события браузера
 * @private
 */
ModularGrid.Utils.EventProvider.prototype.initHandlers = function () {
	this.handlers = [];

	var code = this.target + '.on' + this.eventName.toLowerCase() +  ' = function (event) { self.genericHandler(event); };';

	var self = this;
	eval(code);
};

/**
 * Добавляет обработчик события в конец очереди обработчиков
 * @param {Function} handler обработчик события
 */
ModularGrid.Utils.EventProvider.prototype.addHandler = function (handler) {
	if ( this.handlers == null )
		this.initHandlers();

	this.handlers.push(handler);
};/** @include "index.js" */

/**
 * Меняет состояние объекта по внешнему событию.
 * @constructor
 * @param {ModularGrid.EventProvider} eventProvider прослойка, чье событие слушать
 * @param {Function} shouldChange если вернет true при возникновении события от eventProvider, то вызовится stateChange
 * @param {Function} stateChange вызывается, когда нужно поменять состояние
 * @return {ModularGrid.StateChanger}
 */
ModularGrid.Utils.StateChanger = function (eventProvider, shouldChange, stateChange) {
	eventProvider.addHandler(
		function (params) {
			if ( shouldChange(params) )
				stateChange();
		}
	);

	return this;
};
/** @include "namespace.js" */

/**
 * @return {Number} высота области для сетки в пикселах
 */
ModularGrid.Utils.getClientHeight = function () {
	var height = Math.max(document.documentElement.clientHeight, this.getDocumentBodyElement().offsetHeight);

	if ( window.scrollMaxY )
		height = Math.max(height, window.scrollMaxY);

	if ( document.documentElement.scrollHeight )
		height = Math.max(height, document.documentElement.scrollHeight);

	return height;
};

/**
 * @return {Number} ширина области для сетки в пикселах
 */
ModularGrid.Utils.getClientWidth = function () {
	var width = document.documentElement.clientWidth;
	return width;
};

ModularGrid.Utils.documentBodyElement = null;
/**
* @private
* @return {Element} body
*/
ModularGrid.Utils.getDocumentBodyElement = function () {
	if ( this.documentBodyElement == null )
		this.documentBodyElement = document.getElementsByTagName("body")[0];

	return this.documentBodyElement;
};

/**
 * Сливает два хэша
 * @private
 * @param {Object} defaults значения по-умолчанию
 * @param {Object} params переопределенные значения
 * @return {Object} объект из ключей и значений по-умолчанию и новых значений
 */
ModularGrid.Utils.createParams = function (defaults, params) {
	var result = {};

	for ( var key in defaults )
		result[key] = defaults[key];

	for ( var key in params )
		result[key] = params[key];

	return result;
};

ModularGrid.Utils.defaultStyleValueParams =
	{
		display: 'block',
		width: '100%',
		height: '100%',
		opacity: 1.0,
		background: 'transparent',
		'float': 'none',
		visibility: 'visible',
		border: '0'
	};
/**
 * Возвращает CSS-строку для свойства style
 * @private
 * @param {Object} params параметры для строки
 * @return {String} CSS-строка для свойства style
 */
ModularGrid.Utils.createStyleValue = function (params) {
	var styleParams = ModularGrid.Utils.createParams(ModularGrid.Utils.defaultStyleValueParams, params);

	var result = '';
	for (var key in styleParams) {
		if ( styleParams[key] )
			result += key + ':' + styleParams[key] + ';';

		if ( styleParams[key] == 'opacity')
			result += '-khtml-opacity:' + styleParams[key] + ';-moz-opacity:' + styleParams[key] + ';filter:progid:DXImageTransform.Microsoft.Alpha(opacity=' + (styleParams[key] * 100) + ');';
	}

	return result;
};/** @include "../index.js" */

ModularGrid.OpacityChanger = {};/** @include "index.js" */

ModularGrid.OpacityChanger.defaults = {
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
};ModularGrid.OpacityChanger.params = null;

/** @type Array */
ModularGrid.OpacityChanger.handlers = null;

/**
 * Устанавливает настройки для гайдов
 *
 * @param {Object}
 *            params параметры гайдов
 */
ModularGrid.OpacityChanger.init = function(params) {
	this.params = ModularGrid.Utils.createParams(this.defaults, params);
	this.handlers = [];
};

ModularGrid.OpacityChanger.stepDownOpacity = function() {
	this.params.opacity -= this.params.opacityStep;
	this.params.opacity = (this.params.opacity < 0 ? 0.0 : this.params.opacity);

	this.updateOpacity(this.params.opacity);
};

ModularGrid.OpacityChanger.stepUpOpacity = function() {
	this.params.opacity += this.params.opacityStep;
	this.params.opacity = (this.params.opacity > 1 ? 1.0 : this.params.opacity);

	this.updateOpacity(this.params.opacity);
};

ModularGrid.OpacityChanger.addHandler = function (handler) {
	this.handlers.push(handler);
};

ModularGrid.OpacityChanger.updateOpacity = function(opacity) {
	for(var i = 0, length = this.handlers.length; i < length; i++)
		this.handlers[i]();
};

ModularGrid.OpacityChanger.changeElementOpacity = function (element) {
	if (element)
		element.style.opacity = this.params.opacity;
};/** @include "../index.js" */

ModularGrid.Image = {};/** @include "index.js" */

ModularGrid.Image.defaults = {
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
	 * Центрировать ли изображение относительно рабочей области браузера
	 * @type Boolean
	 */
	centered: false,

	/**
	 * Отступ от верхнего края рабочей области браузера до изображения в пикселах
	 * @type Number
	 */
	marginTop: 0,
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
	src: '',

	/**
	 * Ширина изображения в пикселах
	 * @type Number
	 */
	width: 100,
	/**
	 * Высота изображения в пикселах
	 * @type Number
	 */
	height: 100
};/** @include "namespace.js" */

ModularGrid.Image.showing = false;
ModularGrid.Image.parentElement = null;

ModularGrid.Image.params = null;

ModularGrid.Image.imgElement = null;

/**
 * Устанавливает настройки для гайдов
 *
 * @param {Object}
 *            params параметры гайдов
 */
ModularGrid.Image.init = function(params) {
	this.params = ModularGrid.Utils.createParams(this.defaults, params);
};

/**
 * Создает корневой HTML-элемент и HTML для гайдов и добавляет его в DOM
 *
 * @private
 * @param {Object}
 *            params параметры создания элемента и гайдов
 * @return {Element} корневой HTML-элемент
 */
ModularGrid.Image.createParentElement = function(params) {
	// создаем элемент и ресетим style
	var parentElement = document.createElement("div");

	var parentElementStyle = {
		position : 'absolute',
		left : '0',
		top : '0',

		width : '100%',
		height : params.height + 'px',

		opacity: 1,
		'z-index' : params['z-index']
	};

	parentElement.setAttribute("style", ModularGrid.Utils.createStyleValue(parentElementStyle));

	// создаём HTML гайдов
	parentElement.appendChild(this.createImageDOM(params));

	// добавляем элемент в DOM
	ModularGrid.Utils.getDocumentBodyElement().appendChild(parentElement);

	return parentElement;
};

/**
 * Создает HTML-строку для отображения гайдов
 *
 * @private
 * @param {Array}
 *            items массив настроек для создания гайдов
 * @return {String} HTML-строка для отображения гайдов
 */
ModularGrid.Image.createImageDOM = function(params) {
	var imageStyle = {
		width : 'auto',
		height : 'auto',

		opacity : ModularGrid.OpacityChanger.params.opacity
	};
	var imageContainerStyle = {
		'padding-top' : params.marginTop + 'px',

		width : 'auto',
		height : 'auto'
	};

	if (params.centered) {
		imageContainerStyle['text-align'] = 'center';
		imageStyle.margin = '0 auto';
	} else {
		imageContainerStyle['padding-left'] = params.marginLeft, imageContainerStyle['padding-right'] = params.marginRight;
	};

	var imageDOMParent = document.createElement('div');
	imageDOMParent.setAttribute("style", ModularGrid.Utils.createStyleValue(imageContainerStyle));

	this.imgElement = document.createElement('img');
	this.imgElement.setAttribute('src', params.src);
	this.imgElement.setAttribute('width', params.width);
	this.imgElement.setAttribute('height', params.height);
	this.imgElement.setAttribute('style', ModularGrid.Utils.createStyleValue(imageStyle));

	imageDOMParent.appendChild(this.imgElement);

	return imageDOMParent;
};

ModularGrid.Image.opacityHandler = function () {
	ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Image.imgElement);
};

/**
 * Скрывает-показывает гайды
 */
ModularGrid.Image.toggleVisibility = function() {
	this.showing = !this.showing;

	if (this.showing && this.parentElement == null) {
		this.parentElement = this.createParentElement(this.params);
	}

	if (this.parentElement)
		this.parentElement.style.display = (this.showing ? 'block' : 'none');
};/** @include "../index.js" */
ModularGrid.Guides = {};/** @include "index.js */

ModularGrid.Guides.defaults = {
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
	 * Массив настроек направляющих.
	 * По-умолчанию направляющих нет.
	 * @type Array
	 */
	items: []
};/** @include "namespace.js" */

ModularGrid.Guides.showing = false;
ModularGrid.Guides.parentElement = null;

ModularGrid.Guides.params = null;

/**
 * Устанавливает настройки для гайдов
 * @param {Object} params параметры гайдов
 */
ModularGrid.Guides.init = function (params) {
	this.params = ModularGrid.Utils.createParams(this.defaults, params);
};

/**
 * Создает корневой HTML-элемент и HTML для гайдов и добавляет его в DOM
 * @private
 * @param {Object} params параметры создания элемента и гайдов
 * @return {Element} корневой HTML-элемент
 */
ModularGrid.Guides.createParentElement = function (params) {
	// создаем элемент и ресетим style
	var parentElement = document.createElement("div");

	var parentElementStyleValue =
		ModularGrid.Utils.createStyleValue(
			{
				position: 'absolute',
				left: '0',
				top: '0',

				height: '100%',
				width: '100%',

				'text-align': 'center',

				'z-index': params['z-index']
			}
		);
	parentElement.setAttribute("style", parentElementStyleValue);

	// создаём HTML гайдов
	parentElement.innerHTML = this.createGuidesHTML(params.items);

	// добавляем элемент в DOM
	ModularGrid.Utils.getDocumentBodyElement().appendChild(parentElement);

	return parentElement;
};

/**
 * Создает HTML-строку для отображения гайдов
 * @private
 * @param {Array} items массив настроек для создания гайдов
 * @return {String} HTML-строка для отображения гайдов
 */
ModularGrid.Guides.createGuidesHTML = function (items) {
	var html = '';

	if ( items ) {
		var currentItem, styleParams, borderStyle = this.params.lineWidth + ' ' + this.params.lineStyle + ' ' + this.params.lineColor + ' !important';
		for(var i = 0, length = items.length; i < length; i++) {
			currentItem = items[i];
			styleParams = {
				position: 'absolute'
			};

			switch ( currentItem.type ) {
				case 'center':
					styleParams.width = '100%';
					styleParams.height = '100%';

					var innerStyleParams =
						{
							width: currentItem.width,
							height: '100%',

							margin: '0 auto',

							'border-left': borderStyle,
							'border-right': borderStyle

						};

					html += '<div style="' + ModularGrid.Utils.createStyleValue(styleParams) + '"><div style="' + ModularGrid.Utils.createStyleValue(innerStyleParams) + '"></div></div>';
				break;

				case 'vertical':
					styleParams.width = '0px';
					styleParams.height = '100%';

					if ( currentItem.left != null ) {
						styleParams.left = currentItem.left;
						styleParams['border-right'] = borderStyle;
					}

					if ( currentItem.right != null ) {
						styleParams.right = currentItem.right;
						styleParams['border-left'] = borderStyle;
					}

					html += '<div style="' + ModularGrid.Utils.createStyleValue(styleParams) + '"></div>';
				break;

				case 'horizontal':
					styleParams.width = '100%';
					styleParams.height = '0px';

					if ( currentItem.top != null ) {
						styleParams.top = currentItem.top;
						styleParams['border-bottom'] = borderStyle;
					}

					if ( currentItem.bottom != null ) {
						styleParams.bottom = currentItem.bottom;
						styleParams['border-top'] = borderStyle;
					}

					html += '<div style="' + ModularGrid.Utils.createStyleValue(styleParams) + '"></div>';
				break;
			}

		};
	}

	return html;
};

/**
 * Скрывает-показывает гайды
 */
ModularGrid.Guides.toggleVisibility = function () {
	this.showing = !this.showing;

	if ( this.showing && this.parentElement == null ) {
		this.parentElement = this.createParentElement(this.params);
	}

	if ( this.parentElement )
		this.parentElement.style.display = ( this.showing ? 'block' : 'none' );
};/** @include "../index.js" */
ModularGrid.Grid = {};/** @include "index.js */

/**
 * Настройки для модульной сетки по-умолчанию
 * @type Object
 */
ModularGrid.Grid.defaults = {
	shouldToggleVerticalGridVisibility:
		function (params) {
			// Shift + v
			// показать/скрыть вертикальные элементы сетки (колонки)
			var result = !params.occured_in_form && (params.shiftKey && params.character == 'v' );
			return result;
		},

	shouldToggleHorizontalGridVisibility:
		function (params) {
			// Shift + h
			// показать/скрыть горизонтальные элементы сетки (строки)
			var result = !params.occured_in_form && (params.shiftKey && params.character == 'h' );
			return result;
		},

	shouldToggleFontGridVisibility:
		function (params) {
			// Shift + f
			// показать/скрыть шрифтовую сетку
			var result = !params.occured_in_form && (params.shiftKey && params.character == 'f' );
			return result;
		},

	shouldToggleVisibility:
		function (params) {
			// Ctrl + '
			// показать/скрыть всю сетку
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
	 * Ширина столбца модульной сетки в строках модульной сетки
	 * @see lineHeight
	 * @type Number
	 */
	vDivisions: 6,

	/**
	 * Высота строки модульной сетки в строках модульной сетки.
	 * @see lineHeight
	 * @type Number
	 */
	hDivisions: 4,

	/**
	 * Отступ от верхнего края рабочей области браузера до сетки в пикселах.
	 * @type Number
	 */
	marginTop: 0,
	/**
	 * Отступ от левого края рабочей области браузера до сетки.
	 * Значения аналогичны значениям CSS-свойства margin-left
	 * @type Number
	 */
	marginLeft: '18px',
	/**
	 * Отступ от правого края рабочей области браузера до сетки.
	 * Значения аналогичны значениям CSS-свойства margin-right
	 * @type Number
	 */
	marginRight: '18px',

	width: 464,
	minWidth: 464,
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
};/** @include "namespace.js" */

/**
 * Показывается ли хотя бы один из элементов модульной сетки (шрифтовая сетка, столбцы или строки)
 * @type Boolean
 */
ModularGrid.Grid.showing = false;

ModularGrid.Grid.fontGridShowing = false;
ModularGrid.Grid.fontGridParentElement = null;

ModularGrid.Grid.horizontalGridShowing = false;
ModularGrid.Grid.horizontalGridParentElement = null;

ModularGrid.Grid.verticalGridShowing = false;
ModularGrid.Grid.verticalGridParentElement = null;

/**
 * Параметры модульной сетки (значения по-умолчанию + пользваотельские настройки)
 * @type Object
 */
ModularGrid.Grid.params = null;

/**
 * Устанавливает настройки для гайдов
 * @param {Object} params параметры гайдов
 */
ModularGrid.Grid.init = function (params) {
	this.params = ModularGrid.Utils.createParams(this.defaults, params);
};

/**
 * Создает элементы-родители для элементов модульной сетки
 * в порядке столбцы, строки, шрифтовая сетка и добавляет их в DOM
 * @private
 * @param {Object} params параметры создания элемента и гайдов
 * @return {Element} корневой HTML-элемент модульной сетки
 */
ModularGrid.Grid.createParentElement = function (params) {
	var parentElement = ModularGrid.Utils.getDocumentBodyElement();

	parentElement.appendChild( this.createVerticalGridParentElement(params) );
	parentElement.appendChild( this.createHorizontalGridParentElement(params) );
	parentElement.appendChild( this.createFontGridParentElement(params) );

	return parentElement;
};

ModularGrid.Grid.opacityHandler = function () {
	ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Grid.fontGridParentElement);
	ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Grid.verticalGridParentElement);
	ModularGrid.OpacityChanger.changeElementOpacity(ModularGrid.Grid.horizontalGridParentElement);
};

ModularGrid.Grid.createVerticalGridParentElement = function (params) {
	this.verticalGridParentElement = document.createElement('div');
	this.verticalGridParentElement.setAttribute(
		"style",
		ModularGrid.Utils.createStyleValue(
			{
				position: 'absolute',
				left: '0',
				top: '0',

				display: 'none',

				height: '100%',
				width: '100%',

				opacity: ModularGrid.OpacityChanger.params.opacity,
				'z-index': params['z-index']
			}
		)
	);

	this.verticalGridParentElement.innerHTML = this.createVerticalGridHTML(params);

	return this.verticalGridParentElement;
};

/**
 * @private
 * @return {String} HTML для отображения вертикальной модульной сетки
 */
ModularGrid.Grid.createVerticalGridHTML = function (params) {
	var html = '';

	var fluid = ( typeof(params.width) == "string" && params.width.substr(params.width.length - 1) == "%" );
	var width = (fluid ? params.minWidth : params.width);

	// создаём вертикальную сетку
	var gutterCount = params.vDivisions - 1;
	( params.prependGutter ? gutterCount++ : null );
	( params.appendGutter ? gutterCount++ : null );

	var gutterPercent = (params.gutter / width) * 100;
	var divisionPercent = (100 - gutterCount * gutterPercent) / params.vDivisions;

	var x = (params.prependGutter ? gutterPercent : 0);

	var styleCSS =
		{
			position: 'relative',

			'float': 'left',

			'margin-right': '-' + divisionPercent + '%',

			width: divisionPercent + '%',
			height: '100%',

			background: params.color,

			opacity: params.opacity
		};
	for(var i = 0, length = params.vDivisions; i < length; i++) {
		styleCSS.left = x + '%';
		html += '<div style="' + ModularGrid.Utils.createStyleValue(styleCSS) + '"></div>';

		x += gutterPercent + divisionPercent;
	};

	// создаём контейнер колонок (центрирование, фиксация ширины и т.п.)
	var widthContainerStyle =
		{
			width: ( fluid ? params.width : width + 'px' )
		};
	if ( params.centered ) {
		var centeredContainerStyle =
			{
				'text-align': 'center'
			};
		widthContainerStyle.margin = '0 auto';

		html = '<div style="' + ModularGrid.Utils.createStyleValue(centeredContainerStyle) + '"><div style="' + ModularGrid.Utils.createStyleValue(widthContainerStyle) + '">' + html + '</div></div>';
	}
	else
		html = '<div style="' + ModularGrid.Utils.createStyleValue(widthContainerStyle) + '">' + html + '</div>';

	var marginContainerStyle =
		{
			width: 'auto',

			padding: '0 ' + params.marginRight + ' 0 ' + params.marginLeft
		};
	html = '<div style="' + ModularGrid.Utils.createStyleValue(marginContainerStyle) + '">' + html + '</div>';

	return html;
};

ModularGrid.Grid.createHorizontalGridParentElement = function (params) {
	this.horizontalGridParentElement = document.createElement('div');
	// ресетим style
	var parentElementStyleValue =
		ModularGrid.Utils.createStyleValue(
			{
				position: 'absolute',
				left: '0',
				top: '0',

				display: 'none',

				height: ModularGrid.Utils.getClientHeight() + 'px',
				width: '100%',

				opacity: ModularGrid.OpacityChanger.params.opacity,
				'z-index': params['z-index']
			}
		);
	this.horizontalGridParentElement.setAttribute("style", parentElementStyleValue);

	this.horizontalGridParentElement.innerHTML = this.createHorizontalGridHTML(params);

	return this.horizontalGridParentElement;
};

/**
 * @private
 * @return {String} HTML для отображения горизонтальной модульной сетки
 */
ModularGrid.Grid.createHorizontalGridHTML = function (params) {
	var horizontalGridHTML = '';

	var height = ModularGrid.Utils.getClientHeight();
	var y = params.marginTop;

	var hCounter = 0;
	var hCounterMax = params.hDivisions + 1;
	var hHeight = params.lineHeight * params.hDivisions;

	var styleCSS =
		{
			position: 'absolute',

			width: 'auto',

			left: params.marginLeft,
			right: params.marginRight,

			height: hHeight + 'px',

			background: params.color,
			opacity: params.opacity
		};

	while ( y < height ) {
		if ( hCounter == 0 && (y + hHeight) < height ) {
			styleCSS.top = y + 'px';
			horizontalGridHTML += '<div style="' + ModularGrid.Utils.createStyleValue(styleCSS) + '"></div>';
		}

		y += params.lineHeight;

		hCounter++;
		if ( hCounter == hCounterMax )
			hCounter = 0;
	}

	return horizontalGridHTML;
};

ModularGrid.Grid.createFontGridParentElement = function (params) {
	this.fontGridParentElement = document.createElement('div');
	// ресетим style
	var parentElementStyleValue =
		ModularGrid.Utils.createStyleValue(
			{
				position: 'absolute',
				left: '0',
				top: '0',

				display: 'none',

				height: ModularGrid.Utils.getClientHeight() + 'px',
				width: '100%',

				opacity: ModularGrid.OpacityChanger.params.opacity,
				'z-index': params['z-index']
			}
		);
	this.fontGridParentElement.setAttribute("style", parentElementStyleValue);

	this.fontGridParentElement.innerHTML = this.createFontGridHTML(params);

	return this.fontGridParentElement;
};

/**
 * @private
 * @return {String} HTML для отображения шрифтовой сетки
 */
ModularGrid.Grid.createFontGridHTML = function (params) {
	var fontGridHTML = "";

	var height = ModularGrid.Utils.getClientHeight();
	var y = params.marginTop + params.lineHeight;

	var styleCSS =
		{
			position: 'absolute',
			height: 0,

			opacity: params.opacity,

			'border-bottom': params.lineWidth + ' ' + params.lineStyle + ' ' + params.lineColor + ' !important'
		};

	while ( y < height ) {
		styleCSS.top = (y + 'px');
		fontGridHTML += '<div style="' + ModularGrid.Utils.createStyleValue(styleCSS) + '"></div>';

		y += params.lineHeight;
	};

	return fontGridHTML;
};

/**
 * Скрывает-показывает гайды
 */
ModularGrid.Grid.toggleVisibility = function () {
	this.showing = !this.showing;

	this.fontGridShowing = this.showing;
	this.horizontalGridShowing = this.showing;
	this.verticalGridShowing = this.showing;

	this.updateFontGridVisibility();
	this.updateHorizontalGridVisibility();
	this.updateVerticalGridVisibility();
};

ModularGrid.Grid.updateFontGridVisibility = function () {
	if ( this.fontGridShowing && this.fontGridParentElement == null )
		this.createParentElement(this.params);

	if ( this.fontGridParentElement )
		this.fontGridParentElement.style.display = ( this.fontGridShowing ? 'block' : 'none' );
};

ModularGrid.Grid.updateHorizontalGridVisibility = function () {
	if ( this.horizontalGridShowing && this.horizontalGridParentElement == null )
		this.createParentElement(this.params);

	if ( this.horizontalGridParentElement )
		this.horizontalGridParentElement.style.display = ( this.horizontalGridShowing ? 'block' : 'none' );
};

ModularGrid.Grid.updateVerticalGridVisibility = function () {
	if ( this.verticalGridShowing && this.verticalGridParentElement == null )
		this.createParentElement(this.params);

	if ( this.verticalGridParentElement )
		this.verticalGridParentElement.style.display = ( this.verticalGridShowing ? 'block' : 'none' );
};

ModularGrid.Grid.toggleHorizontalGridVisibility = function () {
	this.horizontalGridShowing = !this.horizontalGridShowing;
	this.updateShowing();

	this.updateHorizontalGridVisibility();
};

ModularGrid.Grid.toggleVerticalGridVisibility = function () {
	this.verticalGridShowing = !this.verticalGridShowing;
	this.updateShowing();

	this.updateVerticalGridVisibility();
};

ModularGrid.Grid.toggleFontGridVisibility = function () {
	this.fontGridShowing = !this.fontGridShowing;
	this.updateShowing();

	this.updateFontGridVisibility();
};

ModularGrid.Grid.updateShowing = function () {
	this.showing = this.fontGridShowing || this.horizontalGridShowing || this.verticalGridShowing;
};/** @include "../index.js" */
ModularGrid.Resizer = {};/** @include "index.js */

ModularGrid.Resizer.defaults = {
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

	/**
	 * Размеры по-умолчанию не заданы
	 * @type Array
	 */
	sizes: []
};/** @include "namespace.js" */

ModularGrid.Resizer.params = null;

ModularGrid.Resizer.sizes = null;
ModularGrid.Resizer.currentSizeIndex = null;

ModularGrid.Resizer.title = null;

ModularGrid.Resizer.detectDefaultSize = function () {
	var result = null;

  if ( typeof( window.innerWidth ) == 'number' && typeof( window.innerHeight ) == 'number' ) {
  	result =
  		{
  			width: window.innerWidth,
  			height: window.innerHeight
  		};
  }
  else
  	if ( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
  		result =
	  		{
	    		width: document.documentElement.clientWidth,
	    		height: document.documentElement.clientHeight
	  		};
  	}
  	else
  		if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
  			result =
	  			{
	    			width: document.body.clientWidth,
	    			height: document.body.clientHeight
	  			};
  		}

	return result;
};

ModularGrid.Resizer.getDefaultSize = function () {
	return this.sizes[0];
};

ModularGrid.Resizer.getCurrentSize = function () {
	return this.sizes[this.currentSizeIndex];
};

/**
 * Устанавливает настройки для гайдов
 * @param {Object} params параметры гайдов
 */
ModularGrid.Resizer.init = function (params, grid) {
	this.params = ModularGrid.Utils.createParams(this.defaults, params);

	var defaultSize = this.detectDefaultSize();
	if ( defaultSize ) {
		var sizes = [ defaultSize ];

		if ( this.params.sizes.length ) {
			for(var i = 0, length = this.params.sizes.length; i < length; i++)
				sizes.push( this.params.sizes[i] );
		}
		else {
			if ( grid.params.minWidth )
				sizes.push(
					{
						width: grid.params.minWidth
					}
				);
		}

		if ( sizes.length > 1 ) {
			if ( this.params.changeTitle )
				this.title = document.title;

			this.sizes = sizes;
			this.currentSizeIndex = 0;
		}
	}
};

ModularGrid.Resizer.toggleSize = function () {
	if ( this.currentSizeIndex != null ) {
		this.currentSizeIndex++;
		this.currentSizeIndex = ( this.currentSizeIndex == this.sizes.length ? 0 : this.currentSizeIndex );

		var width = ( this.getCurrentSize().width ? this.getCurrentSize().width : this.getDefaultSize().width );
		var height = ( this.getCurrentSize().height ? this.getCurrentSize().height : this.getDefaultSize().height );

		window.resizeTo(width, height);

		if ( this.params.changeTitle ) {
			var titleText = ( this.currentSizeIndex ? this.title + ' (' + width + '×' + height + ')' : this.title );
			if ( this.getCurrentSize().title )
				titleText = this.getCurrentSize().title;

			document.title = titleText;
		}
	}
}/**
 * @include "namespace.js"
 * @include "Utils/index.js"
 * @include "Grid/index.js"
 * @include "Guides/index.js"
 * @include "Resizer/index.js"
 * @include "OpacityChanger/index.js"
 */

ModularGrid.keyDownEventProvider = null;
ModularGrid.resizeEventProvider = null;

/**
 * Возвращает обертку для отлова события изменения размера окна браузера
 * @private
 * @return {ModularGrid.Utils.EventProvider} для события изменения размера окна браузера
 */
ModularGrid.getResizeEventProvider = function () {
	if ( this.resizeEventProvider == null ) {
		this.resizeEventProvider =
			new ModularGrid.Utils.EventProvider(
				'resize',
				function (event) {
					return {
						event: event
					};
				},
				'window'
			);
	};

	return this.resizeEventProvider;
};

/**
 * Возвращает обертку для отлова события нажатия клавиш
 * @private
 * @return {ModularGrid.Utils.EventProvider} для события нажатия клавиш
 */
ModularGrid.getKeyDownEventProvider = function () {
	if ( this.keyDownEventProvider == null ) {
		this.keyDownEventProvider =
			new ModularGrid.Utils.EventProvider(
				'keydown',
				function (event) {
					var keyboardEvent = ( event || window.event );
					var keyCode = (keyboardEvent.keyCode ? keyboardEvent.keyCode : (keyboardEvent.which ? keyboardEvent.which : keyboardEvent.keyChar));

					var character = String.fromCharCode(keyCode).toLowerCase();
					var shift_nums = {
						"`":"~",
						"1":"!",
						"2":"@",
						"3":"#",
						"4":"$",
						"5":"%",
						"6":"^",
						"7":"&",
						"8":"*",
						"9":"(",
						"0":")",
						"-":"_",
						"=":"+",
						";":":",
						"'":"\"",
						",":"<",
						".":">",
						"/":"?",
						"\\":"|"
					}
					if ( keyboardEvent.shiftKey && shift_nums[character] )
						character = shift_nums[character];

				var element = ( keyboardEvent.target ? keyboardEvent.target : keyboardEvent.srcElement );
				if ( element && element.nodeType == 3 )
					element = element.parentNode;
				var occured_in_form = ( element && (element.tagName == 'INPUT' || element.tagName == 'TEXTAREA'));

					return {
						occured_in_form: occured_in_form,
						character: character,
						keyCode: keyCode,

						altKey: keyboardEvent.altKey,
						shiftKey: keyboardEvent.shiftKey,
						ctrlKey: keyboardEvent.ctrlKey,

						event: keyboardEvent
					};
				}
			);
	};

	return this.keyDownEventProvider;
};

/**
 * Устанавливает настройки модульной сетки и ставит обработчики событий для показа сетки
 * @param {Object} params параметры инициализации
 */
ModularGrid.init = function (params) {
	var self = this;

	this.OpacityChanger.init(params.opacity);
	var opacityUpChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.OpacityChanger.params.shouldStepUpOpacity,
			function () {
				self.OpacityChanger.stepUpOpacity();
			}
		);
	var opacityDownChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.OpacityChanger.params.shouldStepDownOpacity,
			function () {
				self.OpacityChanger.stepDownOpacity();
			}
		);

	// изображение
	this.Image.init(params.image);
	this.OpacityChanger.addHandler(this.Image.opacityHandler);
	var imageStateChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Image.params.shouldToggleVisibility,
			function () {
				self.Image.toggleVisibility();
			}
		);

	// гайды
	this.Guides.init(params.guides);
	var guidesStateChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Guides.params.shouldToggleVisibility,
			function () {
				self.Guides.toggleVisibility();
			}
		);

	// сетка
	this.Grid.init(params.grid);
	this.OpacityChanger.addHandler(this.Grid.opacityHandler);
	var gridStateChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Grid.params.shouldToggleVisibility,
			function () {
				self.Grid.toggleVisibility();
			}
		);

	var gridFontGridVisibilityChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Grid.params.shouldToggleFontGridVisibility,
			function () {
				self.Grid.toggleFontGridVisibility();
			}
		);

	var gridHorizontalGridVisibilityChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Grid.params.shouldToggleHorizontalGridVisibility,
			function () {
				self.Grid.toggleHorizontalGridVisibility();
			}
		);

	var gridVerticalGridVisibilityChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Grid.params.shouldToggleVerticalGridVisibility,
			function () {
				self.Grid.toggleVerticalGridVisibility();
			}
		);

	// resizer
	this.Resizer.init(params.resizer, this.Grid);
	var resizerSizeChanger =
		new ModularGrid.Utils.StateChanger(
			this.getKeyDownEventProvider(),
			this.Resizer.params.shouldToggleSize,
			function () {
				self.Resizer.toggleSize();
			}
		);
};/** @include "index.js" */

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