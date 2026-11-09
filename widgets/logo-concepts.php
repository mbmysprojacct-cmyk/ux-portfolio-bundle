<?php
/**
 * Logo Concepts Widget
 *
 * Presents finished logo concept directions (as shown to a client), each with a
 * rationale and an optional strip of variations (mono, lockups, mockups). Each
 * concept sits on a switchable backdrop so a mark can be shown to hold up on
 * light or dark. Optional category tabs (via the shared tabbing trait) let you
 * separate e.g. "Round 1" from "Final Three".
 *
 * Pairs with Sketch Ideation: that shows the messy journey; this shows the
 * polished directions you presented.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Logo_Concepts_Widget extends UX_Base_Widget {

	use UX_Tabbed_Trait;

	public function get_name() {
		return 'ux-logo-concepts'; }
	public function get_title() {
		return __( 'Logo Concepts', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-image-bold'; }
	public function get_keywords() {
		return array( 'logo', 'concepts', 'brand', 'mark', 'directions', 'variations', 'ux' );
	}

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Concepts
		$this->start_controls_section(
			'concepts_section',
			array(
				'label' => __( 'Concepts', 'ux-portfolio-optimized' ),
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
				'placeholder' => __( 'e.g. Final Three', 'ux-portfolio-optimized' ),
				'description' => __( 'Concepts sharing a category appear together under that tab. Only used when Categories are enabled below.', 'ux-portfolio-optimized' ),
			)
		);

		$rep->add_control(
			'name',
			array(
				'label'       => __( 'Concept Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Concept A — The Monogram',
				'label_block' => true,
			)
		);

		$rep->add_control(
			'logo',
			array(
				'label'       => __( 'Logo', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'The primary mark for this concept (PNG/SVG on transparent works best).', 'ux-portfolio-optimized' ),
			)
		);

		$rep->add_control(
			'bg',
			array(
				'label'       => __( 'Logo Backdrop', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'light',
				'options'     => array(
					'light'  => __( 'Light', 'ux-portfolio-optimized' ),
					'dark'   => __( 'Dark', 'ux-portfolio-optimized' ),
					'custom' => __( 'Custom color', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'Show the mark on a light or dark stage — useful to prove it holds up either way.', 'ux-portfolio-optimized' ),
			)
		);

		$rep->add_control(
			'bg_custom',
			array(
				'label'     => __( 'Custom Backdrop Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f3f4f6',
				'condition' => array( 'bg' => 'custom' ),
			)
		);

		$rep->add_control(
			'rationale',
			array(
				'label'   => __( 'Rationale', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => 'Why this direction — the thinking behind the mark, what it signals, and how it fits the brand.',
			)
		);

		$rep->add_control(
			'variations',
			array(
				'label'       => __( 'Variations', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::GALLERY,
				'description' => __( 'Optional. Mono version, horizontal lockup, app-icon crop, on a sign/coaster mockup — the system, not one file.', 'ux-portfolio-optimized' ),
			)
		);

		$rep->add_control(
			'variations_bg',
			array(
				'label'   => __( 'Variations Backdrop', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'match',
				'options' => array(
					'match' => __( 'Match the logo backdrop', 'ux-portfolio-optimized' ),
					'light' => __( 'Light', 'ux-portfolio-optimized' ),
					'dark'  => __( 'Dark', 'ux-portfolio-optimized' ),
				),
			)
		);

		$this->add_control(
			'concepts',
			array(
				'label'       => __( 'Concepts', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => array(
					array( 'name' => 'Concept A — The Monogram' ),
					array( 'name' => 'Concept B — The Wordmark' ),
				),
				'title_field' => '{{{ category ? category + " — " : "" }}}{{{ name }}}',
			)
		);

		$this->end_controls_section();

		// CONTENT — Layout
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'rows',
				'options' => array(
					'rows'  => __( 'Rows (logo beside rationale)', 'ux-portfolio-optimized' ),
					'cards' => __( 'Cards (stacked, 2-up grid)', 'ux-portfolio-optimized' ),
				),
			)
		);

		$this->add_control(
			'image_side',
			array(
				'label'       => __( 'Logo Side', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'left',
				'options'     => array(
					'left'  => __( 'Left', 'ux-portfolio-optimized' ),
					'right' => __( 'Right', 'ux-portfolio-optimized' ),
				),
				'condition'   => array( 'layout' => 'rows' ),
				'description' => __( 'Alternate manually per row is not needed — this sets the side for all rows.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'card_columns',
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
					'{{WRAPPER}} .ux-logo-cards' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_control(
			'logo_height',
			array(
				'label'     => __( 'Logo Stage Height', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 120,
						'max' => 480,
					),
				),
				'default'   => array( 'size' => 240 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-logo-stage' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variations_display',
			array(
				'label'   => __( 'Show variations as', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'below',
				'options' => array(
					'below'    => __( 'Thumbnails below', 'ux-portfolio-optimized' ),
					'carousel' => __( 'Carousel (swipe through)', 'ux-portfolio-optimized' ),
				),
			)
		);

		$this->add_control(
			'lightbox',
			array(
				'label'        => __( 'Open images in lightbox', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Click a concept or variation to view it full size.', 'ux-portfolio-optimized' ),
			)
		);

		// Categories (tabs) — shared trait
		$this->add_tab_controls( 'Category', 'cat' );

		$this->end_controls_section();

		// STYLE
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'stage_radius',
			array(
				'label'     => __( 'Stage Corner Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-logo-stage' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-logo-var'   => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'light_bg_color',
			array(
				'label'     => __( 'Light Backdrop Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8f9fa',
				'selectors' => array(
					'{{WRAPPER}} .ux-bg-light' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dark_bg_color',
			array(
				'label'     => __( 'Dark Backdrop Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#1a1a1a',
				'selectors' => array(
					'{{WRAPPER}} .ux-bg-dark' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Concept Name Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#1a1a1a',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ux-logo-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'name_typography', __( 'Concept Name Typography', 'ux-portfolio-optimized' ), '.ux-logo-name' );

		$this->add_control(
			'rationale_color',
			array(
				'label'     => __( 'Rationale Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#374151',
				'selectors' => array(
					'{{WRAPPER}} .ux-logo-rationale' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'rationale_typography', __( 'Rationale Typography', 'ux-portfolio-optimized' ), '.ux-logo-rationale' );

		$this->add_responsive_control(
			'concept_gap',
			array(
				'label'     => __( 'Gap Between Concepts', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 64,
					),
				),
				'default'   => array( 'size' => 32 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-logo-list'  => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-logo-cards' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/** Resolve a media field to a URL. */
	private function media_url( $field ) {
		if ( ! empty( $field['url'] ) ) {
			return $field['url'];
		}
		if ( ! empty( $field['id'] ) ) {
			return wp_get_attachment_url( $field['id'] );
		}
		return '';
	}

	/** Backdrop class + inline style for a given bg setting. */
	private function bg_attrs( $bg, $custom ) {
		$class = 'ux-bg-light';
		$style = '';
		if ( $bg === 'dark' ) {
			$class = 'ux-bg-dark';
		} elseif ( $bg === 'custom' ) {
			$class = 'ux-bg-custom';
			$style = ' style="background: ' . esc_attr( $custom !== '' ? $custom : '#f3f4f6' ) . ';"';
		}
		return array(
			'class' => $class,
			'style' => $style,
		);
	}

	/** Renders one concept (used by both layouts). */
	private function render_concept( $c, $layout, $image_side ) {
		$name      = trim( $c['name'] ?? '' );
		$rationale = $c['rationale'] ?? '';
		$logo      = $this->media_url( $c['logo'] ?? array() );
		$bg        = $c['bg'] ?? 'light';
		$bg_custom = $c['bg_custom'] ?? '';
		$stage     = $this->bg_attrs( $bg, $bg_custom );

		// Variations backdrop
		$var_bg = $c['variations_bg'] ?? 'match';
		if ( $var_bg === 'match' ) {
			$var_attrs = $stage;
		} else {
			$var_attrs = $this->bg_attrs( $var_bg, '' );
		}

		$variations = array();
		if ( ! empty( $c['variations'] ) && is_array( $c['variations'] ) ) {
			foreach ( $c['variations'] as $v ) {
				$u = $this->media_url( $v );
				if ( $u !== '' ) {
					$variations[] = $u;
				}
			}
		}

		$lightbox = ( $this->get_settings_for_display( 'lightbox' ) ?? 'yes' ) === 'yes';
		$lb_group = 'ux-logo-' . $this->get_id();
		$lb_open  = function ( $url, $title ) use ( $lightbox, $lb_group ) {
			if ( ! $lightbox || $url === '' ) {
				return '';
			}
			return '<a class="ux-logo-zoom" href="' . esc_url( $url ) . '" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . esc_attr( $lb_group ) . '" data-elementor-lightbox-title="' . esc_attr( $title ) . '" aria-label="' . esc_attr( sprintf( __( 'View %s larger', 'ux-portfolio-optimized' ), $title ) ) . '">';
		};
		$lb_close = $lightbox ? '</a>' : '';

		$item_class = 'ux-logo-concept';
		if ( $layout === 'rows' ) {
			$item_class .= ' ux-logo-row ux-logo-img-' . ( $image_side === 'right' ? 'right' : 'left' );
		} else {
			$item_class .= ' ux-logo-card';
		}
		?>
		<div class="<?php echo esc_attr( $item_class ); ?>">
			<div class="ux-logo-media">
				<?php $carousel = ! empty( $variations ) && $logo !== '' && ( $this->get_settings_for_display( 'variations_display' ) ?? 'below' ) === 'carousel'; ?>
				<?php
				if ( $carousel ) :
					$slides = array_merge( array( $logo ), $variations );
					$count  = count( $slides );
					$cid    = 'ux-lc-' . $this->get_id() . '-' . substr( md5( $logo . $name ), 0, 6 );
					?>
				<div class="ux-logo-stage ux-logo-carousel <?php echo esc_attr( $stage['class'] ); ?>"<?php echo $stage['style']; ?> data-ux-logo-carousel role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr( $name ); ?>">
					<div class="ux-logo-track" id="<?php echo esc_attr( $cid ); ?>" tabindex="0">
						<?php
						foreach ( $slides as $si => $su ) :
							$stitle = $si === 0 ? $name : $name . ' ' . __( 'variation', 'ux-portfolio-optimized' );
							?>
						<div class="ux-logo-slide" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d', 'ux-portfolio-optimized' ), $si + 1, $count ) ); ?>">
							<?php echo $lb_open( $su, $stitle ); // escaped in closure ?><img class="ux-logo-img" src="<?php echo esc_url( $su ); ?>" alt="<?php echo esc_attr( $stitle ); ?>" loading="lazy"><?php echo $lb_close; ?>
						</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="ux-logo-nav ux-logo-prev" aria-controls="<?php echo esc_attr( $cid ); ?>" aria-label="<?php esc_attr_e( 'Previous image', 'ux-portfolio-optimized' ); ?>" hidden><span aria-hidden="true">‹</span></button>
					<button type="button" class="ux-logo-nav ux-logo-next" aria-controls="<?php echo esc_attr( $cid ); ?>" aria-label="<?php esc_attr_e( 'Next image', 'ux-portfolio-optimized' ); ?>"><span aria-hidden="true">›</span></button>
					<div class="ux-logo-dots">
						<?php for ( $di = 0; $di < $count; $di++ ) : ?>
						<button type="button" class="ux-logo-dot<?php echo $di === 0 ? ' is-active' : ''; ?>" aria-controls="<?php echo esc_attr( $cid ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show image %1$d of %2$d', 'ux-portfolio-optimized' ), $di + 1, $count ) ); ?>"<?php echo $di === 0 ? ' aria-current="true"' : ''; ?>></button>
						<?php endfor; ?>
					</div>
				</div>
				<?php else : ?>
				<div class="ux-logo-stage <?php echo esc_attr( $stage['class'] ); ?>"<?php echo $stage['style']; ?>>
					<?php if ( $logo !== '' ) : ?>
						<?php echo $lb_open( $logo, $name ); // escaped in closure ?><img class="ux-logo-img" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy"><?php echo $lb_close; ?>
					<?php endif; ?>
				</div>

				<?php endif; ?>
				<?php if ( ! empty( $variations ) && ! $carousel ) : ?>
					<div class="ux-logo-vars">
						<?php foreach ( $variations as $vu ) : ?>
							<div class="ux-logo-var <?php echo esc_attr( $var_attrs['class'] ); ?>"<?php echo $var_attrs['style']; ?>>
								<?php echo $lb_open( $vu, $name . ' ' . __( 'variation', 'ux-portfolio-optimized' ) ); // escaped in closure ?><img src="<?php echo esc_url( $vu ); ?>" alt="<?php echo esc_attr( $name ); ?> variation" loading="lazy"><?php echo $lb_close; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="ux-logo-body">
				<?php if ( $name !== '' ) : ?>
					<h3 class="ux-logo-name"><?php echo esc_html( $name ); ?></h3>
				<?php endif; ?>
				<?php if ( trim( wp_strip_all_tags( $rationale ) ) !== '' ) : ?>
					<div class="ux-logo-rationale"><?php echo wp_kses_post( $rationale ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['concepts'] ) ) {
			return;
		}

		$layout          = ( $s['layout'] ?? 'rows' ) === 'cards' ? 'cards' : 'rows';
		$image_side      = ( $s['image_side'] ?? 'left' ) === 'right' ? 'right' : 'left';
		$container_class = $layout === 'cards' ? 'ux-logo-cards' : 'ux-logo-list';

		// Bucket by category using the shared tabbing trait.
		$enabled = ( $s['cat_enable'] ?? '' ) === 'yes';
		$buckets = $this->bucket_by( $s['concepts'], 'category', $enabled );
		$names   = array_keys( $buckets );
		$tabbed  = $enabled && ( ( $s['cat_show_tabs'] ?? 'yes' ) === 'yes' ) && count( $names ) > 1;
		$active  = $this->resolve_active( $names, $s['cat_default'] ?? '' );

		$this->open_tab_shell( $names, $tabbed, $active, 'ux-logo-wrapper' );
		foreach ( $names as $i => $n ) {
			$this->tab_panel_open( $i, $n, $tabbed, $active, $enabled );
			echo '<div class="' . esc_attr( $container_class ) . '">';
			foreach ( $buckets[ $n ] as $c ) {
				$this->render_concept( $c, $layout, $image_side );
			}
			echo '</div>';
			echo '</div>'; // close panel
		}
		$this->close_tab_shell();
	}
}
