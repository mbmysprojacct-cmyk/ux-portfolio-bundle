<?php
/**
 * Empathy Map Widget
 *
 * Says / Thinks / Does / Feels quadrants around an editable persona in the
 * centre, with an optional Pains & Gains band underneath (the extended
 * Gamestorming version of the map).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Empathy_Map_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-empathy-map'; }
	public function get_title() {
		return __( 'Empathy Map', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-sitemap'; }
	public function get_keywords() {
		return array( 'empathy', 'map', 'hears', 'sees', 'says', 'does', 'thinks', 'feels', 'pains', 'gains', 'ux' );
	}

	/** Quadrants: key => [label, default icon, default color, default items] */
	private function quadrant_defs() {
		// Grid fills row-by-row, so this order reads clockwise from top-left:
		// Thinks & Feels (TL) -> Sees (TR) -> Says & Does (BR) -> Hears (BL).
		return array(
			'thinks_feels' => array( __( 'Thinks & Feels', 'ux-portfolio-optimized' ), 'fas fa-brain', '#8b5cf6', "Is this worth the money?\nWorried about wasting time" ),
			'sees'         => array( __( 'Sees', 'ux-portfolio-optimized' ), 'fas fa-eye', '#3b82f6', "Competitors advertising everywhere\nFriends using another app" ),
			'hears'        => array( __( 'Hears', 'ux-portfolio-optimized' ), 'fas fa-headphones', '#10b981', "Colleagues recommend a rival\nReviews are mixed" ),
			'says_does'    => array( __( 'Says & Does', 'ux-portfolio-optimized' ), 'fas fa-comments', '#ef4a8a', "\"I need it to be fast\"\nCompares options before buying" ),
		);
	}

	/** Optional bottom band. */
	private function band_defs() {
		return array(
			'pains' => array( __( 'Pains', 'ux-portfolio-optimized' ), 'fas fa-bolt', '#ef4444', "Too many steps to check out\nUnclear pricing" ),
			'gains' => array( __( 'Gains', 'ux-portfolio-optimized' ), 'fas fa-trophy', '#d4a017', "Saves time\nFeels in control" ),
		);
	}

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Maps (single vs multiple)
		$this->start_controls_section(
			'maps_section',
			array(
				'label' => __( 'Empathy Maps', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'mode',
			array(
				'label'       => __( 'Mode', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'single',
				'options'     => array(
					'single'   => __( 'Single map', 'ux-portfolio-optimized' ),
					'multiple' => __( 'Multiple maps (tabbed)', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'Multiple lets you add one map per persona, switchable by tabs. Section titles, icons and colors stay shared, so every map looks consistent.', 'ux-portfolio-optimized' ),
			)
		);

		$map_repeater = new \Elementor\Repeater();

		$map_repeater->add_control(
			'map_name',
			array(
				'label'       => __( 'Tab Label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Persona', 'ux-portfolio-optimized' ),
				'label_block' => true,
			)
		);

		$map_repeater->add_control(
			'map_user_name',
			array(
				'label'       => __( 'Centre Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Shown in the middle circle. Leave blank to reuse the Tab Label.', 'ux-portfolio-optimized' ),
			)
		);

		$map_repeater->add_control(
			'map_user_image',
			array(
				'label'       => __( 'Centre Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'Optional persona photo for this map.', 'ux-portfolio-optimized' ),
			)
		);

		foreach ( $this->quadrant_defs() as $key => $def ) {
			$map_repeater->add_control(
				'map_' . $key,
				array(
					'label'       => $def[0],
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'rows'        => 4,
					'placeholder' => __( 'One point per line', 'ux-portfolio-optimized' ),
				)
			);
		}
		foreach ( $this->band_defs() as $key => $def ) {
			$map_repeater->add_control(
				'map_' . $key,
				array(
					'label'       => $def[0],
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'rows'        => 4,
					'placeholder' => __( 'One point per line', 'ux-portfolio-optimized' ),
				)
			);
		}

		$this->add_control(
			'maps',
			array(
				'label'       => __( 'Maps', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $map_repeater->get_controls(),
				'default'     => array(
					array( 'map_name' => __( 'Persona 1', 'ux-portfolio-optimized' ) ),
				),
				'title_field' => '{{{ map_name }}}',
				'condition'   => array( 'mode' => 'multiple' ),
			)
		);

		$this->add_control(
			'show_tabs',
			array(
				'label'       => __( 'Show Tabs', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array( 'mode' => 'multiple' ),
				'description' => __( 'Turn off to stack every map down the page instead of tabbing between them.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'default_tab',
			array(
				'label'       => __( 'Default Tab', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					'mode'      => 'multiple',
					'show_tabs' => 'yes',
				),
				'description' => __( 'Name of the map to open first. Leave blank for the first one.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// CONTENT — Centre (the persona)
		$this->start_controls_section(
			'center_section',
			array(
				'label' => __( 'Centre', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_center',
			array(
				'label'   => __( 'Show Centre', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'user_name',
			array(
				'label'       => __( 'Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'User', 'ux-portfolio-optimized' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'show_center' => 'yes',
					'mode'        => 'single',
				),
				'description' => __( 'Who this map is about — a persona name or segment.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'user_icon',
			array(
				'label'     => __( 'Icon', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-user',
					'library' => 'fa-solid',
				),
				'condition' => array( 'show_center' => 'yes' ),
			)
		);

		$this->add_control(
			'user_image',
			array(
				'label'       => __( 'Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'condition'   => array(
					'show_center' => 'yes',
					'mode'        => 'single',
				),
				'description' => __( 'Optional persona photo — replaces the icon if set.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// CONTENT — one section per quadrant
		foreach ( $this->quadrant_defs() as $key => $def ) {
			$this->start_controls_section(
				'section_' . $key,
				array(
					'label' => $def[0],
				)
			);

			$this->add_control(
				$key . '_title',
				array(
					'label'   => __( 'Title', 'ux-portfolio-optimized' ),
					'type'    => \Elementor\Controls_Manager::TEXT,
					'default' => $def[0],
				)
			);

			$this->add_control(
				$key . '_icon',
				array(
					'label'   => __( 'Icon', 'ux-portfolio-optimized' ),
					'type'    => \Elementor\Controls_Manager::ICONS,
					'default' => array(
						'value'   => $def[1],
						'library' => 'fa-solid',
					),
				)
			);

			$this->add_control(
				$key . '_items',
				array(
					'label'       => __( 'Points', 'ux-portfolio-optimized' ),
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'rows'        => 5,
					'default'     => $def[3],
					'description' => __( 'One point per line.', 'ux-portfolio-optimized' ),
					'condition'   => array( 'mode' => 'single' ),
				)
			);

			$this->add_control(
				$key . '_color',
				array(
					'label'   => __( 'Accent Color', 'ux-portfolio-optimized' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => $def[2],
				)
			);

			$this->end_controls_section();
		}

		// CONTENT — Pains & Gains
		$this->start_controls_section(
			'band_section',
			array(
				'label' => __( 'Pains & Gains', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_band',
			array(
				'label'       => __( 'Show Pains & Gains', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Adds a band below the quadrants — the extended empathy map.', 'ux-portfolio-optimized' ),
			)
		);

		foreach ( $this->band_defs() as $key => $def ) {
			$this->add_control(
				$key . '_heading',
				array(
					'label'     => $def[0],
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array( 'show_band' => 'yes' ),
				)
			);

			$this->add_control(
				$key . '_title',
				array(
					'label'     => __( 'Title', 'ux-portfolio-optimized' ),
					'type'      => \Elementor\Controls_Manager::TEXT,
					'default'   => $def[0],
					'condition' => array( 'show_band' => 'yes' ),
				)
			);

			$this->add_control(
				$key . '_icon',
				array(
					'label'     => __( 'Icon', 'ux-portfolio-optimized' ),
					'type'      => \Elementor\Controls_Manager::ICONS,
					'default'   => array(
						'value'   => $def[1],
						'library' => 'fa-solid',
					),
					'condition' => array( 'show_band' => 'yes' ),
				)
			);

			$this->add_control(
				$key . '_items',
				array(
					'label'       => __( 'Points', 'ux-portfolio-optimized' ),
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'rows'        => 5,
					'default'     => $def[3],
					'description' => __( 'One point per line.', 'ux-portfolio-optimized' ),
					'condition'   => array(
						'show_band' => 'yes',
						'mode'      => 'single',
					),
				)
			);

			$this->add_control(
				$key . '_color',
				array(
					'label'     => __( 'Accent Color', 'ux-portfolio-optimized' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => $def[2],
					'condition' => array( 'show_band' => 'yes' ),
				)
			);
		}

		$this->end_controls_section();

		// STYLE — Cards
		$this->start_controls_section(
			'style_cards',
			array(
				'label' => __( 'Cards', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_description_alignment();

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'Card Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8f9fa',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .empathy-quadrant' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border',
			array(
				'label'     => __( 'Card Border', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e0e0e0',
				'selectors' => array(
					'{{WRAPPER}} .empathy-quadrant' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'     => __( 'Corner Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .empathy-quadrant' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 20 ),
				'selectors' => array(
					'{{WRAPPER}} .empathy-map' => '--em-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_bg',
			array(
				'label'     => __( 'Point Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .quadrant-items li' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// STYLE — Centre
		$this->start_controls_section(
			'style_center',
			array(
				'label'     => __( 'Centre', 'ux-portfolio-optimized' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_center' => 'yes' ),
			)
		);

		$this->add_control(
			'center_position',
			array(
				'label'       => __( 'Position', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'overlap',
				'options'     => array(
					'overlap' => __( 'Over the grid (classic)', 'ux-portfolio-optimized' ),
					'above'   => __( 'Above the grid', 'ux-portfolio-optimized' ),
					'below'   => __( 'Below the grid', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'Over the grid floats the circle at the intersection. Above/Below give it its own row so it never covers the text.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'center_offset_x',
			array(
				'label'     => __( 'Nudge Horizontally', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -300,
						'max' => 300,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 0,
				),
				'condition' => array( 'center_position' => 'overlap' ),
				'selectors' => array(
					'{{WRAPPER}} .empathy-map' => '--em-offset-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'center_offset_y',
			array(
				'label'     => __( 'Nudge Vertically', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -300,
						'max' => 300,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 0,
				),
				'condition' => array( 'center_position' => 'overlap' ),
				'selectors' => array(
					'{{WRAPPER}} .empathy-map' => '--em-offset-y: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'center_size',
			array(
				'label'       => __( 'Circle Size', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 70,
						'max' => 400,
					),
				),
				'default'     => array( 'size' => 120 ),
				'selectors'   => array(
					'{{WRAPPER}} .empathy-map' => '--em-center-size: {{SIZE}}{{UNIT}};',
				),
				'description' => __( 'The circle itself — the image scales with it.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'center_image_heading',
			array(
				'label'     => __( 'Image', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'center_image_fit',
			array(
				'label'     => __( 'Image Fit', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => __( 'Fill circle (crops edges)', 'ux-portfolio-optimized' ),
					'contain' => __( 'Fit whole image (no crop)', 'ux-portfolio-optimized' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .center-photo' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'center_image_zoom',
			array(
				'label'       => __( 'Image Zoom', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array(
					'%' => array(
						'min' => 50,
						'max' => 300,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'   => array(
					'{{WRAPPER}} .empathy-map' => '--em-img-zoom: calc({{SIZE}} / 100);',
				),
				'description' => __( 'Scales the photo inside the circle without changing the circle.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'center_image_x',
			array(
				'label'       => __( 'Move Image — Horizontal', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} .empathy-map' => '--em-img-x: {{SIZE}}px;',
				),
				'description' => __( 'Slide the photo left/right inside the circle.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'center_image_y',
			array(
				'label'       => __( 'Move Image — Vertical', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} .empathy-map' => '--em-img-y: {{SIZE}}px;',
				),
				'description' => __( 'Slide the photo up/down inside the circle — e.g. to keep a face in frame.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'center_circle_heading',
			array(
				'label'     => __( 'Circle', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'center_border_color',
			array(
				'label'     => __( 'Ring Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY ),
				'default'   => '#3b82f6',
				'selectors' => array(
					'{{WRAPPER}} .empathy-center' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'center_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .empathy-center' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'center_text_color',
			array(
				'label'     => __( 'Name Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#1a1a1a',
				'selectors' => array(
					'{{WRAPPER}} .center-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .center-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'center_typography', __( 'Name Typography', 'ux-portfolio-optimized' ), '.center-text' );

		$this->end_controls_section();

		// STYLE — Text
		$this->start_controls_section(
			'style_text',
			array(
				'label' => __( 'Text', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'       => __( 'Title Color', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Leave empty to use each section accent color.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .empathy-map' => '--em-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'title_typography', __( 'Title Typography', 'ux-portfolio-optimized' ), '.quadrant-title' );

		$this->add_control(
			'item_color',
			array(
				'label'     => __( 'Point Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#374151',
				'selectors' => array(
					'{{WRAPPER}} .quadrant-items li' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'item_typography', __( 'Point Typography', 'ux-portfolio-optimized' ), '.quadrant-items li' );

		$this->end_controls_section();
	}

	/** Renders one card (quadrant or band item). */
	private function render_card( $s, $key, $def, $raw ) {
		$title = trim( $s[ $key . '_title' ] ?? $def[0] );
		$color = trim( $s[ $key . '_color' ] ?? '' );
		if ( $color === '' ) {
			$color = $def[2];
		}

		$items = array_filter(
			array_map( 'trim', explode( "\n", (string) $raw ) ),
			function ( $i ) {
				return $i !== '';
			}
		);

		$icon     = $s[ $key . '_icon' ] ?? null;
		$has_icon = is_array( $icon ) && ! empty( $icon['value'] );
		?>
		<div class="empathy-quadrant quadrant-<?php echo esc_attr( $key ); ?>" style="--em-color: <?php echo ux_portfolio_esc_css( $color ); ?>;">
			<?php if ( $title !== '' || $has_icon ) : ?>
				<h3 class="quadrant-title">
					<?php if ( $has_icon ) : ?>
						<span class="quad-icon" aria-hidden="true">
						<?php
							\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
						?>
						</span>
					<?php endif; ?>
					<?php echo esc_html( $title ); ?>
				</h3>
			<?php endif; ?>

			<?php if ( ! empty( $items ) ) : ?>
				<ul class="quadrant-items">
					<?php foreach ( $items as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	/** Renders one complete map (centre + quadrants + band). */
	private function render_map( $s, $set, $show_center, $show_band ) {
		$quadrants = $this->quadrant_defs();
		$bands     = $this->band_defs();

		$user_icon     = $s['user_icon'] ?? null;
		$has_user_icon = is_array( $user_icon ) && ! empty( $user_icon['value'] );

		$pos = $s['center_position'] ?? 'overlap';
		if ( ! in_array( $pos, array( 'overlap', 'above', 'below' ), true ) ) {
			$pos = 'overlap';
		}

		$map_class = 'empathy-map';
		if ( $show_center ) {
			$map_class .= ' has-center center-' . $pos;
		}
		?>
		<div class="<?php echo esc_attr( $map_class ); ?>">
			<div class="empathy-quadrants">
				<?php if ( $show_center ) : ?>
					<div class="empathy-center<?php echo ! empty( $set['image'] ) ? ' has-photo' : ''; ?>">
						<?php if ( ! empty( $set['image'] ) ) : ?>
							<img class="center-photo" src="<?php echo esc_url( $set['image'] ); ?>" alt="<?php echo esc_attr( $set['user'] ); ?>" loading="lazy">
						<?php elseif ( $has_user_icon ) : ?>
							<span class="center-icon" aria-hidden="true">
							<?php
								\Elementor\Icons_Manager::render_icon( $user_icon, array( 'aria-hidden' => 'true' ) );
							?>
							</span>
						<?php endif; ?>
						<?php if ( $set['user'] !== '' ) : ?>
							<span class="center-text"><?php echo esc_html( $set['user'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php foreach ( $quadrants as $key => $def ) : ?>
					<?php $this->render_card( $s, $key, $def, $set[ $key ] ); ?>
				<?php endforeach; ?>
			</div>

			<?php if ( $show_band ) : ?>
				<div class="empathy-band">
					<?php foreach ( $bands as $key => $def ) : ?>
						<?php $this->render_card( $s, $key, $def, $set[ $key ] ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$s         = $this->get_settings_for_display();
		$quadrants = $this->quadrant_defs();
		$bands     = $this->band_defs();
		$all_keys  = array_merge( array_keys( $quadrants ), array_keys( $bands ) );

		$show_center = ( $s['show_center'] ?? 'yes' ) === 'yes';
		$show_band   = ( $s['show_band'] ?? 'yes' ) === 'yes';
		$mode        = ( $s['mode'] ?? 'single' ) === 'multiple' ? 'multiple' : 'single';

		// Build the list of maps.
		$sets = array();
		if ( $mode === 'multiple' && ! empty( $s['maps'] ) && is_array( $s['maps'] ) ) {
			foreach ( $s['maps'] as $m ) {
				$label = trim( $m['map_name'] ?? '' );
				$user  = trim( $m['map_user_name'] ?? '' );
				if ( $user === '' ) {
					$user = $label;
				}

				$img = $m['map_user_image']['url'] ?? '';
				if ( empty( $img ) && ! empty( $m['map_user_image']['id'] ) ) {
					$img = wp_get_attachment_url( $m['map_user_image']['id'] );
				}

				$set = array(
					'label' => $label,
					'user'  => $user,
					'image' => $img,
				);
				foreach ( $all_keys as $key ) {
					$set[ $key ] = $m[ 'map_' . $key ] ?? '';
				}
				$sets[] = $set;
			}
		}
		if ( empty( $sets ) ) {
			$img = $s['user_image']['url'] ?? '';
			if ( empty( $img ) && ! empty( $s['user_image']['id'] ) ) {
				$img = wp_get_attachment_url( $s['user_image']['id'] );
			}
			$set = array(
				'label' => '',
				'user'  => trim( $s['user_name'] ?? '' ),
				'image' => $img,
			);
			foreach ( $all_keys as $key ) {
				$set[ $key ] = $s[ $key . '_items' ] ?? '';
			}
			$sets[] = $set;
		}

		$tabbed = ( $mode === 'multiple' ) && ( ( $s['show_tabs'] ?? 'yes' ) === 'yes' ) && count( $sets ) > 1;

		// Which tab opens first.
		$active      = 0;
		$default_tab = trim( $s['default_tab'] ?? '' );
		if ( $tabbed && $default_tab !== '' ) {
			foreach ( $sets as $i => $set ) {
				if ( strcasecmp( $set['label'], $default_tab ) === 0 ) {
					$active = $i;
					break;
				}
			}
		}
		?>

		<div class="empathy-map-wrapper" data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php if ( $tabbed ) : ?>
				<div class="empathy-tabs" role="tablist">
					<?php
					foreach ( $sets as $i => $set ) :
						$is_on = ( $i === $active );
						$label = $set['label'] !== '' ? $set['label'] : sprintf( /* translators: %d: map number */ __( 'Map %d', 'ux-portfolio-optimized' ), $i + 1 );
						?>
						<button type="button"
								class="empathy-tab<?php echo $is_on ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $is_on ? 'true' : 'false'; ?>"
								data-empathy-tab="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $sets as $i => $set ) :
				$hidden = $tabbed && ( $i !== $active );
				?>
				<div class="empathy-panel<?php echo $hidden ? ' is-hidden' : ''; ?>" data-empathy-panel="<?php echo esc_attr( $i ); ?>">
					<?php if ( ! $tabbed && $mode === 'multiple' && $set['label'] !== '' ) : ?>
						<h3 class="empathy-panel__name"><?php echo esc_html( $set['label'] ); ?></h3>
					<?php endif; ?>
					<?php $this->render_map( $s, $set, $show_center, $show_band ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}
}
