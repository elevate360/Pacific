/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $, api ) {

	// Site title and description.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	api( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Post Meta
	api( 'post_date', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-meta .posted-on' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-meta .posted-on' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_author', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-meta .byline' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-meta .byline' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_cat', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-footer .cat-links' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-footer .cat-links' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_tag', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-footer .tags-links' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-footer .tags-links' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_comments', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-footer .comments-link' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-footer .comments-link' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'author_display', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.author-info' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.author-info' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'primary_color', function( value ){
		value.bind( function( to ) {
			var primaryColorBgColor 	= 'button, input[type="button"], input[type="reset"], input[type="submit"], .sticky-label, .post-edit-link:hover, .post-edit-link:focus, .masonry-container a.more-link:hover, .masonry-container a.more-link:focus, .comment-body > .reply a:hover, .comment-body > .reply a:focus, #cancel-comment-reply-link:hover, #cancel-comment-reply-link:focus, .posts-navigation .nav-previous a:hover, .posts-navigation .nav-previous a:focus, .posts-navigation .nav-next a:hover, .posts-navigation .nav-next a:focus, .post-navigation .nav-previous a:hover, .post-navigation .nav-previous a:focus, .post-navigation .nav-next a:hover, .post-navigation .nav-next a:focus, .page-numbers:hover:not(.current), .page-numbers:focus:not(.current), #secondary .widget_tag_cloud a:hover, #secondary .widget_tag_cloud a:focus, .return-to-top:hover, .return-to-top:focus',
				primaryColorTextColor 	= 'a, .main-navigation ul.menu .sub-menu .menu-item a:hover, .main-navigation ul.menu .sub-menu .menu-item a:focus, .entry-meta a:hover, .entry-meta a:focus, .entry-title a:hover, .entry-title a:focus, .entry-footer a:hover, .entry-footer a:focus, .author-title a:hover, .author-title a:focus, .comment-meta a:hover, .comment-meta a:focus, .footer-widgets a:hover, .footer-widgets a:focus, .site-footer a:hover, .site-footer a:focus',
				primaryColorBorderColor = '.post-edit-link:hover, .post-edit-link:focus, .masonry-container a.more-link:hover, .masonry-container a.more-link:focus, .comment-body > .reply a:hover, .comment-body > .reply a:focus, .page-numbers:hover:not(.current), .page-numbers:focus:not(.current), #secondary .widget_tag_cloud a:hover, #secondary .widget_tag_cloud a:focus, .return-to-top:hover, .return-to-top:focus';

			$( '#primary-color' ).text( primaryColorBgColor + '{background-color:'+ to +'}' + primaryColorTextColor + '{color:'+ to +'}' + primaryColorBorderColor + '{border-color:'+ to +'}' );
		} );
	} );

	api( 'secondary_color', function( value ){
		value.bind( function( to ) {
			var secondaryColorBgColor 	= 'button:hover, button:active, button:focus, input[type="button"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:hover, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:hover, input[type="submit"]:active, input[type="submit"]:focus',
				secondaryColorTextColor = 'a:hover, a:focus';

			$( '#secondary-color' ).text( secondaryColorBgColor + '{background-color:'+ to +'}' + secondaryColorTextColor + '{color:'+ to +'}' );
		} );
	} );

	api.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {

		var container, button, menu, links, i, len;

		container = document.getElementById( 'site-navigation' );
		if ( ! container ) {
			return;
		}

		button = container.getElementsByTagName( 'button' )[0];
		if ( 'undefined' === typeof button ) {
			return;
		}

		menu = container.getElementsByTagName( 'ul' )[0];

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			button.style.display = 'none';
			return;
		}

		menu.setAttribute( 'aria-expanded', 'false' );
		if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
			menu.className += ' nav-menu';
		}

		button.onclick = function() {
			if ( -1 !== container.className.indexOf( 'toggled' ) ) {
				container.className = container.className.replace( ' toggled', '' );
				button.setAttribute( 'aria-expanded', 'false' );
				menu.setAttribute( 'aria-expanded', 'false' );
			} else {
				container.className += ' toggled';
				button.setAttribute( 'aria-expanded', 'true' );
				menu.setAttribute( 'aria-expanded', 'true' );
			}
		};

		// Get all the link elements within the menu.
		links    = menu.getElementsByTagName( 'a' );

		// Each time a menu link is focused or blurred, toggle focus.
		for ( i = 0, len = links.length; i < len; i++ ) {
			links[i].addEventListener( 'focus', toggleFocus, true );
			links[i].addEventListener( 'blur', toggleFocus, true );
		}

		/**
		 * Sets or removes .focus class on an element.
		 */
		function toggleFocus() {
			var self = this;

			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					if ( -1 !== self.className.indexOf( 'focus' ) ) {
						self.className = self.className.replace( ' focus', '' );
					} else {
						self.className += ' focus';
					}
				}

				self = self.parentElement;
			}
		}

		/**
		 * Toggles `focus` class to allow submenu access on tablets.
		 */
		( function( container ) {
			var touchStartFn, i,
				parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

			if ( 'ontouchstart' in window ) {
				touchStartFn = function( e ) {
					var menuItem = this.parentNode, i;

					if ( ! menuItem.classList.contains( 'focus' ) ) {
						e.preventDefault();
						for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
							if ( menuItem === menuItem.parentNode.children[i] ) {
								continue;
							}
							menuItem.parentNode.children[i].classList.remove( 'focus' );
						}
						menuItem.classList.add( 'focus' );
					} else {
						menuItem.classList.remove( 'focus' );
					}
				};

				for ( i = 0; i < parentLink.length; ++i ) {
					parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
				}
			}
		}( container ) );

		$( document ).ready( function() {
			$( '.main-navigation .sub-menu' ).before( '<button class="sub-menu-toggle" role="button" aria-expanded="false">' + Pacificl10n.expandMenu + Pacificl10n.collapseMenu + Pacificl10n.subNav + '</button>' );
			$( '.sub-menu-toggle' ).on( 'click', function( e ) {

				e.preventDefault();

				var $this = $( this );
				$this.attr( 'aria-expanded', function( index, value ) {
					return 'false' === value ? 'true' : 'false';
				});

				// Add class to toggled menu
				$this.toggleClass( 'toggled' );
				$this.next( '.sub-menu' ).slideToggle( 0 );

			});
		});

	});

	wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {
		var $footerWidgets = $( '#secondary .footer-widgets' );
		if ( 'sidebar-1' === sidebarPartial.sidebarId ) {
            $footerWidgets.masonry( 'reloadItems' );
            $footerWidgets.masonry( 'layout' );
		}
	});

} )( jQuery, wp.customize );
