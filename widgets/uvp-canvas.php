<?php
/**
 * Value Proposition Canvas Widget - Optimized with UVP Section
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Uvp_Canvas_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-uvp-canvas'; }
	public function get_title() {
		return __( 'Value Proposition Canvas', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-flow'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Customer Jobs
		$this->start_controls_section( 'jobs_section', array( 'label' => __( 'Customer Jobs', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'jobs',
			array(
				'label'   => __( 'Jobs (one per line)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => "Complete tasks quickly\nStay organized\nMeet deadlines",
			)
		);

		$this->end_controls_section();

		// Pains
		$this->start_controls_section( 'pains_section', array( 'label' => __( 'Pains', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'pains',
			array(
				'label'   => __( 'Pains (one per line)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => "Too many tools\nSlow processes\nDifficult collaboration",
			)
		);

		$this->end_controls_section();

		// Gains
		$this->start_controls_section( 'gains_section', array( 'label' => __( 'Gains', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'gains',
			array(
				'label'   => __( 'Gains (one per line)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => "Save time\nIncrease productivity\nBetter outcomes",
			)
		);

		$this->end_controls_section();

		// Products & Services
		$this->start_controls_section( 'products_section', array( 'label' => __( 'Products & Services', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'products',
			array(
				'label'   => __( 'Products (one per line)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => "Task management app\nTeam collaboration tools\nAutomated workflows",
			)
		);

		$this->end_controls_section();

		// Gain Creators
		$this->start_controls_section( 'gain_creators_section', array( 'label' => __( 'Gain Creators', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'creators',
			array(
				'label'   => __( 'Gain Creators (one per line)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => "Fast processing\nIntuitive interface\nReal-time updates",
			)
		);

		$this->end_controls_section();

		// Pain Relievers
		$this->start_controls_section( 'pain_relievers_section', array( 'label' => __( 'Pain Relievers', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'relievers',
			array(
				'label'   => __( 'Pain Relievers (one per line)', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => "Single platform\nSimple workflows\nBuilt-in communication",
			)
		);

		$this->end_controls_section();

		// UVP SECTION
		$this->start_controls_section( 'uvp_section', array( 'label' => __( 'UVP List', 'ux-portfolio-optimized' ) ) );

		$this->add_control(
			'uvp_section_title',
			array(
				'label'   => __( 'Section Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Unique Value Propositions',
			)
		);

		$this->add_control(
			'uvp_right_column_title',
			array(
				'label'   => __( 'Right Column Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Why It Matters',
			)
		);

		$uvp_repeater = new \Elementor\Repeater();

		$uvp_repeater->add_control(
			'uvp_text',
			array(
				'label'   => __( 'UVP Text', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'Real-time collaborative planning tools',
			)
		);

		$uvp_repeater->add_control(
			'uvp_reason',
			array(
				'label'   => __( 'Reason/Benefit', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'Helps users co-plan without confusion',
			)
		);

		$this->add_control(
			'uvp_items',
			array(
				'label'       => __( 'UVP Items', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $uvp_repeater->get_controls(),
				'default'     => array(
					array(
						'uvp_text'   => 'Real-time collaborative planning',
						'uvp_reason' => 'Helps users co-plan',
					),
					array(
						'uvp_text'   => 'Curated local recommendations',
						'uvp_reason' => 'Authentic experiences',
					),
					array(
						'uvp_text'   => 'Offline access available',
						'uvp_reason' => 'Works without connectivity',
					),
				),
				'title_field' => '{{{ uvp_text }}}',
			)
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_description_alignment();

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		
		<div class="ux-uvp-canvas-wrapper">
			<?php $this->render_standard_header(); ?>
			
			<div class="ux-uvp-canvas">
				
				<div class="ux-uvp-section">
					<h3 class="ux-uvp-title"><?php esc_html_e( 'Customer Profile', 'ux-portfolio-optimized' ); ?></h3>
					
					<div><strong><?php esc_html_e( 'Jobs', 'ux-portfolio-optimized' ); ?></strong></div>
					<ul>
						<?php
						foreach ( explode( "\n", $s['jobs'] ) as $item ) :
							$item = trim( $item );
							if ( ! empty( $item ) ) :
								?>
								<li><?php echo esc_html( $item ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
					
					<div><strong><?php esc_html_e( 'Pains', 'ux-portfolio-optimized' ); ?></strong></div>
					<ul>
						<?php
						foreach ( explode( "\n", $s['pains'] ) as $item ) :
							$item = trim( $item );
							if ( ! empty( $item ) ) :
								?>
								<li><?php echo esc_html( $item ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
					
					<div><strong><?php esc_html_e( 'Gains', 'ux-portfolio-optimized' ); ?></strong></div>
					<ul>
						<?php
						foreach ( explode( "\n", $s['gains'] ) as $item ) :
							$item = trim( $item );
							if ( ! empty( $item ) ) :
								?>
								<li><?php echo esc_html( $item ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
				</div>
				
				<div class="ux-uvp-section">
					<h3 class="ux-uvp-title"><?php esc_html_e( 'Value Map', 'ux-portfolio-optimized' ); ?></h3>
					
					<div><strong><?php esc_html_e( 'Products & Services', 'ux-portfolio-optimized' ); ?></strong></div>
					<ul>
						<?php
						foreach ( explode( "\n", $s['products'] ) as $item ) :
							$item = trim( $item );
							if ( ! empty( $item ) ) :
								?>
								<li><?php echo esc_html( $item ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
					
					<div><strong><?php esc_html_e( 'Gain Creators', 'ux-portfolio-optimized' ); ?></strong></div>
					<ul>
						<?php
						foreach ( explode( "\n", $s['creators'] ) as $item ) :
							$item = trim( $item );
							if ( ! empty( $item ) ) :
								?>
								<li><?php echo esc_html( $item ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
					
					<div><strong><?php esc_html_e( 'Pain Relievers', 'ux-portfolio-optimized' ); ?></strong></div>
					<ul>
						<?php
						foreach ( explode( "\n", $s['relievers'] ) as $item ) :
							$item = trim( $item );
							if ( ! empty( $item ) ) :
								?>
								<li><?php echo esc_html( $item ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
				</div>
				
				<!-- UVP SECTION -->
				<?php if ( ! empty( $s['uvp_items'] ) ) : ?>
					<div class="ux-uvp-section ux-uvp-list">
						<?php if ( ! empty( $s['uvp_section_title'] ) ) : ?>
							<h3 class="ux-uvp-title"><?php echo esc_html( $s['uvp_section_title'] ); ?></h3>
						<?php endif; ?>
						
						<div class="uvp-checks-header">
							<div class="uvp-checks-left">
								<div class="uvp-check-item">
									<span class="uvp-check-icon">✓</span>
									<span class="uvp-check-text">Valuable</span>
								</div>
								<div class="uvp-check-item">
									<span class="uvp-check-icon">✓</span>
									<span class="uvp-check-text">Not widely offered by major competitors</span>
								</div>
							</div>
							<div class="uvp-checks-right">
								<span class="uvp-header-title"><?php echo esc_html( $s['uvp_right_column_title'] ); ?></span>
							</div>
						</div>
						
						<div class="uvp-list-table">
							<?php foreach ( $s['uvp_items'] as $item ) : ?>
								<div class="uvp-row">
									<div class="uvp-col uvp-col-left">
										<span class="uvp-text"><?php echo esc_html( $item['uvp_text'] ); ?></span>
									</div>
									<div class="uvp-col uvp-col-right">
										<span class="uvp-reason"><?php echo esc_html( $item['uvp_reason'] ); ?></span>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				
			</div>
		</div>
		
		<?php
	}
}
