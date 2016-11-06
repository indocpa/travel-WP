window[ 'ClassicGallery' ] = function( configObj )
	{

		function formatStr( str )
		{
			return str.replace( /"/g, '\"' ).replace( />/g, '&gt;').replace( /</, '&lt;');
		};
		
		function correctDim( e, v, isWidth )
		{
			v = jQuery.trim( v );
			if( /%$/.test( v ) )
			{
				return e.parent()[ ( ( isWidth ) ? 'width' : 'height' ) ]()*parseFloat( v )/100;
			}
			return v;
		};
		
		// Main function content
		var defaultSettings = {
			'autoplay'			: 0,
			'caption' 			: 0,
			'author'  			: 0,
			'thumbnails'	  	: 0,
			'vertical'		   	: 0,
			'width'	  		   	: '100%',
			'height'  		   	: 600,
			'thumbnail_width'  	: 150,
			'thumbnail_height' 	: 150,
			'visible'			: 1,
			'circular'			: true
		},
		settings = jQuery.extend( {}, defaultSettings, ( ( typeof configObj[ 'settings' ] != 'undefined' ) ? configObj[ 'settings' ] : {} ), true ),
		container = ( typeof configObj[ 'container' ] != 'undefined' ) ? jQuery( '#' +  configObj[ 'container' ] ) : [],
		images = ( typeof configObj[ 'images' ] != 'undefined' ) ? configObj[ 'images' ] : [],
		nextThBtn = '<div class="classic-gallery-next'+( ( settings[ 'vertical' ] ) ? '-bottom' : '' )+' classic-gallery-handle-next"></div>',
		prevThBtn = '<div class="classic-gallery-prev'+( ( settings[ 'vertical' ] ) ? '-top' : '' )+' classic-gallery-handle-prev"></div>';

		if( container.length && images.length )
		{
			var imageList = '',
				alt;
			
			for( var i = 0, h = images.length; i < h; i++ )
			{
				alt = '';
				if( settings[ 'caption' ] && typeof images[ i ][ 'title' ] != 'undefined' && !/^\s*$/.test( images[ i ][ 'title' ] ) )
				{
					alt += formatStr( images[ i ][ 'title' ] );
				}
				
				if( settings[ 'author' ] && typeof images[ i ][ 'author' ] != 'undefined' && !/^\s*$/.test( images[ i ][ 'author' ] ) )
				{
					alt += ' [' + formatStr( images[ i ][ 'author' ] ) + ']';
				}
				
				imageList += '<li><figure class="classic-gallery-image"><img src="' + images[ i ][ 'url' ] + '" /><figcaption>' + alt + '</figcaption></figure></li>';
			}
			
			var containerStyles = {
				'width'  : correctDim( container, settings[ 'width' ] ,true ),
				'height' : correctDim( container, settings[ 'height' ] ,false )
			};
			settings[ 'width' ] = containerStyles[ 'width' ];
			settings[ 'height' ] = containerStyles[ 'height' ];
			container.data( 'imagesCount', images.length )
					 .css( containerStyles );

			// If thumbnails will be visible will be required to insert the thumbnails viewer
			var w = container.width(),
				h = container.height(),
				th_viewer = jQuery( '<div class="classic-gallery-thumbnails" ><ul>' + imageList + '</ul></div>' );
				th_viewerStyles = {};
			
			if( settings[ 'thumbnails' ] )
			{
				var vw, 
					vh, // Width and Height of selected image
					viewerStyles = {};
				
				if( settings[ 'vertical' ] )
				{
					th_viewerStyles[ 'width' ]  = Math.min( w, settings[ 'thumbnail_width' ]*1 );
					th_viewerStyles[ 'height' ] = vh = h;
					th_viewerStyles[ 'float' ] = 'left';
					settings[ 'visible' ] = h / settings[ 'thumbnail_height' ];
					if( settings[ 'visible' ] >= images.length )
					{
						settings[ 'circular' ] = false; 
						//settings[ 'thumbnail_height' ] = h / images.length;
						settings[ 'visible' ] = images.length;
						nextThBtn = prevThBtn = '<span><span/>';
					}
					vw = w -  th_viewerStyles[ 'width' ] - 10;
					viewerStyles[ 'float' ] = 'right';
				}
				else
				{
					th_viewerStyles[ 'height' ] = Math.min( h, settings[ 'thumbnail_height' ]*1 );
					th_viewerStyles[ 'width' ]  = vw = w;
					settings[ 'visible' ] =  w / settings[ 'thumbnail_width' ];
					if( settings[ 'visible' ] >= images.length )
					{
						settings[ 'circular' ] = false; 
						//settings[ 'thumbnail_width' ] = w / images.length;
						settings[ 'visible' ] = images.length;
						nextThBtn = prevThBtn = '<span><span/>';
					}
					vh = h - th_viewerStyles[ 'height' ] - 10;
				}
				
				viewerStyles[ 'width' ]  = vw + 'px';
				viewerStyles[ 'height' ] = vh + 'px';
				// Add the image selected viewer to the gallery
				jQuery( '<div class="classic-gallery-viewer" ></div>' ).css( viewerStyles ).appendTo( container );
			}
			else
			{
				th_viewerStyles[ 'width' ] = w;
				th_viewerStyles[ 'height' ] = h;
			}

			th_viewer.css( th_viewerStyles ).prepend( prevThBtn ).append( nextThBtn );

			if( settings[ 'vertical' ] )
			{
				th_viewer.prependTo( container );
			}
			else
			{
				th_viewer.appendTo( container );
			}
			
			container.data( 'settings', settings );

			jQuery.ajaxSetup({
				timeout: 3000
			});
            
			var format_gallery = function( evt )
				{
					var i = jQuery( evt.target ),
						c = i.parents( '.classic-gallery' ),
						v = c.find( '.classic-gallery-thumbnails' ),
						l = c.data( 'imagesCount' ),
						s = c.data( 'settings' );		
					
					c.data( 'imagesCount', l - 1 );

					if( evt.type == 'error' )
					{
						jQuery( evt.target ).closest( 'li' ).remove();
					}	
					else
					{	
						if( !s[ 'thumbnails' ] )
						{
							i.css( { 'max-width': s[ 'width' ] } ).parents( 'li' ).css( { 'width' : s[ 'width' ], 'overflow' : 'hidden' } );
						}
						else
						{
							if( s[ 'vertical' ] )
							{
								i.width( v.width() );
							}
							else
							{
								i.parents( 'li' ).css( { 'width' : s[ 'thumbnail_width' ], 'overflow' : 'hidden' } );
								i.height( v.height() );
							}
						}
					}
					
					if( l - 1 <= 0 )
					{
						var jCarouselLite_settings = {
							vertical : ( s[ 'vertical' ] ) ? true : false,
							circular : s[ 'circular' ],
							visible  : s[ 'visible' ],
							btnNext  : '.classic-gallery-handle-next',
							btnPrev  : '.classic-gallery-handle-prev'
						};
						
						if( s[ 'autoplay' ] )
						{
							jCarouselLite_settings[ 'auto' ] = 4000;
							jCarouselLite_settings[ 'speed' ] = 1000;
						}
						c.show();

						v.jCarouselLite( jCarouselLite_settings )
						 .find( 'figcaption' )
						 .show()
						 .each(
							function()
							{
								var e = jQuery( this );
								e.width( e.parents( 'li' ).width() );
							}
						 )
						 .parents( '.classic-gallery' )
						 .find( '.classic-gallery-image' )
						 .click(
							function()
							{
								var e = jQuery( this ).find( 'img' ),
									figcaption = e.next( 'figcaption' );
									
								e.parents( '.classic-gallery' )
								 .find( '.classic-gallery-viewer' )
								 .html( '<span></span><figure style="width:100%;height:100%;"><img src="' + e.attr( 'src' ) + '" />' + ( ( figcaption.length ) ? '<figcaption>' + figcaption.html() + '</figcaption>' : '' ) + '</figure>' )
								 .find( 'figure' )
								 .mouseover(
									function()
									{
										var e = jQuery( this ),
											i = e.find( 'img' ),
											figcaption = e.find( 'figcaption' );
											
										if( figcaption.length )
										{
											figcaption.show().offset( i.offset() ).width( i.width() );
										}
									}
								 )
								 .mouseout(
									function(){
										jQuery( this ).find( 'figcaption' ).hide();
									}
								 );
							}
						 )
						 .find( 'img' )
						 .each(
							function()
							{
								var e = jQuery( this ),
									s = e.parents( '.classic-gallery' ).data( 'settings' ),
									p = e.parents( 'li' );

								if( s[ 'vertical' ] && e.height() < p.height() )
								{
									e.css( 'width', 'auto').height( p.height() );
								}
								
								else if( !s[ 'vertical' ] && e.width() < p.width() )
								{
									e.css( 'height', 'auto').width( p.width() );
								}
							}
						 );
						
						if( !settings[ 'thumbnails' ] )
						{
							var h = 0;
							v.find( 'img' ).each( 
								function()
								{ 
									h = Math.max( jQuery( this ).height(), h );
								} 
							);
							
							v.height( h ).parents( '.classic-gallery' ).height( h ).find( 'li' ).height( h ).find( 'img' ).each( function(){ var e = jQuery( this ); e.css( 'margin-top', (h-e.height())/2 ); } );
						}
						c.find( '.classic-gallery-image' ).first().find( 'img' ).click();
					}
				}
			container.find( '.classic-gallery-thumbnails img' )
					 .on( 'error', ( function( f ){ 
						return function( evt ){ f( evt ); }; } )( format_gallery ) ) 	
					 .on( 'load', ( function( f ){ 
						return function( evt ){ f( evt ); }; } )( format_gallery ) );
		}
	};