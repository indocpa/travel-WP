window[ 'single_settings' ] = function( root )
	{
		var settings = {
			'caption' : 0,
			'author'  : 0
		};
		
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
	
window[ 'single_output' ] = function( configObj )
	{
		var settings = single_settings( configObj[ 'entry_point' ] ),
			output = '',
			figcaption,
			alt;
		
		if( typeof configObj[ 'images' ] != 'undefined' && configObj[ 'images' ].length )
		{
			
			for( var i = 0, h = configObj[ 'images' ].length; i < h; i++ )
			{
				figcaption = '';
				alt = '';
				
				if( settings[ 'caption' ] || settings[ 'author' ] )
				{
					figcaption += '<figcaption>';
					alt += 'alt="';
					if( settings[ 'caption' ] && typeof configObj[ 'images' ][ i ][ 'title' ] != 'undefined' && !/^\s*$/.test( configObj[ 'images' ][ i ][ 'title' ] ) )
					{
						var title = configObj[ 'images' ][ i ][ 'title' ];
						title = title.replace( '"', '\"');
						alt += title;
						figcaption += title;
					}
					
					if( settings[ 'author' ] && typeof configObj[ 'images' ][ i ][ 'author' ] != 'undefined'  && !/^\s*$/.test( configObj[ 'images' ][ i ][ 'author' ] ) )
					{
						var author = configObj[ 'images' ][ i ][ 'author' ];
						author = author.replace( '"', '\"');
						alt += ' [' + author + ']';
						figcaption += ' [' + author + ']';
					}
					
					figcaption += '</figcaption>';
					alt += '"';
				}
				
				output += '<figure><img src="' + configObj[ 'images' ][ i ][ 'url' ] + '" '+alt+'>'+figcaption+'</figure>'; 
			}
		}
		
		return output;
	};