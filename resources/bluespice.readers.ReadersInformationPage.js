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
			mw.loader.using( 'ext.bluespice.extjs' ).done( function () {
				Ext.onReady( function( ) {
					me.readerGrid = Ext.create( 'BS.Readers.grid.PageReaders', {
						title: false,
						renderTo: me.$element[0],
						width: me.$element.width(),
						height: me.$element.height()
					});
				}, me );
			});
		}
	}

	bs.readers.info.ReadersInformationPage.prototype.getData = function () {

		var dfd = new $.Deferred();
		mw.loader.using( 'ext.bluespice.extjs' ).done( function () {
			Ext.require( 'BS.Readers.grid.PageReaders', function() {
				dfd.resolve();
			});
		});
		return dfd.promise();
	};

	registryPageInformation.register( 'readers_infos', bs.readers.info.ReadersInformationPage );

})( mediaWiki, jQuery, blueSpice );
