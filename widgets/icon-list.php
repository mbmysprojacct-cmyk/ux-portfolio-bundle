<?php
/**
 * Icon List Widget
 * Each item has an optional Icon Before AND an optional Icon After, so you can
 * put an icon on either side, or both, or neither. Plus standard list controls
 * (vertical/horizontal layout, dividers, alignment, hover states).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Icon_List_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-icon-list'; }
	public function get_title() {
		return __( 'Icon List', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-bullet-list'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Items
		$this->start_controls_section(
			'items_section',
			array(
				'label' => __( 'Items', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_before',
			array(
				'label'       => __( 'Icon Before', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'description' => __( 'Shown to the left of the text. Optional.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'icon_before_lightbox',
			array(
				'label'       => __( 'Open in Lightbox', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Click the icon to open it in Elementor\'s lightbox. Only works for uploaded SVG/image files (not font icons).', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'List Item',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'icon_after',
			array(
				'label'       => __( 'Icon After', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'description' => __( 'Shown to the right of the text. Optional. Useful as a trailing arrow, status indicator, etc.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'icon_after_lightbox',
			array(
				'label'       => __( 'Open in Lightbox', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Click the icon to open it in Elementor\'s lightbox. Only works for uploaded SVG/image files (not font icons).', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'description' => __( 'Optional. When set, the entire row becomes a clickable link. Supports Elementor Pro dynamic tags.', 'ux-portfolio-optimized' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Items', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'text'        => 'First item',
						'icon_before' => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
					),
					array(
						'text'        => 'Second item',
						'icon_before' => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
					),
					array(
						'text'        => 'Third item',
						'icon_before' => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
					),
				),
				'title_field' => '{{{ text }}}',
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
			'layout',
			array(
				'label'        => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => array(
					'vertical' => array(
						'title' => __( 'Vertical', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline'   => array(
						'title' => __( 'Horizontal', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'default'      => 'vertical',
				'prefix_class' => 'ux-icon-list--',
			)
		);

		$this->add_control(
			'items_spacing',
			array(
				'label'     => __( 'Space Between Items', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'text_align',
			array(
				'label'       => __( 'Item Alignment', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => array(
					'flex-start' => array(
						'title' => __( 'Left', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => 'flex-start',
				'description' => __( 'Where each item sits within its row. The Icon After always sits at the row\'s end when present.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .ux-icon-list__item' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_after_pushes_right',
			array(
				'label'       => __( 'Push Icon After to Far Right', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'When on, the Icon After is pushed to the far right of each row (text expands to fill the space). Off keeps it directly next to the text.', 'ux-portfolio-optimized' ),
				'condition'   => array(
					'layout'              => 'vertical',
					'icon_after_position' => 'inline',
				),
			)
		);

		$this->add_control(
			'icon_after_position',
			array(
				'label'        => __( 'Icon After Position', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => array(
					'inline'      => __( 'Inline (next to text)', 'ux-portfolio-optimized' ),
					'superscript' => __( 'Superscript (raised, smaller)', 'ux-portfolio-optimized' ),
					'subscript'   => __( 'Subscript (lowered, smaller)', 'ux-portfolio-optimized' ),
				),
				'default'      => 'inline',
				'prefix_class' => 'ux-icon-after-pos-',
				'description'  => __( 'How the Icon After sits relative to the text. Superscript/Subscript auto-shrink the icon to ~70% and glue it to the text (overrides "Push to Far Right").', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'label'     => __( 'Show Divider Between Items', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array( 'layout' => 'vertical' ),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => __( 'Divider Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e2e8f0',
				'condition' => array(
					'show_divider' => 'yes',
					'layout'       => 'vertical',
				),
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list.has-divider .ux-icon-list__item + .ux-icon-list__item' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => __( 'Divider Style', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'ux-portfolio-optimized' ),
					'dashed' => __( 'Dashed', 'ux-portfolio-optimized' ),
					'dotted' => __( 'Dotted', 'ux-portfolio-optimized' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'show_divider' => 'yes',
					'layout'       => 'vertical',
				),
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list.has-divider .ux-icon-list__item + .ux-icon-list__item' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// STYLE — Icon
		$this->start_controls_section(
			'style_icon',
			array(
				'label' => __( 'Icon', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 8,
						'max' => 64,
					),
				),
				'default'   => array( 'size' => 18 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list__icon'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-icon-list__icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-icon-list__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ux-icon-list__icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_gap',
			array(
				'label'     => __( 'Icon ↔ Text Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 10 ),
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list__item' => '--icon-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'icon_state_tabs' );

		$this->start_controls_tab( 'icon_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ux-icon-list__icon svg' => 'fill: currentColor;',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_hover', array( 'label' => __( 'Hover', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list__item:hover .ux-icon-list__icon' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// STYLE — Text
		$this->start_controls_section(
			'style_text',
			array(
				'label' => __( 'Text', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .ux-icon-list__text',
			)
		);

		$this->start_controls_tabs( 'text_state_tabs' );

		$this->start_controls_tab( 'text_normal', array( 'label' => __( 'Normal', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list__text' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'text_hover', array( 'label' => __( 'Hover', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'text_hover_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ux-icon-list__item:hover .ux-icon-list__text' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function has_icon( $icon ) {
		if ( empty( $icon ) ) {
			return false;
		}
		$value = $icon['value'] ?? null;
		if ( is_string( $value ) && trim( $value ) !== '' ) {
			return true;
		}
		if ( is_array( $value ) ) {
			if ( ! empty( $value['url'] ) ) {
				return true;
			}
			if ( ! empty( $value['id'] ) ) {
				return true;
			}
		}
		if ( ! empty( $icon['url'] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Render an icon robustly.
	 * - For uploaded SVGs (library=svg), inline the SVG file contents so CSS
	 *   can size/color it. Falls back to <img> if file isn't readable.
	 * - For font icons (FA, eicon), defer to Elementor's renderer.
	 */
	private function render_icon_safe( $icon ) {
		if ( empty( $icon ) ) {
			return;
		}

		$library = $icon['library'] ?? '';
		$value   = $icon['value'] ?? null;

		// Uploaded SVG path
		if ( $library === 'svg' && is_array( $value ) ) {
			$svg_inline = null;
			if ( ! empty( $value['id'] ) ) {
				$file = get_attached_file( $value['id'] );
				if ( $file && is_readable( $file ) && filesize( $file ) < 100000 ) {
					$contents = @file_get_contents( $file );
					if ( $contents !== false && stripos( $contents, '<svg' ) !== false ) {
						// Strip XML declaration and DOCTYPE if present.
						$contents   = preg_replace( '/<\?xml[^>]*\?>/i', '', $contents );
						$contents   = preg_replace( '/<!DOCTYPE[^>]*>/i', '', $contents );
						$svg_inline = trim( $contents );
					}
				}
			}
			if ( $svg_inline !== null ) {
				$svg_inline = self::kses_svg( $svg_inline );
			}
			if ( $svg_inline !== null && $svg_inline !== '' ) {
				echo $svg_inline; // cleaned by kses_svg()
				return;
			}
			// Fallback to img tag with explicit fill-the-container styles.
			$url = $value['url'] ?? '';
			if ( ! empty( $url ) ) {
				printf(
					'<img src="%s" alt="" loading="lazy" style="width:100%%;height:100%%;object-fit:contain;display:block;">',
					esc_url( $url )
				);
			}
			return;
		}

		// Default path: Elementor renders font icons (FA, eicon, etc.)
		\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
	}

	/**
	 * Returns the URL of an uploaded image/SVG from an ICONS control value.
	 * Returns empty string for font icons or empty values.
	 */
	private function get_icon_image_url( $icon ) {
		if ( ! is_array( $icon ) ) {
			return '';
		}
		$library = $icon['library'] ?? '';
		$value   = $icon['value'] ?? '';
		if ( $library === 'svg' && is_array( $value ) && ! empty( $value['url'] ) ) {
			return $value['url'];
		}
		if ( is_string( $value ) && preg_match( '/\.(svg|jpe?g|png|gif|webp|avif)(\?|$)/i', $value ) ) {
			return $value;
		}
		return '';
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['items'] ) ) {
			return;
		}

		$layout       = $s['layout'] ?? 'vertical';
		$after_pos    = $s['icon_after_position'] ?? 'inline';
		$show_divider = ( $s['show_divider'] ?? '' ) === 'yes' && $layout === 'vertical';
		$push_right   = ( $s['icon_after_pushes_right'] ?? 'yes' ) === 'yes'
						&& $layout === 'vertical'
						&& $after_pos === 'inline';

		$list_classes = array( 'ux-icon-list', 'layout-' . $layout );
		if ( $show_divider ) {
			$list_classes[] = 'has-divider';
		}
		if ( $push_right ) {
			$list_classes[] = 'push-right';
		}
		?>
		<div class="ux-icon-list-wrapper">
			<?php $this->render_standard_header(); ?>
			<ul class="<?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
				<?php
				$slideshow_id = 'ux-icon-list-' . $this->get_id();
				foreach ( $s['items'] as $item ) :
					$text       = trim( $item['text'] ?? '' );
					$has_before = $this->has_icon( $item['icon_before'] ?? null );
					$has_after  = $this->has_icon( $item['icon_after'] ?? null );
					$link_url   = $item['link']['url'] ?? '';
					$link_attrs = '';
					if ( ! empty( $link_url ) ) {
						$link_attrs = ' href="' . esc_url( $link_url ) . '"';
						if ( ! empty( $item['link']['is_external'] ) ) {
							$link_attrs .= ' target="_blank"';
						}
						$rel_parts = array();
						if ( ! empty( $item['link']['is_external'] ) ) {
							$rel_parts[] = 'noopener';
						}
						if ( ! empty( $item['link']['nofollow'] ) ) {
							$rel_parts[] = 'nofollow';
						}
						if ( ! empty( $rel_parts ) ) {
							$link_attrs .= ' rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"';
						}
					}

					// Lightbox flags — only get URL if switcher is on AND icon is an image/svg
					$before_lb_url = ( $item['icon_before_lightbox'] ?? '' ) === 'yes'
						? $this->get_icon_image_url( $item['icon_before'] ?? null ) : '';
					$after_lb_url  = ( $item['icon_after_lightbox'] ?? '' ) === 'yes'
						? $this->get_icon_image_url( $item['icon_after'] ?? null ) : '';
					$has_lightbox  = $before_lb_url !== '' || $after_lb_url !== '';

					if ( $has_lightbox ) :
						// Split mode: each piece wrapped individually so icons get their own <a>
						?>
						<li class="ux-icon-list__item has-lightbox">
							<div class="ux-icon-list__row">
								<?php
								if ( $has_before ) :
									if ( $before_lb_url !== '' ) :
										?>
										<a href="<?php echo esc_url( $before_lb_url ); ?>"
											class="ux-icon-list__icon ux-icon-list__icon--before"
											data-elementor-open-lightbox="yes"
											data-elementor-lightbox-slideshow="<?php echo esc_attr( $slideshow_id ); ?>">
											<?php
											$this->render_icon_safe( $item['icon_before'] );
											?>
										</a>
									<?php else : ?>
										<span class="ux-icon-list__icon ux-icon-list__icon--before" aria-hidden="true">
										<?php
											$this->render_icon_safe( $item['icon_before'] );
										?>
										</span>
										<?php
									endif;
								endif;
								?>
								<?php
								if ( $text !== '' ) :
									if ( ! empty( $link_url ) ) :
										?>
										<a class="ux-icon-list__text"<?php echo $link_attrs; ?>><?php echo esc_html( $text ); ?></a>
									<?php else : ?>
										<span class="ux-icon-list__text"><?php echo esc_html( $text ); ?></span>
										<?php
									endif;
								endif;
								?>
								<?php
								if ( $has_after ) :
									if ( $after_lb_url !== '' ) :
										?>
										<a href="<?php echo esc_url( $after_lb_url ); ?>"
											class="ux-icon-list__icon ux-icon-list__icon--after"
											data-elementor-open-lightbox="yes"
											data-elementor-lightbox-slideshow="<?php echo esc_attr( $slideshow_id ); ?>">
											<?php
											$this->render_icon_safe( $item['icon_after'] );
											?>
										</a>
									<?php else : ?>
										<span class="ux-icon-list__icon ux-icon-list__icon--after" aria-hidden="true">
										<?php
											$this->render_icon_safe( $item['icon_after'] );
										?>
										</span>
										<?php
									endif;
								endif;
								?>
							</div>
						</li>
						<?php
					else :
						// Unified mode: original structure (row is <a> if link, else <span>)
						$tag = ! empty( $link_url ) ? 'a' : 'span';
						?>
						<li class="ux-icon-list__item">
							<<?php echo $tag; ?> class="ux-icon-list__row"<?php echo $link_attrs; ?>>
								<?php if ( $has_before ) : ?>
									<span class="ux-icon-list__icon ux-icon-list__icon--before" aria-hidden="true">
									<?php
										$this->render_icon_safe( $item['icon_before'] );
									?>
									</span>
								<?php endif; ?>
								<?php if ( $text !== '' ) : ?>
									<span class="ux-icon-list__text"><?php echo esc_html( $text ); ?></span>
								<?php endif; ?>
								<?php if ( $has_after ) : ?>
									<span class="ux-icon-list__icon ux-icon-list__icon--after" aria-hidden="true">
									<?php
										$this->render_icon_safe( $item['icon_after'] );
									?>
									</span>
								<?php endif; ?>
							</<?php echo $tag; ?>>
						</li>
						<?php
					endif;
				endforeach;
				?>
			</ul>
		</div>
		<?php
	}
}
