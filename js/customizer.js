/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a, .site-title' ).text( to );
			console.log("asdasdsadasdsa");
		} );
	} );


	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
			console.log("Hello world");
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	wp.customize( 'hero_title', function( value ) {
		value.bind( function( to ) {
			$( '.is-front-page .site-header .title' ).text( to );
		} );
	} );

	wp.customize( 'hero_subtitle', function( value ) {
		value.bind( function( to ) {
			$( '.is-front-page .site-header .subtitle' ).text( to );
		} );
	} );

	// wp.customize('pacific_background_size', function( value ){
		// value.bind(function( to ){
//
			// console.log("pacific background");
			// $('body').css('background-size', to);
//
		// });
//
	// });




} )( jQuery );
