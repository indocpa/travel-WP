window[ 'swapping_settings' ] = function( root )
	{
		var settings = {
			'gallery' : 'swapping',
			'timeout' : 5,
			'caption' : 0,
			'author'  : 0
		},
		w = root.find( '[name="width"]' ).val(),
		h = root.find( '[name="height"]' ).val(),
		t = root.find( '[name="timeout"]' ).val();
		
		if( !/^\s*$/.test( w ) )
		{
			settings[ 'width' ] = w;
		}
		
		if( !/^\s*$/.test( h ) )
		{
			settings[ 'height' ] = h;
		}
		
		if( !/^\s*$/.test( t ) )
		{
			settings[ 'timeout' ] = t;
		}
		
		if( root.find( '[name="caption"]' ).is( ':checked' ) )
		{
			settings[ 'caption' ] = 1;
		}
		
		if( root.find( '[name="author"]' ).is( ':checked' ) )
		{
			settings[ 'author' ] = 1;
		}
		
		return settings;
	};
	
window[ 'swapping_output' ] = function( configObj )
	{
		var settings = swapping_settings( configObj[ 'entry_point' ] ),
			output = '';
		
		if( typeof configObj[ 'images' ] != 'undefined' && configObj[ 'images' ].length )
		{
			
			output += configObj[ 'start_shortcode' ] + JSON.stringify( { "images" : configObj[ 'images' ], "settings" : settings } ) + configObj[ 'end_shortcode' ]; 
		}
		
		return output;
	};