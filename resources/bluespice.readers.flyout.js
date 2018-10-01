(function( mw, $, bs, undefined ) {
	bs.util.registerNamespace( 'bs.readers.flyout' );

	bs.readers.flyout.makeItems = function() {
		return {
			centerRight: [
				Ext.create( 'BS.Readers.grid.Readers', {} )
			]
		}
	};

})( mediaWiki, jQuery, blueSpice );
