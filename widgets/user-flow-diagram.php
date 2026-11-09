<?php
/**
 * User Flow Diagram Widget
 * Flowchart with connected nodes and arrows.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_User_Flow_Diagram_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-user-flow-diagram'; }
	public function get_title() {
		return __( 'User Flow Diagram', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-flow'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		// Content
		$this->start_controls_section(
			'nodes_section',
			array(
				'label' => __( 'Flow Nodes', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'type',
			array(
				'label'   => __( 'Node Type', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'action'   => __( 'Action', 'ux-portfolio-optimized' ),
					'process'  => __( 'Process', 'ux-portfolio-optimized' ),
					'decision' => __( 'Decision', 'ux-portfolio-optimized' ),
				),
				'default' => 'process',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Step',
			)
		);

		$repeater->add_control(
			'flow',
			array(
				'label'       => __( 'Flow', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Optional. Type a Flow name (defined in the Flows panel) to put this node in that flow\'s tab. Leave blank for the default/first flow.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'nodes',
			array(
				'label'       => __( 'Nodes', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'type'  => 'action',
						'label' => 'Action',
					),
					array(
						'type'  => 'process',
						'label' => 'Process',
					),
					array(
						'type'  => 'decision',
						'label' => 'Decision?',
					),
					array(
						'type'  => 'action',
						'label' => 'Action',
					),
				),
				'title_field' => '{{{ label }}}{{{ flow ? " — " + flow : "" }}}',
			)
		);

		$this->end_controls_section();

		// Flows (tabs)
		$this->start_controls_section(
			'flows_section',
			array(
				'label' => __( 'Flows', 'ux-portfolio-optimized' ),
			)
		);

		$flow_repeater = new \Elementor\Repeater();

		$flow_repeater->add_control(
			'name',
			array(
				'label'       => __( 'Flow Name', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'Main',
				'description' => __( 'Used as the tab label. Match this in each node\'s Flow field.', 'ux-portfolio-optimized' ),
			)
		);

		$flow_repeater->add_control(
			'task_link',
			array(
				'label'       => __( 'Task List Link', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Optional URL', 'ux-portfolio-optimized' ),
				'description' => __( 'Optional. Shown above the task list view (e.g. link to a related user goal).', 'ux-portfolio-optimized' ),
			)
		);

		$flow_repeater->add_control(
			'task_link_label',
			array(
				'label'   => __( 'Task List Link Label', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'View related goal →',
			)
		);

		$this->add_control(
			'flows',
			array(
				'label'       => __( 'Flows', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $flow_repeater->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ name }}}',
				'description' => __( 'Optional. Define multiple flows. Tabs appear when more than one exists. Leave empty for a single untabbed flow.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'tab_icon',
			array(
				'label'       => __( 'Tab Icon', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'eicon-flow',
					'library' => 'eicons',
				),
				'description' => __( 'Icon shown on each flow tab. Pick from the library or upload your own SVG.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// Layout
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'direction',
			array(
				'label'        => __( 'Direction', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => array(
					'column' => __( 'Vertical', 'ux-portfolio-optimized' ),
					'row'    => __( 'Horizontal', 'ux-portfolio-optimized' ),
				),
				'default'      => 'column',
				'selectors'    => array(
					'{{WRAPPER}} .user-flow' => 'flex-direction: {{VALUE}};',
				),
				'prefix_class' => 'ux-flow-dir-%s',
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'     => __( 'Gap Between Nodes', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'   => array( 'size' => 15 ),
				'selectors' => array( '{{WRAPPER}} .user-flow' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'flow_padding',
			array(
				'label'      => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 30,
					'right'    => 30,
					'bottom'   => 30,
					'left'     => 30,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .user-flow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Start', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .user-flow' => 'align-items: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		// Nodes - common
		$this->start_controls_section(
			'nodes_style_section',
			array(
				'label' => __( 'Nodes', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'node_min_width',
			array(
				'label'     => __( 'Min Width', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 80,
						'max' => 500,
					),
				),
				'default'   => array( 'size' => 200 ),
				'selectors' => array( '{{WRAPPER}} .flow-node' => 'min-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'node_padding',
			array(
				'label'      => __( 'Padding', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 20,
					'right'    => 30,
					'bottom'   => 20,
					'left'     => 30,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .flow-node' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'node_typography',
				'selector' => '{{WRAPPER}} .flow-node',
			)
		);

		$this->end_controls_section();

		// Process / default node colors
		$this->start_controls_section(
			'process_style_section',
			array(
				'label' => __( 'Process Nodes', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'process_text',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-node.node-process' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'process_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array( '{{WRAPPER}} .flow-node.node-process' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'process_border',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-node.node-process' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'process_radius',
			array(
				'label'     => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array( '{{WRAPPER}} .flow-node.node-process' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'process_force_square',
			array(
				'label'       => __( 'Force Square Shape', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Locks process nodes to a 1:1 aspect ratio.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'process_size',
			array(
				'label'     => __( 'Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 80,
						'max' => 300,
					),
				),
				'default'   => array( 'size' => 150 ),
				'selectors' => array(
					'{{WRAPPER}} .flow-node.node-process' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'process_force_square' => 'yes' ),
			)
		);

		$this->add_control(
			'node_border_width',
			array(
				'label'     => __( 'Border Width (all nodes)', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 8,
					),
				),
				'default'   => array( 'size' => 2 ),
				'selectors' => array( '{{WRAPPER}} .flow-node' => 'border-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		// Action nodes
		$this->start_controls_section(
			'action_style_section',
			array(
				'label' => __( 'Action Nodes', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'action_text',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-node.node-action' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'action_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array( '{{WRAPPER}} .flow-node.node-action' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'action_border',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-node.node-action' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'action_size',
			array(
				'label'     => __( 'Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 60,
						'max' => 300,
					),
				),
				'default'   => array( 'size' => 120 ),
				'selectors' => array(
					'{{WRAPPER}} .flow-node.node-action' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'action_radius',
			array(
				'label'      => __( 'Border Radius', 'ux-portfolio-optimized' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => '%',
				),
				'selectors'  => array( '{{WRAPPER}} .flow-node.node-action' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		// Decision diamonds
		$this->start_controls_section(
			'decision_style_section',
			array(
				'label' => __( 'Decision Nodes', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'decision_text',
			array(
				'label'     => __( 'Text Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-node.node-decision' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'decision_bg',
			array(
				'label'     => __( 'Background', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array( '{{WRAPPER}} .flow-node.node-decision' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'decision_border',
			array(
				'label'     => __( 'Border Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-node.node-decision' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'decision_size',
			array(
				'label'     => __( 'Diamond Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 80,
						'max' => 300,
					),
				),
				'default'   => array( 'size' => 150 ),
				'selectors' => array(
					'{{WRAPPER}} .flow-node.node-decision' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Arrows
		$this->start_controls_section(
			'arrows_section',
			array(
				'label' => __( 'Arrows', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'arrow_style',
			array(
				'label'       => __( 'Arrow Style', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => array(
					'auto'  => __( 'Auto (matches direction)', 'ux-portfolio-optimized' ),
					'down'  => __( 'Down ↓', 'ux-portfolio-optimized' ),
					'up'    => __( 'Up ↑', 'ux-portfolio-optimized' ),
					'right' => __( 'Right →', 'ux-portfolio-optimized' ),
					'left'  => __( 'Left ←', 'ux-portfolio-optimized' ),
					'none'  => __( 'No Arrow', 'ux-portfolio-optimized' ),
				),
				'default'     => 'auto',
				'description' => __( '"Auto" uses ↓ for vertical, → for horizontal.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'arrow_size',
			array(
				'label'     => __( 'Arrow Size', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 80,
					),
				),
				'default'   => array( 'size' => 30 ),
				'selectors' => array( '{{WRAPPER}} .flow-arrow' => 'font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => __( 'Arrow Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .flow-arrow' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_weight',
			array(
				'label'     => __( 'Arrow Weight', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'400' => __( 'Regular', 'ux-portfolio-optimized' ),
					'600' => __( 'Semi-Bold', 'ux-portfolio-optimized' ),
					'700' => __( 'Bold', 'ux-portfolio-optimized' ),
					'900' => __( 'Extra Bold', 'ux-portfolio-optimized' ),
				),
				'default'   => '700',
				'selectors' => array( '{{WRAPPER}} .flow-arrow' => 'font-weight: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'decision_arrow_gap',
			array(
				'label'       => __( 'Extra Gap Around Decision Nodes', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'     => array( 'size' => 20 ),
				'description' => __( 'Adds breathing room between arrows and the diamond points.', 'ux-portfolio-optimized' ),
				'selectors'   => array(
					'{{WRAPPER}} .flow-node.node-decision' => 'margin: {{SIZE}}{{UNIT}};',
				),
			)
		);

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
					'{{WRAPPER}} .flow-tab__icon'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flow-tab__icon i' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.9);',
				),
			)
		);

		$this->start_controls_tabs( 'tab_icon_state_tabs' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => __( 'Normal', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'tab_icon_color',
			array(
				'label'       => __( 'Color', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .flow-tab__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .flow-tab__icon svg' => 'color: {{VALUE}};',
				),
				'description' => __( 'Works for icon-library icons and SVGs that use currentColor.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => __( 'Hover', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'tab_icon_hover_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .flow-tab:hover .flow-tab__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .flow-tab:hover .flow-tab__icon svg' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active',
			array(
				'label' => __( 'Active', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'tab_icon_active_color',
			array(
				'label'     => __( 'Color', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .flow-tab.is-active .flow-tab__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .flow-tab.is-active .flow-tab__icon svg' => 'color: {{VALUE}};',
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
		return $slug !== '' ? $slug : 'default';
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( empty( $s['nodes'] ) ) {
			return;
		}

		// Also grab raw settings (unsanitized) — Elementor's display sanitization
		// strips values not in the current dropdown (e.g. legacy 'start' / 'end').
		// We need the raw type to migrate legacy nodes to 'action'.
		$raw       = $this->get_settings();
		$raw_nodes = $raw['nodes'] ?? array();

		$arrow_style = $s['arrow_style'] ?? 'auto';

		// Resolve arrow character
		$arrow_map = array(
			'down'  => '↓',
			'up'    => '↑',
			'right' => '→',
			'left'  => '←',
			'none'  => '',
		);

		if ( $arrow_style === 'auto' ) {
			// Always render ↓; CSS rotates it to → when flex-direction is row.
			$arrow_char    = '↓';
			$arrow_classes = 'flow-arrow flow-arrow--auto';
		} else {
			$arrow_char    = $arrow_map[ $arrow_style ] ?? '↓';
			$arrow_classes = 'flow-arrow';
		}

		$count        = count( $s['nodes'] );
		$force_square = ( $s['process_force_square'] ?? '' ) === 'yes';

		// Build the ordered list of flows (slug => meta with name + link).
		$flow_names = array();
		$flow_meta  = array();
		$flows_def  = $s['flows'] ?? array();
		if ( ! empty( $flows_def ) && is_array( $flows_def ) ) {
			foreach ( $flows_def as $fl ) {
				$name = trim( $fl['name'] ?? '' );
				if ( $name === '' ) {
					continue;
				}
				$slug = $this->slugify( $name );
				if ( ! isset( $flow_names[ $slug ] ) ) {
					$flow_names[ $slug ] = $name;
					$flow_meta[ $slug ]  = array(
						'link_url'      => $fl['task_link']['url'] ?? '',
						'link_external' => ! empty( $fl['task_link']['is_external'] ),
						'link_nofollow' => ! empty( $fl['task_link']['nofollow'] ),
						'link_label'    => trim( $fl['task_link_label'] ?? '' ),
					);
				}
			}
		}

		// Default slug for nodes that don't specify a flow.
		$default_slug = '';
		if ( ! empty( $flow_names ) ) {
			$defined_keys = array_keys( $flow_names );
			$default_slug = $defined_keys[0];
		}

		// Group nodes by flow slug, preserving original index for raw_nodes lookup.
		$groups = array();
		foreach ( $s['nodes'] as $i => $node ) {
			$fl_name = trim( $node['flow'] ?? '' );
			if ( $fl_name !== '' ) {
				$slug = $this->slugify( $fl_name );
				if ( ! isset( $flow_names[ $slug ] ) ) {
					// Node references a flow not in the Flows panel — register it so it still shows.
					$flow_names[ $slug ] = $fl_name;
				}
			} elseif ( $default_slug !== '' ) {
					$slug = $default_slug;
			} else {
				$slug = 'default';
				if ( ! isset( $flow_names[ $slug ] ) ) {
					$flow_names[ $slug ] = 'Main';
				}
			}
			if ( ! isset( $groups[ $slug ] ) ) {
				$groups[ $slug ] = array();
			}
			$groups[ $slug ][] = array(
				'orig' => $i,
				'node' => $node,
			);
		}

		// Drop any flow slugs with no nodes.
		$slugs = array_values(
			array_filter(
				array_keys( $flow_names ),
				function ( $sl ) use ( $groups ) {
					return isset( $groups[ $sl ] ) && ! empty( $groups[ $sl ] );
				}
			)
		);

		if ( empty( $slugs ) ) {
			return;
		}

		$active_slug = $slugs[0];
		$show_tabs   = count( $slugs ) > 1;
		?>

		<div class="flow-wrapper" data-active-flow="<?php echo esc_attr( $active_slug ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php
			if ( $show_tabs ) :
				$tab_icon = $s['tab_icon'] ?? null;
				$has_icon = ! empty( $tab_icon ) && ( ! empty( $tab_icon['value'] ) || ! empty( $tab_icon['url'] ) );
				?>
				<div class="flow-tabs" role="tablist">
					<?php
					foreach ( $slugs as $tab_i => $slug ) :
						$tab_num = $tab_i + 1;
						?>
						<button type="button"
								class="flow-tab<?php echo $slug === $active_slug ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $slug === $active_slug ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( $flow_names[ $slug ] ); ?>"
								title="<?php echo esc_attr( $flow_names[ $slug ] ); ?>"
								data-target="<?php echo esc_attr( $slug ); ?>"><span class="flow-tab__icon">
								<?php
								if ( $has_icon ) {
									\Elementor\Icons_Manager::render_icon( $tab_icon, array( 'aria-hidden' => 'true' ) );
								} else {
									?>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg><?php } ?></span><sup class="flow-tab__num"><?php echo esc_html( $tab_num ); ?></sup></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $slugs as $slug ) :
				$group         = $groups[ $slug ];
				$group_count   = count( $group );
				$is_active     = ( $slug === $active_slug );
				$meta          = isset( $flow_meta[ $slug ] ) ? $flow_meta[ $slug ] : array();
				$link_url      = $meta['link_url'] ?? '';
				$link_external = ! empty( $meta['link_external'] );
				$link_nofollow = ! empty( $meta['link_nofollow'] );
				$link_label    = $meta['link_label'] ?? '';
				if ( $link_label === '' ) {
					$link_label = __( 'View related goal →', 'ux-portfolio-optimized' );
				}
				?>
				<div class="flow-container<?php echo $is_active ? '' : ' is-hidden'; ?>"
					data-flow="<?php echo esc_attr( $slug ); ?>"
					data-active-view="flowchart">

					<div class="flow-view-toggle" role="tablist" aria-label="<?php esc_attr_e( 'Choose view', 'ux-portfolio-optimized' ); ?>">
						<button type="button" class="view-tab is-active" role="tab" aria-selected="true" data-view="flowchart"><?php esc_html_e( 'Flowchart', 'ux-portfolio-optimized' ); ?></button>
						<button type="button" class="view-tab" role="tab" aria-selected="false" data-view="tasks"><?php esc_html_e( 'Task List', 'ux-portfolio-optimized' ); ?></button>
					</div>

					<div class="flow-view flow-view-flowchart">
						<div class="user-flow">
							<?php
							foreach ( $group as $local_i => $entry ) :
								$orig_i   = $entry['orig'];
								$node     = $entry['node'];
								$raw_type = $raw_nodes[ $orig_i ]['type'] ?? ( $node['type'] ?? 'process' );
								$type     = $raw_type;
								if ( $type === 'start' || $type === 'end' ) {
									$type = 'action';
								}
								if ( ! in_array( $type, array( 'action', 'process', 'decision' ), true ) ) {
									$type = 'process';
								}
								$node_classes = 'flow-node node-' . $type;
								if ( $type === 'process' && $force_square ) {
									$node_classes .= ' force-square';
								}
								?>
								<div class="<?php echo esc_attr( $node_classes ); ?>">
									<div class="node-content"><?php echo esc_html( $node['label'] ); ?></div>
								</div>
								<?php if ( $local_i < $group_count - 1 && $arrow_char !== '' ) : ?>
									<div class="<?php echo esc_attr( $arrow_classes ); ?>" aria-hidden="true"><?php echo esc_html( $arrow_char ); ?></div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="flow-view flow-view-tasks is-hidden">
						<?php
						if ( ! empty( $link_url ) ) :
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
							<a class="flow-task-link" href="<?php echo esc_url( $link_url ); ?>"<?php echo $target . $rel; ?>><?php echo esc_html( $link_label ); ?></a>
						<?php endif; ?>

						<ul class="flow-task-list">
							<?php
							foreach ( $group as $entry ) :
								$orig_i   = $entry['orig'];
								$node     = $entry['node'];
								$raw_type = $raw_nodes[ $orig_i ]['type'] ?? ( $node['type'] ?? 'process' );
								$type     = $raw_type;
								if ( $type === 'start' || $type === 'end' ) {
									$type = 'action';
								}
								if ( ! in_array( $type, array( 'action', 'process', 'decision' ), true ) ) {
									$type = 'process';
								}
								$li_classes = 'task-item task-' . $type;
								?>
								<li class="<?php echo esc_attr( $li_classes ); ?>">
									<?php if ( $type === 'decision' ) : ?>
										<span class="task-tag"><?php esc_html_e( 'Decision:', 'ux-portfolio-optimized' ); ?></span>
									<?php endif; ?>
									<?php echo esc_html( $node['label'] ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}
}
