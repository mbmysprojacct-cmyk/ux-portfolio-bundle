<?php
/**
 * Benefits Ladder Widget
 *
 * Climbs from a concrete feature at the bottom up through functional and
 * emotional benefits to a core value at the top — the laddering method for
 * showing WHY a feature matters to a person. Each rung is editable; you can
 * run several ladders side by side (one per feature) or stack them.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Benefits_Ladder_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-benefits-ladder'; }
	public function get_title() {
		return __( 'Benefits Ladder', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-time-line'; }
	public function get_keywords() {
		return array( 'benefits', 'ladder', 'laddering', 'feature', 'value', 'emotional', 'ux' );
	}

	/** Default rung palette, bottom (feature) to top (core value). */
	private function rung_defs() {
		return array(
			array( __( 'Feature', 'ux-portfolio-optimized' ), '#6b7280' ),
			array( __( 'Functional Benefit', 'ux-portfolio-optimized' ), '#3b82f6' ),
			array( __( 'Emotional Benefit', 'ux-portfolio-optimized' ), '#8b5cf6' ),
			array( __( 'Core Value', 'ux-portfolio-optimized' ), '#ef4a8a' ),
		);
	}

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Ladders
		$this->start_controls_section(
			'ladders_section',
			array(
				'label' => __( 'Ladders', 'ux-portfolio-optimized' ),
			)
		);

		$rung_rep = new \Elementor\Repeater();

		$rung_rep->add_control(
			'ladder_name',
			array(
				'label'       => __( 'Ladder Label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => __( 'e.g. Musical logo', 'ux-portfolio-optimized' ),
				'description' => __( 'Optional heading above this ladder (useful with multiple ladders).', 'ux-portfolio-optimized' ),
			)
		);

		$rung_rep->add_control(
			'feature',
			array(
				'label'   => __( 'Feature (bottom rung)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => 'Timeless logo with musical cues',
			)
		);

		$rung_rep->add_control(
			'functional',
			array(
				'label'   => __( 'Functional Benefit', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => 'Recognisable across every touchpoint',
			)
		);

		$rung_rep->add_control(
			'emotional',
			array(
				'label'   => __( 'Emotional Benefit', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => 'Feels grounded and trustworthy, not trendy',
			)
		);

		$rung_rep->add_control(
			'core',
			array(
				'label'   => __( 'Core Value (top rung)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => 'Belonging to something with lasting taste',
			)
		);

		$this->add_control(
			'ladders',
			array(
				'label'       => __( 'Ladders', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $rung_rep->get_controls(),
				'default'     => array(
					array( 'ladder_name' => '' ),
				),
				'title_field' => '{{{ ladder_name || feature }}}',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'       => __( 'Columns', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '1',
				'options'     => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
				),
				'selectors'   => array(
					'{{WRAPPER}} .ux-ladder-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
				'description' => __( 'Show multiple ladders side by side.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// CONTENT — Rung labels & arrow
		$this->start_controls_section(
			'labels_section',
			array(
				'label' => __( 'Rung Labels', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_rung_labels',
			array(
				'label'       => __( 'Show Rung Labels', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'The small "Feature / Functional / Emotional / Core Value" tags on each rung.', 'ux-portfolio-optimized' ),
			)
		);

		$defs = $this->rung_defs();
		$keys = array( 'feature', 'functional', 'emotional', 'core' );
		foreach ( $keys as $i => $k ) {
			$this->add_control(
				$k . '_label',
				array(
					'label'     => sprintf( /* translators: %s: rung name */ __( '%s Label', 'ux-portfolio-optimized' ), $defs[ $i ][0] ),
					'type'      => \Elementor\Controls_Manager::TEXT,
					'default'   => $defs[ $i ][0],
					'condition' => array( 'show_rung_labels' => 'yes' ),
				)
			);
		}

		$this->add_control(
			'show_arrow',
			array(
				'label'       => __( 'Show "Why it matters" Arrow', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'separator'   => 'before',
				'description' => __( 'A vertical cue showing the climb from feature to value.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'arrow_text',
			array(
				'label'     => __( 'Arrow Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Why it matters', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_arrow' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Rungs
		$this->start_controls_section(
			'style_rungs',
			array(
				'label' => __( 'Rungs', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$defs = $this->rung_defs();
		$keys = array( 'feature', 'functional', 'emotional', 'core' );
		foreach ( $keys as $i => $k ) {
			$this->add_control(
				$k . '_color',
				array(
					'label'     => sprintf( /* translators: %s: rung name */ __( '%s Color', 'ux-portfolio-optimized' ), $defs[ $i ][0] ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => $defs[ $i ][1],
					'selectors' => array(
						'{{WRAPPER}} .ux-rung-' . $k => '--rung-color: {{VALUE}};',
					),
				)
			);
		}

		$this->add_control(
			'rung_style',
			array(
				'label'     => __( 'Rung Style', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'tint',
				'separator' => 'before',
				'options'   => array(
					'tint'   => __( 'Light tint + colored edge', 'ux-portfolio-optimized' ),
					'solid'  => __( 'Solid color', 'ux-portfolio-optimized' ),
					'border' => __( 'Outline only', 'ux-portfolio-optimized' ),
				),
			)
		);

		$this->add_control(
			'rung_radius',
			array(
				'label'     => __( 'Corner Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'default'   => array( 'size' => 10 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-rung' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rung_gap',
			array(
				'label'     => __( 'Rung Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-ladder' => '--rung-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rung_text_color',
			array(
				'label'       => __( 'Rung Text', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Leave empty for automatic (dark on tints, white on solids).', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .ux-rung__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'rung_typography', __( 'Rung Typography', 'ux-portfolio-optimized' ), '.ux-rung__text' );

		$this->add_control(
			'ladder_name_color',
			array(
				'label'     => __( 'Ladder Label Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ux-ladder__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'ladder_name_typography', __( 'Ladder Label Typography', 'ux-portfolio-optimized' ), '.ux-ladder__name' );

		$this->end_controls_section();
	}

	/** Renders one rung. */
	private function render_rung( $key, $label, $text, $show_labels ) {
		$text = trim( (string) $text );
		if ( $text === '' ) {
			return;
		}
		?>
		<div class="ux-rung ux-rung-<?php echo esc_attr( $key ); ?>">
			<?php if ( $show_labels && $label !== '' ) : ?>
				<span class="ux-rung__label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
			<span class="ux-rung__text"><?php echo esc_html( $text ); ?></span>
		</div>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['ladders'] ) ) {
			return;
		}

		$show_labels = ( $s['show_rung_labels'] ?? 'yes' ) === 'yes';
		$show_arrow  = ( $s['show_arrow'] ?? 'yes' ) === 'yes';
		$arrow_text  = $s['arrow_text'] ?? '';
		$style       = $s['rung_style'] ?? 'tint';

		$labels = array(
			'feature'    => $s['feature_label'] ?? __( 'Feature', 'ux-portfolio-optimized' ),
			'functional' => $s['functional_label'] ?? __( 'Functional Benefit', 'ux-portfolio-optimized' ),
			'emotional'  => $s['emotional_label'] ?? __( 'Emotional Benefit', 'ux-portfolio-optimized' ),
			'core'       => $s['core_label'] ?? __( 'Core Value', 'ux-portfolio-optimized' ),
		);
		?>

		<div class="ux-ladder-wrapper ux-ladder-style-<?php echo esc_attr( $style ); ?>">
			<?php $this->render_standard_header(); ?>

			<div class="ux-ladder-grid">
				<?php
				foreach ( $s['ladders'] as $ladder ) :
					$name = trim( $ladder['ladder_name'] ?? '' );
					?>
					<div class="ux-ladder-col">
						<?php if ( $name !== '' ) : ?>
							<h3 class="ux-ladder__name"><?php echo esc_html( $name ); ?></h3>
						<?php endif; ?>

						<div class="ux-ladder">
							<?php if ( $show_arrow ) : ?>
								<div class="ux-ladder__arrow" aria-hidden="true">
									<span class="ux-ladder__arrow-line"></span>
									<?php if ( trim( $arrow_text ) !== '' ) : ?>
										<span class="ux-ladder__arrow-text"><?php echo esc_html( $arrow_text ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<div class="ux-ladder__rungs">
								<?php
								// Top rung first (visually the top of the climb).
								$this->render_rung( 'core', $labels['core'], $ladder['core'] ?? '', $show_labels );
								$this->render_rung( 'emotional', $labels['emotional'], $ladder['emotional'] ?? '', $show_labels );
								$this->render_rung( 'functional', $labels['functional'], $ladder['functional'] ?? '', $show_labels );
								$this->render_rung( 'feature', $labels['feature'], $ladder['feature'] ?? '', $show_labels );
								?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
	}
}
