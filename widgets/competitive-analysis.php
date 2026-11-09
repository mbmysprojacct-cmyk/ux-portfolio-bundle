<?php
/**
 * Competitive Analysis Widget - Optimized
 * From: 1,839 lines → To: ~200 lines (89% reduction)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Competitive_Analysis_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-competitive-analysis'; }
	public function get_title() {
		return __( 'Competitive Analysis', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-table'; }

	protected function register_controls() {

		// Add standard title/description
		$this->add_standard_header_section();

		$this->start_controls_section( 'content', array( 'label' => __( 'Competitors', 'ux-portfolio-optimized' ) ) );

		$comp_repeater = new \Elementor\Repeater();
		$comp_repeater->add_control(
			'competitor_name',
			array(
				'label'   => __( 'Competitor Name', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Competitor A',
			)
		);

		$this->add_control(
			'competitors',
			array(
				'label'       => __( 'Competitors', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $comp_repeater->get_controls(),
				'title_field' => '{{{ competitor_name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'features_section', array( 'label' => __( 'Features', 'ux-portfolio-optimized' ) ) );

		$feat_repeater = new \Elementor\Repeater();

		$feat_repeater->add_control(
			'feature_name',
			array(
				'label'   => __( 'Feature', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Feature Name',
			)
		);

		$feat_repeater->add_control(
			'ratings',
			array(
				'label'       => __( 'Ratings (comma-separated)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'Yes, No, Partial',
				'description' => __( 'Use: Yes, No, Partial (or ✓, ✗, ⚠)', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'features',
			array(
				'label'       => __( 'Features to Compare', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $feat_repeater->get_controls(),
				'title_field' => '{{{ feature_name }}}',
			)
		);

		$this->end_controls_section();
	}

	private function get_rating_icon( $rating ) {
		$rating = strtolower( trim( $rating ) );

		// Icon map
		$icons = array(
			'yes'     => '<span class="rating-icon rating-yes">✓</span>',
			'no'      => '<span class="rating-icon rating-no">✗</span>',
			'partial' => '<span class="rating-icon rating-partial">⚠</span>',
			'✓'       => '<span class="rating-icon rating-yes">✓</span>',
			'✗'       => '<span class="rating-icon rating-no">✗</span>',
			'⚠'       => '<span class="rating-icon rating-partial">⚠</span>',
		);

		return $icons[ $rating ] ?? esc_html( $rating );
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['competitors'] ) || empty( $s['features'] ) ) {
			echo '<p>' . esc_html__( 'Please add competitors and features.', 'ux-portfolio-optimized' ) . '</p>';
			return;
		}
		?>
		
		<div class="ux-competitive-analysis-wrapper">
			<?php $this->render_standard_header(); ?>
			
			<div class="ux-competitive-analysis">
			<table class="ux-competitive-table">
				<thead>
					<tr>
						<th><?php _e( 'Feature', 'ux-portfolio-optimized' ); ?></th>
						<?php foreach ( $s['competitors'] as $comp ) : ?>
							<th><?php echo esc_html( $comp['competitor_name'] ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $s['features'] as $feature ) :
						$ratings = array_map( 'trim', explode( ',', $feature['ratings'] ) );
						?>
						<tr>
							<td><strong><?php echo esc_html( $feature['feature_name'] ); ?></strong></td>
							<?php foreach ( $ratings as $rating ) : ?>
								<td><?php echo $this->get_rating_icon( $rating ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		</div>
		
		<style>
		.ux-competitive-table {
			width: 100%;
			border-collapse: collapse;
		}
		.ux-competitive-table th,
		.ux-competitive-table td {
			padding: 12px;
			text-align: left;
			border: 1px solid #e0e0e0;
		}
		.ux-competitive-table thead {
			background: #f8f9fa;
		}
		.ux-competitive-table th {
			font-weight: 600;
		}
		.ux-competitive-table td {
			text-align: center;
		}
		.ux-competitive-table td:first-child {
			text-align: left;
		}
		.rating-icon {
			font-size: 20px;
			font-weight: bold;
		}
		.rating-yes {
			color: #2E7D32;
		}
		.rating-no {
			color: #C62828;
		}
		.rating-partial {
			color: #B45309;
		}
		</style>
		
		<?php
	}
}
