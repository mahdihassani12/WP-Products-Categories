( function ( $, window ) {
	'use strict';

	function initCarousel( $scope ) {
		$scope.find( '.mim-pc-carousel' ).each( function () {
			var carousel = this;
			var shell = carousel.closest( '.mim-pc-carousel-shell' );
			var options;

			if ( ! shell ) {
				return;
			}

			if ( carousel.mimPcSwiper && 'function' === typeof carousel.mimPcSwiper.destroy ) {
				carousel.mimPcSwiper.destroy( true, true );
			}

			try {
				options = JSON.parse( carousel.getAttribute( 'data-carousel-options' ) || '{}' );
			} catch ( error ) {
				options = {};
			}

			if ( shell.querySelector( '.mim-pc-prev' ) ) {
				options.navigation = {
					prevEl: shell.querySelector( '.mim-pc-prev' ),
					nextEl: shell.querySelector( '.mim-pc-next' )
				};
			}
			if ( shell.querySelector( '.mim-pc-pagination' ) ) {
				options.pagination = {
					el: shell.querySelector( '.mim-pc-pagination' ),
					clickable: true
				};
			}
			options.keyboard = { enabled: true };
			options.watchOverflow = true;

			if ( window.elementorFrontend && window.elementorFrontend.utils && window.elementorFrontend.utils.swiper ) {
				Promise.resolve( new window.elementorFrontend.utils.swiper( carousel, options ) ).then( function ( swiper ) {
					carousel.mimPcSwiper = swiper;
				} );
			}
		} );
	}

	$( window ).on( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/mim-product-categories.default', initCarousel );
		}
	} );
}( jQuery, window ) );
