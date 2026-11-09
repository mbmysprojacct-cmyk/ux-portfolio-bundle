<?php
/**
 * Remote Navigation Widget - Fixed click handler & global color/font support
 * Bullets that remotely control Accordions, Tabs, Toggles
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Remote_Navigation_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-remote-navigation'; }
	public function get_title() {
		return __( 'Remote Navigation', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-navigation-horizontal'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Target
		$this->start_controls_section(
			'target_section',
			array(
				'label' => __( 'Target', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'target_widget_type',
			array(
				'label'   => __( 'Target Widget Type', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'accordion' => __( 'Accordion', 'ux-portfolio-optimized' ),
					'tabs'      => __( 'Tabs', 'ux-portfolio-optimized' ),
					'toggle'    => __( 'Toggle', 'ux-portfolio-optimized' ),
					'custom'    => __( 'Custom Selector', 'ux-portfolio-optimized' ),
				),
				'default' => 'accordion',
			)
		);

		$this->add_control(
			'target_css_id',
			array(
				'label'       => __( 'Target Widget CSS ID', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'my-target',
				'description' => __( 'Set this ID on the target widget under Advanced → CSS ID (without the #).', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'custom_selector',
			array(
				'label'       => __( 'Custom Item Selector', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '.my-item',
				'description' => __( 'CSS selector for clickable items', 'ux-portfolio-optimized' ),
				'condition'   => array( 'target_widget_type' => 'custom' ),
			)
		);

		$this->end_controls_section();

		// Bullets
		$this->start_controls_section(
			'bullets_section',
			array(
				'label' => __( 'Bullets', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'   => __( 'Orientation', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'row'    => __( 'Horizontal', 'ux-portfolio-optimized' ),
					'column' => __( 'Vertical', 'ux-portfolio-optimized' ),
				),
				'default' => 'row',
			)
		);

		$this->add_control(
			'label_display',
			array(
				'label'       => __( 'Label Display', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'always',
				'options'     => array(
					'always' => __( 'Always visible', 'ux-portfolio-optimized' ),
					'hover'  => __( 'Show on hover (tooltip)', 'ux-portfolio-optimized' ),
					'hidden' => __( 'Hidden (icon only)', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'Hover shows an icon-only nav that reveals each label as a tooltip when you point at it — so it stays compact but still readable.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'enable_superscript',
			array(
				'label'   => __( 'Enable Superscript', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'scroll_to_target',
			array(
				'label'       => __( 'Scroll to Target on Click', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'When enabled, clicking a bullet also scrolls the target widget into view.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'scroll_offset',
			array(
				'label'       => __( 'Scroll Offset', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 80,
				'description' => __( 'Pixel offset above the target when scrolling (accounts for fixed headers).', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'bullet_label',
			array(
				'label'   => __( 'Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Item',
			)
		);

		$repeater->add_control(
			'bullet_index',
			array(
				'label'       => __( 'Target Index', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 1,
				'description' => __( 'Which accordion/tab item to open (1, 2, 3...)', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'bullet_icon',
			array(
				'label'       => __( 'Icon', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'description' => __( 'Font icon or SVG.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'bullet_image',
			array(
				'label'       => __( 'Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'Use a PNG, JPG or SVG instead of an icon. If set, this replaces the Icon above.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'bullet_superscript',
			array(
				'label'   => __( 'Superscript', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'bullets',
			array(
				'label'       => __( 'Navigation Items', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'bullet_label' => 'Item 1',
						'bullet_index' => 1,
					),
					array(
						'bullet_label' => 'Item 2',
						'bullet_index' => 2,
					),
					array(
						'bullet_label' => 'Item 3',
						'bullet_index' => 3,
					),
				),
				'title_field' => '{{{ bullet_label }}}',
			)
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'bullets_gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 10 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'bullets_padding',
			array(
				'label'     => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bullets_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'bullets_typography',
				'selector' => '{{WRAPPER}} .ux-remote-nav-item',
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'       => __( 'Icon Size', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 10,
						'max' => 120,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'   => array(
					'{{WRAPPER}} .ux-remote-nav-item__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-remote-nav-item__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-remote-nav-item__icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'description' => __( 'Applies to font icons, SVGs and uploaded images.', 'ux-portfolio-optimized' ),
			)
		);

		$this->start_controls_tabs( 'tabs_bullets_states' );

		// Normal
		$this->start_controls_tab( 'tab_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item .ux-remote-nav-item__icon, {{WRAPPER}} .ux-remote-nav-item .ux-remote-nav-item__icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab( 'tab_hover', array( 'label' => __( 'Hover', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'text_color_hover',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color_hover',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => __( 'Icon Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item:hover .ux-remote-nav-item__icon, {{WRAPPER}} .ux-remote-nav-item:hover .ux-remote-nav-item__icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Active
		$this->start_controls_tab( 'tab_active', array( 'label' => __( 'Active', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'text_color_active',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item.is-active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color_active',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item.is-active' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color_active',
			array(
				'label'     => __( 'Icon Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-remote-nav-item.is-active .ux-remote-nav-item__icon, {{WRAPPER}} .ux-remote-nav-item.is-active .ux-remote-nav-item__icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_description_alignment();

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['bullets'] ) ) {
			return;
		}

		$target_id        = preg_replace( '/[^A-Za-z0-9\-_]/', '', $s['target_css_id'] ?? 'my-target' );
		$widget_type      = $s['target_widget_type'] ?? 'accordion';
		$custom_selector  = $s['custom_selector'] ?? '';
		$sup_enabled      = ( $s['enable_superscript'] ?? '' ) === 'yes';
		$orientation      = $s['orientation'] ?? 'row';
		$scroll_to_target = ( $s['scroll_to_target'] ?? '' ) === 'yes';
		$scroll_offset    = intval( $s['scroll_offset'] ?? 80 );
		$label_display    = $s['label_display'] ?? 'always';
		if ( ! in_array( $label_display, array( 'always', 'hover', 'hidden' ), true ) ) {
			$label_display = 'always';
		}
		?>

		<div class="ux-remote-nav-wrapper">
			<?php $this->render_standard_header(); ?>

			<div class="ux-remote-nav is-<?php echo esc_attr( $orientation ); ?> label-<?php echo esc_attr( $label_display ); ?>"
				style="flex-direction:<?php echo esc_attr( $orientation ); ?>;"
				data-target-id="<?php echo esc_attr( $target_id ); ?>"
				data-widget-type="<?php echo esc_attr( $widget_type ); ?>"
				data-custom-selector="<?php echo esc_attr( $custom_selector ); ?>"
				data-scroll-to-target="<?php echo esc_attr( $scroll_to_target ? '1' : '0' ); ?>"
				data-scroll-offset="<?php echo esc_attr( $scroll_offset ); ?>">

				<?php
				foreach ( $s['bullets'] as $i => $item ) :
					$label  = $item['bullet_label'] ?? 'Item';
					$index  = intval( $item['bullet_index'] ?? $i + 1 );
					$active = $i === 0 ? ' is-active' : '';
					?>

					<button type="button"
							class="ux-remote-nav-item<?php echo esc_attr( $active ); ?>"
							data-target-index="<?php echo esc_attr( $index ); ?>"
							data-label="<?php echo esc_attr( $label ); ?>"
							<?php
							if ( $label_display !== 'always' ) :
								?>
								aria-label="<?php echo esc_attr( $label ); ?>"<?php endif; ?>>

						<?php
						$img_url = $item['bullet_image']['url'] ?? '';
						if ( empty( $img_url ) && ! empty( $item['bullet_image']['id'] ) ) {
							$img_url = wp_get_attachment_url( $item['bullet_image']['id'] );
						}
						if ( ! empty( $img_url ) ) :
							?>
							<span class="ux-remote-nav-item__icon has-image">
								<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy">
							</span>
						<?php elseif ( ! empty( $item['bullet_icon']['value'] ) ) : ?>
							<span class="ux-remote-nav-item__icon">
								<?php \Elementor\Icons_Manager::render_icon( $item['bullet_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php endif; ?>

						<span class="ux-remote-nav-item__label"><?php echo esc_html( $label ); ?></span>

						<?php if ( $sup_enabled && ! empty( $item['bullet_superscript'] ) ) : ?>
							<sup class="ux-remote-nav-item__sup"><?php echo esc_html( $item['bullet_superscript'] ); ?></sup>
						<?php endif; ?>

					</button>

				<?php endforeach; ?>
			</div>
		</div>

		<?php
	}
}
