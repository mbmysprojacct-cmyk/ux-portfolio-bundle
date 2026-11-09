<?php
/**
 * TL;DR Card — the 60-second version of a case study.
 * Fixed fields (problem, role, what I did, outcome, numbers) plus key moments
 * collected from elements marked "Key moment" (Advanced > Case Study Lens),
 * filtered by the active lens. ?view=tldr shows only this card.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Tldr_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-tldr'; }
	public function get_title() {
		return __( 'TL;DR Card', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-info-box'; }
	public function get_keywords() {
		return array( 'tldr', 'summary', 'overview', 'case study', 'key moments' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content_section', array( 'label' => __( 'Summary', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'eyebrow',
			array(
				'label'   => __( 'Eyebrow', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'TL;DR',
				'dynamic' => array( 'active' => true ),
			)
		);
		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'The 60-second version', 'ux-portfolio-optimized' ),
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
			)
		);
		foreach ( array(
			'problem' => array( __( 'Problem', 'ux-portfolio-optimized' ), __( 'Problem', 'ux-portfolio-optimized' ) ),
			'role'    => array( __( 'My role', 'ux-portfolio-optimized' ), __( 'My role', 'ux-portfolio-optimized' ) ),
			'did'     => array( __( 'What I did', 'ux-portfolio-optimized' ), __( 'What I did (one item per line)', 'ux-portfolio-optimized' ) ),
			'outcome' => array( __( 'Outcome', 'ux-portfolio-optimized' ), __( 'Outcome', 'ux-portfolio-optimized' ) ),
		) as $key => $labels ) {
			$this->add_control(
				$key . '_label',
				array(
					'label'     => sprintf( /* translators: %s: row name */ __( '%s label', 'ux-portfolio-optimized' ), $labels[0] ),
					'type'      => \Elementor\Controls_Manager::TEXT,
					'default'   => $labels[0],
					'separator' => 'before',
				)
			);
			$this->add_control(
				$key,
				array(
					'label'   => $labels[1],
					'type'    => \Elementor\Controls_Manager::TEXTAREA,
					'rows'    => 4,
					'dynamic' => array( 'active' => true ),
				)
			);
		}
		$this->end_controls_section();

		$this->start_controls_section( 'numbers_section', array( 'label' => __( 'Numbers', 'ux-portfolio-optimized' ) ) );
		$rep = new \Elementor\Repeater();
		$rep->add_control(
			'value',
			array(
				'label'   => __( 'Number', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '92%',
			)
		);
		$rep->add_control(
			'label',
			array(
				'label'       => __( 'Label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);
		$this->add_control(
			'numbers',
			array(
				'label'         => __( 'Numbers', 'ux-portfolio-optimized' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $rep->get_controls(),
				'default'       => array(),
				'prevent_empty' => false,
				'title_field'   => '{{{ value }}} {{{ label }}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'moments_section', array( 'label' => __( 'Key moments', 'ux-portfolio-optimized' ) ) );
		$this->add_control(
			'show_moments',
			array(
				'label'        => __( 'Show key moments', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Lists every element marked "Key moment" in Advanced > Case Study Lens, filtered by the active lens.', 'ux-portfolio-optimized' ),
			)
		);
		$this->add_control(
			'moments_label',
			array(
				'label'     => __( 'Label', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Key moments', 'ux-portfolio-optimized' ),
				'condition' => array( 'show_moments' => 'yes' ),
			)
		);
		$this->add_control(
			'show_thumbs',
			array(
				'label'        => __( 'Thumbnails', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'show_moments' => 'yes' ),
			)
		);
		$this->add_control(
			'full_label',
			array(
				'label'     => __( '"Full case study" link (short view)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => __( 'Read the full case study', 'ux-portfolio-optimized' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'copy_label',
			array(
				'label'       => __( 'Copy-link button', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Copy 60-second link', 'ux-portfolio-optimized' ),
				'description' => __( 'Copies a ?view=tldr link (keeps the chosen lens). Leave empty to hide.', 'ux-portfolio-optimized' ),
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
		$this->add_control(
			'ink',
			array(
				'label'     => __( 'Text & rules', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-tldr' => '--ux-tldr-ink: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'paper',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-tldr' => '--ux-tldr-paper: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'accent',
			array(
				'label'     => __( 'Numbers band', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-tldr' => '--ux-tldr-accent: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => __( 'Heading', 'ux-portfolio-optimized' ),
				'selector' => '{{WRAPPER}} .ux-tldr__heading',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Labels', 'ux-portfolio-optimized' ),
				'selector' => '{{WRAPPER}} .ux-tldr__eyebrow, {{WRAPPER}} .ux-tldr__term, {{WRAPPER}} .ux-tldr__num-label, {{WRAPPER}} .ux-tldr__moments-label',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'body_typography',
				'label'    => __( 'Body', 'ux-portfolio-optimized' ),
				'selector' => '{{WRAPPER}} .ux-tldr__def, {{WRAPPER}} .ux-tldr__moment, {{WRAPPER}} .ux-tldr__actions',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'label'    => __( 'Numbers', 'ux-portfolio-optimized' ),
				'selector' => '{{WRAPPER}} .ux-tldr__num-value',
			)
		);
		$this->end_controls_section();
	}

	private function text_block( $value ) {
		return nl2br( esc_html( trim( (string) $value ) ) );
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$id   = 'ux-tldr-' . $this->get_id();
		$rows = array();
		foreach ( array( 'problem', 'role', 'did', 'outcome' ) as $key ) {
			$val = isset( $s[ $key ] ) ? trim( (string) $s[ $key ] ) : '';
			if ( $val !== '' ) {
				$rows[ $key ] = array(
					'label' => (string) ( $s[ $key . '_label' ] ?? '' ),
					'value' => $val,
				);
			}
		}
		$numbers = ! empty( $s['numbers'] ) && is_array( $s['numbers'] ) ? $s['numbers'] : array();
		?>
		<section class="ux-tldr" aria-labelledby="<?php echo esc_attr( $id ); ?>">
			<header class="ux-tldr__head">
				<?php
				if ( ! empty( $s['eyebrow'] ) ) :
					?>
					<span class="ux-tldr__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span><?php endif; ?>
				<h2 class="ux-tldr__heading" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $s['heading'] ?? '' ); ?></h2>
			</header>
			<?php if ( $rows ) : ?>
				<dl class="ux-tldr__facts">
					<?php foreach ( $rows as $key => $row ) : ?>
						<div class="ux-tldr__fact ux-tldr__fact--<?php echo esc_attr( $key ); ?>">
							<dt class="ux-tldr__term"><?php echo esc_html( $row['label'] ); ?></dt>
							<dd class="ux-tldr__def">
								<?php
								if ( $key === 'did' ) :
									$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $row['value'] ) ) );
									?>
									<ul>
									<?php
									foreach ( $lines as $line ) :
										?>
										<li><?php echo esc_html( $line ); ?></li><?php endforeach; ?></ul>
									<?php
								else :
									echo $this->text_block( $row['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in text_block()
endif;
								?>
							</dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>
			<?php if ( $numbers ) : ?>
				<ul class="ux-tldr__numbers" role="list">
					<?php
					foreach ( $numbers as $n ) :
						if ( trim( (string) ( $n['value'] ?? '' ) ) === '' ) {
							continue;}
						?>
						<li class="ux-tldr__num"><span class="ux-tldr__num-value"><?php echo esc_html( $n['value'] ); ?></span><span class="ux-tldr__num-label"><?php echo esc_html( $n['label'] ?? '' ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( ( $s['show_moments'] ?? '' ) === 'yes' ) : ?>
				<nav class="ux-tldr__moments" aria-label="<?php echo esc_attr( $s['moments_label'] ?? __( 'Key moments', 'ux-portfolio-optimized' ) ); ?>" data-thumbs="<?php echo esc_attr( ( $s['show_thumbs'] ?? '' ) === 'yes' ? 'yes' : 'no' ); ?>" hidden>
					<span class="ux-tldr__moments-label"><?php echo esc_html( $s['moments_label'] ?? '' ); ?></span>
					<ol class="ux-tldr__moments-list"></ol>
				</nav>
			<?php endif; ?>
			<div class="ux-tldr__actions">
				<a class="ux-tldr__full" href="#" hidden><?php echo esc_html( $s['full_label'] ?? __( 'Read the full case study', 'ux-portfolio-optimized' ) ); ?> &rarr;</a>
				<?php if ( ! empty( $s['copy_label'] ) ) : ?>
					<button type="button" class="ux-tldr__copy" data-done="<?php esc_attr_e( 'Link copied', 'ux-portfolio-optimized' ); ?>"><?php echo esc_html( $s['copy_label'] ); ?></button>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}