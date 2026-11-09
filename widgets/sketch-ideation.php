<?php
/**
 * Sketch Ideation Widget
 * Display sketches in a grid with optional filterable galleries.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Sketch_Ideation_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-sketch-ideation'; }
	public function get_title() {
		return __( 'Sketch Ideation', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-gallery-grid'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Content
		$this->start_controls_section(
			'sketches_section',
			array(
				'label' => __( 'Sketches', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Sketch Image', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Sketch Title',
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'   => __( 'Description', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
			)
		);

		$repeater->add_control(
			'gallery',
			array(
				'label'       => __( 'Gallery', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Sketches with the same Gallery name appear together and can be filtered by tab. Leave blank if not using galleries.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'sketches',
			array(
				'label'       => __( 'Sketches', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title'   => 'Initial Concept',
						'gallery' => '',
					),
					array(
						'title'   => 'Iteration 1',
						'gallery' => '',
					),
				),
				'title_field' => '{{{ title }}}{{{ gallery ? " — " + gallery : "" }}}',
			)
		);

		$this->end_controls_section();

		// Filter / Gallery options
		$this->start_controls_section(
			'filter_section',
			array(
				'label' => __( 'Filter Tabs', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_all_tab',
			array(
				'label'       => __( 'Show "All" Tab', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Adds an "All" tab that shows every sketch across galleries.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'all_label',
			array(
				'label'     => __( '"All" Tab Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'All', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_all_tab' => 'yes' ),
			)
		);

		$this->add_control(
			'default_tab',
			array(
				'label'       => __( 'Default Active Tab', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Leave blank to use "All" (if shown) or the first gallery. Enter a gallery name to default to that tab.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// Load More
		$this->start_controls_section(
			'load_more_section',
			array(
				'label' => __( 'Load More', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'enable_load_more',
			array(
				'label'       => __( 'Enable Show More Button', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Show only a limited number of sketches at first, with a button to reveal the rest. Hidden sketches don\'t load their images until shown.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'initial_count',
			array(
				'label'       => __( 'Initial Count', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 6,
				'min'         => 1,
				'condition'   => array( 'enable_load_more' => 'yes' ),
				'description' => __( 'How many sketches to show before the button. In filtered galleries this applies to the active tab.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'load_more_text',
			array(
				'label'     => __( 'Button Text', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Show More', 'ux-portfolio-optimized' ),
				'condition' => array( 'enable_load_more' => 'yes' ),
			)
		);

		$this->add_control(
			'load_more_count',
			array(
				'label'       => __( 'Load More Count', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 6,
				'min'         => 1,
				'condition'   => array( 'enable_load_more' => 'yes' ),
				'description' => __( 'How many more sketches to reveal per click. Keep low for very large galleries; set high to reveal everything in one click.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// Style — Layout
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Layout', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_description_alignment();

		$this->add_control(
			'layout',
			array(
				'label'       => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'grid',
				'options'     => array(
					'grid'      => __( 'Grid', 'ux-portfolio-optimized' ),
					'justified' => __( 'Justified', 'ux-portfolio-optimized' ),
					'masonry'   => __( 'Masonry', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'Grid: equal cells. Masonry: packs varying heights into columns — best for mixed tall/short images. Justified: equal-height rows at true aspect ratio (no cropping); captions show on hover.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'condition'      => array( 'layout' => array( 'grid', 'masonry' ) ),
				'selectors'      => array(
					'{{WRAPPER}} .sketch-grid' => '--sketch-cols: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'     => __( 'Spacing', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 25 ),
				'selectors' => array( '{{WRAPPER}} .sketch-grid' => '--sketch-gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'row_height',
			array(
				'label'      => __( 'Row Height', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 120,
						'max' => 500,
					),
					'vh' => array(
						'min' => 15,
						'max' => 70,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 240,
				),
				'condition'  => array( 'layout' => 'justified' ),
				'selectors'  => array( '{{WRAPPER}} .sketch-grid' => '--sketch-row-h: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'justified_caption',
			array(
				'label'       => __( 'Caption Position', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'overlay',
				'options'     => array(
					'overlay' => __( 'Overlay on hover', 'ux-portfolio-optimized' ),
					'below'   => __( 'Below the image', 'ux-portfolio-optimized' ),
				),
				'condition'   => array( 'layout' => 'justified' ),
				'description' => __( 'Show the title/description over the image (on hover) or underneath it.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// Style — Card
		$this->start_controls_section(
			'card_style_section',
			array(
				'label' => __( 'Card', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array( '{{WRAPPER}} .sketch-item' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array( '{{WRAPPER}} .sketch-item' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .sketch-item',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Text Padding', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 20,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sketch-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Sketch Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sketch_title_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sketch_title_typography',
				'selector' => '{{WRAPPER}} .sketch-title',
			)
		);

		$this->add_control(
			'desc_heading',
			array(
				'label'     => __( 'Description', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sketch_desc_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-desc' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sketch_desc_typography',
				'selector' => '{{WRAPPER}} .sketch-desc',
			)
		);

		$this->end_controls_section();

		// Style — Filter Tabs
		$this->start_controls_section(
			'tabs_style_section',
			array(
				'label' => __( 'Filter Tabs', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'tabs_gap',
			array(
				'label'     => __( 'Gap Between Tabs', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array( '{{WRAPPER}} .sketch-tabs' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'tabs_margin_bottom',
			array(
				'label'     => __( 'Margin Below Tabs', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'   => array( 'size' => 30 ),
				'selectors' => array( '{{WRAPPER}} .sketch-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'tab_align',
			array(
				'label'     => __( 'Alignment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .sketch-tabs' => 'justify-content: {{VALUE}};' ),
			)
		);

		$this->start_controls_tabs( 'tab_style_tabs' );

		// Normal state
		$this->start_controls_tab( 'tab_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'tab_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-tab' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'tab_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-tab' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'tab_border_color',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-tab' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		// Active state
		$this->start_controls_tab( 'tab_active', array( 'label' => __( 'Active', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-tab.is-active' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'tab_active_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-tab.is-active' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'tab_active_border_color',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .sketch-tab.is-active' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_padding',
			array(
				'label'      => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 8,
					'right'    => 18,
					'bottom'   => 8,
					'left'     => 18,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sketch-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tab_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 999 ),
				'selectors' => array( '{{WRAPPER}} .sketch-tab' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .sketch-tab',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Slugify a gallery name for use as a data-attribute filter key.
	 */
	private function slugify( $text ) {
		$slug = strtolower( trim( $text ) );
		$slug = preg_replace( '/[^a-z0-9]+/', '-', $slug );
		$slug = trim( $slug, '-' );
		return $slug !== '' ? $slug : 'ungrouped';
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['sketches'] ) ) {
			return;
		}

		// Collect distinct gallery names in order of first appearance
		$galleries   = array();
		$has_grouped = false;
		foreach ( $s['sketches'] as $sketch ) {
			$g = trim( $sketch['gallery'] ?? '' );
			if ( $g !== '' ) {
				$has_grouped = true;
				$slug        = $this->slugify( $g );
				if ( ! isset( $galleries[ $slug ] ) ) {
					$galleries[ $slug ] = $g;
				}
			}
		}

		$show_all_tab = ( $s['show_all_tab'] ?? 'yes' ) === 'yes';
		$all_label    = $s['all_label'] ?? __( 'All', 'ux-portfolio-optimized' );
		$default_tab  = trim( $s['default_tab'] ?? '' );

		$enable_more     = ( $s['enable_load_more'] ?? '' ) === 'yes';
		$initial_count   = max( 1, absint( $s['initial_count'] ?? 6 ) );
		$load_more_text  = $s['load_more_text'] ?? __( 'Show More', 'ux-portfolio-optimized' );
		$load_more_count = max( 1, absint( $s['load_more_count'] ?? 6 ) );

		// Show tabs only when there's more than one gallery (or one gallery + All tab)
		$tab_count = count( $galleries ) + ( $show_all_tab && $has_grouped ? 1 : 0 );
		$show_tabs = $has_grouped && $tab_count > 1;

		// Determine default active tab
		$active_slug = '';
		if ( $show_tabs ) {
			if ( $default_tab !== '' ) {
				$maybe_slug = $this->slugify( $default_tab );
				if ( isset( $galleries[ $maybe_slug ] ) ) {
					$active_slug = $maybe_slug;
				} elseif ( $show_all_tab && ( strcasecmp( $default_tab, 'all' ) === 0 || strcasecmp( $default_tab, $all_label ) === 0 ) ) {
					$active_slug = '__all__';
				}
			}
			if ( $active_slug === '' ) {
				$active_slug = $show_all_tab ? '__all__' : array_key_first( $galleries );
			}
		}
		?>

		<div class="sketch-wrapper"
		<?php
		if ( $show_tabs ) :
			?>
			data-active-tab="<?php echo esc_attr( $active_slug ); ?>"<?php endif; ?>
			<?php
			if ( $enable_more ) :
				?>
			data-limit="<?php echo esc_attr( $initial_count ); ?>" data-batch="<?php echo esc_attr( $load_more_count ); ?>"<?php endif; ?>>
			<?php $this->render_standard_header(); ?>

			<?php if ( $show_tabs ) : ?>
				<div class="sketch-tabs" role="tablist">
					<?php if ( $show_all_tab ) : ?>
						<button type="button"
								class="sketch-tab<?php echo $active_slug === '__all__' ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $active_slug === '__all__' ? 'true' : 'false'; ?>"
								data-filter="__all__"><?php echo esc_html( $all_label ); ?></button>
					<?php endif; ?>
					<?php foreach ( $galleries as $slug => $label ) : ?>
						<button type="button"
								class="sketch-tab<?php echo $active_slug === $slug ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $active_slug === $slug ? 'true' : 'false'; ?>"
								data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="sketch-grid layout-<?php echo esc_attr( $s['layout'] ?? 'grid' ); ?><?php echo ( ( $s['layout'] ?? 'grid' ) === 'justified' ) ? ' caption-' . ( ( ( $s['justified_caption'] ?? 'overlay' ) === 'below' ) ? 'below' : 'overlay' ) : ''; ?>">
				<?php
				// Build per-gallery indices so each gallery's lightbox cycles only its own items
				$gallery_counters = array( '__all__' => 0 );
				foreach ( array_keys( $galleries ) as $slug ) {
					$gallery_counters[ $slug ] = 0;
				}
				$widget_id     = $this->get_id();
				$visible_count = 0;

				foreach ( $s['sketches'] as $sketch ) :
					$img_url = $sketch['image']['url'] ?? '';
					if ( empty( $img_url ) && ! empty( $sketch['image']['id'] ) ) {
						$img_url = wp_get_attachment_url( $sketch['image']['id'] );
					}
					if ( empty( $img_url ) ) {
						continue;
					}

					$img_w = 0;
					$img_h = 0;
					if ( ! empty( $sketch['image']['id'] ) ) {
						$meta = wp_get_attachment_metadata( $sketch['image']['id'] );
						if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
							$img_w = (int) $meta['width'];
							$img_h = (int) $meta['height'];
						}
					}

					$g_raw  = trim( $sketch['gallery'] ?? '' );
					$g_slug = $g_raw !== '' ? $this->slugify( $g_raw ) : '';
					$alt    = $sketch['title'] ?? '';

					// Determine visibility based on active tab
					$is_visible = true;
					if ( $show_tabs ) {
						if ( $active_slug === '__all__' ) {
							$is_visible = true;
						} else {
							$is_visible = ( $g_slug === $active_slug );
						}
					}

					// Server-side limit: hide items beyond the initial count for the active tab
					// so their (lazy) images don't load until revealed.
					$over_limit = false;
					if ( $enable_more && $is_visible ) {
						if ( $visible_count >= $initial_count ) {
							$over_limit = true;
						}
						++$visible_count;
					}

					// Lightbox slideshow id: per-gallery so prev/next stays within a tab.
					// "All" tab uses its own combined slideshow id.
					$all_index     = $gallery_counters['__all__']++;
					$gallery_index = null;
					if ( $g_slug !== '' && isset( $gallery_counters[ $g_slug ] ) ) {
						$gallery_index = $gallery_counters[ $g_slug ]++;
					}

					$slideshow_id   = $widget_id . '-all';
					$lightbox_index = $all_index;
					if ( $show_tabs && $active_slug !== '__all__' && $g_slug !== '' ) {
						$slideshow_id   = $widget_id . '-' . $g_slug;
						$lightbox_index = $gallery_index !== null ? $gallery_index : 0;
					}
					?>
					<div class="sketch-item<?php echo $is_visible ? '' : ' is-hidden'; ?><?php echo $over_limit ? ' is-over-limit' : ''; ?>"
						data-gallery="<?php echo esc_attr( $g_slug ); ?>"
						data-w="<?php echo esc_attr( $img_w ); ?>" data-h="<?php echo esc_attr( $img_h ); ?>">
						<a href="<?php echo esc_url( $img_url ); ?>"
							class="sketch-image"
							data-elementor-open-lightbox="yes"
							data-elementor-lightbox-slideshow="<?php echo esc_attr( $slideshow_id ); ?>"
							data-lightbox-all-id="<?php echo esc_attr( $widget_id . '-all' ); ?>"
							data-lightbox-gallery-id="<?php echo esc_attr( $g_slug !== '' ? $widget_id . '-' . $g_slug : '' ); ?>"
							data-lightbox-all-index="<?php echo esc_attr( $all_index ); ?>"
							data-lightbox-gallery-index="<?php echo esc_attr( $gallery_index !== null ? $gallery_index : '' ); ?>"
							data-elementor-lightbox-index="<?php echo esc_attr( $lightbox_index ); ?>"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %s: sketch title */ __( 'View sketch: %s', 'ux-portfolio-optimized' ), $alt ) ); ?>">
							<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
						</a>
						<?php if ( ! empty( $sketch['title'] ) || ! empty( $sketch['description'] ) ) : ?>
							<div class="sketch-content">
								<?php if ( ! empty( $sketch['title'] ) ) : ?>
									<h4 class="sketch-title"><?php echo esc_html( $sketch['title'] ); ?></h4>
								<?php endif; ?>
								<?php if ( ! empty( $sketch['description'] ) ) : ?>
									<p class="sketch-desc"><?php echo esc_html( $sketch['description'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $enable_more ) : ?>
				<div class="sketch-more-wrap"<?php echo ( $visible_count > $initial_count ) ? '' : ' style="display:none;"'; ?>>
					<button type="button" class="sketch-show-more"><?php echo esc_html( $load_more_text ); ?></button>
				</div>
			<?php endif; ?>
		</div>

		<?php
	}
}
