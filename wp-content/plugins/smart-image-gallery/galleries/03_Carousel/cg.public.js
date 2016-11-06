window[ 'CarouselGallery' ] = function( configObj )
	{
		function get_random_color() 
		{
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++ ) {
				color += letters[Math.round(Math.random() * 15)];
			}
			return color;
		};
			
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
			'caption' 		: 0,
			'author'  		: 0,
			'autoplay'		: 0,
			'gobtn'			: 0,
			'nextprevbtn'	: 0,
			'mousewheel'	: 0,
			'vertical'		: 0,
			'visible'		: 3,
			'width'	  		: '100%',
			'height'  		: 150,
			'customcolors'  : 0
		},
		settings = jQuery.extend( {}, defaultSettings, ( ( typeof configObj[ 'settings' ] != 'undefined' ) ? configObj[ 'settings' ] : {} ), true ),
		container = ( typeof configObj[ 'container' ] != 'undefined' ) ? jQuery( '#' +  configObj[ 'container' ] ) : [],
		images = ( typeof configObj[ 'images' ] != 'undefined' ) ? configObj[ 'images' ] : [],
		prevBtn = '',
		nextBtn = '',
		gobtn = '';

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
				
				imageList += '<li><figure class="carousel-gallery-image"><img src="' + images[ i ][ 'url' ] + '" /><figcaption>' + alt + '</figcaption></figure></li>';
				
				if( settings[ 'gobtn' ] )
				{
					gobtn += '<button class="' + ( i + 1 ) + ' carousel-gallery-gobtn" ' + ( ( settings[ 'customcolors' ] ) ? 'style="background: ' + get_random_color() + ';"' : '' ) + ' ></button>';
				}
			}
			
			var containerStyles = {
				'width'  : correctDim( container, settings[ 'width' ], true ),
				'height' : correctDim( container, settings[ 'height' ], false )
			};

			if( settings[ 'nextprevbtn' ] )
			{
				if( settings[ 'vertical' ] )
				{
					nextBtn = '<div class="carousel-gallery-next-bottom carousel-gallery-handle-next"></div>';
					prevBtn = '<div class="carousel-gallery-prev-top carousel-gallery-handle-prev"></div>';
				}
				else
				{
					nextBtn = '<div class="carousel-gallery-next carousel-gallery-handle-next"></div>';
					prevBtn = '<div class="carousel-gallery-prev carousel-gallery-handle-prev"></div>';
				}	
			}
			
			jQuery.ajaxSetup({
				timeout: 3000
			});
			var format_gallery = function( evt )
				{
					var i = jQuery( evt.target ),
						c = i.parents( '.carousel-gallery' ),
						l = c.data( 'imagesCount' ),
						s = c.data( 'settings' );		
					
					c.data( 'imagesCount', l - 1 );
					
					if( evt.type == 'error' )
					{
						jQuery( evt.target ).closest( 'li' ).remove();
					}
					else
					{
						if( s[ 'vertical' ] )
						{
							i.height( c.height()/s[ 'visible' ] ).parents( 'li' ).width( c.width() );
						}
						else
						{
							i.width( c.width()/s[ 'visible' ] ).parents( 'li' ).height( c.height() );
						}
					}
					
					if( l - 1 <= 0 )
					{
						var jCarouselLite_settings = {
							mouseWheel : ( s[ 'mousewheel' ] ) ? true : false,
							vertical : ( s[ 'vertical' ] ) ? true : false,
							circular : true,
							visible : s[ 'visible' ]*1
						};
						
						if( s[ 'nextprevbtn' ] )
						{
							jCarouselLite_settings[ 'btnNext' ] = '.carousel-gallery-handle-next';
							jCarouselLite_settings[ 'btnPrev' ] = '.carousel-gallery-handle-prev';
						}
						
						if( s[ 'autoplay' ] )
						{
							jCarouselLite_settings[ 'auto' ] = 4000;
							jCarouselLite_settings[ 'speed' ] = 1000;
						}
						
						if( s[ 'gobtn' ] )
						{
							
							var gobtnId = c.attr( 'id' ) + '-gobtn',
								btnGoArr = [];
								
							jQuery( '[id="' + gobtnId + '"]' ).find( '.carousel-gallery-gobtn' )
															  .each(
																function( i, e )
																{
																	btnGoArr.push( '#' + gobtnId + ' ' + '.' + ( i + 1 ) );
																}
															  );	
							jCarouselLite_settings[ 'btnGo' ] = btnGoArr;								  
						}

						c.show()
						 .jCarouselLite( jCarouselLite_settings )
						 .find( 'img' )
						 .each(
							function()
							{
								var e = jQuery( this ),
									s = e.parents( '.carousel-gallery' ).data( 'settings' ),
									p = e.parents( 'li' );
									
								if( !s[ 'vertical' ] && e.height() < p.height() )
								{
									e.css( 'width', 'auto').height( p.height() );
								}
								else if( s[ 'vertical' ] && e.width() < p.width() )
								{
									e.css( 'height', 'auto').width( p.width() );
								}
							}
						 )
						 .parents( '.carousel-gallery' )
						 .find( 'figcaption' )
						 .each(
							function()
							{
								var e = jQuery( this ),
									i = e.siblings( 'img' );
									
								e.show().offset( i.offset() ).width( i.width() );
							}
						 );
					}
				};
			container.data( 'imagesCount', images.length )
					 .data( 'settings', settings )
					 .css( containerStyles )
					 .before( '<div id="' + configObj[ 'container' ] + '-gobtn">' + gobtn + '</div>' )
					 .append( prevBtn + '<ul>' + imageList + '</ul>' + nextBtn )
					 .find( 'img' )
					 .on( 'error', ( function( f ){ 
						return function( evt ){ f( evt ); }; } )( format_gallery ) )
					 .on( 'load', ( function( f ){ 
						return function( evt ){ f( evt ); }; } )( format_gallery ) );
		}
	};