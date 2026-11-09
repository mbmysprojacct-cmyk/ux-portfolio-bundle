<?php
/**
 * Anchor Links Widget (formerly "Section Divider")
 * Manual list of links that jump to section IDs on the same page.
 * Class/file name kept for backward compatibility with existing instances.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Section_Divider_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-section-divider'; }
	public function get_title() {
		return __( 'Anchor Links', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-anchor'; }

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Leave blank to hide.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'section_id',
			array(
				'label'       => __( 'Section ID', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section',
				'description' => __( 'CSS ID of the target section (without #). Set under Advanced → CSS ID.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'section_label',
			array(
				'label'   => __( 'Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Section Name',
			)
		);

		$this->add_control(
			'sections',
			array(
				'label'       => __( 'Links', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'section_id'    => 'section-1',
						'section_label' => 'Section 1',
					),
					array(
						'section_id'    => 'section-2',
						'section_label' => 'Section 2',
					),
					array(
						'section_id'    => 'section-3',
						'section_label' => 'Section 3',
					),
				),
				'title_field' => '{{{ section_label }}}',
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'          => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::CHOOSE,
				'options'        => array(
					'grid'  => array(
						'title' => __( 'Grid', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'stack' => array(
						'title' => __( 'Stack', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-ellipsis-v',
					),
				),
				'default'        => 'grid',
				'tablet_default' => 'stack',
				'mobile_default' => 'stack',
				'prefix_class'   => 'ux-anchor-links--%s',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Container
		$this->add_control(
			'container_heading',
			array(
				'label' => __( 'Container', 'ux-portfolio-optimized' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'     => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
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
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .ux-anchor-links',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} .ux-anchor-links',
			)
		);

		// Title
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
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ux-anchor-links__title',
			)
		);

		// Links
		$this->add_control(
			'links_heading',
			array(
				'label'     => __( 'Links', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'links_gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__list' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'link_padding',
			array(
				'label'     => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'link_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .ux-anchor-links__link',
			)
		);

		$this->start_controls_tabs( 'tabs_link_states' );

		$this->start_controls_tab( 'tab_link_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'link_border',
				'selector' => '{{WRAPPER}} .ux-anchor-links__link',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_link_hover', array( 'label' => __( 'Hover', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'link_color_hover',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link:hover, {{WRAPPER}} .ux-anchor-links__link:focus-visible' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_bg_hover',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link:hover, {{WRAPPER}} .ux-anchor-links__link:focus-visible' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_border_hover',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-anchor-links__link:hover, {{WRAPPER}} .ux-anchor-links__link:focus-visible' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['sections'] ) ) {
			return;
		}

		$title = $s['title'] ?? '';
		?>

		<div class="ux-anchor-links">
			<?php if ( $title ) : ?>
				<h2 class="ux-anchor-links__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<ul class="ux-anchor-links__list" role="list">
				<?php
				foreach ( $s['sections'] as $section ) :
					$id    = sanitize_title( $section['section_id'] ?? '' );
					$label = $section['section_label'] ?? '';
					if ( ! $id || ! $label ) {
						continue;
					}
					?>
					<li class="ux-anchor-links__item">
						<a href="#<?php echo esc_attr( $id ); ?>" class="ux-anchor-links__link">
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
	}
}
