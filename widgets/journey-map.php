<?php
/**
 * Journey Map Widget - Complete Rebuild
 * All user requirements implemented
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Journey_Map_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-journey-map'; }
	public function get_title() {
		return __( 'User Journey Map', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-navigation-horizontal'; }

	protected function register_controls() {

		// HEADER
		$this->start_controls_section(
			'header_section',
			array(
				'label' => __( 'Header', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'User Journey Map',
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => __( 'Show Description', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'     => __( 'Description', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'default'   => '',
				'condition' => array( 'show_description' => 'yes' ),
			)
		);

		$this->add_control(
			'show_persona',
			array(
				'label'        => __( 'Show Persona', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'persona',
			array(
				'label'       => __( 'Persona', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'e.g. Sarah Martinez - The Busy Professional',
				'condition'   => array( 'show_persona' => 'yes' ),
			)
		);

		$this->add_control(
			'persona_link',
			array(
				'label'         => __( 'Persona Link (optional)', 'ux-portfolio-optimized' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => __( 'Paste URL or anchor (e.g. #persona-sarah)', 'ux-portfolio-optimized' ),
				'description'   => __( 'If filled, the persona name becomes a clickable link back to the persona section.', 'ux-portfolio-optimized' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
				'condition'     => array( 'show_persona' => 'yes' ),
			)
		);

		$this->add_control(
			'show_goals',
			array(
				'label'        => __( 'Show Goals', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'goals',
			array(
				'label'       => __( 'Goals', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => 'Enter goals, one per line',
				'condition'   => array( 'show_goals' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// PHASES (Tasks)
		$this->start_controls_section(
			'phases_section',
			array(
				'label' => __( 'Phases', 'ux-portfolio-optimized' ),
			)
		);

		$phases = new \Elementor\Repeater();

		$phases->add_control(
			'phase_name',
			array(
				'label'   => __( 'Phase Name', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Phase',
			)
		);

		$phases->add_control(
			'tasks',
			array(
				'label'       => __( 'Tasks (one per line)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => 'Task 1' . "\n" . 'Task 2' . "\n" . 'Task 3',
			)
		);

		$phases->add_control(
			'feelings',
			array(
				'label'       => __( 'Feelings (one per line)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => 'Feeling 1' . "\n" . 'Feeling 2',
			)
		);

		$phases->add_control(
			'show_pain_points',
			array(
				'label'        => __( 'Show Pain Points', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$phases->add_control(
			'pain_points',
			array(
				'label'       => __( 'Pain Points (one per line)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => 'Pain point 1' . "\n" . 'Pain point 2',
				'condition'   => array( 'show_pain_points' => 'yes' ),
			)
		);

		$phases->add_control(
			'opportunities',
			array(
				'label'       => __( 'Improvement Opportunities (one per line)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => 'Opportunity 1' . "\n" . 'Opportunity 2',
			)
		);

		$this->add_control(
			'phases',
			array(
				'label'       => __( 'Journey Phases', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $phases->get_controls(),
				'default'     => array(
					array(
						'phase_name'    => 'Awareness',
						'tasks'         => 'Browse website' . "\n" . 'Read reviews',
						'feelings'      => 'Curious' . "\n" . 'Interested',
						'pain_points'   => 'Too much information',
						'opportunities' => 'Simplify navigation',
					),
					array(
						'phase_name'    => 'Consideration',
						'tasks'         => 'Compare options' . "\n" . 'Check pricing',
						'feelings'      => 'Thoughtful' . "\n" . 'Uncertain',
						'pain_points'   => 'Unclear pricing',
						'opportunities' => 'Clear pricing page',
					),
				),
				'title_field' => '{{{ phase_name }}}',
			)
		);

		$this->end_controls_section();

		// EMOTION CURVE - CONTENT
		$this->start_controls_section(
			'curve_section',
			array(
				'label' => __( 'Emotion Curve', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_curve',
			array(
				'label'       => __( 'Show Emotion Curve', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'TIP: Add one curve point per journey phase so the line aligns with the columns above/below it.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'curve_position',
			array(
				'label'     => __( 'Position', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'above' => __( 'Above Journey', 'ux-portfolio-optimized' ),
					'below' => __( 'Below Journey', 'ux-portfolio-optimized' ),
				),
				'default'   => 'below',
				'condition' => array( 'show_curve' => 'yes' ),
			)
		);

		$curve_repeater = new \Elementor\Repeater();

		$curve_repeater->add_control(
			'point_label',
			array(
				'label'       => __( 'Label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Awareness',
				'description' => __( 'Usually matches the phase name above/below this point.', 'ux-portfolio-optimized' ),
			)
		);

		$curve_repeater->add_control(
			'point_score',
			array(
				'label'       => __( 'Score', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 5,
				'step'        => 1,
				'default'     => 3,
				'description' => __( '1 = lowest (frustrated/sad), 5 = highest (delighted/excited).', 'ux-portfolio-optimized' ),
			)
		);

		$curve_repeater->add_control(
			'point_emoji',
			array(
				'label'       => __( 'Emoji Override (optional)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Leave blank to auto-pick by score: 1=😞, 2=😟, 3=😐, 4=🙂, 5=😄', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'curve_points',
			array(
				'label'       => __( 'Curve Points', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $curve_repeater->get_controls(),
				'default'     => array(
					array(
						'point_label' => 'Awareness',
						'point_score' => 3,
					),
					array(
						'point_label' => 'Consideration',
						'point_score' => 2,
					),
					array(
						'point_label' => 'Decision',
						'point_score' => 4,
					),
					array(
						'point_label' => 'Retention',
						'point_score' => 5,
					),
				),
				'title_field' => '{{{ point_label }}} ({{{ point_score }}})',
				'condition'   => array( 'show_curve' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// STYLE
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'description_align',
			array(
				'label'     => __( 'Description Alignment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .journey-description' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'phase_bg_color',
			array(
				'label'     => __( 'Phase Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .journey-phase' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// EMOTION CURVE - STYLE
		$this->start_controls_section(
			'curve_style_section',
			array(
				'label'     => __( 'Emotion Curve', 'ux-portfolio-optimized' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_curve' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'curve_height',
			array(
				'label'       => __( 'Chart Height', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
				),
				'default'     => array( 'size' => 240 ),
				'selectors'   => array( '{{WRAPPER}} .journey-curve' => 'height: {{SIZE}}{{UNIT}}; width: auto; max-width: 100%;' ),
				'description' => __( 'Width auto-scales to keep the chart\'s aspect ratio so emojis stay round.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'curve_margin',
			array(
				'label'      => __( 'Margin', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 20,
					'right'    => 0,
					'bottom'   => 20,
					'left'     => 0,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .journey-curve' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'curve_line_color',
			array(
				'label'     => __( 'Line Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .journey-curve__line' => 'stroke: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'curve_line_weight',
			array(
				'label'     => __( 'Line Weight', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 8,
					),
				),
				'default'   => array( 'size' => 3 ),
				'selectors' => array( '{{WRAPPER}} .journey-curve__line' => 'stroke-width: {{SIZE}};' ),
			)
		);

		$this->add_control(
			'curve_smoothing',
			array(
				'label'   => __( 'Smoothing', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'smooth'   => __( 'Smooth Curve', 'ux-portfolio-optimized' ),
					'straight' => __( 'Straight Lines', 'ux-portfolio-optimized' ),
				),
				'default' => 'smooth',
			)
		);

		$this->add_control(
			'curve_dot_color',
			array(
				'label'     => __( 'Dot Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .journey-curve__dot' => 'fill: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'curve_dot_size',
			array(
				'label'   => __( 'Dot Size', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min' => 0,
						'max' => 14,
					),
				),
				'default' => array( 'size' => 6 ),
			)
		);

		$this->add_control(
			'curve_show_fill',
			array(
				'label'   => __( 'Show Area Fill', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'curve_fill_color',
			array(
				'label'     => __( 'Fill Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .journey-curve__area' => 'fill: {{VALUE}};' ),
				'condition' => array( 'curve_show_fill' => 'yes' ),
			)
		);

		$this->add_control(
			'curve_fill_opacity',
			array(
				'label'     => __( 'Fill Opacity', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'   => array( 'size' => 15 ),
				'selectors' => array( '{{WRAPPER}} .journey-curve__area' => 'opacity: calc({{SIZE}} / 100);' ),
				'condition' => array( 'curve_show_fill' => 'yes' ),
			)
		);

		$this->add_control(
			'curve_show_labels',
			array(
				'label'   => __( 'Show Point Labels', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'curve_show_scores',
			array(
				'label'   => __( 'Show Score Values', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'curve_show_emojis',
			array(
				'label'       => __( 'Show Emojis', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Auto-picks emoji by score (1=😞, 2=😟, 3=😐, 4=🙂, 5=😄) unless overridden per point.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'curve_emoji_size',
			array(
				'label'     => __( 'Emoji Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 48,
					),
				),
				'default'   => array( 'size' => 22 ),
				'condition' => array( 'curve_show_emojis' => 'yes' ),
			)
		);

		$this->add_control(
			'curve_show_grid',
			array(
				'label'   => __( 'Show Grid Lines', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'curve_label_color',
			array(
				'label'     => __( 'Label Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .journey-curve__label, {{WRAPPER}} .journey-curve__score' => 'fill: {{VALUE}};',
				),
				'condition' => array( 'curve_show_labels' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['phases'] ) ) {
			return;
		}
		?>
		
		<div class="ux-journey-map-wrapper">
			
			<?php if ( $s['show_title'] === 'yes' && ! empty( $s['title'] ) ) : ?>
				<h2 class="journey-title"><?php echo esc_html( $s['title'] ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $s['show_description'] === 'yes' && ! empty( $s['description'] ) ) : ?>
				<p class="journey-description"><?php echo esc_html( $s['description'] ); ?></p>
			<?php endif; ?>
			
			<?php
			if ( $s['show_persona'] === 'yes' && ! empty( $s['persona'] ) ) :
				$persona_link = $s['persona_link'] ?? array();
				$persona_url  = $persona_link['url'] ?? '';
				?>
				<div class="journey-persona">
					<strong>Persona:</strong>
					<?php
					if ( ! empty( $persona_url ) ) :
						$target    = ! empty( $persona_link['is_external'] ) ? ' target="_blank"' : '';
						$rel_parts = array();
						if ( ! empty( $persona_link['is_external'] ) ) {
							$rel_parts[] = 'noopener';
						}
						if ( ! empty( $persona_link['nofollow'] ) ) {
							$rel_parts[] = 'nofollow';
						}
						$rel = $rel_parts ? ' rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"' : '';
						?>
						<a class="journey-persona__link" href="<?php echo esc_url( $persona_url ); ?>"<?php echo $target . $rel; ?>>
							<?php echo esc_html( $s['persona'] ); ?>
						</a>
					<?php else : ?>
						<?php echo esc_html( $s['persona'] ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $s['show_goals'] === 'yes' && ! empty( $s['goals'] ) ) : ?>
				<div class="journey-goals">
					<strong>Goals:</strong>
					<ul>
						<?php
						$goals = explode( "\n", $s['goals'] );
						foreach ( $goals as $goal ) :
							$goal = trim( $goal );
							if ( ! empty( $goal ) ) :
								?>
							<li><?php echo esc_html( $goal ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
				</div>
			<?php endif; ?>
			
			<?php
			$show_curve     = ( $s['show_curve'] ?? '' ) === 'yes';
			$curve_position = $s['curve_position'] ?? 'below';
			if ( $show_curve && $curve_position === 'above' ) {
				$this->render_curve( $s );
			}
			?>

			<div class="ux-journey-map">
				<?php foreach ( $s['phases'] as $phase ) : ?>
					<div class="journey-phase">
						
						<h3 class="phase-name"><?php echo esc_html( $phase['phase_name'] ); ?></h3>
						
						<?php if ( ! empty( $phase['tasks'] ) ) : ?>
							<div class="journey-row">
								<div class="row-label">Tasks:</div>
								<ul class="row-content">
									<?php
									$tasks = explode( "\n", $phase['tasks'] );
									foreach ( $tasks as $task ) :
										$task = trim( $task );
										if ( ! empty( $task ) ) :
											?>
										<li><?php echo esc_html( $task ); ?></li>
											<?php
										endif;
									endforeach;
									?>
								</ul>
							</div>
						<?php endif; ?>
						
						<?php if ( ! empty( $phase['feelings'] ) ) : ?>
							<div class="journey-row">
								<div class="row-label">Feelings:</div>
								<ul class="row-content">
									<?php
									$feelings = explode( "\n", $phase['feelings'] );
									foreach ( $feelings as $feeling ) :
										$feeling = trim( $feeling );
										if ( ! empty( $feeling ) ) :
											?>
										<li><?php echo esc_html( $feeling ); ?></li>
											<?php
										endif;
									endforeach;
									?>
								</ul>
							</div>
						<?php endif; ?>
						
						<?php if ( $phase['show_pain_points'] === 'yes' && ! empty( $phase['pain_points'] ) ) : ?>
							<div class="journey-row">
								<div class="row-label">Pain Points:</div>
								<ul class="row-content">
									<?php
									$pains = explode( "\n", $phase['pain_points'] );
									foreach ( $pains as $pain ) :
										$pain = trim( $pain );
										if ( ! empty( $pain ) ) :
											?>
										<li><?php echo esc_html( $pain ); ?></li>
											<?php
										endif;
									endforeach;
									?>
								</ul>
							</div>
						<?php endif; ?>
						
						<?php if ( ! empty( $phase['opportunities'] ) ) : ?>
							<div class="journey-row">
								<div class="row-label">Improvement Opportunities:</div>
								<ul class="row-content">
									<?php
									$opps = explode( "\n", $phase['opportunities'] );
									foreach ( $opps as $opp ) :
										$opp = trim( $opp );
										if ( ! empty( $opp ) ) :
											?>
										<li><?php echo esc_html( $opp ); ?></li>
											<?php
										endif;
									endforeach;
									?>
								</ul>
							</div>
						<?php endif; ?>
						
					</div>
				<?php endforeach; ?>
			</div>

			<?php
			if ( $show_curve && $curve_position === 'below' ) {
				$this->render_curve( $s );
			}
			?>
			
		</div>
		
		<?php
	}

	private function render_curve( $s ) {
		$points = $s['curve_points'] ?? array();
		if ( empty( $points ) ) {
			return;
		}

		$smoothing   = $s['curve_smoothing'] ?? 'smooth';
		$show_fill   = ( $s['curve_show_fill'] ?? 'yes' ) === 'yes';
		$show_labels = ( $s['curve_show_labels'] ?? 'yes' ) === 'yes';
		$show_scores = ( $s['curve_show_scores'] ?? '' ) === 'yes';
		$show_grid   = ( $s['curve_show_grid'] ?? '' ) === 'yes';
		$show_emojis = ( $s['curve_show_emojis'] ?? '' ) === 'yes';
		$emoji_size  = $s['curve_emoji_size']['size'] ?? 22;
		$dot_size    = $s['curve_dot_size']['size'] ?? 6;

		$emoji_map = array(
			1 => '😞',
			2 => '😟',
			3 => '😐',
			4 => '🙂',
			5 => '😄',
		);

		$max_score = 5;
		$min_score = 1;
		$count     = count( $points );
		if ( $count < 2 ) {
			return;
		}

		// SVG coordinate space — use a normalized viewBox.
		// Wider side padding so the first/last labels have room to extend.
		$vb_width     = 1000;
		$vb_height    = 400;
		$chart_top    = 30;
		$chart_bottom = 340;
		$chart_left   = 120;
		$chart_right  = $vb_width - 120;
		$chart_w      = $chart_right - $chart_left;
		$chart_h      = $chart_bottom - $chart_top;

		// Compute (x, y) for each point
		$coords = array();
		foreach ( $points as $i => $pt ) {
			$score = max( $min_score, min( $max_score, (int) ( $pt['point_score'] ?? 3 ) ) );
			$x     = $chart_left + ( $chart_w * $i / max( 1, $count - 1 ) );
			// Higher score = higher position = lower y
			$y              = $chart_top + $chart_h * ( (float) ( $max_score - $score ) / max( 1, $max_score - $min_score ) );
			$emoji_override = trim( $pt['point_emoji'] ?? '' );
			$emoji          = $emoji_override !== '' ? $emoji_override : ( $emoji_map[ $score ] ?? '' );
			$coords[]       = array(
				'x'     => $x,
				'y'     => $y,
				'label' => $pt['point_label'] ?? '',
				'score' => $score,
				'emoji' => $emoji,
			);
		}

		// Build path d= attribute
		if ( $smoothing === 'smooth' && $count >= 2 ) {
			$d = 'M ' . round( $coords[0]['x'], 2 ) . ' ' . round( $coords[0]['y'], 2 );
			for ( $i = 1; $i < $count; $i++ ) {
				$p0 = $coords[ $i - 1 ];
				$p1 = $coords[ $i ];
				// Simple cubic bezier with horizontal control points (smooth horizontal curve)
				$cx1 = $p0['x'] + ( $p1['x'] - $p0['x'] ) / 2;
				$cy1 = $p0['y'];
				$cx2 = $p0['x'] + ( $p1['x'] - $p0['x'] ) / 2;
				$cy2 = $p1['y'];
				$d  .= ' C ' . round( $cx1, 2 ) . ' ' . round( $cy1, 2 )
					. ', ' . round( $cx2, 2 ) . ' ' . round( $cy2, 2 )
					. ', ' . round( $p1['x'], 2 ) . ' ' . round( $p1['y'], 2 );
			}
		} else {
			$segs = array();
			foreach ( $coords as $i => $c ) {
				$segs[] = ( $i === 0 ? 'M' : 'L' ) . ' ' . round( $c['x'], 2 ) . ' ' . round( $c['y'], 2 );
			}
			$d = implode( ' ', $segs );
		}

		// Area path closes the line down to the baseline and back
		$area_d = $d
			. ' L ' . round( $coords[ $count - 1 ]['x'], 2 ) . ' ' . $chart_bottom
			. ' L ' . round( $coords[0]['x'], 2 ) . ' ' . $chart_bottom
			. ' Z';

		?>
		<div class="journey-curve-wrap">
			<svg class="journey-curve" viewBox="0 0 <?php echo $vb_width; ?> <?php echo $vb_height; ?>" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

				<?php if ( $show_grid ) : ?>
					<g class="journey-curve__grid">
						<?php
						for ( $score = $min_score; $score <= $max_score; $score++ ) :
							$gy = $chart_top + $chart_h * ( ( $max_score - $score ) / max( 1, $max_score - $min_score ) );
							?>
							<line x1="<?php echo $chart_left; ?>" y1="<?php echo round( $gy, 2 ); ?>"
									x2="<?php echo $chart_right; ?>" y2="<?php echo round( $gy, 2 ); ?>"
									stroke="currentColor" stroke-opacity="0.1" stroke-width="1"/>
						<?php endfor; ?>
					</g>
				<?php endif; ?>

				<?php if ( $show_fill ) : ?>
					<path class="journey-curve__area" d="<?php echo esc_attr( $area_d ); ?>" fill="currentColor"/>
				<?php endif; ?>

				<path class="journey-curve__line" d="<?php echo esc_attr( $d ); ?>"
						fill="none" stroke="currentColor" stroke-linejoin="round" stroke-linecap="round"/>

				<?php foreach ( $coords as $c ) : ?>
					<circle class="journey-curve__dot"
							cx="<?php echo round( $c['x'], 2 ); ?>"
							cy="<?php echo round( $c['y'], 2 ); ?>"
							r="<?php echo (float) $dot_size; ?>"
							fill="currentColor"/>

					<?php if ( $show_scores ) : ?>
						<text class="journey-curve__score"
								x="<?php echo round( $c['x'], 2 ); ?>"
								y="<?php echo round( $c['y'] - $dot_size - 8, 2 ); ?>"
								text-anchor="middle"
								font-size="14"
								font-weight="600"
								fill="currentColor"><?php echo (int) $c['score']; ?></text>
					<?php endif; ?>

					<?php
					if ( $show_emojis && ! empty( $c['emoji'] ) ) :
						// Position emoji so its bottom sits a consistent gap above the dot.
						// Add the emoji's half-height since dominant-baseline=middle anchors at center.
						$emoji_half   = $emoji_size / 2;
						$emoji_offset = $dot_size + $emoji_half + ( $show_scores ? 22 : 8 );
						?>
						<text class="journey-curve__emoji"
								x="<?php echo round( $c['x'], 2 ); ?>"
								y="<?php echo round( $c['y'] - $emoji_offset, 2 ); ?>"
								text-anchor="middle"
								dominant-baseline="middle"
								font-size="<?php echo (float) $emoji_size; ?>"><?php echo esc_html( $c['emoji'] ); ?></text>
					<?php endif; ?>

					<?php if ( $show_labels && ! empty( $c['label'] ) ) : ?>
						<text class="journey-curve__label"
								x="<?php echo round( $c['x'], 2 ); ?>"
								y="<?php echo $chart_bottom + 24; ?>"
								text-anchor="middle"
								font-size="14"
								fill="currentColor"><?php echo esc_html( $c['label'] ); ?></text>
					<?php endif; ?>
				<?php endforeach; ?>
			</svg>
		</div>
		<?php
	}
}
