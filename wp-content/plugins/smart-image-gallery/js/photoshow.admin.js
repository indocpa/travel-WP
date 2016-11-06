jQuery( function($){
	var currentTerm = '',
		imagesCounter = 0,
		imagesList = [];
	
	function loadWindow(){
		if( typeof photoshow == 'undefined' )
		{
			return;
		}
		
		var counter = 0,
			dlg = 	'<div title="'+photoshow.title+'">\
						<div class="photoshow">\
							<div class="photoshow-headers">\
								<div rel="body-images" class="header active" onclick="photoshowAdmin.headersHandle(this);" >' + photoshow[ 'texts' ][ 'images' ] + '</div><div rel="body-galleries" class="header" onclick="photoshowAdmin.headersHandle(this);">' + photoshow[ 'texts' ][ 'galleries' ] + '</div>\
							</div>\
							<div class="photoshow-bodies">\
								<div class="body-images">\
									<div>'+photoshow[ 'texts' ][ 'terms' ]+'*: <input type="text" id="searchTerms" style="width:250px;" value="" /> <input type="button" value="Search" onclick="photoshowAdmin.search();" /></div>\
									<div id="modulesContainer">';
		
		if( typeof photoshow.modules != 'undefined' )
		{
			for( var key in photoshow.modules )
			{	
				dlg += '<div id="' + key + '" class="module"><h2 class="module-title">' + photoshow.modules[ key ] + '</h2><div class="more-btn" onclick="photoshowAdmin.moreHandle( \'' + key + '\' );" ></div><div id="' + key + 'Container" class="images-carousel" ><div class="container"></div></div></div>'; 
			}
		}	
		
		dlg += '</div>\
				</div>\
				<div class="body-galleries">';
		
		if( typeof photoshow.galleries != 'undefined' )
		{
			var menu = '',
				content = '',
				active = ' active';
			
			for( var key in photoshow.galleries )
			{
				menu += '<option value="' + key + '" >' + photoshow.galleries[ key ][ 'title' ] + '</option>'; 
				content += '<div id="' + key + '" class="gallery' + active + '"><h2 class="gallery-title">' + photoshow.galleries[ key ][ 'title' ] + '</h2>' + photoshow.galleries[ key ][ 'settings' ] + '</div>';
				
				active = '';
			}
			
			dlg += '<div>' + photoshow[ 'texts' ][ 'gallery-layout' ] + ' <select id="photoshow_gallery" onchange="photoshowAdmin.selectGallery( this );">' + menu + '</select></div>' + content;
		}
		
		dlg += '</div>\
				</div>\
				</div>\
				</div>';
		   
		var c = $( dlg ),
			w = $( window ).width() - 180,
			h = $( window ).height() - 180;
		
		c.find( '#searchTerms' ).keypress( function( evt )
			{
				if( evt.which == 13 )
				{
					photoshowAdmin.search();
				}
			} );
		c.dialog({
			dialogClass: 'wp-dialog',
            modal: true,
            closeOnEscape: true,
			width: w,
			height: h,
			buttons: [
                {text: photoshow[ 'texts' ][ 'insert_bttn' ], click: function() {
					var images = $( '.body-images' ).find( 'input[name="image"]:checked' );

					if( images.length )
					{
						var gallery = $( '#photoshow_gallery' ).val(),
							gallery_get_shortcode =  gallery + '_output',
							list = [],
							shortcode = '';
							
						images.each( function( i, e )
										{
											var v = $( e ).val();
											list.push( imagesList[ v ] ); 	
										}
									);

						if( typeof window[ gallery_get_shortcode ] != 'undefined' )
						{
							shortcode = window[ gallery_get_shortcode ]( 
																		{ 
																			'images' 	  	  : list,
																			'entry_point' 	  : $( '#' + gallery ),
																			'start_shortcode' : '[' + photoshow[ 'shortcode' ] + ']',
																			'end_shortcode'   : '[/' + photoshow[ 'shortcode' ] + ']'
																		}	
							                                          );
						}
						
						if( !/^\s*$/.test( shortcode ) )
						{
							// Insert the shortcode and close the dialog.
							if( typeof window[ 'send_to_editor' ] != 'undefined' )
							{
								window[ 'send_to_editor' ]( shortcode );
							}
                            else
                            {
                                shortcode = shortcode.replace( '[smart-image-gallery]', '' ).replace( '[/smart-image-gallery]', '' );
                                $( '.cpsig_shortcode' ).val( shortcode );
                            }
							$(this).dialog( "close" ); 
						}
					}
					else
					{
						// Alert message images required
						alert( photoshow[ 'texts' ][ 'images_required_error' ] );
					}
				}}
            ],
			close:function(){
				$(this).dialog('destroy');
                c.remove();
			},
			open:function( evt, ui ){
				var me = $( this );
				me.find( '.photoshow-bodies' ).height( me.height() - me.find( '.photoshow-headers' ).height() - 42 );
			}
        });
	};
	
	function headersHandle( e )
	{
		var e = $( e );
		if( e.hasClass( 'active' ) )
		{
			return;
		}
		var a = $( '.photoshow-headers .active');
		a.removeClass( 'active' );	
		$( '.'+a.attr( 'rel' ) ).hide();	
		
		e.addClass( 'active' );
		$( '.'+e.attr( 'rel' ) ).show();
	}
	
	function searchTerm()
	{
		var q = $.trim( $( '#searchTerms' ).val() );
		$( '.more-btn' ).hide();
		$( '.module .images-carousel .container' ).html( '' );
		
		currentTerm = q;
		
		if( !/^\s*$/.test( q ) )
		{
			$( '.module' ).each( function(){ displayLoadingScreen( this.id ); } );
			$.getJSON( photoshow.site_url, { 'photoshow_action' : 'get', 'terms' : q, 'from' : 0 } )
			 .done(
				( function( action )
				{
					return function( data )
							{
								$( '.module .images-carousel .container' ).html( '' );
								photoshowAdmin.loadImages( data, action );
							};
				} )( 'get' )	
			 );
		}
	};
	
	function loadImages( data, action )
	{
		for( var module in data )
		{
			var md = data[ module ],
				t = '',
				contentObj,
				imgs;
				
			if( typeof md[ 'error' ] != 'undefined' )
			{
				t = '<div class="photoshow-error" >' + md[ 'error' ] + '</div>';
			}
			else
			{
				var r = md[ 'results' ];
					
				if( r.length )
				{
					var counter;
					for( var i = 0, h = r.length; i < h; i++ )
					{
					
						counter = imagesCounter;
						imagesCounter++;
						
						imagesList[ counter ] = r[ i ];
						
						var origin_begin_tag = origin_end_tag = '';
						if( typeof r[ i ][ 'origin' ] != 'undefined' && !/^\s*$/.test( r[ i ][ 'origin' ] ) )
						{
							origin_begin_tag = '<a href="' + r[ i ][ 'origin' ] + '" target="_blank" >';
							origin_end_tag = '</a>';
						}
						
						t += '<div class="image">' + origin_begin_tag + '<img src="' + r[ i ][ 'url' ] + '" alt="' + r[ i ][ 'alt' ] + '" />' + origin_end_tag + '<input type="checkbox" name="image" value="' + counter + '" /></div>';
					}
				}
				else
				{
					t = '<div class="photoshow-error" > 0 results </div>';
				}	
			}
			
			contentObj = $( t );
			imgs = contentObj.find( 'img' );
			imgs.on( 'load',
				function( evt )
				{
					jQuery( evt.target ).parents( '.image' ).css( 'display', 'inline-block' );
				}
			);
			
			if( imgs.length || action == 'get' )
			{
				$( '#' + module + 'Container .container' ).append( contentObj );
			}	
			
			showHideHandleBtns( module, imgs.length );
			hideLoadingScreen( module );
		}
	};
	
	function showHideHandleBtns( module, show )
	{
		var hndl = $( '#'+module+'Container' ).siblings( '.more-btn' );
		if( show )
		{
			hndl.show();
		}
		else
		{
			hndl.hide();
		}
	};
	
	function moreHandle( module )
	{
		if( !/^\s+$/.test( currentTerm ) )
		{
			displayLoadingScreen( module );
			$.getJSON( photoshow.site_url, { 'photoshow_action' : 'more', 'terms' : currentTerm, 'from' : $( '#'+module+'Container' ).find( '.image' ).length, 'module' : module } )
			 .done(
				( function( action )
				{
					return function( data )
							{
								photoshowAdmin.loadImages( data, action );
							}; 
				} )( 'more' )	
			 );
		}
	};
	
	function displayLoadingScreen( module )
	{
		var moduleDiv = $( '#'+module ),
			h = moduleDiv.height();
		if( moduleDiv.find( '.loading' ).length == 0 )
		{	
			moduleDiv.append( $( '<div class="loading" style="height:'+h+'px;line-height:'+h+'px;">Loading</div>' ) );
		}	
	};
	
	function hideLoadingScreen( module )
	{
		$( '#'+module ).find( '.loading' ).remove();
	};
	
	function selectGallery( e )
	{
		$( '.photoshow .body-galleries' ).find( '.active' ).removeClass( 'active' );
		$( '#' + e.options[ e.selectedIndex ].value ).addClass( 'active' );
	};
	
	var obj = {};
	obj.open = loadWindow;
	obj.search = searchTerm;
	obj.loadImages = loadImages;
	obj.moreHandle = moreHandle;
	obj.headersHandle = headersHandle;
	obj.selectGallery = selectGallery;
	
	window[ 'photoshowAdmin' ] = obj;
	
} );