<?php
/**
 * Prototype Display Widget
 * One or more prototypes rendered inside a device frame.
 * Each prototype can be: Image, Video walkthrough, or Interactive (iframe) embed.
 * Multiple prototypes get icon+superscript tabs (same pattern as storyboard).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Prototype_Display_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-prototype-display'; }
	public function get_title() {
		return __( 'Prototype Display', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-device-mobile'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// CONTENT — Prototypes
		$this->start_controls_section(
			'prototypes_section',
			array(
				'label' => __( 'Prototypes', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'name',
			array(
				'label'       => __( 'Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Prototype',
				'description' => __( 'Used as tab tooltip and for screen readers.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'tab_icon',
			array(
				'label'       => __( 'Tab Icon', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'description' => __( 'Optional. Per-prototype icon for this tab. If empty, the default icon from the Prototypes section is used.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'device',
			array(
				'label'   => __( 'Device', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'mobile'  => __( 'Mobile', 'ux-portfolio-optimized' ),
					'tablet'  => __( 'Tablet', 'ux-portfolio-optimized' ),
					'desktop' => __( 'Desktop', 'ux-portfolio-optimized' ),
				),
				'default' => 'mobile',
			)
		);

		$repeater->add_control(
			'show_frame',
			array(
				'label'       => __( 'Show Device Frame', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'When off, content renders raw — no frame, no padding, no rounded corners.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'display_mode',
			array(
				'label'   => __( 'Display Mode', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'image'       => __( 'Image', 'ux-portfolio-optimized' ),
					'video'       => __( 'Video Walkthrough', 'ux-portfolio-optimized' ),
					'interactive' => __( 'Interactive Embed (Figma / InVision / etc.)', 'ux-portfolio-optimized' ),
				),
				'default' => 'image',
			)
		);

		// Image mode
		$repeater->add_control(
			'image',
			array(
				'label'     => __( 'Prototype Image', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'default'   => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
				'condition' => array( 'display_mode' => 'image' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'External Link', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://figma.com/...',
				'description' => __( 'Optional. Wraps the image so clicking opens the link.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'display_mode' => 'image' ),
			)
		);

		// Video mode
		$repeater->add_control(
			'video_source',
			array(
				'label'     => __( 'Video Source', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'upload' => __( 'Upload', 'ux-portfolio-optimized' ),
					'url'    => __( 'External URL (YouTube / Vimeo / Google Drive)', 'ux-portfolio-optimized' ),
				),
				'default'   => 'upload',
				'condition' => array( 'display_mode' => 'video' ),
			)
		);

		$repeater->add_control(
			'video',
			array(
				'label'       => __( 'Video File', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_type'  => 'video',
				'description' => __( 'MP4 / WebM file. Plays inline with native controls.', 'ux-portfolio-optimized' ),
				'condition'   => array(
					'display_mode' => 'video',
					'video_source' => 'upload',
				),
			)
		);

		$repeater->add_control(
			'video_url',
			array(
				'label'       => __( 'Video URL', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://www.youtube.com/watch?v=... or https://vimeo.com/... or https://drive.google.com/file/d/.../view',
				'description' => __( 'Paste a YouTube watch URL, Vimeo URL, Google Drive share URL, or a direct .mp4 / .webm URL. The platform is auto-detected.', 'ux-portfolio-optimized' ),
				'condition'   => array(
					'display_mode' => 'video',
					'video_source' => 'url',
				),
			)
		);

		$repeater->add_control(
			'video_poster',
			array(
				'label'       => __( 'Poster (Thumbnail)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => __( 'Optional. Image shown before play.', 'ux-portfolio-optimized' ),
				'condition'   => array(
					'display_mode' => 'video',
					'video_source' => 'upload',
				),
			)
		);

		$repeater->add_control(
			'video_loop',
			array(
				'label'       => __( 'Loop', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'condition'   => array( 'display_mode' => 'video' ),
				'description' => __( 'For external URLs, applies to YouTube and Vimeo via URL params; Drive ignores it.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'video_muted',
			array(
				'label'       => __( 'Start Muted', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Required for autoplay to work in most browsers.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'display_mode' => 'video' ),
			)
		);

		$repeater->add_control(
			'video_autoplay',
			array(
				'label'     => __( 'Autoplay', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array( 'display_mode' => 'video' ),
			)
		);

		// Interactive mode
		$repeater->add_control(
			'embed_url',
			array(
				'label'       => __( 'Embed URL', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://www.figma.com/embed?embed_host=share&url=...',
				'description' => __( 'Paste the embed URL from Figma, InVision, Marvel, Adobe XD, etc. Use the platform\'s "Embed" or "Share" option to get this URL.', 'ux-portfolio-optimized' ),
				'condition'   => array( 'display_mode' => 'interactive' ),
			)
		);

		$repeater->add_control(
			'hide_embed_footer',
			array(
				'label'       => __( 'Hide Embed Footer', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Hides the platform branding bar at the bottom (e.g., Figma\'s "Edited X months ago" label).', 'ux-portfolio-optimized' ),
				'condition'   => array( 'display_mode' => 'interactive' ),
			)
		);

		$repeater->add_control(
			'hide_embed_navigation',
			array(
				'label'       => __( 'Hide Embed Navigation', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Hides the platform\'s prev/next/reset navigation chrome (Figma only — appends hide-ui=1 to the URL).', 'ux-portfolio-optimized' ),
				'condition'   => array( 'display_mode' => 'interactive' ),
			)
		);

		$repeater->add_control(
			'screen_aspect',
			array(
				'label'       => __( 'Screen Aspect Ratio', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''         => __( 'Default (by device)', 'ux-portfolio-optimized' ),
					'9 / 19.5' => __( 'Tall phone (9:19.5)', 'ux-portfolio-optimized' ),
					'9 / 16'   => __( 'Phone (9:16)', 'ux-portfolio-optimized' ),
					'3 / 4'    => __( 'Tablet (3:4)', 'ux-portfolio-optimized' ),
					'4 / 3'    => __( 'Tablet landscape (4:3)', 'ux-portfolio-optimized' ),
					'16 / 10'  => __( 'Desktop (16:10)', 'ux-portfolio-optimized' ),
					'16 / 9'   => __( 'Landscape (16:9)', 'ux-portfolio-optimized' ),
					'1 / 1'    => __( 'Square (1:1)', 'ux-portfolio-optimized' ),
					'custom'   => __( 'Custom (enter exact ratio)', 'ux-portfolio-optimized' ),
				),
				'condition'   => array( 'display_mode' => array( 'video', 'interactive' ) ),
				'description' => __( 'Match this to your video/prototype to remove black bars. Vimeo and YouTube letterbox when the frame ratio differs from the clip.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'screen_aspect_custom',
			array(
				'label'       => __( 'Custom Ratio (W / H)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '886 / 1918',
				'condition'   => array(
					'display_mode'  => array( 'video', 'interactive' ),
					'screen_aspect' => 'custom',
				),
				'description' => __( 'Enter your video\'s exact pixel dimensions as width / height (find them in Vimeo under the video\'s settings). Example: 886 / 1918.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'prototypes',
			array(
				'label'       => __( 'Prototypes', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'name'         => 'Prototype',
						'device'       => 'mobile',
						'display_mode' => 'image',
					),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->add_control(
			'tab_icon',
			array(
				'label'       => __( 'Default Tab Icon', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'eicon-device-mobile',
					'library' => 'eicons',
				),
				'description' => __( 'Default icon for tabs that don\'t set a per-prototype Tab Icon. Each prototype can override this in its own Tab Icon field.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// STYLE — Frame
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'       => __( 'Background Color', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'Color behind the device frame (the prototype display area).', 'ux-portfolio-optimized' ),
				'selectors'   => array( '{{WRAPPER}} .prototype-display' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'frame_color',
			array(
				'label'     => __( 'Frame Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				'default'   => '#1a1a1a',
				'selectors' => array( '{{WRAPPER}} .device-frame' => 'background: {{VALUE}};' ),
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
					'{{WRAPPER}} .prototype-tab__icon'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .prototype-tab__icon i' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.9);',
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
					'{{WRAPPER}} .prototype-tab__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .prototype-tab__icon svg' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .prototype-tab:hover .prototype-tab__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .prototype-tab:hover .prototype-tab__icon svg' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .prototype-tab.is-active .prototype-tab__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .prototype-tab.is-active .prototype-tab__icon svg' => 'color: {{VALUE}};',
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
		return $slug !== '' ? $slug : 'p';
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$prototypes = $s['prototypes'] ?? array();

		// Backward compat shim: if the new repeater is empty but legacy top-level
		// image data still exists from an older version, synthesize a single entry.
		if ( empty( $prototypes ) ) {
			$raw          = $this->get_settings();
			$legacy_image = $raw['image'] ?? null;
			if ( ! empty( $legacy_image ) && ( ! empty( $legacy_image['url'] ) || ! empty( $legacy_image['id'] ) ) ) {
				$prototypes = array(
					array(
						'name'         => 'Prototype',
						'device'       => $raw['device'] ?? 'mobile',
						'display_mode' => 'image',
						'image'        => $legacy_image,
						'link'         => $raw['link'] ?? array(),
					),
				);
			}
		}

		if ( empty( $prototypes ) ) {
			return;
		}

		$show_tabs = count( $prototypes ) > 1;
		$widget_id = $this->get_id();

		// Pre-compute unique slugs for data attributes.
		$slugs = array();
		$used  = array();
		foreach ( $prototypes as $i => $p ) {
			$base = $this->slugify( $p['name'] ?? ( 'p-' . ( $i + 1 ) ) );
			$slug = $base;
			$n    = 2;
			while ( isset( $used[ $slug ] ) ) {
				$slug = $base . '-' . $n;
				++$n;
			}
			$used[ $slug ] = true;
			$slugs[ $i ]   = $slug;
		}
		$active_slug = $slugs[0];
		?>

		<div class="prototype-wrapper" data-active-prototype="<?php echo esc_attr( $active_slug ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php
			if ( $show_tabs ) :
				$default_icon = $s['tab_icon'] ?? null;
				?>
				<div class="prototype-tabs" role="tablist">
					<?php
					foreach ( $prototypes as $i => $p ) :
						$slug    = $slugs[ $i ];
						$name    = $p['name'] ?? ( 'Prototype ' . ( $i + 1 ) );
						$tab_num = $i + 1;

						// Per-prototype icon overrides the widget-level default.
						$proto_icon     = $p['tab_icon'] ?? null;
						$proto_has_icon = ! empty( $proto_icon ) && ( ! empty( $proto_icon['value'] ) || ! empty( $proto_icon['url'] ) );
						$effective_icon = $proto_has_icon ? $proto_icon : $default_icon;
						$has_icon       = ! empty( $effective_icon ) && ( ! empty( $effective_icon['value'] ) || ! empty( $effective_icon['url'] ) );
						?>
						<button type="button"
								class="prototype-tab<?php echo $slug === $active_slug ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $slug === $active_slug ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( $name ); ?>"
								title="<?php echo esc_attr( $name ); ?>"
								data-target="<?php echo esc_attr( $slug ); ?>"><span class="prototype-tab__icon">
								<?php
								if ( $has_icon ) {
									\Elementor\Icons_Manager::render_icon( $effective_icon, array( 'aria-hidden' => 'true' ) );
								} else {
									?>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="6" y="2" width="12" height="20" rx="2"/><line x1="11" y1="18" x2="13" y2="18"/></svg><?php } ?></span><sup class="prototype-tab__num"><?php echo esc_html( $tab_num ); ?></sup></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $prototypes as $i => $p ) :
				$slug      = $slugs[ $i ];
				$is_active = ( $slug === $active_slug );

				$device = $p['device'] ?? 'mobile';
				if ( ! in_array( $device, array( 'mobile', 'tablet', 'desktop' ), true ) ) {
					$device = 'mobile';
				}

				$mode = $p['display_mode'] ?? 'image';
				if ( ! in_array( $mode, array( 'image', 'video', 'interactive' ), true ) ) {
					$mode = 'image';
				}

				$show_frame     = ( $p['show_frame'] ?? 'yes' ) === 'yes';
				$no_frame_class = $show_frame ? '' : ' no-frame';

				$hide_embed_footer = ( $mode === 'interactive' ) && ( ( $p['hide_embed_footer'] ?? '' ) === 'yes' );
				$hide_footer_class = $hide_embed_footer ? ' hide-embed-footer' : '';

				$screen_aspect = ( $mode === 'video' || $mode === 'interactive' ) ? trim( $p['screen_aspect'] ?? '' ) : '';
				if ( $screen_aspect === 'custom' ) {
					$raw = trim( $p['screen_aspect_custom'] ?? '' );
					if ( preg_match( '#(\d+(?:\.\d+)?)[^\d.]+(\d+(?:\.\d+)?)#', $raw, $cm ) ) {
						$screen_aspect = $cm[1] . ' / ' . $cm[2]; // any separator: / : x space "by"
					} elseif ( preg_match( '#^\d+(?:\.\d+)?$#', $raw ) ) {
						$screen_aspect = $raw; // single-number ratio
					} else {
						$screen_aspect = '';
					}
				}
				$screen_style = ( $screen_aspect !== '' ) ? ' style="aspect-ratio: ' . ux_portfolio_esc_css( $screen_aspect ) . ';"' : '';
				?>
				<div class="prototype-container<?php echo $is_active ? '' : ' is-hidden'; ?>"
					data-prototype="<?php echo esc_attr( $slug ); ?>">
					<div class="prototype-display device-<?php echo esc_attr( $device ); ?> mode-<?php echo esc_attr( $mode ); ?><?php echo esc_attr( $no_frame_class ); ?><?php echo esc_attr( $hide_footer_class ); ?>">
						<div class="device-frame">
							<div class="device-screen"<?php echo $screen_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with ux_portfolio_esc_css() ?>>
								<?php $this->render_prototype_content( $p, $mode ); ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}

	private function render_prototype_content( $p, $mode ) {
		if ( $mode === 'image' ) {
			$this->render_image_mode( $p );
		} elseif ( $mode === 'video' ) {
			$this->render_video_mode( $p );
		} else { // interactive
			$this->render_interactive_mode( $p );
		}
	}

	private function render_image_mode( $p ) {
		$img_url = $p['image']['url'] ?? '';
		if ( empty( $img_url ) && ! empty( $p['image']['id'] ) ) {
			$img_url = wp_get_attachment_url( $p['image']['id'] );
		}
		if ( empty( $img_url ) ) {
			return;
		}

		$link_url      = $p['link']['url'] ?? '';
		$link_external = ! empty( $p['link']['is_external'] );
		$link_nofollow = ! empty( $p['link']['nofollow'] );

		if ( ! empty( $link_url ) ) {
			$target    = $link_external ? ' target="_blank"' : '';
			$rel_parts = array();
			if ( $link_external ) {
				$rel_parts[] = 'noopener';
			}
			if ( $link_nofollow ) {
				$rel_parts[] = 'nofollow';
			}
			$rel = $rel_parts ? ' rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"' : '';
			?>
			<a href="<?php echo esc_url( $link_url ); ?>"<?php echo $target . $rel; ?> aria-label="<?php esc_attr_e( 'Open prototype', 'ux-portfolio-optimized' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- target/rel are fixed strings; rel built with esc_attr() ?>">
				<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy">
			</a>
			<?php
		} else {
			?>
			<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy">
			<?php
		}
	}

	private function render_video_mode( $p ) {
		$source   = $p['video_source'] ?? 'upload';
		$loop     = ( $p['video_loop'] ?? '' ) === 'yes';
		$muted    = ( $p['video_muted'] ?? '' ) === 'yes';
		$autoplay = ( $p['video_autoplay'] ?? '' ) === 'yes';

		if ( $source === 'url' ) {
			$url = trim( $p['video_url'] ?? '' );
			if ( empty( $url ) ) {
				return;
			}

			$info = $this->parse_video_url( $url );
			if ( ! $info ) {
				return;
			}

			if ( $info['type'] === 'video' ) {
				// Direct video file URL → render <video> tag the same way as uploads.
				?>
				<video class="prototype-video"
						controls
						playsinline
						preload="metadata"
						<?php
						if ( $loop ) :
							?>
							loop<?php endif; ?>
						<?php
						if ( $muted ) :
							?>
							muted<?php endif; ?>
						<?php
						if ( $autoplay ) :
							?>
							autoplay<?php endif; ?>>
					<source src="<?php echo esc_url( $info['src'] ); ?>">
					<?php esc_html_e( 'Your browser does not support embedded video.', 'ux-portfolio-optimized' ); ?>
				</video>
				<?php
				return;
			}

			// iframe-based embed (YouTube, Vimeo, Drive, or generic).
			$src = $info['src'];

			// Add autoplay / loop params for platforms that support them via URL.
			$params = array();
			if ( $info['platform'] === 'youtube' ) {
				if ( $autoplay ) {
					$params['autoplay'] = '1';
				}
				if ( $muted ) {
					$params['mute'] = '1';
				}
				if ( $loop ) {
					// YouTube loop requires playlist=VIDEO_ID alongside loop=1
					$params['loop'] = '1';
					if ( ! empty( $info['id'] ) ) {
						$params['playlist'] = $info['id'];
					}
				}
				$params['rel'] = '0'; // hide related videos at end
			} elseif ( $info['platform'] === 'vimeo' ) {
				if ( $autoplay ) {
					$params['autoplay'] = '1';
				}
				if ( $muted ) {
					$params['muted'] = '1';
				}
				if ( $loop ) {
					$params['loop'] = '1';
				}
			}
			// Google Drive embeds don't support autoplay, mute or loop via URL, so nothing is added for them.

			if ( ! empty( $params ) ) {
				$sep  = ( strpos( $src, '?' ) === false ) ? '?' : '&';
				$src .= $sep . http_build_query( $params );
			}

			$allow = $info['allow'] ?? 'autoplay; fullscreen; picture-in-picture';
			?>
			<iframe class="prototype-video-embed"
					src="<?php echo esc_url( $src ); ?>"
					loading="lazy"
					allowfullscreen
					allow="<?php echo esc_attr( $allow ); ?>"
					title="<?php echo esc_attr( $p['name'] ?? 'Prototype video' ); ?>"></iframe>
			<?php
			return;
		}

		// Upload mode (default).
		$video_url = $p['video']['url'] ?? '';
		if ( empty( $video_url ) && ! empty( $p['video']['id'] ) ) {
			$video_url = wp_get_attachment_url( $p['video']['id'] );
		}
		if ( empty( $video_url ) ) {
			return;
		}

		$poster_url = $p['video_poster']['url'] ?? '';
		if ( empty( $poster_url ) && ! empty( $p['video_poster']['id'] ) ) {
			$poster_url = wp_get_attachment_url( $p['video_poster']['id'] );
		}
		?>
		<video class="prototype-video"
				controls
				playsinline
				preload="metadata"
				<?php
				if ( $poster_url ) :
					?>
					poster="<?php echo esc_url( $poster_url ); ?>"<?php endif; ?>
				<?php
				if ( $loop ) :
					?>
					loop<?php endif; ?>
				<?php
				if ( $muted ) :
					?>
					muted<?php endif; ?>
				<?php
				if ( $autoplay ) :
					?>
					autoplay<?php endif; ?>>
			<source src="<?php echo esc_url( $video_url ); ?>">
			<?php esc_html_e( 'Your browser does not support embedded video.', 'ux-portfolio-optimized' ); ?>
		</video>
		<?php
	}

	/**
	 * Parse a video URL and return embed info, or null if unrecognized/empty.
	 * Returns:
	 *   ['type' => 'iframe', 'platform' => 'youtube|vimeo|drive|generic',
	 *    'src' => embed_url, 'id' => video_id (when known), 'allow' => allow_attr]
	 *   OR
	 *   ['type' => 'video', 'src' => direct_file_url]
	 */
	private function parse_video_url( $url ) {
		$url = trim( $url );
		if ( $url === '' ) {
			return null;
		}

		// YouTube — watch, short, or already-embed URL.
		if ( preg_match( '#(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})#', $url, $m ) ) {
			return array(
				'type'     => 'iframe',
				'platform' => 'youtube',
				'id'       => $m[1],
				'src'      => 'https://www.youtube.com/embed/' . $m[1],
				'allow'    => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen',
			);
		}

		// Vimeo — public, unlisted (with privacy hash), player, channel, group URLs.
		if ( preg_match( '#vimeo\.com/(?:video/|channels/[^/]+/|groups/[^/]+/videos/)?(\d+)#', $url, $m ) ) {
			$vid = $m[1];
			$src = 'https://player.vimeo.com/video/' . $vid;
			// Unlisted videos carry a privacy hash, as a /HASH path segment or ?h=HASH query.
			$hash = '';
			if ( preg_match( '#vimeo\.com/(?:video/)?\d+/([A-Za-z0-9]+)#', $url, $hm ) ) {
				$hash = $hm[1];
			} elseif ( preg_match( '#[?&]h=([A-Za-z0-9]+)#', $url, $hm ) ) {
				$hash = $hm[1];
			}
			if ( $hash !== '' ) {
				$src .= '?h=' . $hash;
			}
			return array(
				'type'     => 'iframe',
				'platform' => 'vimeo',
				'id'       => $vid,
				'src'      => $src,
				'allow'    => 'autoplay; fullscreen; picture-in-picture',
			);
		}

		// Google Drive — file/d/ID or open?id=ID.
		if ( preg_match( '#drive\.google\.com/file/d/([A-Za-z0-9_-]+)#', $url, $m ) ) {
			return array(
				'type'     => 'iframe',
				'platform' => 'drive',
				'id'       => $m[1],
				'src'      => 'https://drive.google.com/file/d/' . $m[1] . '/preview',
				'allow'    => 'autoplay; fullscreen',
			);
		}
		if ( preg_match( '#drive\.google\.com/open\?id=([A-Za-z0-9_-]+)#', $url, $m ) ) {
			return array(
				'type'     => 'iframe',
				'platform' => 'drive',
				'id'       => $m[1],
				'src'      => 'https://drive.google.com/file/d/' . $m[1] . '/preview',
				'allow'    => 'autoplay; fullscreen',
			);
		}

		// Direct video file (.mp4 / .webm / .mov / .ogg / .ogv).
		if ( preg_match( '#\.(mp4|webm|mov|ogg|ogv)(\?.*)?$#i', $url ) ) {
			return array(
				'type' => 'video',
				'src'  => $url,
			);
		}

		// Unknown — render as a generic iframe and hope for the best.
		return array(
			'type'     => 'iframe',
			'platform' => 'generic',
			'src'      => $url,
			'allow'    => 'autoplay; fullscreen; picture-in-picture',
		);
	}

	private function render_interactive_mode( $p ) {
		$embed_url = trim( $p['embed_url'] ?? '' );
		if ( empty( $embed_url ) ) {
			return;
		}

		// If "Hide Embed Navigation" is on and this is a Figma URL, append hide-ui=1.
		$hide_nav = ( $p['hide_embed_navigation'] ?? '' ) === 'yes';
		$is_figma = ( stripos( $embed_url, 'figma.com' ) !== false );
		if ( $hide_nav && $is_figma && stripos( $embed_url, 'hide-ui' ) === false ) {
			$sep        = ( strpos( $embed_url, '?' ) === false ) ? '?' : '&';
			$embed_url .= $sep . 'hide-ui=1';
		}
		?>
		<iframe class="prototype-embed"
				src="<?php echo esc_url( $embed_url ); ?>"
				loading="lazy"
				allowfullscreen
				allow="fullscreen"
				title="<?php echo esc_attr( $p['name'] ?? 'Interactive prototype' ); ?>"></iframe>
		<?php
	}
}
