<?php
/**
 * User Persona Widget - Optimized
 * From: 857 lines → To: ~350 lines (59% reduction)
 * Most complex widget with full feature preservation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_User_Persona_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-user-persona'; }
	public function get_title() {
		return __( 'User Persona', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-person'; }

	protected function register_controls() {

		// Add standard title/description
		$this->add_standard_header_section();

		// CONTENT - Sections Repeater
		$this->start_controls_section( 'sections', array( 'label' => __( 'Sections (Drag to Reorder)', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'main_title',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'User Persona',
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'persona_tag',
			array(
				'label'       => __( 'Persona', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => __( 'e.g. Casual Traveller', 'ux-portfolio-optimized' ),
				'description' => __( 'Sections sharing a persona name group together under that tab. Only used when Multiple Personas is enabled.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'item_name',
			array(
				'label'       => __( 'Item Name (for your reference)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'e.g. Age, Goals, Frustrations', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'section_type',
			array(
				'label'   => __( 'Type', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'basic_info'    => __( 'Basic Info', 'ux-portfolio-optimized' ),
					'demographic'   => __( 'Demographic', 'ux-portfolio-optimized' ),
					'problem'       => __( 'Problem', 'ux-portfolio-optimized' ),
					'background'    => __( 'Background', 'ux-portfolio-optimized' ),
					'quote'         => __( 'Quote', 'ux-portfolio-optimized' ),
					'section_boxes' => __( 'Section Box', 'ux-portfolio-optimized' ),
					'icon_item'     => __( 'Icon/Image', 'ux-portfolio-optimized' ),
				),
				'default' => 'basic_info',
			)
		);

		$repeater->add_responsive_control(
			'column_span',
			array(
				'label'   => __( 'Columns', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 6,
				'default' => 1,
			)
		);

		$repeater->add_responsive_control(
			'row_span',
			array(
				'label'   => __( 'Rows', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 6,
				'default' => 1,
			)
		);

		// Basic Info
		$repeater->add_control(
			'persona_photo',
			array(
				'label'     => __( 'Photo', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'condition' => array( 'section_type' => 'basic_info' ),
			)
		);

		$repeater->add_control(
			'persona_name',
			array(
				'label'     => __( 'Name', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'Sarah Martinez',
				'condition' => array( 'section_type' => 'basic_info' ),
			)
		);

		$repeater->add_control(
			'persona_segment',
			array(
				'label'     => __( 'Segment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'The Busy Professional',
				'condition' => array( 'section_type' => 'basic_info' ),
			)
		);

		// Demographic
		$repeater->add_control(
			'demographic_label',
			array(
				'label'     => __( 'Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => array( 'section_type' => 'demographic' ),
			)
		);

		$repeater->add_control(
			'demographic_value',
			array(
				'label'     => __( 'Value', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => array( 'section_type' => 'demographic' ),
			)
		);

		// Problem
		$repeater->add_control(
			'problem_title',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => array( 'section_type' => 'problem' ),
			)
		);

		$repeater->add_control(
			'problem_text',
			array(
				'label'     => __( 'Description', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'condition' => array( 'section_type' => 'problem' ),
			)
		);

		// Background
		$repeater->add_control(
			'background_title',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => array( 'section_type' => 'background' ),
			)
		);

		$repeater->add_control(
			'background_bio',
			array(
				'label'     => __( 'Bio', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'condition' => array( 'section_type' => 'background' ),
			)
		);

		// Quote
		$repeater->add_control(
			'quote_text',
			array(
				'label'     => __( 'Quote', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'condition' => array( 'section_type' => 'quote' ),
			)
		);

		$repeater->add_control(
			'quote_style',
			array(
				'label'     => __( 'Style', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'blockquote' => __( 'Blockquote (Left Border)', 'ux-portfolio-optimized' ),
					'pullquote'  => __( 'Pullquote (Gradient Box)', 'ux-portfolio-optimized' ),
					'custom'     => __( 'Custom', 'ux-portfolio-optimized' ),
				),
				'default'   => 'pullquote',
				'condition' => array( 'section_type' => 'quote' ),
			)
		);

		$repeater->add_responsive_control(
			'quote_align',
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
				'condition' => array( 'section_type' => 'quote' ),
			)
		);

		// Section Box
		$repeater->add_control(
			'box_title',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => array( 'section_type' => 'section_boxes' ),
			)
		);

		$repeater->add_control(
			'box_content',
			array(
				'label'     => __( 'Content', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::WYSIWYG,
				'default'   => '<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>',
				'condition' => array( 'section_type' => 'section_boxes' ),
			)
		);

		// Icon Item
		$repeater->add_control(
			'icon_type',
			array(
				'label'     => __( 'Type', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'icon'  => __( 'Icon', 'ux-portfolio-optimized' ),
					'image' => __( 'Image', 'ux-portfolio-optimized' ),
				),
				'default'   => 'icon',
				'condition' => array( 'section_type' => 'icon_item' ),
			)
		);

		$repeater->add_control(
			'icon_icon',
			array(
				'label'     => __( 'Icon', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'condition' => array(
					'section_type' => 'icon_item',
					'icon_type'    => 'icon',
				),
			)
		);

		$repeater->add_control(
			'icon_image',
			array(
				'label'     => __( 'Image', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'condition' => array(
					'section_type' => 'icon_item',
					'icon_type'    => 'image',
				),
			)
		);

		$repeater->add_control(
			'icon_label',
			array(
				'label'     => __( 'Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => array( 'section_type' => 'icon_item' ),
			)
		);

		$this->add_control(
			'persona_sections',
			array(
				'label'       => __( 'Persona Sections', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'section_type'    => 'basic_info',
						'persona_name'    => 'Sarah Martinez',
						'persona_segment' => 'The Busy Professional',
					),
				),
				'title_field' => '{{{ persona_tag ? persona_tag + " — " : "" }}}{{{ item_name || section_type }}}',
			)
		);

		$this->end_controls_section();

		// PERSONAS (multiple)
		$this->start_controls_section( 'personas_section', array( 'label' => __( 'Multiple Personas', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'enable_personas',
			array(
				'label'       => __( 'Enable Multiple Personas', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Show several personas, switchable by tabs. Give each section above a Persona name — sections sharing a name group together.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_tabs',
			array(
				'label'       => __( 'Show Tabs', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array( 'enable_personas' => 'yes' ),
				'description' => __( 'Turn off to stack every persona down the page instead of tabbing between them.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'default_persona',
			array(
				'label'       => __( 'Default Persona', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					'enable_personas' => 'yes',
					'show_tabs'       => 'yes',
				),
				'description' => __( 'Name of the persona to open first. Leave blank for the first one.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// LAYOUT
		$this->start_controls_section( 'layout', array( 'label' => __( 'Layout', 'ux-portfolio-optimized' ) ) );

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
			)
		);

		$this->end_controls_section();

		// DEMOGRAPHICS STYLE
		$this->start_controls_section(
			'demographics_style',
			array(
				'label' => __( 'Demographics', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'demographics_layout',
			array(
				'label'   => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'horizontal' => __( 'Horizontal', 'ux-portfolio-optimized' ),
					'vertical'   => __( 'Vertical', 'ux-portfolio-optimized' ),
				),
				'default' => 'horizontal',
			)
		);

		$this->add_control(
			'demographics_alignment',
			array(
				'label'   => __( 'Alignment', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
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
				'default' => 'center',
			)
		);

		$this->add_control(
			'demographics_bullet',
			array(
				'label'   => __( 'Bullet Icon', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_responsive_control(
			'demographics_bullet_size',
			array(
				'label'      => __( 'Bullet Size', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 4,
						'max' => 20,
					),
				),
				'default'    => array( 'size' => 8 ),
				'selectors'  => array(
					'{{WRAPPER}} .ux-persona-demo-bullet' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} svg.ux-persona-demo-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-persona-demo-bullet svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// IMAGE STYLE
		$this->start_controls_section(
			'image_style',
			array(
				'label' => __( 'Persona Photo', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'photo_width',
			array(
				'label'      => __( 'Width', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'default'    => array( 'size' => 200 ),
				'selectors'  => array(
					'{{WRAPPER}} .persona-photo' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'photo_height',
			array(
				'label'      => __( 'Height', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'default'    => array( 'size' => 200 ),
				'selectors'  => array(
					'{{WRAPPER}} .persona-photo' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'photo_border_radius',
			array(
				'label'      => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '50',
					'right'  => '50',
					'bottom' => '50',
					'left'   => '50',
					'unit'   => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .persona-photo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'photo_object_fit',
			array(
				'label'     => __( 'Object Fit', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'cover'   => __( 'Cover', 'ux-portfolio-optimized' ),
					'contain' => __( 'Contain', 'ux-portfolio-optimized' ),
					'fill'    => __( 'Fill', 'ux-portfolio-optimized' ),
				),
				'default'   => 'cover',
				'selectors' => array(
					'{{WRAPPER}} .persona-photo' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Section Icon Size', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 150,
					),
				),
				'default'    => array( 'size' => 60 ),
				'selectors'  => array(
					'{{WRAPPER}} .ux-user-persona'         => '--persona-icon-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-persona-icon-large'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-persona-icon-large svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-persona-icon-large i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_description_alignment();

		$this->end_controls_section();

		// SECTION STYLE
		$this->start_controls_section(
			'style',
			array(
				'label' => __( 'Sections', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_background_control( 'section_bg', __( 'Section Background', 'ux-portfolio-optimized' ), '.ux-user-persona .section' );
		$this->add_shadow_control( 'section_shadow', __( 'Section Shadow', 'ux-portfolio-optimized' ), '.ux-user-persona .section' );
		$this->add_border_radius( 'section_radius', __( 'Section Border Radius', 'ux-portfolio-optimized' ), '.ux-user-persona .section' );
		$this->add_responsive_number( 'gap', __( 'Gap', 'ux-portfolio-optimized' ), '.ux-user-persona', 'gap', 30 );

		$this->end_controls_section();
	}

	/** Renders one persona's sections. */
	private function render_persona_body( $s, $sections ) {
		$cols        = absint( $s['columns'] ?? 3 );
		$demo_align  = $s['demographics_alignment'] ?? 'center';
		$demo_layout = $s['demographics_layout'] ?? 'horizontal';
		$demo_bullet = $s['demographics_bullet'] ?? array(
			'value'   => 'fas fa-circle',
			'library' => 'fa-solid',
		);

		// Collect demographics and icons
		$demographics           = array();
		$icon_groups            = array();
		$last_section_box_index = -1;

		foreach ( $sections as $index => $item ) {
			if ( $item['section_type'] === 'demographic' ) {
				$demographics[] = $item;
			} elseif ( $item['section_type'] === 'section_boxes' ) {
				$last_section_box_index = $index;
				$icon_groups[ $index ]  = array();
			} elseif ( $item['section_type'] === 'icon_item' && $last_section_box_index >= 0 ) {
				$icon_groups[ $last_section_box_index ][] = $item;
			}
		}
		?>
		
			<div class="ux-user-persona" style="--columns: <?php echo $cols; ?>">
			
			<?php
			foreach ( $sections as $index => $item ) :
				if ( $item['section_type'] === 'demographic' || $item['section_type'] === 'icon_item' ) {
					continue;
				}

				$span     = absint( $item['column_span'] ?? 1 );
				$row_span = absint( $item['row_span'] ?? 1 );
				?>
				
				<div class="section" style="grid-column: span <?php echo $span; ?>; grid-row: span <?php echo $row_span; ?>;">
					
					<?php if ( $item['section_type'] === 'basic_info' ) : ?>
						<?php
						$photo_url = ! empty( $item['persona_photo']['url'] ) ? $item['persona_photo']['url'] : '';
						if ( ! $photo_url && ! empty( $item['persona_photo']['id'] ) ) {
							$photo_url = (string) wp_get_attachment_image_url( absint( $item['persona_photo']['id'] ), 'medium_large' );
						}
						if ( $photo_url ) :
							?>
							<img class="persona-photo" src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $item['persona_name'] ?? '' ); ?>" loading="lazy" decoding="async">
						<?php endif; ?>
						<h2><?php echo esc_html( $item['persona_name'] ); ?></h2>
						<p class="persona-segment"><?php echo esc_html( $item['persona_segment'] ); ?></p>
						
						<?php if ( ! empty( $demographics ) ) : ?>
							<div style="text-align: <?php echo esc_attr( $demo_align ); ?>; margin-top: 15px;">
								<?php if ( $demo_layout === 'vertical' ) : ?>
									<div style="display: inline-block; text-align: <?php echo esc_attr( $demo_align ); ?>;">
										<?php foreach ( $demographics as $demo ) : ?>
											<div class="ux-persona-demo-item">
												<?php if ( ! empty( $demo_bullet['value'] ) ) : ?>
													<?php
													\Elementor\Icons_Manager::render_icon(
														$demo_bullet,
														array(
															'class' => 'ux-persona-demo-bullet',
															'aria-hidden' => 'true',
														)
													);
													?>
												<?php endif; ?>
												<span class="ux-persona-demo-label"><?php echo esc_html( $demo['demographic_label'] ); ?>:</span>
												<span class="ux-persona-demo-value"><?php echo esc_html( $demo['demographic_value'] ); ?></span>
											</div>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<div class="ux-persona-demo-horizontal">
										<?php foreach ( $demographics as $demo ) : ?>
											<span class="ux-persona-demo-item ux-persona-demo-item--inline">
												<?php if ( ! empty( $demo_bullet['value'] ) ) : ?>
													<?php
													\Elementor\Icons_Manager::render_icon(
														$demo_bullet,
														array(
															'class' => 'ux-persona-demo-bullet',
															'aria-hidden' => 'true',
														)
													);
													?>
												<?php endif; ?>
												<span class="ux-persona-demo-label"><?php echo esc_html( $demo['demographic_label'] ); ?>:</span>
												<span class="ux-persona-demo-value"><?php echo esc_html( $demo['demographic_value'] ); ?></span>
											</span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						
					<?php elseif ( $item['section_type'] === 'problem' ) : ?>
						<h3><?php echo esc_html( $item['problem_title'] ); ?></h3>
						<p><?php echo esc_html( $item['problem_text'] ); ?></p>
						
					<?php elseif ( $item['section_type'] === 'background' ) : ?>
						<h3><?php echo esc_html( $item['background_title'] ); ?></h3>
						<p><?php echo nl2br( esc_html( $item['background_bio'] ) ); ?></p>
						
					<?php elseif ( $item['section_type'] === 'quote' ) : ?>
						<?php
						$quote_style = $item['quote_style'] ?? 'pullquote';
						$quote_align = $item['quote_align'] ?? 'center';
						$class       = 'persona-' . $quote_style;
						?>
						<div class="<?php echo esc_attr( $class ); ?>" style="text-align: <?php echo esc_attr( $quote_align ); ?>;">
							<p>"<?php echo esc_html( $item['quote_text'] ); ?>"</p>
						</div>
						
					<?php elseif ( $item['section_type'] === 'section_boxes' ) : ?>
						<h3><?php echo esc_html( $item['box_title'] ); ?></h3>
						<div><?php echo wp_kses_post( $item['box_content'] ); ?></div>
						
						<?php if ( isset( $icon_groups[ $index ] ) && ! empty( $icon_groups[ $index ] ) ) : ?>
							<div class="ux-persona-icon-grid">
								<?php foreach ( $icon_groups[ $index ] as $icon_item ) : ?>
									<div class="ux-persona-icon-cell">
										<div class="ux-persona-icon-media">
											<?php if ( $icon_item['icon_type'] === 'image' && ! empty( $icon_item['icon_image']['url'] ) ) : ?>
												<img class="ux-persona-icon-img" src="<?php echo esc_url( $icon_item['icon_image']['url'] ); ?>" alt="<?php echo esc_attr( $icon_item['icon_label'] ?? '' ); ?>" loading="lazy">
											<?php elseif ( ! empty( $icon_item['icon_icon']['value'] ) ) : ?>
												<div class="ux-persona-icon-large">
													<?php \Elementor\Icons_Manager::render_icon( $icon_item['icon_icon'], array( 'aria-hidden' => 'true' ) ); ?>
												</div>
											<?php endif; ?>
										</div>
										<?php if ( ! empty( $icon_item['icon_label'] ) ) : ?>
											<div class="ux-persona-icon-label"><?php echo esc_html( $icon_item['icon_label'] ); ?></div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					
				</div>
				
			<?php endforeach; ?>
			
		</div>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['persona_sections'] ) ) {
			return;
		}

		$use_personas = ( $s['enable_personas'] ?? '' ) === 'yes';

		// Bucket sections by persona name, preserving first-seen order.
		$personas = array();
		foreach ( $s['persona_sections'] as $item ) {
			$tag = $use_personas ? trim( $item['persona_tag'] ?? '' ) : '';
			if ( $tag === '' ) {
				$tag = $use_personas
					? __( 'Untitled', 'ux-portfolio-optimized' )
					: '__single__';
			}
			if ( ! isset( $personas[ $tag ] ) ) {
				$personas[ $tag ] = array();
			}
			$personas[ $tag ][] = $item;
		}

		$names  = array_keys( $personas );
		$tabbed = $use_personas && ( ( $s['show_tabs'] ?? 'yes' ) === 'yes' ) && count( $names ) > 1;

		// Which tab opens first.
		$active          = 0;
		$default_persona = trim( $s['default_persona'] ?? '' );
		if ( $tabbed && $default_persona !== '' ) {
			foreach ( $names as $i => $n ) {
				if ( strcasecmp( $n, $default_persona ) === 0 ) {
					$active = $i;
					break;
				}
			}
		}
		?>
		<div class="ux-user-persona-wrapper" data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php if ( $tabbed ) : ?>
				<div class="persona-tabs" role="tablist">
					<?php
					foreach ( $names as $i => $n ) :
						$is_on = ( $i === $active );
						?>
						<button type="button"
								class="persona-tab<?php echo $is_on ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $is_on ? 'true' : 'false'; ?>"
								data-persona-tab="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $n ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $names as $i => $n ) :
				$hidden = $tabbed && ( $i !== $active );
				?>
				<div class="persona-panel<?php echo $hidden ? ' is-hidden' : ''; ?>" data-persona-panel="<?php echo esc_attr( $i ); ?>">
					<?php if ( ! $tabbed && $use_personas && $n !== '__single__' ) : ?>
						<h3 class="persona-panel__name"><?php echo esc_html( $n ); ?></h3>
					<?php endif; ?>
					<?php $this->render_persona_body( $s, $personas[ $n ] ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
