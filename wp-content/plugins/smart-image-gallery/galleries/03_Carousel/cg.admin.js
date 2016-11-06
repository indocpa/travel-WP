window[ 'carousel_settings' ] = function( root )
	{
	
		var settings = {
			'gallery' 		: 'carousel',
			'visible'  		: 3,
			'vertical' 		: root.find( '[name="vertical"]' ).is( ':checked' ),
			'nextprevbtn' 	: root.find( '[name="nextprevbtn"]' ).is( ':checked' ),
			'gobtn' 		: root.find( '[name="gobtn"]' ).is( ':checked' ),
			'customcolors' 	: root.find( '[name="customcolors"]' ).is( ':checked' ),
			'mousewheel'	: root.find( '[name="mousewheel"]' ).is( ':checked' ),
			'autoplay' 		: root.find( '[name="autoplay"]' ).is( ':checked' ),
			'caption'  		: root.find( '[name="caption"]' ).is( ':checked' ),
			'author'   		: root.find( '[name="author"]' ).is( ':checked' )
		},
		h = root.find( '[name="height"]' ).val(),
		w = root.find( '[name="width"]' ).val(),
		v = root.find( '[name="visible"]' ).val();
		
		if( !/^\s*$/.test( h ) )
		{
			settings[ 'height' ] = h;
		}
		
		if( !/^\s*$/.test( w ) )
		{
			settings[ 'width' ] = w;
		}
		
		if( !/^\s*$/.test( v ) )
		{
			settings[ 'visible' ] = v;
		}
		
		return settings;
	};
	
window[ 'carousel_output' ] = function( configObj )
	{
		var settings = carousel_settings( configObj[ 'entry_point' ] ),
			output = '';
		
		if( typeof configObj[ 'images' ] != 'undefined' && configObj[ 'images' ].length )
		{
			output += configObj[ 'start_shortcode' ] + JSON.stringify( { "images" : configObj[ 'images' ], "settings" : settings } ) + configObj[ 'end_shortcode' ]; 
		}
		
		return output;
	};