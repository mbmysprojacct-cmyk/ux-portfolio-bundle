<?php
/**
 * Base Widget Class
 * Shared helper methods for all UX Portfolio widgets
 */

if (!defined('ABSPATH')) exit;

abstract class UX_Base_Widget extends \Elementor\Widget_Base {
    
    public function get_categories() {
        return ['ux-portfolio'];
    }
    
    /**
     * Add standard title and description fields
     */
    protected function add_standard_header_section() {
        $this->start_controls_section('header_section', [
            'label' => __('Header', 'ux-portfolio-optimized')
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no',
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $this->add_control('show_description', [
            'label' => __('Show Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no',
        ]);
        
        $this->add_control('description', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => '',
            'condition' => ['show_description' => 'yes'],
        ]);
        
        $this->end_controls_section();
    }
    
    /**
     * Add standard description alignment in style tab
     */
    protected function add_description_alignment() {
        $this->add_responsive_control('description_align', [
            'label' => __('Description Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
                'justify' => ['title' => __('Justified', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-justify'],
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .widget-description' => 'text-align: {{VALUE}};',
            ],
        ]);
    }
    
    /**
     * Render standard header HTML
     */
    protected function render_standard_header() {
        $s = $this->get_settings_for_display();
        
        if ($s['show_title'] === 'yes' && !empty($s['title'])) {
            echo '<h2 class="widget-title">' . esc_html($s['title']) . '</h2>';
        }
        
        if ($s['show_description'] === 'yes' && !empty($s['description'])) {
            echo '<div class="widget-description">' . wp_kses_post($s['description']) . '</div>';
        }
    }
    
    /**
     * Add color control with simplified syntax
     */
    protected function add_color($id, $label, $selector, $property = 'color', $default = '') {
        $this->add_control($id, [
            'label' => $label,
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => $default,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => $property . ': {{VALUE}};',
            ],
        ]);
    }
    
    /**
     * Add responsive number control (slider)
     */
    protected function add_responsive_number($id, $label, $selector, $property, $default = null, $min = 0, $max = 500) {
        $this->add_responsive_control($id, [
            'label' => $label,
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => $min, 'max' => $max],
            ],
            'default' => $default ? ['size' => $default, 'unit' => 'px'] : null,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => $property . ': {{SIZE}}{{UNIT}};',
            ],
        ]);
    }
    
    /**
     * Add typography control
     */
    protected function add_typography_control($id, $label, $selector) {
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $id,
                'label' => $label,
                'selector' => '{{WRAPPER}} ' . $selector,
            ]
        );
    }
    
    /**
     * Add background control
     */
    protected function add_background_control($id, $label, $selector) {
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => $id,
                'label' => $label,
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} ' . $selector,
            ]
        );
    }
    
    /**
     * Add border control
     */
    protected function add_border_control($id, $label, $selector) {
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $id,
                'label' => $label,
                'selector' => '{{WRAPPER}} ' . $selector,
            ]
        );
    }
    
    /**
     * Add box shadow control
     */
    protected function add_shadow_control($id, $label, $selector) {
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => $id,
                'label' => $label,
                'selector' => '{{WRAPPER}} ' . $selector,
            ]
        );
    }
    
    /**
     * Add padding control
     */
    protected function add_padding_control($id, $label, $selector) {
        $this->add_responsive_control($id, [
            'label' => $label,
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
    }
    
    /**
     * Add margin control
     */
    protected function add_margin_control($id, $label, $selector) {
        $this->add_responsive_control($id, [
            'label' => $label,
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
    }
    
    /**
     * Add border radius control
     */
    protected function add_border_radius($id, $label, $selector, $default = null) {
        $this->add_responsive_control($id, [
            'label' => $label,
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => $default,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
    }
    
    /**
     * Render icon safely
     */
    protected function render_icon($icon) {
        if (!empty($icon['value'])) {
            \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
        }
    }
    
    /**
     * Get unique ID for widget instance
     */
    protected function get_widget_id() {
        return 'ux-' . $this->get_name() . '-' . $this->get_id();
    }
}
