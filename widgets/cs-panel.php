<?php
/**
 * Case Study Panel — the "remote": Hiring-for lens filter, On this page list,
 * expand / collapse all. Docked on wide screens, a drawer on tablets and a
 * bottom sheet on phones. Reads the Case Study Section widgets on the page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Cs_Panel_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-cs-panel'; }
	public function get_title() {
		return __( 'Case Study Panel', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-sidebar'; }
	public function get_keywords() {
		return array( 'case study', 'panel', 'filter', 'lens', 'contents', 'toc', 'remote' ); }
	public function get_style_depends() {
		return array( 'ux-cs-layout' ); }
	public function get_script_depends() {
		return array( 'ux-cs-layout' ); }

	protected function register_controls() {
		$this->start_controls_section( 'filter', array( 'label' => __( 'Hiring for', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Hiring for', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'all_label',
			array(
				'label'   => __( '"All" button', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Everything', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'which',
			array(
				'label'   => __( 'Buttons to show', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto' => __(
						'Only lenses used on this page',
						'ux-portfolio-optimized'
					),
					'all'  => __(
						'Every lens',
						'ux-portfolio-optimized'
					),
				),
			)
		);
		$this->add_control(
			'sync',
			array(
				'label'        => __( 'Keep ?lens= in the address', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __(
					'So a link like …/?lens=brand opens filtered.',
					'ux-portfolio-optimized'
				),
			)
		);
		$this->add_control(
			'start',
			array(
				'label'   => __( 'On wide screens, start', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'collapsed',
				'options' => array(
					'collapsed' => __(
						'Collapsed to a strip',
						'ux-portfolio-optimized'
					),
					'open'      => __(
						'Open',
						'ux-portfolio-optimized'
					),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'contents', array( 'label' => __( 'Contents', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'toc_heading',
			array(
				'label'   => __( 'Heading', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'On this page', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'expand_label',
			array(
				'label'   => __( 'Expand all', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Expand all', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'collapse_label',
			array(
				'label'   => __( 'Collapse all', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Collapse all', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'open_label',
			array(
				'label'   => __( 'Open button (tablet / phone)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Contents & filter', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'strip_label',
			array(
				'label'   => __( 'Collapsed strip', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Contents', 'ux-portfolio-optimized' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$lenses = array( 'all' => $s['all_label'] ) + UX_Cs_Section_Widget::lens_options();
		$pid    = 'mcs-panel-' . $this->get_id();
		$hid    = 'mcs-lens-h-' . $this->get_id();
		$all    = $s['all_label'];
		?>
		<button type="button" class="mcs-open-btn mcs-drawer-btn" data-mcs-open aria-controls="<?php echo esc_attr( $pid ); ?>" aria-expanded="false"><?php echo esc_html( $s['open_label'] ); ?> · <span data-mcs-lenslabel><?php echo esc_html( $all ); ?></span></button>
		<button type="button" class="mcs-open-btn mcs-fab" data-mcs-open aria-controls="<?php echo esc_attr( $pid ); ?>" aria-expanded="false"><?php echo esc_html( $s['open_label'] ); ?> · <span data-mcs-lenslabel><?php echo esc_html( $all ); ?></span></button>
		<aside class="mcs-panel<?php echo ( ( $s['start'] ?? 'collapsed' ) === 'collapsed' ) ? ' is-collapsed' : ''; ?>" id="<?php echo esc_attr( $pid ); ?>" aria-label="<?php esc_attr_e( 'Contents and filter', 'ux-portfolio-optimized' ); ?>"
				data-which="<?php echo esc_attr( $s['which'] ); ?>" data-start="<?php echo esc_attr( $s['start'] ?? 'collapsed' ); ?>" data-sync="<?php echo esc_attr( $s['sync'] ); ?>"
				data-close-label="<?php esc_attr_e( 'Close', 'ux-portfolio-optimized' ); ?>" data-collapse-label="<?php esc_attr_e( 'Collapse panel', 'ux-portfolio-optimized' ); ?>">
			<div class="mcs-panel__open">
				<div class="mcs-panel__head">
					<div class="mcs-panel__row">
						<span class="mcs-panel__h" id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( $s['heading'] ); ?></span>
						<button type="button" class="mcs-panel__x" aria-label="<?php esc_attr_e( 'Collapse panel', 'ux-portfolio-optimized' ); ?>">→</button>
					</div>
					<div class="mcs-lenses" role="group" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
						<?php foreach ( $lenses as $k => $name ) : ?>
							<button type="button" class="mcs-lens" data-lens="<?php echo esc_attr( $k ); ?>" aria-pressed="<?php echo $k === 'all' ? 'true' : 'false'; ?>"><?php echo esc_html( $k === 'all' ? $name : UX_Cs_Section_Widget::short_name( $k, $name ) ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="mcs-panel__toc">
					<button type="button" class="mcs-panel__tt" aria-expanded="true"><span><?php echo esc_html( $s['toc_heading'] ); ?></span><span aria-hidden="true">−</span></button>
					<ol class="mcs-toc"></ol>
					<div class="mcs-panel__tools">
						<button type="button" class="mcs-link-btn" data-mcs-expand="1"><?php echo esc_html( $s['expand_label'] ); ?></button>
						<button type="button" class="mcs-link-btn" data-mcs-expand="0"><?php echo esc_html( $s['collapse_label'] ); ?></button>
					</div>
				</div>
			</div>
			<button type="button" class="mcs-panel__strip" aria-label="<?php esc_attr_e( 'Show contents and filter', 'ux-portfolio-optimized' ); ?>">
				<span aria-hidden="true">←</span><span><?php echo esc_html( $s['strip_label'] ); ?> · <span data-mcs-lenslabel><?php echo esc_html( $all ); ?></span></span>
			</button>
		</aside>
		<?php
	}
}
