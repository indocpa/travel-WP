window[ 'SwappingGallery_loadNext' ] = function( c, i )
	{
		SwappingGallery_loadImage( c, ( ( i+1 < c.data( 'images' ).length ) ? i+1 : 0 ) );
	};

window[ 'SwappingGallery_loadImage' ] = function( c, i )
	{
		function formatStr( str )
		{
			return str.replace( /"/g, '\"' ).replace( />/g, '&gt;').replace( /</, '&lt;');
		};
		
		var imgId = c.attr( 'id' ) + '_img' + i;
		
		if( c.find( '#' + imgId ).length )
		{
			c.find( '.swapping-gallery-image' ).hide();
			c.find( '#' + imgId ).show();
			setTimeout(
				function()
				{
					SwappingGallery_loadNext( c, i );
				},
				c.data( 'settings' )[ 'timeout' ]*1000
			);
		}
		else
		{
			var iTag = jQuery( '<figure id="' + imgId + '" class="swapping-gallery-image" style="display:none;"><img src="'+ c.data( 'images' )[ i ][ 'url' ] +'" /></figure>' ),
				alt = '';
		
			if( c.data( 'settings' )[ 'caption' ] && typeof c.data( 'images' )[ i ][ 'title' ] != 'undefined' && !/^\s*$/.test( c.data( 'images' )[ i ][ 'title' ] ) )
			{
				alt += '<span class="swapping-gallery-image-title">' + formatStr( c.data( 'images' )[ i ][ 'title' ] ) + '</span>';
			}
			
			if( c.data( 'settings' )[ 'author' ] && typeof c.data( 'images' )[ i ][ 'author' ] != 'undefined' && !/^\s*$/.test( c.data( 'images' )[ i ][ 'author' ] ) )
			{
				alt += ' <span class="swapping-gallery-image-author">[' + formatStr( c.data( 'images' )[ i ][ 'author' ] ) + ']</span>';
			}
			
			if( !/^\s*$/.test( alt ) )
			{
				iTag.append( '<figcaption>' + alt + '</figcaption>' ).find( 'img' ); 
			}
			
			jQuery.ajaxSetup({
				timeout: 3000
			});
			
			var format_gallery = function( evt, c )
				{
					if( evt.type == 'error' )
					{	
						jQuery( evt.target ).closest( '.swapping-gallery-image' ).remove();
					}
					else
					{	
						c.find( '.swapping-gallery-image' ).hide();
						jQuery( evt.target ).parents( '.swapping-gallery-image' ).show();
					}
					setTimeout(
							function()
							{
								SwappingGallery_loadNext( c, i );
							},
							c.data( 'settings' )[ 'timeout' ]*1000
						);
					
				};
				
			iTag.appendTo( c );
			c.find( '.swapping-gallery-image img' )
			 .on( 'error', ( function( f, c ){ 
						return function( evt ){ f( evt, c ); }; } )( format_gallery, c ) )
			 .on( 'load', ( function( f, c ){ 
						return function( evt ){ f( evt, c ); }; } )( format_gallery, c ) );
		}	
	};

window[ 'SwappingGallery' ] = function( configObj )
	{
		
		// Main function content
	
		var defaultSettings = {
			'timeout' : 5,
			'caption' : 0,
			'author'  : 0,
			'width'	  :	250,
			'height'  :	250,
			'timeout' : 5
			
		},
		settings = jQuery.extend( {}, defaultSettings, ( ( typeof configObj[ 'settings' ] != 'undefined' ) ? configObj[ 'settings' ] : {} ), true ),
		container = ( typeof configObj[ 'container' ] != 'undefined' ) ? jQuery( '#' +  configObj[ 'container' ] ) : [],
		images = ( typeof configObj[ 'images' ] != 'undefined' ) ? configObj[ 'images' ] : [];

		if( container.length && images.length )
		{
			var containerStyles = {
				'width' : settings[ 'width' ],
				'height' : settings[ 'height' ]
			};

			container.css( containerStyles );
			container.data( 'images', images );
			container.data( 'settings', settings );
			
			SwappingGallery_loadImage( container, 0 );
		}
		
	};