<?php
/**
 * User Statements Widget
 * Structured UX research statements: User Story, If/Then Hypothesis, or Goal Statement.
 * Each repeater item picks its own type and fills the relevant fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_User_Statements_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-user-statements'; }
	public function get_title() {
		return __( 'User Statements', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-blockquote'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Content
		$this->start_controls_section(
			'statements_section',
			array(
				'label' => __( 'Statements', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'group',
			array(
				'label'       => __( 'Group', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => __( 'e.g. Primary Persona', 'ux-portfolio-optimized' ),
				'description' => __( 'Optional. Statements sharing a group name show together. Only used when Grouping is enabled below.', 'ux-portfolio-optimized' ),
			)
		);

		// Each statement picks its own type
		$repeater->add_control(
			'statement_type',
			array(
				'label'   => __( 'Statement Type', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'user_story' => __( 'User Story (As a… I want… So that…)', 'ux-portfolio-optimized' ),
					'problem'    => __( 'Problem Statement (X is a… who needs… because…)', 'ux-portfolio-optimized' ),
					'if_then'    => __( 'If/Then Hypothesis (If… Then… Because…)', 'ux-portfolio-optimized' ),
					'goal'       => __( 'Goal Statement (Persona needs… Because…)', 'ux-portfolio-optimized' ),
				),
				'default' => 'user_story',
			)
		);

		// User Story fields
		$repeater->add_control(
			'us_persona',
			array(
				'label'     => __( 'As a…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'frequent traveler',
				'condition' => array( 'statement_type' => 'user_story' ),
			)
		);

		$repeater->add_control(
			'us_want',
			array(
				'label'     => __( 'I want to…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'save all my itineraries in one place',
				'condition' => array( 'statement_type' => 'user_story' ),
			)
		);

		$repeater->add_control(
			'us_outcome',
			array(
				'label'     => __( 'So that…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'I can quickly reference plans on the go',
				'condition' => array( 'statement_type' => 'user_story' ),
			)
		);

		// Problem Statement fields — "[User] is a [characteristic] who needs [need] because [insight]."
		$repeater->add_control(
			'ps_user',
			array(
				'label'       => __( 'User Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Lena',
				'placeholder' => __( 'e.g. Lena', 'ux-portfolio-optimized' ),
				'condition'   => array( 'statement_type' => 'problem' ),
			)
		);

		$repeater->add_control(
			'ps_characteristic',
			array(
				'label'     => __( 'Is a… (characteristic)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'frequent city traveller who plans trips on her phone',
				'condition' => array( 'statement_type' => 'problem' ),
			)
		);

		$repeater->add_control(
			'ps_need',
			array(
				'label'     => __( 'Who needs… (need)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'a way to save places without switching between apps',
				'condition' => array( 'statement_type' => 'problem' ),
			)
		);

		$repeater->add_control(
			'ps_insight',
			array(
				'label'     => __( 'Because… (insight)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'losing track of recommendations makes her abandon planning altogether',
				'condition' => array( 'statement_type' => 'problem' ),
			)
		);

		// If/Then fields
		$repeater->add_control(
			'ift_if',
			array(
				'label'     => __( 'If we…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'add a shared itinerary feature',
				'condition' => array( 'statement_type' => 'if_then' ),
			)
		);

		$repeater->add_control(
			'ift_then',
			array(
				'label'     => __( 'Then…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'group travelers will plan trips together more easily',
				'condition' => array( 'statement_type' => 'if_then' ),
			)
		);

		$repeater->add_control(
			'ift_because',
			array(
				'label'     => __( 'Because… (optional)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'coordinating plans across multiple apps causes confusion',
				'condition' => array( 'statement_type' => 'if_then' ),
			)
		);

		// Goal Statement fields
		$repeater->add_control(
			'goal_product',
			array(
				'label'       => __( 'Product/feature', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Our itinerary planning tool',
				'description' => __( 'Will appear as: "[Product] will let…"', 'ux-portfolio-optimized' ),
				'condition'   => array( 'statement_type' => 'goal' ),
			)
		);

		$repeater->add_control(
			'goal_action',
			array(
				'label'     => __( 'Will let users…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 3,
				'default'   => 'filter and customize their travel plans based on time, budget, and mobility preferences',
				'condition' => array( 'statement_type' => 'goal' ),
			)
		);

		$repeater->add_control(
			'goal_audience',
			array(
				'label'     => __( 'Which will affect (audience)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 2,
				'default'   => 'budget-conscious and efficiency-minded travelers',
				'condition' => array( 'statement_type' => 'goal' ),
			)
		);

		$repeater->add_control(
			'goal_impact',
			array(
				'label'     => __( 'By (impact)…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 3,
				'default'   => 'reducing planning friction and helping them make faster, more confident decisions',
				'condition' => array( 'statement_type' => 'goal' ),
			)
		);

		$repeater->add_control(
			'goal_measure',
			array(
				'label'     => __( 'We will measure effectiveness by…', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 3,
				'default'   => 'using scenario-based usability tests that observe completion rates, decision-making time, and self-reported confidence scores',
				'condition' => array( 'statement_type' => 'goal' ),
			)
		);

		// Label (shown in repeater header)
		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => __( 'e.g. Discovery friction', 'ux-portfolio-optimized' ),
				'description' => __( 'Optional heading shown on the card itself. Leave blank for none.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'       => __( 'Label (internal)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional name for this statement, shown in the editor list only.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'anchor_id',
			array(
				'label'       => __( 'Anchor ID', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Letters, numbers, and hyphens only (e.g. "goal-checkout"). Other widgets can link to this card with #your-id.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'statements',
			array(
				'label'       => __( 'Statements', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'label' => 'Statement 1' ),
				),
				'title_field' => '{{{ group ? group + " — " : "" }}}{{{ label || title || "Statement" }}}',
			)
		);

		$this->end_controls_section();

		// Grouping
		$this->start_controls_section(
			'grouping_section',
			array(
				'label' => __( 'Grouping', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'enable_groups',
			array(
				'label'       => __( 'Enable Grouping', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Group statements under named headings, or switch between groups with tabs. Give each statement above a Group name.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'group_display',
			array(
				'label'     => __( 'Display As', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'headings',
				'options'   => array(
					'headings' => __( 'Headings (all groups visible)', 'ux-portfolio-optimized' ),
					'tabs'     => __( 'Tabs (one group at a time)', 'ux-portfolio-optimized' ),
				),
				'condition' => array( 'enable_groups' => 'yes' ),
			)
		);

		$this->add_control(
			'default_tab',
			array(
				'label'       => __( 'Default Tab', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					'enable_groups' => 'yes',
					'group_display' => 'tabs',
				),
				'description' => __( 'Name of the group to open first. Leave blank for the first one.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'untitled_label',
			array(
				'label'       => __( 'Ungrouped Heading', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Other', 'ux-portfolio-optimized' ),
				'condition'   => array( 'enable_groups' => 'yes' ),
				'description' => __( 'Heading used for statements you left without a group name.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_description_alignment();

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
				),
				'default'        => '2',
				'tablet_default' => '1',
				'mobile_default' => '1',
				'selectors'      => array(
					'{{WRAPPER}} .ux-statements__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'     => __( 'Grid Gap', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'   => array( 'size' => 24 ),
				'selectors' => array( '{{WRAPPER}} .ux-statements__grid' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		// Card
		$this->add_control(
			'card_heading',
			array(
				'label'     => __( 'Card', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-statements__card' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'   => array( 'size' => 12 ),
				'selectors' => array( '{{WRAPPER}} .ux-statements__card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array( '{{WRAPPER}} .ux-statements__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .ux-statements__card',
			)
		);

		// Labels (As a, I want, So that, etc.)
		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Card Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'       => __( 'Title Color', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'default'     => '',
				'description' => __( 'Leave empty to inherit your global styles.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .ux-statements__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_typography_control( 'title_typography', __( 'Title Typography', 'ux-portfolio-optimized' ), '.ux-statements__title' );

		$this->add_control(
			'label_heading',
			array(
				'label'     => __( 'Field Labels', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-statements__field-label' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .ux-statements__field-label',
			)
		);

		// Statement text (the user's content)
		$this->add_control(
			'text_heading',
			array(
				'label'     => __( 'Statement Text', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ux-statements__text' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .ux-statements__text',
			)
		);

		$this->end_controls_section();
	}

	/** Renders a grid of statement cards. */
	private function render_cards( $items ) {
		?>
		<div class="ux-statements__grid">
			<?php
			foreach ( $items as $item ) :
				$type = $item['statement_type'] ?? 'user_story';

				// Sanitize the anchor id: lowercase, letters/digits/hyphens/underscores only.
				$anchor_id  = '';
				$raw_anchor = trim( $item['anchor_id'] ?? '' );
				if ( $raw_anchor !== '' ) {
					$anchor_id = preg_replace( '/[^a-z0-9_-]+/', '-', strtolower( $raw_anchor ) );
					$anchor_id = trim( $anchor_id, '-' );
				}
				?>
				<div class="ux-statements__card ux-statements--<?php echo esc_attr( str_replace( '_', '-', $type ) ); ?>"
				<?php
				if ( $anchor_id !== '' ) {
					echo ' id="' . esc_attr( $anchor_id ) . '"';}
				?>
				>
					<?php $card_title = trim( $item['title'] ?? '' ); ?>
					<?php if ( $card_title !== '' ) : ?>
						<h4 class="ux-statements__title"><?php echo esc_html( $card_title ); ?></h4>
					<?php endif; ?>
					<?php $this->render_statement( $item, $type ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( empty( $s['statements'] ) ) {
			return;
		}

		$use_groups = ( $s['enable_groups'] ?? '' ) === 'yes';
		$display    = ( $s['group_display'] ?? 'headings' ) === 'tabs' ? 'tabs' : 'headings';
		$untitled   = trim( $s['untitled_label'] ?? '' );
		if ( $untitled === '' ) {
			$untitled = __( 'Other', 'ux-portfolio-optimized' );
		}

		// Bucket statements by group, preserving first-seen order.
		$groups = array();
		foreach ( $s['statements'] as $item ) {
			$g = $use_groups ? trim( $item['group'] ?? '' ) : '';
			if ( $g === '' ) {
				$g = $use_groups ? $untitled : '__all__';
			}
			if ( ! isset( $groups[ $g ] ) ) {
				$groups[ $g ] = array();
			}
			$groups[ $g ][] = $item;
		}

		$names  = array_keys( $groups );
		$tabbed = $use_groups && ( $display === 'tabs' ) && count( $names ) > 1;

		// Which tab opens first.
		$active      = 0;
		$default_tab = trim( $s['default_tab'] ?? '' );
		if ( $tabbed && $default_tab !== '' ) {
			foreach ( $names as $i => $n ) {
				if ( strcasecmp( $n, $default_tab ) === 0 ) {
					$active = $i;
					break;
				}
			}
		}
		?>

		<div class="ux-statements-wrapper" data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php if ( $tabbed ) : ?>
				<div class="statements-tabs" role="tablist">
					<?php
					foreach ( $names as $i => $n ) :
						$is_on = ( $i === $active );
						?>
						<button type="button"
								class="statements-tab<?php echo $is_on ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $is_on ? 'true' : 'false'; ?>"
								data-statements-tab="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $n ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $names as $i => $n ) :
				$hidden = $tabbed && ( $i !== $active );
				?>
				<div class="statements-panel<?php echo $hidden ? ' is-hidden' : ''; ?>" data-statements-panel="<?php echo esc_attr( $i ); ?>">
					<?php if ( ! $tabbed && $use_groups && $n !== '__all__' ) : ?>
						<h3 class="statements-group__name"><?php echo esc_html( $n ); ?></h3>
					<?php endif; ?>
					<?php $this->render_cards( $groups[ $n ] ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}

	private function render_statement( $item, $type ) {
		if ( $type === 'user_story' ) {
			$this->render_part( __( 'As a', 'ux-portfolio-optimized' ), $item['us_persona'] ?? '' );
			$this->render_part( __( 'I want to', 'ux-portfolio-optimized' ), $item['us_want'] ?? '' );
			$this->render_part( __( 'So that', 'ux-portfolio-optimized' ), $item['us_outcome'] ?? '' );
		} elseif ( $type === 'problem' ) {
			// "[User] is a [characteristic] who needs [need] because [insight]."
			$this->render_part( '', $item['ps_user'] ?? '', 'ux-statements__subject' );
			$this->render_part( __( 'is a', 'ux-portfolio-optimized' ), $item['ps_characteristic'] ?? '' );
			$this->render_part( __( 'who needs', 'ux-portfolio-optimized' ), $item['ps_need'] ?? '' );
			$this->render_part( __( 'because', 'ux-portfolio-optimized' ), $item['ps_insight'] ?? '' );
		} elseif ( $type === 'if_then' ) {
			$this->render_part( __( 'If we', 'ux-portfolio-optimized' ), $item['ift_if'] ?? '' );
			$this->render_part( __( 'Then', 'ux-portfolio-optimized' ), $item['ift_then'] ?? '' );
			if ( ! empty( $item['ift_because'] ) ) {
				$this->render_part( __( 'Because', 'ux-portfolio-optimized' ), $item['ift_because'] );
			}
		} elseif ( $type === 'goal' ) {
			$this->render_part( __( 'Product', 'ux-portfolio-optimized' ), $item['goal_product'] ?? '' );
			$this->render_part( __( 'Will let users', 'ux-portfolio-optimized' ), $item['goal_action'] ?? '' );
			$this->render_part( __( 'Which will affect', 'ux-portfolio-optimized' ), $item['goal_audience'] ?? '' );
			$this->render_part( __( 'By', 'ux-portfolio-optimized' ), $item['goal_impact'] ?? '' );
			$this->render_part( __( 'We will measure effectiveness by', 'ux-portfolio-optimized' ), $item['goal_measure'] ?? '' );
		}
	}

	private function render_part( $label, $text, $extra_class = '' ) {
		if ( empty( $text ) ) {
			return;
		}
		?>
		<div class="ux-statements__part <?php echo esc_attr( $extra_class ); ?>">
			<?php if ( ! empty( $label ) ) : ?>
				<span class="ux-statements__field-label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
			<span class="ux-statements__text"><?php echo esc_html( $text ); ?></span>
		</div>
		<?php
	}
}
