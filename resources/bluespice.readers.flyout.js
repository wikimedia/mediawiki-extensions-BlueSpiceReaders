(function( mw, $, bs, undefined ) {
	bs.util.registerNamespace( 'bs.readers.flyout' );

	bs.readers.flyout.makeItems = function() {
		return {
			centerRight: [
				Ext.create( 'BS.Readers.panel.Readers', {} )
			]
		}
	};

})( mediaWiki, jQuery, blueSpice );
