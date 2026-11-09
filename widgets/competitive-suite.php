<?php
/**
 * Competitive Suite Widget
 *
 * Consolidates the competitive-analysis widgets into one, chosen via an
 * "Analysis Type" selector so fewer widgets register. Modes are added
 * incrementally; each preserves the full behavior of the widget it replaces.
 *
 * Modes: diff (them vs you) [+ matrix, visual added next]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Competitive_Suite_Widget extends UX_Base_Widget {

	use UX_Tabbed_Trait;

	public function get_name() {
		return 'ux-competitive-suite'; }
	public function get_title() {
		return __( 'Competitive Suite', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-check-circle'; }
	public function get_keywords() {
		return array( 'competitive', 'competitor', 'comparison', 'differentiation', 'matrix', 'brands', 'analysis', 'ux' );
	}

	protected function register_controls() {

		$this->add_standard_header_section();

		// MODE SELECTOR
		$this->start_controls_section(
			'mode_section',
			array(
				'label' => __( 'Analysis Type', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'mode',
			array(
				'label'       => __( 'Type', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'diff',
				'options'     => array(
					'diff' => __( 'Differentiation (them vs you)', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'More analysis types coming.', 'ux-portfolio-optimized' ),
			)
		);
		$this->end_controls_section();

		// ===== DIFF MODE CONTROLS =====

		// CONTENT — Competitors
		$this->start_controls_section(
			'competitors_section',
			array(
				'condition' => array( 'mode' => 'diff' ),
				'label'     => __( 'Competitors', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'you_name',
			array(
				'label'       => __( 'Your Name / Brand', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Us', 'ux-portfolio-optimized' ),
				'description' => __( 'Used in the "what you do better" side, e.g. "Lucinda\'s".', 'ux-portfolio-optimized' ),
			)
		);

		$rep = new \Elementor\Repeater();

		$rep->add_control(
			'category',
			array(
				'label'       => __( 'Category', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => __( 'e.g. Brand', 'ux-portfolio-optimized' ),
				'description' => __( 'Competitors sharing a category appear together under that tab. Only used when Categories are enabled below.', 'ux-portfolio-optimized' ),
			)
		);

		$rep->add_control(
			'competitor',
			array(
				'label'       => __( 'Competitor', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$rep->add_control(
			'competitor_logo',
			array(
				'label'       => __( 'Logo / Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'Optional small logo shown beside the competitor name.', 'ux-portfolio-optimized' ),
			)
		);

		$rep->add_control(
			'they_do',
			array(
				'label'   => __( 'What They Do', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => '',
			)
		);

		$rep->add_control(
			'you_do',
			array(
				'label'   => __( 'What You Do Better', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => '',
			)
		);

		$this->add_control(
			'competitors',
			array(
				'label'       => __( 'Competitors', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => array(
					array(
						'category'   => 'Brand',
						'competitor' => 'Skinny Dennis',
						'they_do'    => 'Leans into kitsch; lacks cohesion or polish',
						'you_do'     => 'Sophistication without losing soul',
					),
					array(
						'category'   => 'Brand',
						'competitor' => 'Desert 5 Spot',
						'they_do'    => 'Loud, thematic, fun-first',
						'you_do'     => 'Vibe-first, emotionally rich, musically anchored',
					),
					array(
						'category'   => 'Website',
						'competitor' => 'Holiday Lounge',
						'they_do'    => 'Minimalist, but empty',
						'you_do'     => 'Minimalism that feels intentional and textured',
					),
					array(
						'category'   => 'Website',
						'competitor' => 'Bar Lunàtico',
						'they_do'    => 'Great vibe, lacks scalability or identity system',
						'you_do'     => 'A scalable brand from the same emotional truth',
					),
				),
				'title_field' => '{{{ category ? category + " — " : "" }}}{{{ competitor }}}',
			)
		);

		$links = new \Elementor\Repeater();
		$links->add_control(
			'link_name',
			array(
				'label'       => __( 'Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Write it exactly as it appears in the Competitor field. Several names there can be separated with a middle dot ( · ).', 'ux-portfolio-optimized' ),
			)
		);
		$links->add_control(
			'link_url',
			array(
				'label'       => __( 'Website', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'options'     => false,
				'placeholder' => 'https://',
			)
		);
		$this->add_control(
			'site_links',
			array(
				'label'         => __( 'Competitor links', 'ux-portfolio-optimized' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $links->get_controls(),
				'default'       => array(),
				'prevent_empty' => false,
				'title_field'   => '{{{ link_name }}}',
				'description'   => __( 'Optional. Matching names become links that open in a new tab.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// CONTENT — Categories (tabs)
		$this->start_controls_section(
			'categories_section',
			array(
				'condition' => array( 'mode' => 'diff' ),
				'label'     => __( 'Categories', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_tab_controls( 'Category', 'cat' );

		$this->end_controls_section();

		// CONTENT — Layout & labels
		$this->start_controls_section(
			'layout_section',
			array(
				'condition' => array( 'mode' => 'diff' ),
				'label'     => __( 'Layout & Labels', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'cards',
				'options' => array(
					'cards' => __( 'Cards (best for web / mobile)', 'ux-portfolio-optimized' ),
					'table' => __( 'Table (classic, scrolls on mobile)', 'ux-portfolio-optimized' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => __( 'Card Columns', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '2',
				'options'   => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
				),
				'condition' => array( 'layout' => 'cards' ),
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-cards' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_control(
			'they_label',
			array(
				'label'   => __( '"What They Do" Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'What they do', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'you_label',
			array(
				'label'   => __( '"What You Do Better" Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'What we do better', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'competitor_col_label',
			array(
				'label'     => __( 'Competitor Column Heading (table)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Competitor', 'ux-portfolio-optimized' ),
				'condition' => array( 'layout' => 'table' ),
			)
		);

		$this->add_control(
			'show_icons',
			array(
				'label'       => __( 'Show ✗ / ✓ Icons', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'A muted mark on "what they do" and a check on "what you do better".', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Cards
		$this->start_controls_section(
			'style_cards',
			array(
				'label'     => __( 'Cards', 'ux-portfolio-optimized' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'mode'   => 'diff',
					'layout' => 'cards',
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
					'{{WRAPPER}} .ux-cd-card' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border',
			array(
				'label'     => __( 'Card Border', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e5e7eb',
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-card' => 'border-color: {{VALUE}};',
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
						'max' => 24,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_gap',
			array(
				'label'     => __( 'Gap Between Cards', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 8,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 18 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-cards' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'competitor_name_color',
			array(
				'label'     => __( 'Competitor Name Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#1a1a1a',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'name_typography', __( 'Competitor Name Typography', 'ux-portfolio-optimized' ), '.ux-cd-name' );

		$this->end_controls_section();

		// STYLE — The two sides
		$this->start_controls_section(
			'style_sides',
			array(
				'condition' => array( 'mode' => 'diff' ),
				'label'     => __( 'Them vs You', 'ux-portfolio-optimized' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'them_heading',
			array(
				'label' => __( 'What They Do', 'ux-portfolio-optimized' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'them_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6b7280',
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-them .ux-cd-side-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'them_accent',
			array(
				'label'     => __( 'Accent / Icon', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#9ca3af',
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-them' => '--cd-accent: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'you_heading',
			array(
				'label'     => __( 'What You Do Better', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'you_color',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#1a1a1a',
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-you .ux-cd-side-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'you_accent',
			array(
				'label'     => __( 'Accent / Icon', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_SECONDARY ),
				'default'   => '#10b981',
				'selectors' => array(
					'{{WRAPPER}} .ux-cd-you' => '--cd-accent: {{VALUE}};',
					'{{WRAPPER}} .ux-cd-you .ux-cd-side-text' => 'font-weight: 600;',
				),
			)
		);

		$this->add_control(
			'you_highlight',
			array(
				'label'       => __( 'Highlight "You" Side', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'separator'   => 'before',
				'description' => __( 'Give your side a subtle tinted background so it stands out.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .ux-cd-you' => 'background: color-mix(in srgb, var(--cd-accent) 8%, transparent);',
				),
			)
		);

		$this->add_typography_control( 'side_typography', __( 'Side Text Typography', 'ux-portfolio-optimized' ), '.ux-cd-side-text' );

		$this->add_typography_control( 'side_label_typography', __( 'Side Label Typography', 'ux-portfolio-optimized' ), '.ux-cd-side-label' );

		$this->end_controls_section();
	}


	private function media_url( $field ) {
		if ( ! empty( $field['url'] ) ) {
			return $field['url'];
		}
		if ( ! empty( $field['id'] ) ) {
			return wp_get_attachment_url( $field['id'] );
		}
		return '';
	}

	/** Name => URL map from the Competitor links repeater, set per render. */
	private $cd_links = array();

	/** Escaped competitor names; names with a matching link open the site in a new tab. */
	private function linked_names( $name ) {
		$parts = preg_split( '/\s*·\s*/u', $name );
		$out   = array();
		foreach ( $parts as $p ) {
			$p = trim( $p );
			if ( $p === '' ) {
				continue;
			}
			$u     = $this->cd_links[ strtolower( $p ) ] ?? '';
			$out[] = $u !== ''
				? '<a class="ux-cd-link" href="' . esc_url( $u ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $p ) . '<span class="screen-reader-text"> ' . esc_html__( '(opens in a new tab)', 'ux-portfolio-optimized' ) . '</span></a>'
				: esc_html( $p );
		}
		return implode( ' · ', $out );
	}

	/** Renders the competitor name + optional logo. */
	private function render_competitor_head( $comp ) {
		$name = trim( $comp['competitor'] ?? '' );
		$logo = $this->media_url( $comp['competitor_logo'] ?? array() );
		?>
		<div class="ux-cd-head">
			<?php if ( $logo !== '' ) : ?>
				<img class="ux-cd-logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
			<?php endif; ?>
			<?php if ( $name !== '' ) : ?>
				<span class="ux-cd-name"><?php echo $this->linked_names( $name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in linked_names() ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/** Renders the table layout for a subset of competitors. */
	private function render_table_layout( $comps, $comp_col, $they_label, $you_label ) {
		?>
		<div class="ux-cd-scroll">
			<table class="ux-cd-table">
				<thead>
					<tr>
						<th><?php echo esc_html( $comp_col ); ?></th>
						<th><?php echo esc_html( $they_label ); ?></th>
						<th class="ux-cd-you-col"><?php echo esc_html( $you_label ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $comps as $comp ) : ?>
						<tr>
							<td><?php $this->render_competitor_head( $comp ); ?></td>
							<td class="ux-cd-them"><span class="ux-cd-side-text"><?php echo esc_html( $comp['they_do'] ?? '' ); ?></span></td>
							<td class="ux-cd-you"><span class="ux-cd-side-text"><?php echo esc_html( $comp['you_do'] ?? '' ); ?></span></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/** Renders the cards layout for a subset of competitors. */
	private function render_cards_layout( $comps, $they_label, $you_label ) {
		?>
		<div class="ux-cd-cards">
			<?php foreach ( $comps as $comp ) : ?>
				<div class="ux-cd-card">
					<?php $this->render_competitor_head( $comp ); ?>
					<div class="ux-cd-sides">
						<div class="ux-cd-side ux-cd-them">
							<?php if ( $they_label !== '' ) : ?>
								<span class="ux-cd-side-label"><?php echo esc_html( $they_label ); ?></span>
							<?php endif; ?>
							<span class="ux-cd-side-text"><?php echo esc_html( $comp['they_do'] ?? '' ); ?></span>
						</div>
						<div class="ux-cd-side ux-cd-you">
							<?php if ( $you_label !== '' ) : ?>
								<span class="ux-cd-side-label"><?php echo esc_html( $you_label ); ?></span>
							<?php endif; ?>
							<span class="ux-cd-side-text"><?php echo esc_html( $comp['you_do'] ?? '' ); ?></span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function render_diff( $s ) {
		if ( empty( $s['competitors'] ) ) {
			return;
		}

		$this->cd_links = array();
		foreach ( (array) ( $s['site_links'] ?? array() ) as $l ) {
			$ln = strtolower( trim( (string) ( $l['link_name'] ?? '' ) ) );
			$lu = esc_url_raw( trim( (string) ( $l['link_url']['url'] ?? '' ) ), array( 'http', 'https' ) );
			if ( $ln !== '' && $lu !== '' ) {
				$this->cd_links[ $ln ] = $lu;
			}
		}

		$layout     = ( $s['layout'] ?? 'cards' ) === 'table' ? 'table' : 'cards';
		$show_icons = ( $s['show_icons'] ?? 'yes' ) === 'yes';
		$they_label = trim( $s['they_label'] ?? '' );
		$you_label  = trim( $s['you_label'] ?? '' );
		$comp_col   = trim( $s['competitor_col_label'] ?? __( 'Competitor', 'ux-portfolio-optimized' ) );

		// Bucket by category using the shared tabbing trait.
		$enabled = ( $s['cat_enable'] ?? '' ) === 'yes';
		$buckets = $this->bucket_by( $s['competitors'], 'category', $enabled );
		$names   = array_keys( $buckets );
		$tabbed  = $enabled && ( ( $s['cat_show_tabs'] ?? 'yes' ) === 'yes' ) && count( $names ) > 1;
		$active  = $this->resolve_active( $names, $s['cat_default'] ?? '' );

		$wrapper_extra = 'ux-cd-wrapper' . ( $show_icons ? ' ux-cd-has-icons' : '' );
		$this->open_tab_shell( $names, $tabbed, $active, $wrapper_extra );
		foreach ( $names as $i => $n ) {
			$this->tab_panel_open( $i, $n, $tabbed, $active, $enabled );
			if ( $layout === 'table' ) {
				$this->render_table_layout( $buckets[ $n ], $comp_col, $they_label, $you_label );
			} else {
				$this->render_cards_layout( $buckets[ $n ], $they_label, $you_label );
			}
			echo '</div>'; // close panel
		}
		$this->close_tab_shell();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$mode = $s['mode'] ?? 'diff';
		// Only diff implemented so far; router expands as modes are added.
		$this->render_diff( $s );
	}
}
