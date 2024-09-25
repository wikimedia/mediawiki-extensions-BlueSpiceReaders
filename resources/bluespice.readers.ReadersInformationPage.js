(function( mw, $, bs ) {
	bs.util.registerNamespace( 'bs.readers.info' );

	bs.readers.info.ReadersInformationPage = function ReadersInformationPage( name, config ) {
		this.readerGrid = null;
		bs.readers.info.ReadersInformationPage.super.call( this, name, config );
	};

	OO.inheritClass( bs.readers.info.ReadersInformationPage, StandardDialogs.ui.BasePage );

	bs.readers.info.ReadersInformationPage.prototype.setupOutlineItem = function () {
		bs.readers.info.ReadersInformationPage.super.prototype.setupOutlineItem.apply( this, arguments );

		if ( this.outlineItem ) {
			this.outlineItem.setLabel( mw.message( 'bs-readers-info-dialog' ).plain() );
		}
	};

	bs.readers.info.ReadersInformationPage.prototype.setup = function () {
		return;
	};

	bs.readers.info.ReadersInformationPage.prototype.onInfoPanelSelect = function () {
		var me = this;
		if ( me.readerGrid === null ){
			mw.loader.using( [ 'ext.oOJSPlus.data', 'oojs-ui.styles.icons-user' ] ).done( function () {
				bs.api.store.getData( 'readers-page-readers' ).done( function ( data ) {
					me.readerGrid = new OOJSPlus.ui.data.GridWidget( {
						columns: {
							readers_user_name: {
								headerText: mw.message( 'bs-authors-info-dialog-grid-column-author' ).text(),
								type: 'user',
								showImage: true
							}
						},
						data: data.results
					} );
					me.$element.append( me.readerGrid.$element );
				} )
			} );
		}
	}

	registryPageInformation.register( 'readers_infos', bs.readers.info.ReadersInformationPage );

})( mediaWiki, jQuery, blueSpice );
