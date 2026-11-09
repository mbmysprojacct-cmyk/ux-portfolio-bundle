<?php
/**
 * Visual Competitive Analysis Widget
 * Table comparing competitor brands across visual identity dimensions:
 * wordmark image, icon image, brand color swatches, CTA color swatches, typography.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Visual_Competitive_Analysis_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-visual-competitive-analysis'; }
	public function get_title() {
		return __( 'Visual Competitive Analysis', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-table'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Brands
		$this->start_controls_section(
			'brands_section',
			array(
				'label' => __( 'Brands', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'Brand Name', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Brand',
			)
		);

		$repeater->add_control(
			'wordmark',
			array(
				'label'       => __( 'Wordmark Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'SVG or PNG. Renders at up to ~200px wide.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'       => __( 'Icon Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'SVG or PNG. Renders at ~40px.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'icons_extra',
			array(
				'label'       => __( 'Additional Icons', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::GALLERY,
				'description' => __( 'Optional. For brands with more than one icon/mark — add the rest here and they show alongside the Icon Image above.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'brand_colors',
			array(
				'label'       => __( 'Brand Colors', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '#3CB371, #cccccc',
				'description' => __( 'Comma-separated hex codes. With or without #.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'cta_colors',
			array(
				'label'       => __( 'CTA Colors', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '#3CB371',
				'description' => __( 'Comma-separated hex codes.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'typography',
			array(
				'label'       => __( 'Typography', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'placeholder' => 'Trip Sans (Updated 2025)',
			)
		);

		$this->add_control(
			'brands',
			array(
				'label'       => __( 'Brands', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'name' => 'Brand 1' ),
					array( 'name' => 'Brand 2' ),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		// CONTENT — Collapse
		$this->start_controls_section(
			'collapse_section',
			array(
				'label' => __( 'Collapse', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'collapsible',
			array(
				'label'        => __( 'Collapse long lists', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Show only the first few brands, with a button to show the rest.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'visible_count',
			array(
				'label'     => __( 'Brands to show first', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'step'      => 1,
				'default'   => 4,
				'condition' => array( 'collapsible' => 'yes' ),
			)
		);

		$this->add_control(
			'more_label',
			array(
				'label'       => __( 'Show more label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Show all {total} brands', 'ux-portfolio-optimized' ),
				'description' => __( '{total} = all brands, {hidden} = how many are hidden.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'collapsible' => 'yes' ),
			)
		);

		$this->add_control(
			'less_label',
			array(
				'label'     => __( 'Show fewer label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Show fewer', 'ux-portfolio-optimized' ),
				'condition' => array( 'collapsible' => 'yes' ),
			)
		);

		$this->end_controls_section();

		// CONTENT — Columns
		$this->start_controls_section(
			'columns_section',
			array(
				'label' => __( 'Columns', 'ux-portfolio-optimized' ),
			)
		);

		$columns = array(
			'brand'        => array( 'Brand', 'yes' ),
			'wordmark'     => array( 'Wordmark', 'yes' ),
			'icon'         => array( 'Icon', 'yes' ),
			'brand_colors' => array( 'Brand Colors', 'yes' ),
			'cta_colors'   => array( 'CTA Colors', 'yes' ),
			'typography'   => array( 'Typography', 'yes' ),
		);

		foreach ( $columns as $key => $info ) {
			list($default_label, $default_show) = $info;

			$this->add_control(
				'show_' . $key,
				array(
					'label'   => sprintf( __( 'Show "%s"', 'ux-portfolio-optimized' ), $default_label ),
					'type'    => \Elementor\Controls_Manager::SWITCHER,
					'default' => $default_show,
				)
			);

			$this->add_control(
				'label_' . $key,
				array(
					'label'     => sprintf( __( '"%s" Label', 'ux-portfolio-optimized' ), $default_label ),
					'type'      => \Elementor\Controls_Manager::TEXT,
					'default'   => $default_label,
					'condition' => array( 'show_' . $key => 'yes' ),
				)
			);
		}

		$this->end_controls_section();

		// STYLE — Table
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_bg',
			array(
				'label'     => __( 'Header Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8f9fa',
				'selectors' => array( '{{WRAPPER}} .vca-table thead' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e2e8f0',
				'selectors' => array(
					'{{WRAPPER}} .vca-table th, {{WRAPPER}} .vca-table td' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'swatch_size',
			array(
				'label'     => __( 'Color Swatch Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 16,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 30 ),
				'selectors' => array(
					'{{WRAPPER}} .vca-color-swatch' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_description_alignment();

		$this->end_controls_section();
	}

	/**
	 * Parse a comma- or whitespace-separated string of hex colors.
	 * Accepts "#3CB371", "3CB371", "#fff", "fff" — 3/4/6/8 hex digits, with or without #.
	 * Returns array of normalized "#xxxxxx"-form strings.
	 */
	private function parse_colors( $input ) {
		$input = trim( (string) $input );
		if ( $input === '' ) {
			return array();
		}
		$parts = preg_split( '/[,\s]+/', $input );
		$out   = array();
		foreach ( $parts as $p ) {
			$p = trim( $p );
			if ( $p === '' ) {
				continue;
			}
			if ( $p[0] !== '#' ) {
				$p = '#' . $p;
			}
			if ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $p ) ) {
				$out[] = $p;
			}
		}
		return $out;
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['brands'] ) ) {
			return;
		}

		$cols     = array();
		$col_keys = array( 'brand', 'wordmark', 'icon', 'brand_colors', 'cta_colors', 'typography' );
		foreach ( $col_keys as $k ) {
			if ( ( $s[ 'show_' . $k ] ?? 'yes' ) === 'yes' ) {
				$cols[ $k ] = $s[ 'label_' . $k ] ?? ucfirst( str_replace( '_', ' ', $k ) );
			}
		}

		if ( empty( $cols ) ) {
			return;
		}

		$total    = count( $s['brands'] );
		$limit    = max( 1, (int) ( $s['visible_count'] ?? 4 ) );
		$collapse = ( $s['collapsible'] ?? '' ) === 'yes' && $total > $limit;
		$body_id  = 'vca-body-' . $this->get_id();
		$more     = str_replace( array( '{total}', '{hidden}' ), array( $total, $total - $limit ), (string) ( $s['more_label'] ?? '' ) );
		?>

		<div class="vca-wrapper">
			<?php $this->render_standard_header(); ?>

			<div class="vca-scroll">
				<table class="vca-table">
					<thead>
						<tr>
							<?php foreach ( $cols as $k => $label ) : ?>
								<th class="vca-col-<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody id="<?php echo esc_attr( $body_id ); ?>">
						<?php
						foreach ( $s['brands'] as $i => $brand ) :
							$extra_row = $collapse && $i >= $limit;
							?>
							<tr<?php echo $extra_row ? ' class="vca-row-more" hidden' : ''; ?>>
								<?php foreach ( $cols as $k => $_ ) : ?>
									<td class="vca-col-<?php echo esc_attr( $k ); ?>"><?php $this->render_cell( $brand, $k ); ?></td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php if ( $collapse ) : ?>
				<button type="button" class="vca-toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $body_id ); ?>"
						data-more="<?php echo esc_attr( $more ); ?>" data-less="<?php echo esc_attr( $s['less_label'] ?? '' ); ?>"><?php echo esc_html( $more ); ?></button>
			<?php endif; ?>
		</div>

		<?php
	}

	private function render_cell( $brand, $key ) {
		switch ( $key ) {
			case 'brand':
				echo esc_html( $brand['name'] ?? '' );
				break;

			case 'wordmark':
				$img = $brand['wordmark'] ?? null;
				if ( empty( $img ) ) {
					return;
				}
				$url = $img['url'] ?? '';
				if ( empty( $url ) && ! empty( $img['id'] ) ) {
					$url = wp_get_attachment_url( $img['id'] );
				}
				if ( empty( $url ) ) {
					return;
				}
				$alt = ( $brand['name'] ?? '' ) . ' wordmark';
				?>
				<img src="<?php echo esc_url( $url ); ?>" class="vca-wordmark" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
				<?php
				break;

			case 'icon':
				$urls = array();

				// Primary icon first (keeps existing single-icon setups working).
				$img = $brand['icon'] ?? null;
				if ( ! empty( $img ) ) {
					$u = $img['url'] ?? '';
					if ( empty( $u ) && ! empty( $img['id'] ) ) {
						$u = wp_get_attachment_url( $img['id'] );
					}
					if ( ! empty( $u ) ) {
						$urls[] = $u;
					}
				}

				// Any additional icons from the gallery.
				$extra = $brand['icons_extra'] ?? array();
				if ( ! empty( $extra ) && is_array( $extra ) ) {
					foreach ( $extra as $it ) {
						$u = $it['url'] ?? '';
						if ( empty( $u ) && ! empty( $it['id'] ) ) {
							$u = wp_get_attachment_url( $it['id'] );
						}
						if ( ! empty( $u ) ) {
							$urls[] = $u;
						}
					}
				}

				if ( empty( $urls ) ) {
					return;
				}
				$alt = ( $brand['name'] ?? '' ) . ' icon';
				?>
				<div class="vca-icons">
					<?php foreach ( $urls as $u ) : ?>
						<img src="<?php echo esc_url( $u ); ?>" class="vca-icon" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
					<?php endforeach; ?>
				</div>
				<?php
				break;

			case 'brand_colors':
			case 'cta_colors':
				$colors = $this->parse_colors( $brand[ $key ] ?? '' );
				if ( empty( $colors ) ) {
					return;
				}
				?>
				<div class="vca-colors">
					<?php foreach ( $colors as $c ) : ?>
						<span class="vca-color-swatch" style="background: <?php echo esc_attr( $c ); ?>;" title="<?php echo esc_attr( $c ); ?>"></span>
					<?php endforeach; ?>
				</div>
				<?php
				break;

			case 'typography':
				echo nl2br( esc_html( $brand['typography'] ?? '' ) );
				break;
		}
	}
}
