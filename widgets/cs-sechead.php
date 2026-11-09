<?php
/**
 * Case Study Section Header — put it first in a container to make that
 * container a case study section: number, label, lenses, heading, summary.
 * Everything else in the container is ordinary Elementor widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Cs_Sechead_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-cs-sechead'; }
	public function get_title() {
		return __( 'Case Study Section Header', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-heading'; }
	public function get_keywords() {
		return array( 'case study', 'section', 'header', 'lens', 'heading' ); }
	public function get_style_depends() {
		return array( 'ux-cs-layout' ); }
	public function get_script_depends() {
		return array( 'ux-cs-layout' ); }

	protected function register_controls() {
		$this->start_controls_section( 'meta', array( 'label' => __( 'Section', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'help',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Place this first in a container. The container becomes a case study section: it gets the lens filter, a place in "On this page", and the section styling. Add any widgets below it.', 'ux-portfolio-optimized' ),
				'content_classes' => 'elementor-descriptor',
			)
		);
		$this->add_control(
			'number',
			array(
				'label'   => __( 'Number', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '01',
			)
		);
		$this->add_control(
			'anchor',
			array(
				'label'       => __( 'Anchor ID', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __(
					'Used in links like #cs-s01. Blank = s + the number.',
					'ux-portfolio-optimized'
				),
			)
		);
		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Client work', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'lenses',
			array(
				'label'       => __( 'Lenses', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => array( 'all' => __( 'All roles', 'ux-portfolio-optimized' ) ) + UX_Cs_Section_Widget::lens_options(),
				'default'     => array( 'all' ),
				'description' => __( 'Which "Hiring for" filters show this section.', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'lens_text',
			array(
				'label'   => __( 'Lens text (optional)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			)
		);
		$this->add_control(
			'short',
			array(
				'label'       => __( 'Contents label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __(
					'Short name in "On this page". Blank = the heading.',
					'ux-portfolio-optimized'
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'content', array( 'label' => __( 'Heading', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'title',
			array(
				'label'       => __( 'Heading', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Section heading.', 'ux-portfolio-optimized' ),
				'dynamic'     => array( 'active' => true ),
			)
		);
		$this->add_control(
			'summary',
			array(
				'label'   => __( 'Summary', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$num    = trim( (string) ( $s['number'] ?? '' ) );
		$anchor = ! empty( $s['anchor'] ) ? sanitize_title( $s['anchor'] ) : ( 's' . sanitize_title( $num !== '' ? $num : $this->get_id() ) );
		$opts   = UX_Cs_Section_Widget::lens_options();
		$lenses = array_values( array_filter( array_map( 'sanitize_key', (array) ( $s['lenses'] ?? array() ) ) ) );
		if ( empty( $lenses ) ) {
			$lenses = array( 'all' );
		}
		if ( ! empty( $s['lens_text'] ) ) {
			$lens_text = $s['lens_text'];
		} elseif ( in_array( 'all', $lenses, true ) ) {
			$lens_text = __( 'All roles', 'ux-portfolio-optimized' );
		} else {
			$names = array();
			foreach ( $lenses as $k ) {
				if ( isset( $opts[ $k ] ) ) {
					$names[] = UX_Cs_Section_Widget::short_name( $k, $opts[ $k ] );
				}
			}
			$lens_text = implode( ' · ', $names );
		}
		$title = (string) ( $s['title'] ?? '' );
		$short = ! empty( $s['short'] ) ? $s['short'] : wp_strip_all_tags( $title );
		?>
		<div class="mcs-sechead" data-anchor="<?php echo esc_attr( $anchor ); ?>" data-lens="<?php echo esc_attr( implode( ' ', $lenses ) ); ?>"
			data-n="<?php echo esc_attr( $num ); ?>" data-short="<?php echo esc_attr( $short ); ?>">
			<div class="mcs-sec__meta">
				<?php
				if ( $num !== '' ) :
					?>
					<span class="mcs-sec__n"><?php echo esc_html( $num ); ?></span><?php endif; ?>
				<?php
				if ( ! empty( $s['label'] ) ) :
					?>
					<span class="mcs-label"><?php echo esc_html( $s['label'] ); ?></span><?php endif; ?>
				<?php
				if ( $lens_text !== '' ) :
					?>
					<span class="mcs-label mcs-label--sm mcs-label--muted mcs-sec__lens"><?php echo esc_html( $lens_text ); ?></span><?php endif; ?>
			</div>
			<h2 class="mcs-sec__title" id="<?php echo esc_attr( 'h-' . $anchor ); ?>"><?php echo esc_html( $title ); ?></h2>
			<?php
			if ( ! empty( $s['summary'] ) ) :
				?>
				<div class="mcs-sec__sum"><?php echo wp_kses_post( $s['summary'] ); ?></div><?php endif; ?>
		</div>
		<?php
	}
}
