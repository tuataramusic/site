/** @include "namespace.js" */

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
}