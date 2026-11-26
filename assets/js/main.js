/**
 * ECWRD Institutional — front-end behaviour.
 *
 * Modules: smooth scroll (Lenis) · header state · mobile menu · reveal
 * animations · sand-particle hero · page transitions.
 *
 * No build step: plain ES2017, loaded with `defer`.
 */
( function () {
	'use strict';

	window.ecwrdBooted = true;

	var doc = document;
	var root = doc.documentElement;
	var EASE_OUT_MS = 500;
	var reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var lenis = null;

	/* ---------------------------------------------------------------------
	 * Smooth scroll
	 * ------------------------------------------------------------------ */
	function initSmoothScroll() {
		if ( reducedMotion || typeof window.Lenis !== 'function' ) {
			return;
		}

		lenis = new window.Lenis( { duration: 1.15, smoothWheel: true } );
		window.ecwrdLenis = lenis;

		function loop( time ) {
			lenis.raf( time );
			window.requestAnimationFrame( loop );
		}
		window.requestAnimationFrame( loop );
	}

	/* ---------------------------------------------------------------------
	 * Header: solid background once the page is scrolled
	 * ------------------------------------------------------------------ */
	function initHeader() {
		var header = doc.querySelector( '[data-header]' );
		if ( ! header ) {
			return;
		}

		var ticking = false;
		function update() {
			header.classList.toggle( 'is-scrolled', window.scrollY > 32 );
			ticking = false;
		}

		update();
		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		}, { passive: true } );
	}

	/* ---------------------------------------------------------------------
	 * Mobile menu
	 * ------------------------------------------------------------------ */
	var menu = {
		open: false,
		toggle: null,
		panel: null,
		header: null,

		init: function () {
			this.toggle = doc.querySelector( '[data-menu-toggle]' );
			this.panel = doc.querySelector( '[data-mobile-menu]' );
			this.header = doc.querySelector( '[data-header]' );

			if ( ! this.toggle || ! this.panel ) {
				return;
			}

			var self = this;
			this.toggle.addEventListener( 'click', function () {
				self.set( ! self.open );
			} );

			doc.addEventListener( 'keydown', function ( event ) {
				if ( 'Escape' === event.key && self.open ) {
					self.set( false );
					self.toggle.focus();
				}
			} );

			// Close if the viewport grows past the mobile breakpoint.
			window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( event ) {
				if ( event.matches ) {
					self.set( false );
				}
			} );
		},

		set: function ( open ) {
			if ( ! this.panel || open === this.open ) {
				return;
			}
			this.open = open;

			this.panel.classList.toggle( 'is-open', open );
			this.header.classList.toggle( 'is-menu-open', open );
			doc.body.classList.toggle( 'is-menu-open', open );

			if ( open ) {
				this.panel.removeAttribute( 'inert' );
			} else {
				this.panel.setAttribute( 'inert', '' );
			}

			this.toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			this.toggle.setAttribute( 'aria-label', this.toggle.getAttribute( open ? 'data-label-close' : 'data-label-open' ) );

			if ( lenis ) {
				if ( open ) {
					lenis.stop();
				} else {
					lenis.start();
				}
			}
		},
	};

	/* ---------------------------------------------------------------------
	 * Reveal animations
	 *
	 * [data-reveal="fade"]  — fades/slides up.
	 * [data-reveal="lines"] — each line slides up out of a mask.
	 * [data-reveal-onload]  — plays on page load instead of on scroll.
	 *
	 * The heading itself is observed (not the masked lines inside it): the
	 * lines start fully clipped, so observing them would never fire.
	 * ------------------------------------------------------------------ */
	function initReveals() {
		var items = Array.prototype.slice.call( doc.querySelectorAll( '[data-reveal]' ) );

		function reveal( el ) {
			el.classList.add( 'is-revealed' );
		}

		if ( reducedMotion || ! ( 'IntersectionObserver' in window ) ) {
			items.forEach( reveal );
			return;
		}

		var onLoad = items.filter( function ( el ) {
			return el.hasAttribute( 'data-reveal-onload' );
		} );
		var onScroll = items.filter( function ( el ) {
			return ! el.hasAttribute( 'data-reveal-onload' );
		} );

		// Two frames: guarantee the hidden state has been painted first.
		window.requestAnimationFrame( function () {
			window.requestAnimationFrame( function () {
				onLoad.forEach( reveal );
			} );
		} );

		function observe( selector, margin ) {
			var observer = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						reveal( entry.target );
						observer.unobserve( entry.target );
					}
				} );
			}, { rootMargin: margin } );

			onScroll.filter( function ( el ) {
				return el.matches( selector );
			} ).forEach( function ( el ) {
				observer.observe( el );
			} );
		}

		observe( '[data-reveal="fade"]', '-60px' );
		observe( '[data-reveal="lines"]', '-80px' );
	}

	/* ---------------------------------------------------------------------
	 * Sand-particle hero
	 *
	 * Grains drift and follow the cursor; as the visitor scrolls they settle
	 * into ordered rows.
	 * ------------------------------------------------------------------ */
	function initSandHero() {
		var canvas = doc.querySelector( '[data-sand-canvas]' );
		if ( ! canvas || ! canvas.getContext ) {
			return;
		}

		var ctx = canvas.getContext( '2d' );
		var GRAINS = [ '#B08D57', '#C4A882', '#8A6A3F', '#9C8A6B' ];
		var ROWS = 9;
		var w = 0;
		var h = 0;
		var field = [];
		var mouse = { x: 0, y: 0 };

		function build() {
			var dpr = Math.min( window.devicePixelRatio || 1, 2 );
			w = canvas.offsetWidth;
			h = canvas.offsetHeight;
			canvas.width = w * dpr;
			canvas.height = h * dpr;
			ctx.setTransform( dpr, 0, 0, dpr, 0, 0 );

			var count = w < 768 ? 120 : 260;
			field = [];
			for ( var i = 0; i < count; i++ ) {
				var row = i % ROWS;
				field.push( {
					x: Math.random() * w,
					y: Math.random() * h,
					vx: 0.12 + Math.random() * 0.28,
					tx: ( i / count ) * w + ( Math.random() - 0.5 ) * 12,
					ty: h * 0.2 + ( row * ( h * 0.56 ) ) / ( ROWS - 1 ) + ( Math.random() - 0.5 ) * 2.5,
					r: 0.7 + Math.random() * 1.7,
					c: GRAINS[ i % GRAINS.length ],
					a: 0.3 + Math.random() * 0.55,
					ph: Math.random() * Math.PI * 2,
					sp: 0.3 + Math.random() * 0.7,
					d: 0.3 + Math.random() * 0.7,
				} );
			}
		}

		function frame( t ) {
			var scrollY = window.scrollY;

			// Off-screen: keep the loop alive but skip drawing.
			if ( ! reducedMotion && scrollY > h * 1.2 ) {
				window.requestAnimationFrame( frame );
				return;
			}

			var raw = Math.min( 1, Math.max( 0, scrollY / ( h * 0.85 ) ) );
			var p = 1 - Math.pow( 1 - raw, 3 );
			var time = t * 0.001;

			ctx.clearRect( 0, 0, w, h );
			for ( var i = 0; i < field.length; i++ ) {
				var pt = field[ i ];
				pt.x += pt.vx * ( 1 - p );
				if ( pt.x > w + 20 ) {
					pt.x = -20;
				}
				var nx = pt.x + Math.sin( time * pt.sp + pt.ph ) * 10;
				var ny = pt.y + Math.cos( time * pt.sp * 0.8 + pt.ph ) * 8;
				var x = nx + ( pt.tx - nx ) * p + mouse.x * 14 * pt.d * ( 1 - p );
				var y = ny + ( pt.ty - ny ) * p + mouse.y * 10 * pt.d * ( 1 - p );

				ctx.globalAlpha = pt.a;
				ctx.fillStyle = pt.c;
				ctx.beginPath();
				ctx.arc( x, y, pt.r * ( 1 - p * 0.3 ), 0, Math.PI * 2 );
				ctx.fill();
			}
			ctx.globalAlpha = 1;

			if ( ! reducedMotion ) {
				window.requestAnimationFrame( frame );
			}
		}

		build();

		if ( reducedMotion ) {
			frame( 0 );
		} else {
			window.requestAnimationFrame( frame );
		}

		// Rebuild only when the canvas size really changes. Mobile browsers fire
		// `resize` while scrolling (toolbar show/hide), which used to reshuffle
		// every grain mid-scroll.
		var resizeTimer = 0;
		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( function () {
				if ( canvas.offsetWidth !== w || canvas.offsetHeight !== h ) {
					build();
					if ( reducedMotion ) {
						frame( 0 );
					}
				}
			}, 120 );
		} );

		window.addEventListener( 'mousemove', function ( event ) {
			mouse.x = ( event.clientX / w - 0.5 ) * 2;
			mouse.y = ( event.clientY / h - 0.5 ) * 2;
		}, { passive: true } );
	}

	/* ---------------------------------------------------------------------
	 * Page transitions
	 *
	 * Internal links fade the page out (0.5s) before navigating; the next page
	 * fades in via CSS. Hovered links are prefetched to hide network latency.
	 * ------------------------------------------------------------------ */
	function initPageTransitions() {
		var page = doc.querySelector( '[data-page]' );
		if ( ! page ) {
			return;
		}

		var prefetched = {};

		function internalUrl( link ) {
			if ( ! link || ! link.href || link.hasAttribute( 'download' ) || link.hasAttribute( 'data-no-transition' ) ) {
				return null;
			}
			if ( link.target && '_self' !== link.target ) {
				return null;
			}
			var url = new URL( link.href, window.location.href );
			if ( url.origin !== window.location.origin || ! /^https?:$/.test( url.protocol ) ) {
				return null;
			}
			if ( /\/wp-(admin|login\.php)/.test( url.pathname ) || /\.(pdf|zip|jpe?g|png|webp|svg|docx?|xlsx?)$/i.test( url.pathname ) ) {
				return null;
			}
			return url;
		}

		function prefetch( event ) {
			var url = internalUrl( event.target.closest && event.target.closest( 'a' ) );
			if ( ! url || prefetched[ url.href ] || url.href === window.location.href ) {
				return;
			}
			prefetched[ url.href ] = true;
			var hint = doc.createElement( 'link' );
			hint.rel = 'prefetch';
			hint.href = url.href;
			doc.head.appendChild( hint );
		}

		doc.addEventListener( 'mouseover', prefetch, { passive: true } );
		doc.addEventListener( 'touchstart', prefetch, { passive: true } );

		doc.addEventListener( 'click', function ( event ) {
			if ( event.defaultPrevented || 0 !== event.button || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) {
				return;
			}

			var link = event.target.closest && event.target.closest( 'a' );
			var url = internalUrl( link );
			if ( ! url ) {
				return;
			}

			var current = new URL( window.location.href );
			var samePage = url.pathname === current.pathname && url.search === current.search;

			// In-page anchors: let the browser / Lenis handle them.
			if ( samePage && url.hash ) {
				return;
			}

			event.preventDefault();
			menu.set( false );

			if ( samePage ) {
				if ( lenis ) {
					lenis.scrollTo( 0 );
				} else {
					window.scrollTo( { top: 0, behavior: reducedMotion ? 'auto' : 'smooth' } );
				}
				return;
			}

			if ( reducedMotion ) {
				window.location.href = url.href;
				return;
			}

			page.classList.add( 'is-leaving' );
			window.setTimeout( function () {
				window.location.href = url.href;
			}, EASE_OUT_MS );
		} );

		// Returning via the back/forward cache: undo the exit state.
		window.addEventListener( 'pageshow', function ( event ) {
			if ( event.persisted ) {
				page.classList.remove( 'is-leaving' );
				menu.set( false );
			}
		} );
	}

	/* ---------------------------------------------------------------------
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		root.classList.add( 'js' );
		initSmoothScroll();
		initHeader();
		menu.init();
		initReveals();
		initSandHero();
		initPageTransitions();
	}

	if ( 'loading' === doc.readyState ) {
		doc.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
