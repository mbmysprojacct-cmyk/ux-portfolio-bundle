<?php
/**
 * Description Widget
 *
 * A standalone title + description block, using the exact same markup and
 * classes (.widget-title / .widget-description) as the shared header that
 * every other widget in this bundle renders. That means it inherits the same
 * theme and global styling automatically, so a description placed on its own
 * matches one sitting inside another widget.
 *
 * Useful when you want the intro copy separated from the content it describes
 * (different column, above a tab group, between sections, etc.).
 */

if (!defined('ABSPATH')) exit;

class UX_Description_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-description'; }
    public function get_title() { return __('Header & Description', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-text-align-left'; }
    public function get_keywords() {
        return ['description', 'text', 'intro', 'caption', 'header', 'ux'];
    }

    protected function register_controls() {

        // Reuse the shared header controls so the markup matches every other widget.
        $this->add_standard_header_section();

        // This widget exists to show a title + description, so turn both on by default.
        $this->update_control('show_title', ['default' => 'yes']);
        $this->update_control('show_description', ['default' => 'yes']);

        // STYLE
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('title_align', [
            'label' => __('Title Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left'   => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'right'  => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'condition' => ['show_title' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .widget-title' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_color', [
            'label' => __('Title Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'condition' => ['show_title' => 'yes'],
            'description' => __('Leave empty to inherit your global styles (keeps it matching other widgets).', 'ux-portfolio-optimized'),
            'selectors' => [
                '{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('title_typography', __('Title Typography', 'ux-portfolio-optimized'), '.widget-title');

        // Same alignment control the other widgets use, so behavior matches.
        $this->add_description_alignment();

        $this->add_control('description_color', [
            'label' => __('Description Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'description' => __('Leave empty to inherit your global styles.', 'ux-portfolio-optimized'),
            'selectors' => [
                '{{WRAPPER}} .widget-description' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('description_typography', __('Description Typography', 'ux-portfolio-optimized'), '.widget-description');

        $this->add_responsive_control('max_width', [
            'label' => __('Max Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em'],
            'range' => [
                'px' => ['min' => 200, 'max' => 1200],
                '%'  => ['min' => 20, 'max' => 100],
                'em' => ['min' => 10, 'max' => 60],
            ],
            'description' => __('Optional. Caps the line length for easier reading.', 'ux-portfolio-optimized'),
            'selectors' => [
                '{{WRAPPER}} .ux-description' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('block_align', [
            'label' => __('Block Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-h-align-left'],
                'center'     => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-h-align-center'],
                'flex-end'   => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-h-align-right'],
            ],
            'default' => 'flex-start',
            'description' => __('Where the block sits when a Max Width is set.', 'ux-portfolio-optimized'),
            'selectors' => [
                '{{WRAPPER}} .ux-description-wrapper' => 'display: flex; justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        $has_title = ($s['show_title'] ?? '') === 'yes' && !empty($s['title']);
        $has_desc  = ($s['show_description'] ?? '') === 'yes' && !empty($s['description']);
        if (!$has_title && !$has_desc) return;
        ?>
        <div class="ux-description-wrapper">
            <div class="ux-description">
                <?php $this->render_standard_header(); ?>
            </div>
        </div>
        <?php
    }
}
