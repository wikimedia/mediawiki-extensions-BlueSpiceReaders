Ext.define( 'BS.Readers.panel.Readers', {
	extend: 'Ext.Panel',
	requires: [ 'BS.store.BSApi' ],
	cls: 'bs-readers-flyout',
	articleId: mw.config.get( 'wgArticleId' ),
	readersLimit: mw.config.get( 'bsgReadersNumOfReaders' ),
	title: mw.message( 'bs-readers-flyout-title' ).plain(),
	initComponent: function () {
		this.store =  new BS.store.BSApi({
			apiAction: 'bs-readers-page-readers-store',
			fields: [ 'user_image_html', 'readers_page_id'  ],
			proxy: {
				extraParams: {
					limit: this.readersLimit
				}
			},
			filters: [{
				property: 'readers_page_id',
				type: 'numeric',
				comparison: 'eq',
				value: this.articleId
			}]
		} );

		this.items = [
			new Ext.DataView( {
				store: this.store,
				itemTpl: "{user_image_html}"
			} )
		];

		this.callParent( arguments );
	}
})
