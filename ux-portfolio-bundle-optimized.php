<?php
/**
 * Plugin Name: UX Portfolio Bundle - Optimized
 * Description: 51 Elementor widgets for UX and design case studies: personas, journey maps, affinity maps, user flows, storyboards and more, sharing one stylesheet and one script.
 * Version: 2.21.0
 * Author: Made by Mystie
 * Author URI: https://www.mystiechamberlin.com/design/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.4
 * Text Domain: ux-portfolio-optimized
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Give widget registration headroom on memory-constrained hosts. Only ever
// raises the limit, never lowers it, and only if the current limit is below
// 256M — so it's a no-op where the server already allows more.
if ( function_exists( 'wp_convert_hr_to_bytes' ) ) {
	add_action(
		'elementor/init',
		function () {
			$current = wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) );
			if ( $current > 0 && $current < 268435456 ) { // 256M
				@ini_set( 'memory_limit', '256M' );
			}
		},
		0
	);
}

// Autoload widget + include classes on first use, so a class file is only
// require'd when its class is actually referenced — instead of eagerly loading
// every widget file up front. Maps UX_Foo_Bar_Widget -> widgets/foo-bar.php and
// the base/trait classes -> includes/.
spl_autoload_register(
	function ( $class ) {
		if ( strpos( $class, 'UX_' ) !== 0 ) {
			return;
		}

		// Base classes live in includes/.
		$includes = array(
			'UX_Base_Widget'  => __DIR__ . '/includes/base-widget.php',
			'UX_Tabbed_Trait' => __DIR__ . '/includes/tabbed-trait.php',
			'UX_Lens'         => __DIR__ . '/includes/lens.php',
		);
		if ( isset( $includes[ $class ] ) ) {
			if ( is_readable( $includes[ $class ] ) ) {
				require_once $includes[ $class ];
			}
			return;
		}

		// Widget classes: UX_Foo_Bar_Widget -> widgets/foo-bar.php
		if ( substr( $class, -7 ) === '_Widget' ) {
			$core = substr( $class, 3, -7 );              // "Foo_Bar"
			$slug = strtolower( str_replace( '_', '-', $core ) ); // "foo-bar"
			$file = __DIR__ . '/widgets/' . $slug . '.php';
			if ( is_readable( $file ) ) {
				require_once $file;
			}
		}
	}
);

class UX_Portfolio_Bundle_Optimized {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Register widgets
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Case study lenses: per-element Lens setting + data-lens output.
		UX_Lens::init();

		// Frontend assets: load ONLY on pages that actually contain a UX widget,
		// instead of on every page. The Elementor frontend/editor/preview hooks
		// below still load unconditionally in those contexts (the editor needs
		// the CSS to render), so nothing there changes — this only trims the
		// general front-end page load where no UX widget is present.
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_frontend' ), 20 );

		// Elementor frontend/editor/preview — these load in Elementor contexts
		// where the assets are genuinely needed.
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );  // Frontend
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_styles' ) );  // Editor
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_styles' ) );  // Preview

		// Enqueue scripts
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'enqueue_scripts' ) );

		// Add accordion opener JavaScript
		add_action( 'wp_footer', array( $this, 'accordion_opener_js' ), 999 );

		// Register category
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );

		// Reliably load Pro Custom / Google fonts used by any Brand Assets widget
		// on the page. Scanning the document up front (rather than relying solely
		// on the widget's render) guarantees the font <link>/@font-face is printed
		// even when widget render runs late in the front-end cycle.
		add_action( 'wp_enqueue_scripts', array( $this, 'preload_brand_fonts' ), 5 );
		add_action( 'elementor/frontend/before_render', array( $this, 'preload_brand_fonts_from_widget' ) );
	}

	/**
	 * Scan the current document's Elementor data for Brand Assets widgets and
	 * enqueue each picker-selected font so it loads on the front end/preview.
	 */
	public function preload_brand_fonts() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! isset( \Elementor\Plugin::instance()->frontend ) ) {
			return;
		}
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return;
		}
		$data = get_post_meta( $post_id, '_elementor_data', true );
		if ( ! is_string( $data ) || $data === '' || strpos( $data, 'ux-brand-assets' ) === false ) {
			return;
		}

		$decoded = json_decode( $data, true );
		if ( ! is_array( $decoded ) ) {
			return;
		}
		$this->enqueue_fonts_in_elements( $decoded );
	}

	/** Recursively walk Elementor elements, enqueuing Brand Assets picker fonts. */
	private function enqueue_fonts_in_elements( $elements ) {
		if ( ! is_array( $elements ) ) {
			return;
		}
		foreach ( $elements as $el ) {
			if ( ! is_array( $el ) ) {
				continue;
			}
			$type = $el['widgetType'] ?? '';
			if ( $type === 'ux-brand-assets' && ! empty( $el['settings']['fonts'] ) && is_array( $el['settings']['fonts'] ) ) {
				foreach ( $el['settings']['fonts'] as $font ) {
					$picker = isset( $font['name'] ) ? trim( $font['name'] ) : '';
					$custom = isset( $font['name_custom'] ) ? trim( $font['name_custom'] ) : '';
					if ( $custom === '' && $picker !== '' ) {
						\Elementor\Plugin::instance()->frontend->enqueue_font( $picker );
					}
				}
			}
			if ( ! empty( $el['elements'] ) ) {
				$this->enqueue_fonts_in_elements( $el['elements'] );
			}
		}
	}

	/** Belt-and-suspenders: also enqueue when a Brand Assets widget is about to render. */
	public function preload_brand_fonts_from_widget( $element ) {
		if ( ! method_exists( $element, 'get_name' ) || $element->get_name() !== 'ux-brand-assets' ) {
			return;
		}
		if ( ! isset( \Elementor\Plugin::instance()->frontend ) ) {
			return;
		}
		$settings = $element->get_settings_for_display();
		if ( empty( $settings['fonts'] ) || ! is_array( $settings['fonts'] ) ) {
			return;
		}
		foreach ( $settings['fonts'] as $font ) {
			$picker = isset( $font['name'] ) ? trim( $font['name'] ) : '';
			$custom = isset( $font['name_custom'] ) ? trim( $font['name_custom'] ) : '';
			if ( $custom === '' && $picker !== '' ) {
				\Elementor\Plugin::instance()->frontend->enqueue_font( $picker );
			}
		}
	}

	/**
	 * On the general front-end, only enqueue the bundle if the current document
	 * actually uses a UX widget. Falls back to always-enqueue if Elementor's
	 * document API isn't available, so pages can never end up unstyled.
	 */
	public function maybe_enqueue_frontend() {
		$should = true; // safe default: if we can't tell, load it

		if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
			// In Elementor preview mode the saved meta may lag the current edits,
			// so always load there to avoid an unstyled preview.
			$is_preview = false;
			if ( isset( $_GET['elementor-preview'] ) ) {
				$is_preview = true;
			} elseif ( method_exists( \Elementor\Plugin::$instance->preview, 'is_preview_mode' )
						&& \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				$is_preview = true;
			}

			if ( ! $is_preview ) {
				$post_id = get_the_ID();
				if ( $post_id ) {
					$meta = get_post_meta( $post_id, '_elementor_data', true );
					if ( is_string( $meta ) && $meta !== '' ) {
						// Our widgets are stored as "widgetType":"ux-..." in the data.
						// Match both "ux- (widgetType) and ux- appearing elsewhere.
						$should = ( strpos( $meta, 'ux-' ) !== false );
					}
				}
			}
		}

		if ( $should ) {
			$this->enqueue_styles();
			$this->enqueue_scripts();
		}
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'ux-portfolio',
			array(
				'title' => __( 'UX Portfolio', 'ux-portfolio-optimized' ),
				'icon'  => 'fa fa-briefcase',
			)
		);
	}

	public function enqueue_styles() {
		wp_enqueue_style(
			'ux-portfolio-bundle',
			plugins_url( 'assets/css/ux-bundle.css', __FILE__ ),
			array(),
			'2.21.0.' . filemtime( __DIR__ . '/assets/css/ux-bundle.css' )
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'ux-portfolio-bundle',
			plugins_url( 'assets/js/ux-bundle.js', __FILE__ ),
			array( 'jquery' ),
			'2.21.0.' . filemtime( __DIR__ . '/assets/js/ux-bundle.js' ),
			true
		);
	}

	public function register_widgets( $widgets_manager ) {

		// Base class + trait load on demand via the autoloader when the first
		// widget class is instantiated below — no manual require needed.

		// Widget slugs. Each maps to widgets/{slug}.php and the class UX_{Slug}_Widget.
		$widgets = array(
			'user-persona',
			'logo-grid',
			'case-study-overview',
			'project-introduction',
			'wireframe-showcase',
			'section-divider',
			'journey-map',
			'empathy-map',
			'user-flow-diagram',
			'prototype-display',
			'user-statements',
			'accordion-link',
			'pdf-viewer',
			'brand-assets',
			'number-box',
			'competitive-analysis',
			'visual-competitive-analysis',
			'moodboard',
			'style-tile',
			'mockup-flow',
			'icon-list',
			'uvp-canvas',
			'affinity-map',
			'description',
			'features-benefits',
			'benefits-ladder',
			'value-principles',
			'competitive-differentiation',
			'competitive-suite',
			'logo-concepts',
			'annotated-design',
			'ui-kit',
			'bar-chart',
			'word-cloud',
			'swot-analysis',
			'sitemap',
			'sketch-ideation',
			'storyboard',
			'visual-design-gallery',
			'remote-navigation',
			'brand-development-process',
			'table-of-contents',
			'lens-switch',
			'tldr',
			'cs-rail',
			'cs-panel',
			'cs-hero',
			'cs-section',
			'cs-footer',
			'cs-sechead',
			'cs-more',
		);

		foreach ( $widgets as $widget ) {
			// Class name follows the file convention: foo-bar -> UX_Foo_Bar_Widget.
			// Referencing the class triggers the autoloader to load its file only
			// if it isn't already loaded — so nothing is require'd twice, and the
			// base class/trait load exactly once on the first instantiation.
			$class_name = 'UX_' . str_replace( '-', '_', ucwords( $widget, '-' ) ) . '_Widget';

			if ( class_exists( $class_name ) ) {
				$widgets_manager->register( new $class_name() );
			}
		}
	}

	public function accordion_opener_js() {
		?>
		<script>
		jQuery(document).ready(function($) {
			function openAccordionItem(accordionId, itemNumber) {
				var $accordion;
				if (accordionId) {
					$accordion = $('#' + accordionId);
					if (!$accordion.length) {
						$accordion = $('#' + accordionId).find('.elementor-accordion').first();
					}
					if (!$accordion.length) {
						if (window.console) console.warn('UX Accordion Link: accordion #' + accordionId + ' not found. Add that CSS ID to the Accordion widget.');
						return false;
					}
				} else {
					$accordion = $('.elementor-accordion').first();
				}

				// If accordion lives inside any hidden tab panel(s), activate
				// the owning tabs first (handles Atomic V4 + V3 nested + legacy
				// by walking up via the standard ARIA tabpanel/tab/aria-controls).
				var hiddenPanels = $accordion.parents('[role="tabpanel"]').filter(function() {
					var $p = $(this);
					// Hidden if not currently selected (covers all variants)
					return $p.is(':hidden') ||
							($p.attr('hidden') !== undefined) ||
							(!$p.hasClass('e--selected') &&
							!$p.hasClass('elementor-active') &&
							!$p.hasClass('e-active'));
				}).toArray();

				if (hiddenPanels.length) {
					// Click outermost first so nested tabs activate in order
					hiddenPanels.reverse().forEach(function(panel) {
						var panelId = panel.id;
						if (!panelId) return;
						var $tabBtn = $('[role="tab"][aria-controls="' + panelId + '"]');
						if ($tabBtn.length) {
							$tabBtn[0].click();
						}
					});
					setTimeout(function() {
						openAccordionItemAfterTab($accordion, itemNumber);
					}, 600);
					return true;
				}

				return openAccordionItemAfterTab($accordion, itemNumber);
			}
			
			function openAccordionItemAfterTab($accordion, itemNumber) {
				var $accordionItem = $accordion.find('.e-n-accordion-item').eq(itemNumber - 1);
				if (!$accordionItem.length) {
					$accordionItem = $accordion.find('.elementor-accordion-item').eq(itemNumber - 1);
				}
				
				if (!$accordionItem.length) {
					if (window.console) console.warn('UX Accordion Link: item #' + itemNumber + ' not found.');
					return false;
				}
				
				var $accordionTitle = $accordionItem.find('.e-n-accordion-item-title, .elementor-tab-title').first();
				
				if ($accordionTitle.length) {
					if ($accordionTitle[0].tagName === 'SUMMARY') {
						var $detailsElement = $accordionTitle.closest('details');
						if ($detailsElement.length) {
							$detailsElement[0].open = true;
						} else if ($accordionItem[0].tagName === 'DETAILS') {
							$accordionItem[0].open = true;
						}
					} else {
						if (!$accordionTitle.hasClass('elementor-active')) {
							$accordionTitle[0].click();
						}
					}
					
					setTimeout(function() {
						$('html, body').animate({
							scrollTop: $accordionItem.offset().top - 100
						}, 500);
					}, 150);
					
					return true;
				}
				
				return false;
			}
			
			$(document).on('click', '[data-accordion-widget][data-accordion-item]', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var accordionId = $(this).data('accordion-widget');
				var itemNumber = $(this).data('accordion-item');
				var targetUrl = $(this).data('accordion-url');
				
				if (targetUrl && targetUrl !== window.location.pathname) {
					window.location.href = targetUrl + '#accordion-' + accordionId + '-item-' + itemNumber;
				} else {
					openAccordionItem(accordionId, itemNumber);
				}
				
				return false;
			});
			
			if (window.location.hash) {
				var hash = window.location.hash.substring(1).replace(/[^a-z0-9\-_]/gi, '');
				var match = hash.match(/^accordion-(.+)-item-(\d+)$/);
				if (match) {
					var accordionId = match[1];
					var itemNumber = parseInt(match[2]);
					setTimeout(function() {
						openAccordionItem(accordionId, itemNumber);
					}, 500);
				}
			}
			
			$(window).on('hashchange', function() {
				if (window.location.hash) {
					var hash = window.location.hash.substring(1).replace(/[^a-z0-9\-_]/gi, '');
					var match = hash.match(/^accordion-(.+)-item-(\d+)$/);
					if (match) {
						var accordionId = match[1];
						var itemNumber = parseInt(match[2]);
						openAccordionItem(accordionId, itemNumber);
					}
				}
			});
		});
		</script>
		<?php
	}
}

// Initialize
UX_Portfolio_Bundle_Optimized::instance();

// Case study layout assets (loaded only by the cs-* widgets that depend on them).
add_action(
	'init',
	function () {
		$u = plugins_url( 'assets/', __FILE__ );
		wp_register_style( 'ux-cs-layout', $u . 'css/cs-layout.css', array(), '2.21.0.' . filemtime( __DIR__ . '/assets/css/cs-layout.css' ) );
		wp_register_script( 'ux-cs-layout', $u . 'js/cs-layout.js', array(), '2.21.0.' . filemtime( __DIR__ . '/assets/js/cs-layout.js' ), true );
	}
);
