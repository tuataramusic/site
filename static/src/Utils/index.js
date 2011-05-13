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
};