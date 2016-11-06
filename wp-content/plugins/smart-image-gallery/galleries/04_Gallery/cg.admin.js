window[ 'gallery_classic_settings' ] = function( root )
	{
		var settings = {
			'gallery' 	 : 'gallery_classic',
			'autoplay'	 : root.find( '[name="autoplay"]' ).is( ':checked' ),
			'caption'    : root.find( '[name="caption"]' ).is( ':checked' ),
			'author'     : root.find( '[name="author"]' ).is( ':checked' ),
			'thumbnails' : root.find( '[name="thumbnails"]' ).is( ':checked' ),
			'vertical' 	 : root.find( '[name="vertical"]' ).is( ':checked' )
		},
		w  = root.find( '[name="width"]' ).val(),
		h  = root.find( '[name="height"]' ).val(),
		tw = root.find( '[name="thumbnail_width"]' ).val(),
		th = root.find( '[name="thumbnail_height"]' ).val();
		
		if( !/^\s*$/.test( w ) )
		{
			settings[ 'width' ] = w;
		}
		
		if( !/^\s*$/.test( h ) )
		{
			settings[ 'height' ] = h;
		}
		
		if( !/^\s*$/.test( tw ) )
		{
			settings[ 'thumbnail_width' ] = tw;
		}
		
		if( !/^\s*$/.test( th ) )
		{
			settings[ 'thumbnail_height' ] = th;
		}
		
		return settings;
	};
	
window[ 'gallery_classic_output' ] = function( configObj )
	{
		var settings = gallery_classic_settings( configObj[ 'entry_point' ] ),
			output = '';
		
		if( typeof configObj[ 'images' ] != 'undefined' && configObj[ 'images' ].length )
		{
			
			output += configObj[ 'start_shortcode' ] + JSON.stringify( { "images" : configObj[ 'images' ], "settings" : settings } ) + configObj[ 'end_shortcode' ]; 
		}
		
		return output;
	};