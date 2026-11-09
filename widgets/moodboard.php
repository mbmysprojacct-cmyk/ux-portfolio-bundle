<?php
/**
 * Moodboard Widget
 * Masonry-style image grid for inspiration/moodboards.
 * Per-image: lightbox-clickable image, color tags, optional caption + source link.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Moodboard_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-moodboard'; }
	public function get_title() {
		return __( 'Moodboard', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-gallery-grid'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Images
		$this->start_controls_section(
			'images_section',
			array(
				'label' => __( 'Images', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_control(
			'moodboard',
			array(
				'label'       => __( 'Moodboard', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Type a Moodboard name (defined in the Moodboards panel) to put this image in that tab. Leave blank for the default/first moodboard.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'caption',
			array(
				'label'       => __( 'Caption', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Optional. Shown on hover.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'colors',
			array(
				'label'       => __( 'Color Tags', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '#3CB371, #cccccc',
				'description' => __( 'Comma-separated hex codes. Shown as small swatches over the image.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'source',
			array(
				'label'       => __( 'Source Link', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://...',
				'description' => __( 'Optional. Where the image came from — credit/attribution. Opens in a new tab.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'images',
			array(
				'label'       => __( 'Images', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'caption' => 'Inspiration 1' ),
					array( 'caption' => 'Inspiration 2' ),
					array( 'caption' => 'Inspiration 3' ),
				),
				'title_field' => '{{{ caption || "Image" }}}{{{ moodboard ? " — " + moodboard : "" }}}',
			)
		);

		$this->end_controls_section();

		// CONTENT — Moodboards (tabs)
		$this->start_controls_section(
			'moodboards_section',
			array(
				'label' => __( 'Moodboards', 'ux-portfolio-optimized' ),
			)
		);

		$mb_repeater = new \Elementor\Repeater();

		$mb_repeater->add_control(
			'name',
			array(
				'label'       => __( 'Moodboard Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Main',
				'description' => __( 'Used as the tab tooltip (and aria-label). Match this in each image\'s Moodboard field.', 'ux-portfolio-optimized' ),
			)
		);

		$mb_repeater->add_control(
			'tab_icon',
			array(
				'label'       => __( 'Tab Icon', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'description' => __( 'Optional. Per-moodboard icon. If empty, the Default Tab Icon is used.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'moodboards',
			array(
				'label'       => __( 'Moodboards', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $mb_repeater->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ name }}}',
				'description' => __( 'Optional. Define multiple moodboards. Tabs appear when more than one exists. Leave empty for a single untabbed moodboard.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'default_tab_icon',
			array(
				'label'       => __( 'Default Tab Icon', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'eicon-image-rollover',
					'library' => 'eicons',
				),
				'description' => __( 'Used for any moodboard that doesn\'t set its own Tab Icon.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_all_tab',
			array(
				'label'       => __( 'Show "All" Tab', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Adds an "All" tab that combines images from every moodboard into a single view. Only relevant when more than one moodboard exists.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'all_label',
			array(
				'label'     => __( '"All" Tab Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'All', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_all_tab' => 'yes' ),
			)
		);

		$this->add_control(
			'all_tab_icon',
			array(
				'label'     => __( '"All" Tab Icon', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'eicon-gallery-grid',
					'library' => 'eicons',
				),
				'condition' => array( 'show_all_tab' => 'yes' ),
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

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns (single-moodboard view)', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::NUMBER,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'min'            => 1,
				'max'            => 6,
				'description'    => __( 'Applies when viewing one moodboard alone. In the "All" view, each moodboard becomes its own column automatically.', 'ux-portfolio-optimized' ),
				'selectors'      => array( '{{WRAPPER}} .moodboard' => '--mb-user-columns: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'gap',
			array(
				'label'     => __( 'Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 16 ),
				'selectors' => array(
					'{{WRAPPER}} .moodboard' => '--mb-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'color_position',
			array(
				'label'   => __( 'Color Tag Position', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'top-left'     => __( 'Top Left', 'ux-portfolio-optimized' ),
					'top-right'    => __( 'Top Right', 'ux-portfolio-optimized' ),
					'bottom-left'  => __( 'Bottom Left', 'ux-portfolio-optimized' ),
					'bottom-right' => __( 'Bottom Right', 'ux-portfolio-optimized' ),
				),
				'default' => 'bottom-left',
			)
		);

		$this->add_control(
			'show_caption_default',
			array(
				'label'   => __( 'Caption Visibility', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'hover'  => __( 'On Hover', 'ux-portfolio-optimized' ),
					'always' => __( 'Always Visible', 'ux-portfolio-optimized' ),
					'never'  => __( 'Never', 'ux-portfolio-optimized' ),
				),
				'default' => 'hover',
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

		$this->add_control(
			'swatch_size',
			array(
				'label'     => __( 'Color Swatch Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 20 ),
				'selectors' => array(
					'{{WRAPPER}} .moodboard-color' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'     => __( 'Image Corner Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 32,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .moodboard-item' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_description_alignment();

		$this->end_controls_section();

		// STYLE — Tab Icon
		$this->start_controls_section(
			'tab_icon_style_section',
			array(
				'label' => __( 'Tab Icon', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tab_icon_size',
			array(
				'label'     => __( 'Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 48,
					),
				),
				'default'   => array( 'size' => 20 ),
				'selectors' => array(
					'{{WRAPPER}} .moodboard-tab__icon'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .moodboard-tab__icon i' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.9);',
				),
			)
		);

		$this->start_controls_tabs( 'tab_icon_state_tabs' );

		$this->start_controls_tab( 'tab_icon_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'tab_icon_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .moodboard-tab__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .moodboard-tab__icon svg' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_icon_hover', array( 'label' => __( 'Hover', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'tab_icon_hover_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .moodboard-tab:hover .moodboard-tab__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .moodboard-tab:hover .moodboard-tab__icon svg' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_icon_active', array( 'label' => __( 'Active', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'tab_icon_active_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .moodboard-tab.is-active .moodboard-tab__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .moodboard-tab.is-active .moodboard-tab__icon svg' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function slugify( $text ) {
		$slug = strtolower( trim( (string) $text ) );
		$slug = preg_replace( '/[^a-z0-9]+/', '-', $slug );
		$slug = trim( $slug, '-' );
		return $slug !== '' ? $slug : 'default';
	}

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

		if ( empty( $s['images'] ) ) {
			return;
		}

		$widget_id      = $this->get_id();
		$color_position = $s['color_position'] ?? 'bottom-left';
		$caption_mode   = $s['show_caption_default'] ?? 'hover';

		// Build the ordered list of moodboards (slug => meta).
		$mb_names = array();
		$mb_meta  = array();
		$mb_def   = $s['moodboards'] ?? array();
		if ( ! empty( $mb_def ) && is_array( $mb_def ) ) {
			foreach ( $mb_def as $mb ) {
				$name = trim( $mb['name'] ?? '' );
				if ( $name === '' ) {
					continue;
				}
				$slug = $this->slugify( $name );
				if ( ! isset( $mb_names[ $slug ] ) ) {
					$mb_names[ $slug ] = $name;
					$mb_meta[ $slug ]  = array( 'tab_icon' => $mb['tab_icon'] ?? null );
				}
			}
		}

		// Default slug for images without a moodboard assignment.
		$default_slug = '';
		if ( ! empty( $mb_names ) ) {
			$defined_keys = array_keys( $mb_names );
			$default_slug = $defined_keys[0];
		}

		// Walk images, resolve each to a slug, count per-moodboard occurrences.
		// Items keep their original repeater position; we attach a slug + per-group index.
		$image_rows   = array();
		$group_counts = array();
		foreach ( $s['images'] as $item ) {
			$mb_name = trim( $item['moodboard'] ?? '' );
			if ( $mb_name !== '' ) {
				$slug = $this->slugify( $mb_name );
				if ( ! isset( $mb_names[ $slug ] ) ) {
					// Auto-register an unknown moodboard so the image still shows.
					$mb_names[ $slug ] = $mb_name;
					$mb_meta[ $slug ]  = array( 'tab_icon' => null );
				}
			} elseif ( $default_slug !== '' ) {
					$slug = $default_slug;
			} else {
				$slug = 'main';
				if ( ! isset( $mb_names[ $slug ] ) ) {
					$mb_names[ $slug ] = 'Main';
					$mb_meta[ $slug ]  = array( 'tab_icon' => null );
				}
			}
			if ( ! isset( $group_counts[ $slug ] ) ) {
				$group_counts[ $slug ] = 0;
			}
			$group_index = $group_counts[ $slug ];
			++$group_counts[ $slug ];
			$image_rows[] = array(
				'item'        => $item,
				'slug'        => $slug,
				'group_index' => $group_index,
			);
		}

		if ( empty( $image_rows ) ) {
			return;
		}

		// Filter out slugs that ended up with no images.
		$slugs = array();
		foreach ( array_keys( $mb_names ) as $sl ) {
			if ( ! empty( $group_counts[ $sl ] ) ) {
				$slugs[] = $sl;
			}
		}

		if ( empty( $slugs ) ) {
			return;
		}

		$has_multiple = count( $slugs ) > 1;
		$show_all_tab = $has_multiple && ( ( $s['show_all_tab'] ?? 'yes' ) === 'yes' );
		$all_label    = $s['all_label'] ?? __( 'All', 'ux-portfolio-optimized' );
		$show_tabs    = $has_multiple; // tabs render when more than one moodboard

		// Active filter: prefer "All" when enabled, otherwise the first moodboard.
		$active_filter = $show_all_tab ? '__all__' : $slugs[0];

		$default_icon = $s['default_tab_icon'] ?? null;
		$all_tab_icon = $s['all_tab_icon'] ?? null;
		?>

		<div class="moodboard-wrapper caption-<?php echo esc_attr( $caption_mode ); ?>" data-active-moodboard="<?php echo esc_attr( $active_filter ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php if ( $show_tabs ) : ?>
				<div class="moodboard-tabs" role="tablist">
					<?php
					if ( $show_all_tab ) :
						$all_has_icon = ! empty( $all_tab_icon ) && ( ! empty( $all_tab_icon['value'] ) || ! empty( $all_tab_icon['url'] ) );
						?>
						<button type="button"
								class="moodboard-tab<?php echo $active_filter === '__all__' ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $active_filter === '__all__' ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( $all_label ); ?>"
								title="<?php echo esc_attr( $all_label ); ?>"
								data-target="__all__"><span class="moodboard-tab__icon">
								<?php
								if ( $all_has_icon ) {
									\Elementor\Icons_Manager::render_icon( $all_tab_icon, array( 'aria-hidden' => 'true' ) );
								} else {
									?>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg><?php } ?></span><span class="moodboard-tab__all-label"><?php echo esc_html( $all_label ); ?></span></button>
					<?php endif; ?>

					<?php
					foreach ( $slugs as $tab_i => $slug ) :
						$tab_num        = $tab_i + 1;
						$proto_icon     = $mb_meta[ $slug ]['tab_icon'] ?? null;
						$proto_has_icon = ! empty( $proto_icon ) && ( ! empty( $proto_icon['value'] ) || ! empty( $proto_icon['url'] ) );
						$effective_icon = $proto_has_icon ? $proto_icon : $default_icon;
						$has_icon       = ! empty( $effective_icon ) && ( ! empty( $effective_icon['value'] ) || ! empty( $effective_icon['url'] ) );
						?>
						<button type="button"
								class="moodboard-tab<?php echo $slug === $active_filter ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $slug === $active_filter ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( $mb_names[ $slug ] ); ?>"
								title="<?php echo esc_attr( $mb_names[ $slug ] ); ?>"
								data-target="<?php echo esc_attr( $slug ); ?>"><span class="moodboard-tab__icon">
								<?php
								if ( $has_icon ) {
									\Elementor\Icons_Manager::render_icon( $effective_icon, array( 'aria-hidden' => 'true' ) );
								} else {
									?>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg><?php } ?></span><sup class="moodboard-tab__num"><?php echo esc_html( $tab_num ); ?></sup></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="moodboard" style="--mb-count: <?php echo count( $slugs ); ?>;">
				<?php
				$all_index     = 0;
				$all_slideshow = $widget_id . '-all';

				// Group image_rows by slug to preserve repeater order within each group.
				$rows_by_slug = array();
				foreach ( $slugs as $sl ) {
					$rows_by_slug[ $sl ] = array();
				}
				foreach ( $image_rows as $row ) {
					if ( isset( $rows_by_slug[ $row['slug'] ] ) ) {
						$rows_by_slug[ $row['slug'] ][] = $row;
					}
				}

				foreach ( $slugs as $slug ) :
					$group_slideshow = $widget_id . '-' . $slug;
					$is_active_group = ( $slug === $active_filter );
					$group_class     = 'moodboard-group';
					if ( $is_active_group ) {
						$group_class .= ' is-active-group';
					}
					?>
					<div class="<?php echo esc_attr( $group_class ); ?>" data-moodboard="<?php echo esc_attr( $slug ); ?>">
						<?php
						foreach ( $rows_by_slug[ $slug ] as $row ) :
							$item        = $row['item'];
							$group_index = $row['group_index'];

							$img_url = $item['image']['url'] ?? '';
							if ( empty( $img_url ) && ! empty( $item['image']['id'] ) ) {
								$img_url = wp_get_attachment_url( $item['image']['id'] );
							}
							if ( empty( $img_url ) ) {
								continue;
							}

							$caption = trim( $item['caption'] ?? '' );
							$colors  = $this->parse_colors( $item['colors'] ?? '' );

							$source_url      = $item['source']['url'] ?? '';
							$source_nofollow = ! empty( $item['source']['nofollow'] );

							// Initial lightbox slideshow: All vs per-moodboard.
							$current_index     = $all_index;
							$current_slideshow = $all_slideshow;
							if ( $show_tabs && $active_filter !== '__all__' && $slug === $active_filter ) {
								$current_index     = $group_index;
								$current_slideshow = $group_slideshow;
							}
							$this_all_index = $all_index;
							++$all_index;
							?>
							<div class="moodboard-item">
								<a href="<?php echo esc_url( $img_url ); ?>"
									class="moodboard-image-link"
									data-elementor-open-lightbox="yes"
									data-elementor-lightbox-slideshow="<?php echo esc_attr( $current_slideshow ); ?>"
									data-elementor-lightbox-index="<?php echo esc_attr( $current_index ); ?>"
									data-lightbox-all-id="<?php echo esc_attr( $all_slideshow ); ?>"
									data-lightbox-all-index="<?php echo esc_attr( $this_all_index ); ?>"
									data-lightbox-gallery-id="<?php echo esc_attr( $group_slideshow ); ?>"
									data-lightbox-gallery-index="<?php echo esc_attr( $group_index ); ?>"
									aria-label="<?php echo esc_attr( $caption !== '' ? $caption : sprintf( /* translators: %d: image number */ __( 'Image %d', 'ux-portfolio-optimized' ), $this_all_index + 1 ) ); ?>">
									<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $caption ); ?>" loading="lazy">
								</a>

								<?php if ( ! empty( $colors ) ) : ?>
									<div class="moodboard-colors pos-<?php echo esc_attr( $color_position ); ?>">
										<?php foreach ( $colors as $c ) : ?>
											<span class="moodboard-color" style="background: <?php echo ux_portfolio_esc_css( $c ); ?>;" title="<?php echo esc_attr( $c ); ?>"></span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

								<?php if ( $caption !== '' && $caption_mode !== 'never' ) : ?>
									<div class="moodboard-caption"><?php echo esc_html( $caption ); ?></div>
								<?php endif; ?>

								<?php
								if ( ! empty( $source_url ) ) :
									$rel_parts = array( 'noopener' );
									if ( $source_nofollow ) {
										$rel_parts[] = 'nofollow';
									}
									$rel = ' rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"';
									?>
									<a class="moodboard-source"
										href="<?php echo esc_url( $source_url ); ?>" target="_blank"<?php echo $rel; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rel built with esc_attr() ?>
										aria-label="<?php esc_attr_e( 'View image source', 'ux-portfolio-optimized' ); ?>"
										title="<?php esc_attr_e( 'View source', 'ux-portfolio-optimized' ); ?>">↗</a>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
	}
}
