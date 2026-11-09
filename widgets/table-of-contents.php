<?php
/**
 * Table of Contents Widget
 * Auto-generates or manually-built TOC with collapsible/sticky behavior
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Table_Of_Contents_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-table-of-contents'; }
	public function get_title() {
		return __( 'Table of Contents', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-table-of-contents'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Source section
		$this->start_controls_section(
			'source_section',
			array(
				'label' => __( 'Source', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'source_mode',
			array(
				'label'   => __( 'Source', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'auto'   => __( 'Auto-detect from headings', 'ux-portfolio-optimized' ),
					'manual' => __( 'Manual list', 'ux-portfolio-optimized' ),
				),
				'default' => 'auto',
			)
		);

		$this->add_control(
			'scope',
			array(
				'label'       => __( 'Look for Headings In', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => array(
					'selector' => __( 'Content selector', 'ux-portfolio-optimized' ),
					'tabpanel' => __( 'Same tab as this TOC', 'ux-portfolio-optimized' ),
					'page'     => __( 'Whole page', 'ux-portfolio-optimized' ),
				),
				'default'     => 'selector',
				'description' => __( '"Same tab" only reads headings inside the tab panel this TOC sits in.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'source_mode' => 'auto' ),
			)
		);

		$this->add_control(
			'content_selector',
			array(
				'label'       => __( 'Content Selector', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'article, .elementor-widget-theme-post-content, .entry-content',
				'description' => __( 'CSS selector(s) where headings should be detected. Comma-separated. The TOC searches all matching containers.', 'ux-portfolio-optimized' ),
				'condition'   => array(
					'source_mode' => 'auto',
					'scope'       => 'selector',
				),
			)
		);

		$this->add_control(
			'heading_levels',
			array(
				'label'     => __( 'Heading Levels', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'default'   => array( 'h2', 'h3' ),
				'condition' => array( 'source_mode' => 'auto' ),
			)
		);

		$this->add_control(
			'ux_headings',
			array(
				'label'       => __( 'Headings Inside UX Widgets', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => array(
					'all'    => __( 'Include all', 'ux-portfolio-optimized' ),
					'titles' => __( 'Widget titles only (nested)', 'ux-portfolio-optimized' ),
					'skip'   => __( 'Skip', 'ux-portfolio-optimized' ),
				),
				'default'     => 'all',
				'description' => __( '"Widget titles only" lists each UX widget by its main title, indented under the section heading above it.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'source_mode' => 'auto' ),
			)
		);

		$this->add_control(
			'exclude_selector',
			array(
				'label'       => __( 'Exclude Selector', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '.no-toc',
				'description' => __( 'Headings matching or inside this selector are left out. Add the class no-toc to any heading or container to hide it.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'source_mode' => 'auto' ),
			)
		);

		$label_repeater = new \Elementor\Repeater();

		$label_repeater->add_control(
			'match_text',
			array(
				'label'       => __( 'Heading Text', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'The heading as it appears on the page (case and spacing don\'t matter).', 'ux-portfolio-optimized' ),
			)
		);

		$label_repeater->add_control(
			'toc_label',
			array(
				'label'       => __( 'TOC Label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'label_overrides',
			array(
				'label'         => __( 'Label Overrides', 'ux-portfolio-optimized' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $label_repeater->get_controls(),
				'default'       => array(),
				'prevent_empty' => false,
				'title_field'   => '{{{ toc_label }}}',
				'condition'     => array( 'source_mode' => 'auto' ),
			)
		);
		// Manual repeater
		$manual_repeater = new \Elementor\Repeater();

		$manual_repeater->add_control(
			'item_label',
			array(
				'label'   => __( 'Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Section',
			)
		);

		$manual_repeater->add_control(
			'item_anchor',
			array(
				'label'       => __( 'Anchor ID', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section',
				'description' => __( 'The id attribute of the target element (without #).', 'ux-portfolio-optimized' ),
			)
		);

		$manual_repeater->add_control(
			'item_level',
			array(
				'label'   => __( 'Indent Level', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 5,
			)
		);

		$this->add_control(
			'manual_items',
			array(
				'label'       => __( 'TOC Items', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $manual_repeater->get_controls(),
				'default'     => array(
					array(
						'item_label'  => 'Overview',
						'item_anchor' => 'overview',
						'item_level'  => 1,
					),
					array(
						'item_label'  => 'Process',
						'item_anchor' => 'process',
						'item_level'  => 1,
					),
					array(
						'item_label'  => 'Outcomes',
						'item_anchor' => 'outcomes',
						'item_level'  => 1,
					),
				),
				'title_field' => '{{{ item_label }}}',
				'condition'   => array( 'source_mode' => 'manual' ),
			)
		);

		$this->end_controls_section();

		// Behavior section
		$this->start_controls_section(
			'behavior_section',
			array(
				'label' => __( 'Behavior', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'toc_title',
			array(
				'label'   => __( 'TOC Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Contents',
			)
		);

		$this->add_control(
			'scroll_offset',
			array(
				'label'       => __( 'Scroll Offset', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 80,
				'description' => __( 'Pixel offset above the heading when scrolling to it (accounts for fixed headers).', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'highlight_active',
			array(
				'label'   => __( 'Highlight Current Section', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'toc_sticky',
			array(
				'label'       => __( 'Sticky', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Stays in view while its column scrolls. The parent column must be taller than the TOC and not use overflow: hidden.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}}' => 'position: sticky; align-self: flex-start; z-index: 2;',
				),
			)
		);

		$this->add_responsive_control(
			'sticky_top',
			array(
				'label'     => __( 'Sticky Top Offset', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'default'   => array(
					'size' => 24,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'toc_sticky' => 'yes' ),
			)
		);

		$this->add_control(
			'collapse_on',
			array(
				'label'        => __( 'Collapse Into Dropdown', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => array(
					'none'   => __( 'Never', 'ux-portfolio-optimized' ),
					'tablet' => __( 'Tablet & mobile', 'ux-portfolio-optimized' ),
					'mobile' => __( 'Mobile only', 'ux-portfolio-optimized' ),
				),
				'default'      => 'none',
				'prefix_class' => 'ux-toc--collapse-',
				'description'  => __( 'Below this size the list hides behind a "Contents" toggle, and Sticky turns off.', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_responsive_control(
			'max_height',
			array(
				'label'          => __( 'Max Height', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array(
						'min' => 100,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 20,
						'max' => 95,
					),
				),
				'size_units'     => array( 'px', 'vh' ),
				'default'        => array(
					'size' => 600,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 500,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 400,
					'unit' => 'px',
				),
				'description'    => __( 'When the list exceeds this height it scrolls within the TOC. Set to 0 for no limit.', 'ux-portfolio-optimized' ),
				'selectors'      => array(
					'{{WRAPPER}} .ux-toc__list' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				),
			)
		);

		$this->add_control(
			'scrollbar_style',
			array(
				'label'        => __( 'Scrollbar Style', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => array(
					'default' => __( 'Default', 'ux-portfolio-optimized' ),
					'thin'    => __( 'Thin', 'ux-portfolio-optimized' ),
					'hidden'  => __( 'Auto-hide', 'ux-portfolio-optimized' ),
				),
				'default'      => 'thin',
				'prefix_class' => 'ux-toc--scrollbar-',
			)
		);

		$this->end_controls_section();

		// Drawer section
		// Style section
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'container_heading',
			array(
				'label' => __( 'Container', 'ux-portfolio-optimized' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'container_padding',
			array(
				'label'     => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => 16,
					'right'    => 20,
					'bottom'   => 16,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .ux-toc',
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc__title, {{WRAPPER}} .ux-toc__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ux-toc__title, {{WRAPPER}} .ux-toc__toggle',
			)
		);

		$this->add_control(
			'items_heading',
			array(
				'label'     => __( 'Items', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'items_gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-toc__list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_indent',
			array(
				'label'     => __( 'Nested Indent', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 16 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => '--ux-toc-indent: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'items_typography',
				'selector' => '{{WRAPPER}} .ux-toc__link',
			)
		);

		// Bullet style controls
		$this->add_control(
			'bullet_style',
			array(
				'label'        => __( 'Bullet Style', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => array(
					'none'    => __( 'None', 'ux-portfolio-optimized' ),
					'dot'     => __( 'Dot (filled)', 'ux-portfolio-optimized' ),
					'outline' => __( 'Outline circle', 'ux-portfolio-optimized' ),
					'square'  => __( 'Square', 'ux-portfolio-optimized' ),
					'line'    => __( 'Vertical line', 'ux-portfolio-optimized' ),
					'number'  => __( 'Number', 'ux-portfolio-optimized' ),
				),
				'default'      => 'none',
				'prefix_class' => 'ux-toc--bullet-',
			)
		);

		$this->add_control(
			'bullet_size',
			array(
				'label'     => __( 'Bullet Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 4,
						'max' => 24,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => '--ux-toc-bullet-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'bullet_style!' => 'none' ),
			)
		);

		$this->add_control(
			'bullet_gap',
			array(
				'label'     => __( 'Bullet Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 4,
						'max' => 30,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => '--ux-toc-bullet-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'bullet_style!' => 'none' ),
			)
		);

		$this->start_controls_tabs( 'tabs_item_states' );

		// NORMAL
		$this->start_controls_tab( 'tab_item_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'item_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc .ux-toc__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_color',
			array(
				'label'     => __( 'Bullet Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc' => '--ux-toc-bullet-color: {{VALUE}};',
				),
				'condition' => array( 'bullet_style!' => 'none' ),
			)
		);

		$this->end_controls_tab();

		// HOVER
		$this->start_controls_tab( 'tab_item_hover', array( 'label' => __( 'Hover', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'item_color_hover',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc .ux-toc__link:hover,
                 {{WRAPPER}} .ux-toc .ux-toc__link:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_color_hover',
			array(
				'label'     => __( 'Bullet Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc .ux-toc__item:hover' => '--ux-toc-bullet-color: {{VALUE}};',
				),
				'condition' => array( 'bullet_style!' => 'none' ),
			)
		);

		$this->end_controls_tab();

		// ACTIVE (current section while scrolling)
		$this->start_controls_tab( 'tab_item_active', array( 'label' => __( 'Active', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'item_color_active',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc .ux-toc__item.is-active > .ux-toc__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_color_active',
			array(
				'label'     => __( 'Bullet Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-toc .ux-toc__item.is-active' => '--ux-toc-bullet-color: {{VALUE}};',
				),
				'condition' => array( 'bullet_style!' => 'none' ),
			)
		);

		$this->add_control(
			'item_weight_active',
			array(
				'label'     => __( 'Font Weight', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					''    => __( 'Default', 'ux-portfolio-optimized' ),
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
				),
				'default'   => '600',
				'selectors' => array(
					'{{WRAPPER}} .ux-toc .ux-toc__item.is-active > .ux-toc__link' => 'font-weight: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$mode          = $s['source_mode'] ?? 'auto';
		$title         = $s['toc_title'] ?? '';
		$scroll_offset = intval( $s['scroll_offset'] ?? 80 );

		$levels           = ! empty( $s['heading_levels'] ) ? implode( ',', $s['heading_levels'] ) : 'h2,h3';
		$content_selector = $s['content_selector'] ?? '.elementor-widget-theme-post-content, .entry-content, article';
		$scope            = $s['scope'] ?? 'selector';
		if ( ! in_array( $scope, array( 'selector', 'tabpanel', 'page' ), true ) ) {
			$scope = 'selector';
		}
		$ux_headings = $s['ux_headings'] ?? 'all';
		if ( ! in_array( $ux_headings, array( 'all', 'titles', 'skip' ), true ) ) {
			$ux_headings = 'all';
		}
		$exclude   = sanitize_text_field( $s['exclude_selector'] ?? '' );
		$label_map = array();
		if ( ! empty( $s['label_overrides'] ) && is_array( $s['label_overrides'] ) ) {
			foreach ( $s['label_overrides'] as $row ) {
				$match = sanitize_text_field( $row['match_text'] ?? '' );
				$lbl   = sanitize_text_field( $row['toc_label'] ?? '' );
				if ( $match !== '' && $lbl !== '' ) {
					$label_map[ $match ] = $lbl;
				}
			}
		}
		$highlight = ( ( $s['highlight_active'] ?? 'yes' ) === 'yes' ) ? 'yes' : 'no';
		$list_id   = 'ux-toc-list-' . $this->get_id();        ?>

		<div class="ux-toc-wrapper">
			<?php $this->render_standard_header(); ?>

			<nav class="ux-toc"
				aria-label="<?php echo esc_attr( $title ?: __( 'Table of contents', 'ux-portfolio-optimized' ) ); ?>"
				data-source-mode="<?php echo esc_attr( $mode ); ?>"
				data-content-selector="<?php echo esc_attr( $content_selector ); ?>"
				data-heading-levels="<?php echo esc_attr( $levels ); ?>"
				data-scroll-offset="<?php echo esc_attr( $scroll_offset ); ?>"
				data-scope="<?php echo esc_attr( $scope ); ?>"
				data-ux-headings="<?php echo esc_attr( $ux_headings ); ?>"
				data-exclude="<?php echo esc_attr( $exclude ); ?>"
				data-highlight="<?php echo esc_attr( $highlight ); ?>"
				data-label-map="<?php echo esc_attr( wp_json_encode( (object) $label_map ) ); ?>">

				<?php if ( $title ) : ?>
					<h3 class="ux-toc__title"><?php echo esc_html( $title ); ?></h3>
				<?php endif; ?>

				<button type="button" class="ux-toc__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $list_id ); ?>">
					<span class="ux-toc__toggle-label"><?php echo esc_html( $title ?: __( 'Contents', 'ux-portfolio-optimized' ) ); ?></span>
					<span class="ux-toc__chevron" aria-hidden="true"></span>
				</button>

				<ul class="ux-toc__list" id="<?php echo esc_attr( $list_id ); ?>" role="list">
					<?php if ( $mode === 'manual' && ! empty( $s['manual_items'] ) ) : ?>
						<?php
						foreach ( $s['manual_items'] as $item ) :
							$label  = $item['item_label'] ?? '';
							$anchor = $item['item_anchor'] ?? '';
							$level  = max( 1, intval( $item['item_level'] ?? 1 ) );
							?>
							<li class="ux-toc__item ux-toc__item--level-<?php echo esc_attr( $level ); ?>">
								<a href="#<?php echo esc_attr( sanitize_title( $anchor ) ); ?>"
									class="ux-toc__link"
									data-anchor="<?php echo esc_attr( sanitize_title( $anchor ) ); ?>">
									<?php echo esc_html( $label ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php // For auto mode, JS will populate the list ?>
				</ul>
			</nav>
		</div>

		<?php
	}
}
