Ext.Loader.setPath(
	'BS.Readers',
	bs.em.paths.get( 'BlueSpiceReaders' ) + '/resources/BS.Readers'
);

Ext.onReady( function(){
	Ext.create( 'BS.Readers.panel.Readers', {
		renderTo: 'bs-readers-grid'
	} );
} );
