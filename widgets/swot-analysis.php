<?php
/**
 * SWOT Analysis Widget
 *
 * Classic 2x2 SWOT grid — Strengths, Weaknesses, Opportunities, Threats —
 * with an optional Helpful/Harmful x Internal/External axis framing.
 * Each quadrant takes an icon, a title, and a list of points (one per line).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Swot_Analysis_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-swot-analysis'; }
	public function get_title() {
		return __( 'SWOT Analysis', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-table'; }
	public function get_keywords() {
		return array( 'swot', 'strengths', 'weaknesses', 'opportunities', 'threats', 'analysis', 'strategy', 'ux' );
	}

	/** The four quadrants: key => [label, default color, default icon] */
	private function quadrant_defs() {
		return array(
			'strengths'     => array( __( 'Strengths', 'ux-portfolio-optimized' ), '#10b981', 'fas fa-check-circle' ),
			'weaknesses'    => array( __( 'Weaknesses', 'ux-portfolio-optimized' ), '#ef4444', 'fas fa-exclamation-circle' ),
			'opportunities' => array( __( 'Opportunities', 'ux-portfolio-optimized' ), '#3b82f6', 'fas fa-lightbulb' ),
			'threats'       => array( __( 'Threats', 'ux-portfolio-optimized' ), '#f59e0b', 'fas fa-bolt' ),
		);
	}

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — SWOT sets
		$this->start_controls_section(
			'sets_section',
			array(
				'label' => __( 'SWOT Sets', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'mode',
			array(
				'label'       => __( 'Mode', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'single',
				'options'     => array(
					'single'   => __( 'Single SWOT', 'ux-portfolio-optimized' ),
					'multiple' => __( 'Multiple SWOTs (tabbed)', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'Multiple lets you add one SWOT per competitor plus a consolidated one, switchable by tabs. Points are entered below; titles, icons and colors are shared across all sets.', 'ux-portfolio-optimized' ),
			)
		);

		$set_repeater = new \Elementor\Repeater();

		$set_repeater->add_control(
			'set_name',
			array(
				'label'       => __( 'Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Competitor', 'ux-portfolio-optimized' ),
				'label_block' => true,
				'description' => __( 'Shown as the tab label — e.g. a competitor name, or "Consolidated".', 'ux-portfolio-optimized' ),
			)
		);

		foreach ( $this->quadrant_defs() as $key => $def ) {
			$set_repeater->add_control(
				'set_' . $key,
				array(
					'label'       => $def[0],
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'rows'        => 5,
					'placeholder' => __( 'One point per line', 'ux-portfolio-optimized' ),
				)
			);
		}

		$this->add_control(
			'swots',
			array(
				'label'       => __( 'SWOTs', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $set_repeater->get_controls(),
				'default'     => array(
					array( 'set_name' => __( 'Consolidated', 'ux-portfolio-optimized' ) ),
				),
				'title_field' => '{{{ set_name }}}',
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
				'description' => __( 'Turn off to stack every SWOT down the page instead of tabbing between them.', 'ux-portfolio-optimized' ),
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
				'description' => __( 'Name of the SWOT to open first. Leave blank for the first one.', 'ux-portfolio-optimized' ),
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
						'value'   => $def[2],
						'library' => 'fa-solid',
					),
				)
			);

			$this->add_control(
				$key . '_items',
				array(
					'label'       => __( 'Points', 'ux-portfolio-optimized' ),
					'type'        => \Elementor\Controls_Manager::TEXTAREA,
					'rows'        => 6,
					'default'     => '',
					'placeholder' => __( 'One point per line', 'ux-portfolio-optimized' ),
					'description' => __( 'Enter one point per line.', 'ux-portfolio-optimized' ),
					'condition'   => array( 'mode' => 'single' ),
				)
			);

			$this->add_control(
				$key . '_color',
				array(
					'label'   => __( 'Accent Color', 'ux-portfolio-optimized' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => $def[1],
				)
			);

			$this->end_controls_section();
		}

		// CONTENT — Axis framing
		$this->start_controls_section(
			'axis_section',
			array(
				'label' => __( 'Axis Labels', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_axis',
			array(
				'label'       => __( 'Show Axis Labels', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Adds the classic SWOT framing: Helpful/Harmful across the top, Internal/External down the side.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'axis_helpful',
			array(
				'label'     => __( 'Top Left Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Helpful', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_axis' => 'yes' ),
			)
		);

		$this->add_control(
			'axis_harmful',
			array(
				'label'     => __( 'Top Right Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Harmful', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_axis' => 'yes' ),
			)
		);

		$this->add_control(
			'axis_internal',
			array(
				'label'     => __( 'Top Row Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Internal', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_axis' => 'yes' ),
			)
		);

		$this->add_control(
			'axis_external',
			array(
				'label'     => __( 'Bottom Row Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'External', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_axis' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Cards
		$this->start_controls_section(
			'style_cards',
			array(
				'label' => __( 'Cards', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'accent_style',
			array(
				'label'   => __( 'Accent Style', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'header',
				'options' => array(
					'header' => __( 'Solid header bar', 'ux-portfolio-optimized' ),
					'top'    => __( 'Top border', 'ux-portfolio-optimized' ),
					'left'   => __( 'Left border', 'ux-portfolio-optimized' ),
					'tint'   => __( 'Tinted card', 'ux-portfolio-optimized' ),
				),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'Card Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ux-swot__quadrant' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border_color',
			array(
				'label'     => __( 'Card Border', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e5e7eb',
				'selectors' => array(
					'{{WRAPPER}} .ux-swot__quadrant' => 'border-color: {{VALUE}};',
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
				'default'   => array( 'size' => 10 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-swot__quadrant' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'     => __( 'Card Padding', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 8,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 20 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-swot' => '--swot-pad: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 18 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-swot' => '--swot-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

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
				'description' => __( 'Leave empty to use each quadrant\'s accent color.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .ux-swot' => '--swot-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'title_typography', __( 'Title Typography', 'ux-portfolio-optimized' ), '.ux-swot__title' );

		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 48,
					),
				),
				'default'   => array( 'size' => 20 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-swot__icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-swot__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'     => __( 'Point Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#374151',
				'selectors' => array(
					'{{WRAPPER}} .ux-swot__item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'item_typography', __( 'Point Typography', 'ux-portfolio-optimized' ), '.ux-swot__item' );

		$this->add_control(
			'axis_color',
			array(
				'label'     => __( 'Axis Label Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6b7280',
				'condition' => array( 'show_axis' => 'yes' ),
				'selectors' => array(
					'{{WRAPPER}} .ux-swot__axis' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function render_quadrant( $s, $key, $def, $raw ) {
		$title = trim( $s[ $key . '_title' ] ?? $def[0] );
		$color = trim( $s[ $key . '_color' ] ?? '' );
		if ( $color === '' ) {
			$color = $def[1];
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
		<div class="ux-swot__quadrant quadrant-<?php echo esc_attr( $key ); ?>" style="--swot-color: <?php echo ux_portfolio_esc_css( $color ); ?>;">
			<?php if ( $title !== '' || $has_icon ) : ?>
				<div class="ux-swot__head">
					<?php if ( $has_icon ) : ?>
						<span class="ux-swot__icon" aria-hidden="true">
						<?php
							\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
						?>
						</span>
					<?php endif; ?>
					<?php if ( $title !== '' ) : ?>
						<h4 class="ux-swot__title"><?php echo esc_html( $title ); ?></h4>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $items ) ) : ?>
				<ul class="ux-swot__items">
					<?php foreach ( $items as $item ) : ?>
						<li class="ux-swot__item"><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$s         = $this->get_settings_for_display();
		$defs      = $this->quadrant_defs();
		$show_axis = ( $s['show_axis'] ?? '' ) === 'yes';
		$mode      = ( $s['mode'] ?? 'single' ) === 'multiple' ? 'multiple' : 'single';

		// Build the list of SWOT sets.
		$sets = array();
		if ( $mode === 'multiple' && ! empty( $s['swots'] ) && is_array( $s['swots'] ) ) {
			foreach ( $s['swots'] as $sw ) {
				$set = array( 'name' => trim( $sw['set_name'] ?? '' ) );
				foreach ( $defs as $key => $def ) {
					$set[ $key ] = $sw[ 'set_' . $key ] ?? '';
				}
				$sets[] = $set;
			}
		}
		if ( empty( $sets ) ) {
			$set = array( 'name' => '' );
			foreach ( $defs as $key => $def ) {
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
				if ( strcasecmp( $set['name'], $default_tab ) === 0 ) {
					$active = $i;
					break;
				}
			}
		}

		$classes = 'ux-swot accent-' . esc_attr( $s['accent_style'] ?? 'header' );
		if ( $show_axis ) {
			$classes .= ' has-axis';
		}
		?>
		<div class="ux-swot-wrapper" data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php if ( $tabbed ) : ?>
				<div class="ux-swot-tabs" role="tablist">
					<?php
					foreach ( $sets as $i => $set ) :
						$is_on = ( $i === $active );
						$label = $set['name'] !== '' ? $set['name'] : sprintf( /* translators: %d: SWOT number */ __( 'SWOT %d', 'ux-portfolio-optimized' ), $i + 1 );
						?>
						<button type="button"
								class="ux-swot-tab<?php echo $is_on ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $is_on ? 'true' : 'false'; ?>"
								data-swot-tab="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $sets as $i => $set ) :
				$hidden = $tabbed && ( $i !== $active );
				?>
				<div class="ux-swot-panel<?php echo $hidden ? ' is-hidden' : ''; ?>" data-swot-panel="<?php echo esc_attr( $i ); ?>">
					<?php if ( ! $tabbed && $mode === 'multiple' && $set['name'] !== '' ) : ?>
						<h3 class="ux-swot-panel__name"><?php echo esc_html( $set['name'] ); ?></h3>
					<?php endif; ?>

					<div class="<?php echo esc_attr( $classes ); ?>">
						<?php if ( $show_axis ) : ?>
							<div class="ux-swot__corner"></div>
							<div class="ux-swot__axis ux-swot__axis--col"><?php echo esc_html( $s['axis_helpful'] ?? '' ); ?></div>
							<div class="ux-swot__axis ux-swot__axis--col"><?php echo esc_html( $s['axis_harmful'] ?? '' ); ?></div>
							<div class="ux-swot__axis ux-swot__axis--row"><span><?php echo esc_html( $s['axis_internal'] ?? '' ); ?></span></div>
						<?php endif; ?>

						<?php $this->render_quadrant( $s, 'strengths', $defs['strengths'], $set['strengths'] ); ?>
						<?php $this->render_quadrant( $s, 'weaknesses', $defs['weaknesses'], $set['weaknesses'] ); ?>

						<?php if ( $show_axis ) : ?>
							<div class="ux-swot__axis ux-swot__axis--row"><span><?php echo esc_html( $s['axis_external'] ?? '' ); ?></span></div>
						<?php endif; ?>

						<?php $this->render_quadrant( $s, 'opportunities', $defs['opportunities'], $set['opportunities'] ); ?>
						<?php $this->render_quadrant( $s, 'threats', $defs['threats'], $set['threats'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
