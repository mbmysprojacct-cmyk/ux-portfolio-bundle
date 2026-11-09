<?php
/**
 * UI Kit Widget
 *
 * Shows the parts a product is built from: components grouped into tabs
 * (Buttons, Event cards, Navigation…), each with its states, the reasoning
 * behind it, the tokens it uses, an accessibility note and an optional
 * Do / Don't pair. Images only, so it works for any tool's exports.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Ui_Kit_Widget extends UX_Base_Widget {

	use UX_Tabbed_Trait;

	public function get_name() {
		return 'ux-ui-kit'; }
	public function get_title() {
		return __( 'UI Kit', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-kit-parts'; }
	public function get_keywords() {
		return array( 'ui kit', 'components', 'states', 'design system', 'buttons', 'cards' ); }

	protected function register_controls() {

		$this->add_standard_header_section();

		$this->start_controls_section( 'components_section', array( 'label' => __( 'Components', 'ux-portfolio-optimized' ) ) );

		$r = new \Elementor\Repeater();

		$r->add_control(
			'group',
			array(
				'label'       => __( 'Group', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Buttons', 'ux-portfolio-optimized' ),
				'description' => __( 'Components with the same group share a tab (when groups are enabled below).', 'ux-portfolio-optimized' ),
			)
		);
		$r->add_control(
			'name',
			array(
				'label'       => __( 'Component Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Primary button', 'ux-portfolio-optimized' ),
				'label_block' => true,
			)
		);
		$r->add_control(
			'image',
			array(
				'label'       => __( 'Main Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_types' => array( 'image', 'svg' ),
			)
		);
		$r->add_control(
			'stage',
			array(
				'label'   => __( 'Stage Background', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'light' => __( 'Light', 'ux-portfolio-optimized' ),
					'dark'  => __( 'Dark', 'ux-portfolio-optimized' ),
					'none'  => __( 'None', 'ux-portfolio-optimized' ),
				),
				'default' => 'light',
			)
		);
		$r->add_control(
			'states_heading',
			array(
				'label'     => __( 'States', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$r->add_control(
			'states',
			array(
				'label'       => __( 'State Images', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::GALLERY,
				'default'     => array(),
				'description' => __( 'Optional. One image per state, in order.', 'ux-portfolio-optimized' ),
			)
		);
		$r->add_control(
			'state_labels',
			array(
				'label'       => __( 'State Labels', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Default, Hover, Focus, Disabled',
				'description' => __( 'Comma-separated, in the same order as the images.', 'ux-portfolio-optimized' ),
			)
		);
		$r->add_control(
			'notes_heading',
			array(
				'label'     => __( 'Reasoning', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$r->add_control(
			'principle',
			array(
				'label'       => __( 'Principle', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Designed Mobile-First',
				'description' => __( 'Optional tag naming the principle or finding it answers.', 'ux-portfolio-optimized' ),
			)
		);
		$r->add_control(
			'why',
			array(
				'label'   => __( 'Why', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
			)
		);
		$r->add_control(
			'specs',
			array(
				'label'       => __( 'Spec Chips', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Sign red, Ultra 32, radius 0',
				'description' => __( 'Optional. Comma-separated tokens or specs.', 'ux-portfolio-optimized' ),
			)
		);
		$r->add_control(
			'a11y',
			array(
				'label'       => __( 'Accessibility Note', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => '5.9:1 contrast · 48px tap target · 3px focus ring',
			)
		);
		$r->add_control(
			'dodont_heading',
			array(
				'label'     => __( 'Do / Don\'t', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$r->add_control(
			'do_image',
			array(
				'label'       => __( 'Do Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_types' => array( 'image', 'svg' ),
			)
		);
		$r->add_control(
			'do_note',
			array(
				'label'       => __( 'Do Note', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$r->add_control(
			'dont_image',
			array(
				'label'       => __( 'Don\'t Image', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_types' => array( 'image', 'svg' ),
			)
		);
		$r->add_control(
			'dont_note',
			array(
				'label'       => __( 'Don\'t Note', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'components',
			array(
				'label'       => __( 'Components', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ group }}} · {{{ name }}}',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'     => __( 'Layout', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'stack' => __( 'Image beside notes', 'ux-portfolio-optimized' ),
					'cards' => __( 'Cards, two across', 'ux-portfolio-optimized' ),
				),
				'default'   => 'stack',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'lightbox',
			array(
				'label'        => __( 'Open Images in Lightbox', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'groups_section', array( 'label' => __( 'Groups', 'ux-portfolio-optimized' ) ) );
		$this->add_tab_controls( 'Group', 'grp' );
		$this->end_controls_section();
	}

	private function media_url( $m, $size = 'large' ) {
		$url = $m['url'] ?? '';
		if ( $url === '' && ! empty( $m['id'] ) ) {
			$url = (string) wp_get_attachment_image_url( absint( $m['id'] ), $size );
		}
		return (string) $url;
	}

	private function media_alt( $m, $fallback ) {
		$alt = trim( (string) ( $m['alt'] ?? '' ) );
		if ( $alt === '' && ! empty( $m['id'] ) ) {
			$alt = trim( (string) get_post_meta( absint( $m['id'] ), '_wp_attachment_image_alt', true ) );
		}
		return $alt !== '' ? $alt : $fallback;
	}

	private function img( $m, $alt, $lightbox, $slideshow, $title = '' ) {
		$url = $this->media_url( $m );
		if ( $url === '' ) {
			return '';
		}
		$full = ! empty( $m['id'] ) ? (string) wp_get_attachment_url( absint( $m['id'] ) ) : $url;
		$tag  = '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" decoding="async">';
		if ( ! $lightbox ) {
			return $tag;
		}
		return '<a href="' . esc_url( $full ) . '" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . esc_attr( $slideshow ) . '"'
			. ( $title !== '' ? ' data-elementor-lightbox-title="' . esc_attr( $title ) . '"' : '' ) . '>' . $tag . '</a>';
	}

	private function render_component( $c, $lightbox, $slideshow ) {
		$name  = trim( (string) ( $c['name'] ?? '' ) );
		$stage = in_array( ( $c['stage'] ?? 'light' ), array( 'light', 'dark', 'none' ), true ) ? $c['stage'] : 'light';
		$main  = $this->img( $c['image'] ?? array(), $this->media_alt( $c['image'] ?? array(), $name ), $lightbox, $slideshow, $name );

		$labels = array_map( 'trim', explode( ',', (string) ( $c['state_labels'] ?? '' ) ) );
		$states = ( ! empty( $c['states'] ) && is_array( $c['states'] ) ) ? $c['states'] : array();

		$specs     = array_filter( array_map( 'trim', explode( ',', (string) ( $c['specs'] ?? '' ) ) ), 'strlen' );
		$principle = trim( (string) ( $c['principle'] ?? '' ) );
		$why       = trim( (string) ( $c['why'] ?? '' ) );
		$a11y      = trim( (string) ( $c['a11y'] ?? '' ) );

		$do        = $this->media_url( $c['do_image'] ?? array() );
		$dont      = $this->media_url( $c['dont_image'] ?? array() );
		$do_note   = trim( (string) ( $c['do_note'] ?? '' ) );
		$dont_note = trim( (string) ( $c['dont_note'] ?? '' ) );
		?>
		<article class="ux-uikit-item">
			<?php if ( $main !== '' || $states ) : ?>
				<div class="ux-uikit-visual">
					<?php
					if ( $main !== '' ) :
						?>
						<div class="ux-uikit-stage is-<?php echo esc_attr( $stage ); ?>"><?php echo $main; // escaped in img() ?></div><?php endif; ?>
					<?php if ( $states ) : ?>
						<ul class="ux-uikit-states">
							<?php
							foreach ( $states as $i => $st ) :
								$label = $labels[ $i ] ?? '';
								$alt   = trim( $name . ( $label !== '' ? ', ' . $label . ' state' : '' ) );
								$html  = $this->img( $st, $this->media_alt( $st, $alt ), $lightbox, $slideshow, $alt );
								if ( $html === '' ) {
									continue;
								}
								?>
								<li class="ux-uikit-state">
									<div class="ux-uikit-state-img is-<?php echo esc_attr( $stage ); ?>"><?php echo $html; // escaped in img() ?></div>
									<?php
									if ( $label !== '' ) :
										?>
										<span class="ux-uikit-state-label"><?php echo esc_html( $label ); ?></span><?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="ux-uikit-body">
				<?php
				if ( $name !== '' ) :
					?>
					<h4 class="ux-uikit-name"><?php echo esc_html( $name ); ?></h4><?php endif; ?>
				<?php
				if ( $principle !== '' ) :
					?>
					<p class="ux-uikit-principle"><?php echo esc_html( $principle ); ?></p><?php endif; ?>
				<?php
				if ( $why !== '' ) :
					?>
					<div class="ux-uikit-why"><?php echo wp_kses_post( $why ); ?></div><?php endif; ?>
				<?php if ( $specs ) : ?>
					<ul class="ux-uikit-specs" aria-label="<?php esc_attr_e( 'Specs', 'ux-portfolio-optimized' ); ?>">
						<?php
						foreach ( $specs as $sp ) :
							?>
							<li><?php echo esc_html( $sp ); ?></li><?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php
				if ( $a11y !== '' ) :
					?>
					<p class="ux-uikit-a11y"><span class="ux-uikit-a11y-label"><?php esc_html_e( 'Accessibility', 'ux-portfolio-optimized' ); ?></span> <?php echo esc_html( $a11y ); ?></p><?php endif; ?>
				<?php if ( $do !== '' || $dont !== '' ) : ?>
					<div class="ux-uikit-dodont">
						<?php
						foreach ( array( array( 'do', $c['do_image'] ?? array(), $do_note, __( 'Do', 'ux-portfolio-optimized' ) ), array( 'dont', $c['dont_image'] ?? array(), $dont_note, __( 'Don’t', 'ux-portfolio-optimized' ) ) ) as $p ) :
							$html = $this->img( $p[1], $this->media_alt( $p[1], $p[3] . ': ' . ( $p[2] !== '' ? $p[2] : $name ) ), $lightbox, $slideshow, $p[3] );
							if ( $html === '' ) {
								continue;
							}
							?>
							<figure class="ux-uikit-<?php echo esc_attr( $p[0] ); ?>">
								<div class="ux-uikit-dd-img"><?php echo $html; // escaped in img() ?></div>
								<figcaption><strong><?php echo esc_html( $p[3] ); ?></strong><?php echo $p[2] !== '' ? ' ' . esc_html( $p[2] ) : ''; ?></figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['components'] ) || ! is_array( $s['components'] ) ) {
			return;
		}

		$layout    = ( $s['layout'] ?? 'stack' ) === 'cards' ? 'cards' : 'stack';
		$lightbox  = ( $s['lightbox'] ?? 'yes' ) === 'yes';
		$slideshow = 'uikit-' . $this->get_id();

		$enabled = ( $s['grp_enable'] ?? '' ) === 'yes';
		$buckets = $this->bucket_by( $s['components'], 'group', $enabled );
		$names   = array_keys( $buckets );
		$tabbed  = $enabled && ( ( $s['grp_show_tabs'] ?? 'yes' ) === 'yes' ) && count( $names ) > 1;
		$active  = $this->resolve_active( $names, $s['grp_default'] ?? '' );

		$this->open_tab_shell( $names, $tabbed, $active, 'ux-uikit-wrapper' );
		foreach ( $names as $i => $n ) {
			$this->tab_panel_open( $i, $n, $tabbed, $active, $enabled );
			echo '<div class="ux-uikit-list is-' . esc_attr( $layout ) . '">';
			foreach ( $buckets[ $n ] as $c ) {
				$this->render_component( $c, $lightbox, $slideshow );
			}
			echo '</div>';
			echo '</div>'; // close panel
		}
		$this->close_tab_shell();
		?>
		<style>
		.ux-uikit-list { display: grid; gap: 28px; }
		.ux-uikit-list.is-cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
		.ux-uikit-list.is-stack .ux-uikit-item { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); gap: 24px; align-items: start; }
		@media (max-width: 767px) { .ux-uikit-list.is-cards, .ux-uikit-list.is-stack .ux-uikit-item { grid-template-columns: 1fr; } }
		.ux-uikit-item { min-width: 0; }
		.ux-uikit-stage, .ux-uikit-state-img, .ux-uikit-dd-img { display: flex; align-items: center; justify-content: center; padding: 20px; border-radius: 8px; overflow: hidden; }
		.ux-uikit-stage.is-light, .ux-uikit-state-img.is-light, .ux-uikit-dd-img { background: #f6f6f6; }
		.ux-uikit-stage.is-dark, .ux-uikit-state-img.is-dark { background: #1f1f1f; }
		.ux-uikit-stage.is-none, .ux-uikit-state-img.is-none { padding: 0; }
		.ux-uikit-stage img, .ux-uikit-state-img img, .ux-uikit-dd-img img { max-width: 100%; height: auto; display: block; }
		.ux-uikit-states { list-style: none; margin: 10px 0 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(96px, 1fr)); gap: 8px; }
		.ux-uikit-state-img { padding: 10px; }
		.ux-uikit-state-label { display: block; margin-top: 4px; font-size: 12px; text-align: center; }
		.ux-uikit-name { margin: 0 0 4px; font-size: 18px; }
		.ux-uikit-principle { margin: 0 0 8px; font-size: 12px; letter-spacing: .08em; text-transform: uppercase; opacity: .75; }
		.ux-uikit-why p { margin: 0 0 8px; }
		.ux-uikit-specs { list-style: none; margin: 10px 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: 6px; }
		.ux-uikit-specs li { font-size: 12px; padding: 3px 8px; border: 1px solid currentColor; border-radius: 999px; opacity: .85; }
		.ux-uikit-a11y { margin: 10px 0 0; font-size: 13px; }
		.ux-uikit-a11y-label { font-weight: 600; }
		.ux-uikit-dodont { margin-top: 14px; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
		.ux-uikit-dodont figure { margin: 0; }
		.ux-uikit-dodont figcaption { margin-top: 6px; font-size: 13px; line-height: 1.4; }
		.ux-uikit-do .ux-uikit-dd-img { box-shadow: inset 0 -4px 0 #2e7d32; }
		.ux-uikit-dont .ux-uikit-dd-img { box-shadow: inset 0 -4px 0 #c62828; }
		</style>
		<?php
	}
}
