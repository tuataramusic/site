/** @include "namespace.js" */

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
};