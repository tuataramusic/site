/** @include "namespace.js" */

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
};