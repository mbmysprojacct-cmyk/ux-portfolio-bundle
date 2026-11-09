<?php
/**
 * Wireframe Showcase Widget
 * Carousel of wireframe screens with hotspot annotations.
 * Click any wireframe to open the full image in Elementor's lightbox.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Wireframe_Showcase_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-wireframe-showcase'; }
	public function get_title() {
		return __( 'Wireframe Showcase', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-slider-push'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		$this->start_controls_section(
			'slides_section',
			array(
				'label' => __( 'Slides', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'slide_type',
			array(
				'label'   => __( 'Slide Type', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'image'        => __( 'Image (with optional annotations)', 'ux-portfolio-optimized' ),
					'before_after' => __( 'Before / After slider', 'ux-portfolio-optimized' ),
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label'     => __( 'Wireframe Image', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'default'   => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
				'condition' => array( 'slide_type' => 'image' ),
			)
		);

		$repeater->add_control(
			'ba_before',
			array(
				'label'       => __( 'Before (Concept)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'condition'   => array( 'slide_type' => 'before_after' ),
				'description' => __( 'Revealed as the divider drags left.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'ba_after',
			array(
				'label'       => __( 'After (Final)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'condition'   => array( 'slide_type' => 'before_after' ),
				'description' => __( 'Shown on top.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'ba_before_label',
			array(
				'label'     => __( 'Before Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Concept', 'ux-portfolio-optimized' ),
				'condition' => array( 'slide_type' => 'before_after' ),
			)
		);

		$repeater->add_control(
			'ba_after_label',
			array(
				'label'     => __( 'After Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Final', 'ux-portfolio-optimized' ),
				'condition' => array( 'slide_type' => 'before_after' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Screen',
			)
		);

		$annotations_repeater = new \Elementor\Repeater();

		$annotations_repeater->add_control(
			'annotation_title',
			array(
				'label'   => __( 'Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Feature',
			)
		);

		$annotations_repeater->add_control(
			'annotation_text',
			array(
				'label'   => __( 'Text', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'Description',
			)
		);

		$annotations_repeater->add_control(
			'x_position',
			array(
				'label'       => __( 'Horizontal Offset', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'description' => __( 'Drag to position the dot left/right on the wireframe.', 'ux-portfolio-optimized' ),
			)
		);

		$annotations_repeater->add_control(
			'y_position',
			array(
				'label'       => __( 'Vertical Offset', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'description' => __( 'Drag to position the dot up/down on the wireframe.', 'ux-portfolio-optimized' ),
			)
		);

		$annotations_repeater->add_control(
			'tooltip_position',
			array(
				'label'       => __( 'Tooltip Position', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-arrow-left',
					),
					'top'    => array(
						'title' => __( 'Top', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-arrow-up',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-arrow-down',
					),
					'right'  => array(
						'title' => __( 'Right', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-arrow-right',
					),
				),
				'default'     => 'top',
				'toggle'      => false,
				'description' => __( 'Where the annotation box appears relative to the dot.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'annotations',
			array(
				'label'       => __( 'Annotations', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $annotations_repeater->get_controls(),
				'title_field' => '{{{ annotation_title }}}',
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slides', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'title' => 'Homepage' ),
					array( 'title' => 'Product Page' ),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'carousel_section',
			array(
				'label' => __( 'Carousel Settings', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'label'          => __( 'Slides Per View', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::NUMBER,
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
				'min'            => 1,
				'max'            => 10,
				'selectors'      => array(
					'{{WRAPPER}} .wireframe-carousel' => '--slides-per-view: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hotspot_trigger',
			array(
				'label'       => __( 'Hotspot Trigger', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'hover',
				'options'     => array(
					'hover' => __( 'Hover', 'ux-portfolio-optimized' ),
					'click' => __( 'Click', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'How the annotation box opens on each hotspot dot.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'       => __( 'Autoplay', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Reserved for future use — not yet wired up.', 'ux-portfolio-optimized' ),
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

		$this->add_description_alignment();

		$this->add_control(
			'equal_height',
			array(
				'label'       => __( 'Equal Slide Heights', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'separator'   => 'before',
				'description' => __( 'Give every slide the same height with its full image at natural width (no cropping, no side gaps) — a justified-gallery look that still scrolls as a carousel.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'image_height',
			array(
				'label'      => __( 'Slide Height', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 900,
					),
					'vh' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 480,
				),
				'condition'  => array( 'equal_height' => 'yes' ),
				'selectors'  => array(
					'{{WRAPPER}} .equal-height .slide-content img' => 'height: {{SIZE}}{{UNIT}}; width: 100%; object-fit: contain;',
				),
			)
		);

		$this->add_control(
			'ba_max_height_heading',
			array(
				'label'     => __( 'Before / After Sliders', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'ba_max_height',
			array(
				'label'       => __( 'Max Height', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'vh' ),
				'range'       => array(
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 600,
				),
				'description' => __( 'Caps the height of before/after sliders — useful for tall portrait posters. The slider stays centered and keeps its proportions.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .ux-ba-frame' => 'max-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dots_style_heading',
			array(
				'label'     => __( 'Hotspot Dots', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => __( 'Dot Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .annotation-dot' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dot_size',
			array(
				'label'     => __( 'Dot Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .annotation-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tooltip_style_heading',
			array(
				'label'     => __( 'Annotation Box', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tooltip_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .annotation-tooltip' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_title_color',
			array(
				'label'     => __( 'Title Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .annotation-tooltip .tooltip-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_text_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .annotation-tooltip .tooltip-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_title_size',
			array(
				'label'     => __( 'Title Text Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 32,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .annotation-tooltip .tooltip-title' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tooltip_text_size',
			array(
				'label'     => __( 'Body Text Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 24,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .annotation-tooltip .tooltip-content' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tooltip_min_width',
			array(
				'label'     => __( 'Box Min Width', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 100,
						'max' => 400,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .annotation-tooltip' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['slides'] ) ) {
			return;
		}

		$widget_id    = $this->get_id();
		$autoplay     = ( $s['autoplay'] ?? '' ) === 'yes';
		$slideshow_id = $widget_id . '-wireframes';
		?>

		<div class="wireframe-wrapper">
			<?php $this->render_standard_header(); ?>

			<div class="wireframe-carousel carousel-<?php echo esc_attr( $widget_id ); ?><?php echo ( ( $s['equal_height'] ?? '' ) === 'yes' ) ? ' equal-height' : ''; ?>"
				data-id="<?php echo esc_attr( $widget_id ); ?>"
				data-trigger="<?php echo esc_attr( $s['hotspot_trigger'] ?? 'hover' ); ?>"
				data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>">

				<div class="carousel-track">
					<?php
					foreach ( $s['slides'] as $i => $slide ) :
						$slide_type = $slide['slide_type'] ?? 'image';
						$img_url    = $slide['image']['url'] ?? '';
						if ( empty( $img_url ) && ! empty( $slide['image']['id'] ) ) {
							$img_url = wp_get_attachment_url( $slide['image']['id'] );
						}
						$slide_title = $slide['title'] ?? '';

						// Resolve before/after image URLs for that slide type.
						$ba_before = $slide['ba_before']['url'] ?? '';
						if ( empty( $ba_before ) && ! empty( $slide['ba_before']['id'] ) ) {
							$ba_before = wp_get_attachment_url( $slide['ba_before']['id'] );
						}
						$ba_after = $slide['ba_after']['url'] ?? '';
						if ( empty( $ba_after ) && ! empty( $slide['ba_after']['id'] ) ) {
							$ba_after = wp_get_attachment_url( $slide['ba_after']['id'] );
						}
						$ba_before_label = trim( $slide['ba_before_label'] ?? '' );
						$ba_after_label  = trim( $slide['ba_after_label'] ?? '' );
						?>
						<div class="carousel-slide">
							<div class="slide-content">
								<?php if ( $slide_type === 'before_after' && $ba_before !== '' && $ba_after !== '' ) : ?>
									<div class="ux-ba-frame" data-start="50">
										<img class="ux-ba-after-img" src="<?php echo esc_url( $ba_after ); ?>" alt="<?php echo esc_attr( $ba_after_label !== '' ? $ba_after_label : $slide_title ); ?>" draggable="false">
										<div class="ux-ba-before" style="width: 50%;">
											<div class="ux-ba-before-inner">
												<img class="ux-ba-before-img" src="<?php echo esc_url( $ba_before ); ?>" alt="<?php echo esc_attr( $ba_before_label !== '' ? $ba_before_label : $slide_title ); ?>" draggable="false">
											</div>
											<?php if ( $ba_before_label !== '' ) : ?>
												<span class="ux-ba-label ux-ba-label-before"><?php echo esc_html( $ba_before_label ); ?></span>
											<?php endif; ?>
										</div>
										<?php if ( $ba_after_label !== '' ) : ?>
											<span class="ux-ba-label ux-ba-label-after"><?php echo esc_html( $ba_after_label ); ?></span>
										<?php endif; ?>
										<div class="ux-ba-divider" style="left: 50%;"><span class="ux-ba-handle" aria-hidden="true"></span></div>
										<input type="range" class="ux-ba-range" min="0" max="100" value="50" aria-label="<?php echo esc_attr( $slide_title !== '' ? $slide_title : __( 'Before after slider', 'ux-portfolio-optimized' ) ); ?>">
									</div>
								<?php else : ?>
									<?php if ( ! empty( $img_url ) ) : ?>
										<a href="<?php echo esc_url( $img_url ); ?>"
											class="slide-image-link"
											data-elementor-open-lightbox="yes"
											data-elementor-lightbox-slideshow="<?php echo esc_attr( $slideshow_id ); ?>"
											data-elementor-lightbox-index="<?php echo esc_attr( $i ); ?>"
											aria-label="<?php echo esc_attr( sprintf( __( 'View wireframe: %s', 'ux-portfolio-optimized' ), $slide_title ) ); ?>">
											<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $slide_title ); ?>" loading="lazy">
										</a>
									<?php endif; ?>

									<?php if ( ! empty( $slide['annotations'] ) ) : ?>
										<?php
										foreach ( $slide['annotations'] as $anno ) :
											$x_off   = $anno['x_position']['size'] ?? 50;
											$y_off   = $anno['y_position']['size'] ?? 50;
											$tip_pos = $anno['tooltip_position'] ?? 'top';
											?>
											<div class="annotation-dot tooltip-pos-<?php echo esc_attr( $tip_pos ); ?>"
												style="left: <?php echo esc_attr( floatval( $x_off ) ); ?>%; top: <?php echo esc_attr( floatval( $y_off ) ); ?>%;"
												data-title="<?php echo esc_attr( $anno['annotation_title'] ?? '' ); ?>"
												data-content="<?php echo esc_attr( $anno['annotation_text'] ?? '' ); ?>">
												<span class="dot-pulse"></span>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<?php if ( ! empty( $slide_title ) ) : ?>
								<div class="slide-title"><?php echo esc_html( $slide_title ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( count( $s['slides'] ) > 1 ) : ?>
					<button type="button" class="carousel-nav carousel-prev" aria-label="<?php esc_attr_e( 'Previous slide', 'ux-portfolio-optimized' ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 6 9 12 15 18"/></svg></button>
					<button type="button" class="carousel-nav carousel-next" aria-label="<?php esc_attr_e( 'Next slide', 'ux-portfolio-optimized' ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 6 15 12 9 18"/></svg></button>

					<div class="carousel-dots">
						<?php foreach ( $s['slides'] as $i => $slide ) : ?>
							<button type="button" class="carousel-dot<?php echo $i === 0 ? ' active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'ux-portfolio-optimized' ), $i + 1 ) ); ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php
	}
}
