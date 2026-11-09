<?php
/**
 * Logo Grid Widget
 * Display client/brand logos in a responsive grid.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Logo_Grid_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-logo-grid'; }
	public function get_title() {
		return __( 'Logo Grid', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-gallery-grid'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Content
		$this->start_controls_section(
			'logos_section',
			array(
				'label' => __( 'Logos', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Logo', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'       => __( 'Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Used as the image alt text and accessible label if no Alt Text Override is set.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'alt_override',
			array(
				'label'       => __( 'Alt Text Override', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Overrides the auto-derived alt text from Name.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Optional URL', 'ux-portfolio-optimized' ),
				'description' => __( 'Manual URL. Leave blank if using "Linked Post" below.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'linked_post',
			array(
				'label'       => __( 'Linked Post/Page', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'options'     => $this->get_linkable_posts(),
				'default'     => '',
				'description' => __( 'Pick an internal post or page. Overrides the manual Link above if set. URL updates automatically if the slug changes.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'logos',
			array(
				'label'       => __( 'Logos', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'image' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
						'name'  => 'Brand 1',
					),
					array(
						'image' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
						'name'  => 'Brand 2',
					),
					array(
						'image' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
						'name'  => 'Brand 3',
					),
					array(
						'image' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
						'name'  => 'Brand 4',
					),
				),
				'title_field' => '{{{ name || "Logo" }}}',
			)
		);

		$this->end_controls_section();

		// STYLE — Layout
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_description_alignment();

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
					'5' => '5',
					'6' => '6',
				),
				'default'        => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'selectors'      => array(
					'{{WRAPPER}} .logo-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 20 ),
				'selectors' => array( '{{WRAPPER}} .logo-grid' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'logo_align',
			array(
				'label'     => __( 'Logo Alignment', 'ux-portfolio-optimized' ),
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
				'selectors' => array( '{{WRAPPER}} .logo-item' => 'justify-content: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Logos
		$this->start_controls_section(
			'logo_style_section',
			array(
				'label' => __( 'Logos', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_max_height',
			array(
				'label'       => __( 'Max Height', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 30,
						'max' => 200,
					),
				),
				'default'     => array( 'size' => 80 ),
				'selectors'   => array(
					'{{WRAPPER}} .logo-item img' => 'max-height: {{SIZE}}{{UNIT}};',
				),
				'description' => __( 'Keeps logos at a consistent size even when source images vary.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'logo_max_width',
			array(
				'label'      => __( 'Max Width', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 400,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .logo-item img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'grayscale',
			array(
				'label'       => __( 'Grayscale Logos', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'When on, logos render desaturated and gain full color on hover.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'grayscale_opacity',
			array(
				'label'     => __( 'Default Opacity', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 20,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'   => array( 'size' => 70 ),
				'selectors' => array(
					'{{WRAPPER}} .logo-grid.is-grayscale .logo-item img' => 'opacity: calc({{SIZE}} / 100);',
				),
				'condition' => array( 'grayscale' => 'yes' ),
			)
		);

		$this->add_control(
			'hover_lift',
			array(
				'label'       => __( 'Lift on Hover', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Logo nudges up slightly when hovered.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'       => __( 'Show Title', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Displays each logo\'s Name as a visible title. Logos without a Name are skipped.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'title_position',
			array(
				'label'     => __( 'Title Position', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'below' => __( 'Below Logo', 'ux-portfolio-optimized' ),
					'above' => __( 'Above Logo', 'ux-portfolio-optimized' ),
				),
				'default'   => 'below',
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Title
		$this->start_controls_section(
			'title_style_section',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .logo-item__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .logo-item__title',
			)
		);

		$this->add_responsive_control(
			'title_gap',
			array(
				'label'     => __( 'Gap from Logo', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array( '{{WRAPPER}} .logo-item' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'title_align',
			array(
				'label'     => __( 'Alignment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .logo-item__title' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Card (optional background)
		$this->start_controls_section(
			'card_section',
			array(
				'label' => __( 'Card', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_card',
			array(
				'label'       => __( 'Show Card', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Wraps each logo in a card with background, border, and padding.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => __( 'Padding', 'ux-portfolio-optimized' ),
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
					'{{WRAPPER}} .logo-grid.has-cards .logo-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'show_card' => 'yes' ),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array( '{{WRAPPER}} .logo-grid.has-cards .logo-item' => 'background: {{VALUE}};' ),
				'condition' => array( 'show_card' => 'yes' ),
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
				'selectors' => array( '{{WRAPPER}} .logo-grid.has-cards .logo-item' => 'border-radius: {{SIZE}}{{UNIT}};' ),
				'condition' => array( 'show_card' => 'yes' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'card_border',
				'selector'  => '{{WRAPPER}} .logo-grid.has-cards .logo-item',
				'condition' => array( 'show_card' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Build a dropdown of linkable posts/pages for the SELECT2 control.
	 * Returns an associative array: post_id => "Title (post_type)".
	 */
	private function get_linkable_posts() {
		$options = array( '' => __( '— None —', 'ux-portfolio-optimized' ) );

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		unset( $post_types['attachment'] );

		foreach ( $post_types as $pt ) {
			$posts = get_posts(
				array(
					'post_type'      => $pt->name,
					'posts_per_page' => 100,
					'post_status'    => 'publish',
					'orderby'        => 'title',
					'order'          => 'ASC',
				)
			);
			foreach ( $posts as $post ) {
				$options[ $post->ID ] = sprintf( '%s (%s)', $post->post_title ?: __( '(no title)', 'ux-portfolio-optimized' ), $pt->labels->singular_name );
			}
		}

		return $options;
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['logos'] ) ) {
			return;
		}

		$show_title     = ( $s['show_title'] ?? '' ) === 'yes';
		$title_position = $s['title_position'] ?? 'below';

		$grid_classes = array( 'logo-grid' );
		if ( ( $s['show_card'] ?? '' ) === 'yes' ) {
			$grid_classes[] = 'has-cards';
		}
		if ( ( $s['grayscale'] ?? '' ) === 'yes' ) {
			$grid_classes[] = 'is-grayscale';
		}
		if ( ( $s['hover_lift'] ?? 'yes' ) === 'yes' ) {
			$grid_classes[] = 'has-hover-lift';
		}
		if ( $show_title ) {
			$grid_classes[] = 'has-titles title-' . $title_position;
		}
		?>

		<div class="logo-grid-wrapper">
			<?php $this->render_standard_header(); ?>

			<div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
				<?php
				foreach ( $s['logos'] as $logo ) :
					// Get URL from either direct URL or by looking up the attachment ID.
					// Elementor stores SVGs and some other media types with just an ID.
					$img_url = $logo['image']['url'] ?? '';
					if ( empty( $img_url ) && ! empty( $logo['image']['id'] ) ) {
						$img_url = wp_get_attachment_url( $logo['image']['id'] );
					}
					if ( empty( $img_url ) ) {
						continue;
					}

					$name = trim( $logo['name'] ?? '' );

					// Alt text: override > name > empty
					$alt = trim( $logo['alt_override'] ?? '' );
					if ( $alt === '' ) {
						$alt = $name !== '' ? $name : '';
					}

					$link_url      = '';
					$link_external = false;
					$link_nofollow = false;

					// Linked post wins (internal, dynamic URL via get_permalink)
					if ( ! empty( $logo['linked_post'] ) ) {
						$permalink = get_permalink( (int) $logo['linked_post'] );
						if ( $permalink ) {
							$link_url = $permalink;
						}
					}

					// Fall back to manual link if no linked post
					if ( empty( $link_url ) ) {
						$link_url      = $logo['link']['url'] ?? '';
						$link_external = ! empty( $logo['link']['is_external'] );
						$link_nofollow = ! empty( $logo['link']['nofollow'] );
					}

					// Build image markup once
					ob_start();
					if ( ! empty( $link_url ) ) {
						$target    = $link_external ? ' target="_blank"' : '';
						$rel_parts = array();
						if ( $link_external ) {
							$rel_parts[] = 'noopener';
						}
						if ( $link_nofollow ) {
							$rel_parts[] = 'nofollow';
						}
						$rel = $rel_parts ? ' rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"' : '';
						echo '<a href="' . esc_url( $link_url ) . '"' . $target . $rel . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- target/rel are fixed strings; rel built with esc_attr()
						echo '<img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
						echo '</a>';
					} else {
						echo '<img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
					}
					$img_markup = ob_get_clean();

					$has_title = $show_title && $name !== '';
					?>
					<div class="logo-item">
						<?php if ( $has_title && $title_position === 'above' ) : ?>
							<span class="logo-item__title"><?php echo esc_html( $name ); ?></span>
						<?php endif; ?>

						<div class="logo-item__image"><?php echo $img_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- image markup from wp_get_attachment_image() or escaped <img> above ?></div>

						<?php if ( $has_title && $title_position === 'below' ) : ?>
							<span class="logo-item__title"><?php echo esc_html( $name ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
	}
}
