<?php
/**
 * Lens Switch — lets a visitor filter a case study by skill lens.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Lens_Switch_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-lens-switch'; }
	public function get_title() {
		return __( 'Lens Switch', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-filter'; }
	public function get_keywords() {
		return array( 'lens', 'filter', 'skills', 'case study', 'hiring' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content_section', array( 'label' => __( 'Lens Switch', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'label_text',
			array(
				'label'   => __( 'Label', 'ux-portfolio-optimized' ),
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
				'label'       => __( 'Buttons to show', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => array(
					'auto' => __( 'Only lenses used on this page', 'ux-portfolio-optimized' ),
					'all'  => __( 'Every lens', 'ux-portfolio-optimized' ),
				),
				'default'     => 'auto',
				'description' => __( 'With "Only lenses used", the switch hides itself on pages with no tagged sections.', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'mode',
			array(
				'label'   => __( 'Other sections', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'hide' => __( 'Hide them', 'ux-portfolio-optimized' ),
					'dim'  => __( 'Dim them', 'ux-portfolio-optimized' ),
				),
				'default' => 'hide',
			)
		);
		$this->add_control(
			'sync_url',
			array(
				'label'        => __( 'Update the link (?lens=)', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'Lets you share a link that opens with a lens already chosen, e.g. ?lens=ux.', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'show_note',
			array(
				'label'        => __( 'Show "sections hidden" note', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
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
				'selectors' => array( '{{WRAPPER}} .ux-lens' => 'justify-content: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text & border', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-lens' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'on_bg',
			array(
				'label'     => __( 'Selected background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-lens' => '--ux-lens-on-bg: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'on_ink',
			array(
				'label'     => __( 'Selected text', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-lens' => '--ux-lens-on-ink: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Label typography', 'ux-portfolio-optimized' ),
				'selector' => '{{WRAPPER}} .ux-lens__label',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Button typography', 'ux-portfolio-optimized' ),
				'selector' => '{{WRAPPER}} .ux-lens__btn, {{WRAPPER}} .ux-lens__note',
			)
		);
		$this->add_responsive_control(
			'gap',
			array(
				'label'     => __( 'Button gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors' => array( '{{WRAPPER}} .ux-lens' => '--ux-lens-gap: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$lenses = UX_Lens::lenses();
		$label  = isset( $s['label_text'] ) ? (string) $s['label_text'] : '';
		$all    = isset( $s['all_label'] ) && $s['all_label'] !== '' ? (string) $s['all_label'] : __( 'Everything', 'ux-portfolio-optimized' );
		$which  = ( isset( $s['which'] ) && $s['which'] === 'all' ) ? 'all' : 'auto';
		$mode   = ( isset( $s['mode'] ) && $s['mode'] === 'dim' ) ? 'dim' : 'hide';
		$sync   = ( isset( $s['sync_url'] ) && $s['sync_url'] === 'yes' ) ? 'yes' : 'no';
		$note   = ( isset( $s['show_note'] ) && $s['show_note'] === 'yes' ) ? 'yes' : 'no';
		$group  = 'ux-lens-' . $this->get_id();
		?>
		<div class="ux-lens"
			data-which="<?php echo esc_attr( $which ); ?>"
			data-mode="<?php echo esc_attr( $mode ); ?>"
			data-sync="<?php echo esc_attr( $sync ); ?>"
			data-note="<?php echo esc_attr( $note ); ?>"
			data-all-label="<?php echo esc_attr( $all ); ?>">
			<?php if ( $label !== '' ) : ?>
				<span class="ux-lens__label" id="<?php echo esc_attr( $group ); ?>"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
			<div class="ux-lens__group" role="group" <?php echo $label !== '' ? 'aria-labelledby="' . esc_attr( $group ) . '"' : 'aria-label="' . esc_attr__( 'Filter by skill', 'ux-portfolio-optimized' ) . '"'; ?>>
				<button type="button" class="ux-lens__btn is-active" data-lens-key="all" aria-pressed="true"><?php echo esc_html( $all ); ?></button>
				<?php foreach ( $lenses as $key => $text ) : ?>
					<button type="button" class="ux-lens__btn" data-lens-key="<?php echo esc_attr( $key ); ?>" aria-pressed="false"><?php echo esc_html( $text ); ?></button>
				<?php endforeach; ?>
			</div>
			<p class="ux-lens__note" aria-live="polite" hidden></p>
		</div>
		<?php
	}
}