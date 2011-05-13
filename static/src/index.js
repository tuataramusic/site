/**
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
};