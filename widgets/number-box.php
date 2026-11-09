<?php
/**
 * Number Box Widget - Full Original
 * Circular number/icon boxes with extensive options
 */

if (!defined('ABSPATH')) exit;

class UX_Number_Box_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-number-box'; }
    public function get_title() { return __('Number Box', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-number-field'; }
    
    protected function register_controls() {
        
        $this->add_standard_header_section();
        
        // Content
        $this->start_controls_section('content_section', [
            'label' => __('Content', 'ux-portfolio-optimized')
        ]);
        
        $this->add_control('show_main_title', [
            'label' => __('Show Main Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);
        
        $this->add_control('main_title', [
            'label' => __('Main Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main Title',
            'condition' => ['show_main_title' => 'yes'],
        ]);
        
        $this->add_control('main_title_tag', [
            'label' => __('Main Title HTML Tag', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'div' => 'div', 'span' => 'span', 'p' => 'p',
            ],
            'default' => 'h2',
            'condition' => ['show_main_title' => 'yes'],
        ]);
        
        $this->add_control('show_main_subtitle', [
            'label' => __('Show Main Subtitle', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);
        
        $this->add_control('main_subtitle', [
            'label' => __('Main Subtitle', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main Subtitle',
            'condition' => ['show_main_subtitle' => 'yes'],
        ]);
        
        $this->add_control('main_subtitle_tag', [
            'label' => __('Main Subtitle HTML Tag', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'div' => 'div', 'span' => 'span', 'p' => 'p',
            ],
            'default' => 'h3',
            'condition' => ['show_main_subtitle' => 'yes'],
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('content_type', [
            'label' => __('Content Type', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'number' => ['title' => __('Number/Text', 'ux-portfolio-optimized'), 'icon' => 'eicon-text'],
                'icon' => ['title' => __('Icon', 'ux-portfolio-optimized'), 'icon' => 'eicon-star'],
            ],
            'default' => 'number',
        ]);
        
        $repeater->add_control('text', [
            'label' => __('Number/Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '01',
            'condition' => ['content_type' => 'number'],
        ]);
        
        $repeater->add_control('icon', [
            'label' => __('Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-star', 'library' => 'fa-solid'],
            'condition' => ['content_type' => 'icon'],
        ]);
        
        $repeater->add_control('show_title', [
            'label' => __('Show Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $repeater->add_control('title', [
            'label' => __('Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Your Title',
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $repeater->add_control('title_tag', [
            'label' => __('Title HTML Tag', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'div' => 'div', 'span' => 'span', 'p' => 'p',
            ],
            'default' => 'h3',
            'condition' => ['show_title' => 'yes'],
        ]);
        
        $repeater->add_control('show_description', [
            'label' => __('Show Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $repeater->add_control('description', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Your description text goes here',
            'condition' => ['show_description' => 'yes'],
        ]);
        
        $repeater->add_control('linkable', [
            'label' => __('Make Linkable', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);
        
        $repeater->add_control('link', [
            'label' => __('Link', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'condition' => ['linkable' => 'yes'],
        ]);
        
        $this->add_control('items', [
            'label' => __('Number Box Items', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['content_type' => 'number', 'text' => '01', 'title' => 'First Item', 'description' => 'Description for first item'],
                ['content_type' => 'number', 'text' => '02', 'title' => 'Second Item', 'description' => 'Description for second item'],
            ],
            'title_field' => '{{{ text }}} - {{{ title }}}',
        ]);
        
        $this->end_controls_section();
        
        // Layout
        $this->start_controls_section('layout_section', [
            'label' => __('Layout', 'ux-portfolio-optimized')
        ]);
        
        $this->add_control('layout', [
            'label' => __('Item Layout', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'row',
            'options' => [
                'row' => __('Horizontal', 'ux-portfolio-optimized'),
                'column' => __('Vertical', 'ux-portfolio-optimized'),
            ],
        ]);
        
        $this->add_control('items_layout', [
            'label' => __('Items Direction', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'row',
            'options' => [
                'row' => __('Row', 'ux-portfolio-optimized'),
                'column' => __('Column', 'ux-portfolio-optimized'),
            ],
        ]);
        
        $this->add_responsive_control('items_gap', [
            'label' => __('Gap Between Items', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'default' => ['unit' => 'px', 'size' => 30],
            'selectors' => [
                '{{WRAPPER}} .number-box-items-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('spacing', [
            'label' => __('Content Spacing', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'default' => ['unit' => 'px', 'size' => 15],
            'selectors' => [
                '{{WRAPPER}} .ue-spacing' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['layout' => 'row'],
        ]);
        
        $this->add_responsive_control('items_align', [
            'label' => __('Items Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .number-box-items-wrapper' => 'justify-content: {{VALUE}};',
            ],
        ]);
        
        $this->add_responsive_control('item_width', [
            'label' => __('Item Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => ['min' => 10, 'max' => 100],
                'px' => ['min' => 100, 'max' => 1000],
            ],
            'default' => ['unit' => '%', 'size' => 100],
            'selectors' => [
                '{{WRAPPER}} .number-box-single-item' => 'width: {{SIZE}}{{UNIT}}; flex-basis: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('min_width', [
            'label' => __('Min Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => ['min' => 0, 'max' => 100],
                'px' => ['min' => 0, 'max' => 1000],
            ],
            'selectors' => [
                '{{WRAPPER}} .number-box-single-item' => 'min-width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('max_width', [
            'label' => __('Max Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => ['min' => 0, 'max' => 100],
                'px' => ['min' => 0, 'max' => 1000],
            ],
            'selectors' => [
                '{{WRAPPER}} .number-box-single-item' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('content_align', [
            'label' => __('Title Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .ue-widget-content .ue-title' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('item_desc_align', [
            'label' => __('Description Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left'    => ['title' => __('Left', 'ux-portfolio-optimized'),    'icon' => 'eicon-text-align-left'],
                'center'  => ['title' => __('Center', 'ux-portfolio-optimized'),  'icon' => 'eicon-text-align-center'],
                'right'   => ['title' => __('Right', 'ux-portfolio-optimized'),   'icon' => 'eicon-text-align-right'],
                'justify' => ['title' => __('Justify', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-justify'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .ue-widget-content .ue-description' => 'text-align: {{VALUE}};',
            ],
        ]);
        
        $this->add_responsive_control('icon_align', [
            'label' => __('Icon/Number Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget_layout.layout-column .ue-number-container' => 'justify-content: {{VALUE}};',
                '{{WRAPPER}} .circle_number_widget_layout.layout-row .ue-number-container' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('number_valign', [
            'label' => __('Number Vertical Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Top', 'ux-portfolio-optimized'), 'icon' => 'eicon-v-align-top'],
                'center'     => ['title' => __('Middle', 'ux-portfolio-optimized'), 'icon' => 'eicon-v-align-middle'],
                'flex-end'   => ['title' => __('Bottom', 'ux-portfolio-optimized'), 'icon' => 'eicon-v-align-bottom'],
            ],
            'default' => 'center',
            'description' => __('In horizontal layout, controls the number\'s vertical position independently of the text. Use Top to align the number with the title when descriptions are long.', 'ux-portfolio-optimized'),
            'condition' => ['layout' => 'row'],
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget_layout.layout-row' => 'align-items: flex-start;',
                '{{WRAPPER}} .circle_number_widget_layout.layout-row .ue-number-container' => 'align-self: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('content_valign', [
            'label' => __('Text Vertical Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Top', 'ux-portfolio-optimized'), 'icon' => 'eicon-v-align-top'],
                'center'     => ['title' => __('Middle', 'ux-portfolio-optimized'), 'icon' => 'eicon-v-align-middle'],
                'flex-end'   => ['title' => __('Bottom', 'ux-portfolio-optimized'), 'icon' => 'eicon-v-align-bottom'],
            ],
            'default' => 'flex-start',
            'description' => __('In horizontal layout, controls where the title/description block sits vertically.', 'ux-portfolio-optimized'),
            'condition' => ['layout' => 'row'],
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget_layout.layout-row .ue-widget-content' => 'align-self: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('rotate', [
            'label' => __('Rotate Circle', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 360]],
            'default' => ['size' => 0],
        ]);
        
        $this->add_control('hover_animation', [
            'label' => __('Hover Animation', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
        ]);
        
        $this->end_controls_section();

        // Style - Main Title / Subtitle
        $this->start_controls_section('style_main_section', [
            'label' => __('Main Title & Subtitle', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('main_title_heading', [
            'label' => __('Main Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('main_title_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ue-main-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'main_title_typography',
            'selector' => '{{WRAPPER}} .ue-main-title',
        ]);

        $this->add_responsive_control('main_title_margin', [
            'label' => __('Margin', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .ue-main-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('main_title_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .ue-main-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('main_title_align', [
            'label' => __('Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .ue-main-title' => 'text-align: {{VALUE}};'],
        ]);

        $this->add_control('main_subtitle_heading', [
            'label' => __('Main Subtitle', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('main_subtitle_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ue-main-subtitle' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'main_subtitle_typography',
            'selector' => '{{WRAPPER}} .ue-main-subtitle',
        ]);

        $this->add_responsive_control('main_subtitle_margin', [
            'label' => __('Margin', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .ue-main-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('main_subtitle_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .ue-main-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('main_subtitle_align', [
            'label' => __('Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .ue-main-subtitle' => 'text-align: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // Style - Number Box
        $this->start_controls_section('style_number_section', [
            'label' => __('Number Box', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('box_size', [
            'label' => __('Box Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 50, 'max' => 300]],
            'default' => ['unit' => 'px', 'size' => 80],
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_responsive_control('icon_size', [
            'label' => __('Icon/Number Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 10, 'max' => 200]],
            'default' => ['size' => 32],
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .circle_number_widget svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .circle_number_widget' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'number_typography',
            'selector' => '{{WRAPPER}} .circle_number_widget',
        ]);
        
        $this->add_control('number_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .circle_number_widget' => 'color: {{VALUE}};'],
        ]);
        
        $this->add_control('background_color', [
            'label' => __('Background Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f0f0f0',
            'selectors' => ['{{WRAPPER}} .circle_number_widget' => 'background-color: {{VALUE}};'],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'border',
            'selector' => '{{WRAPPER}} .circle_number_widget',
        ]);
        
        $this->add_responsive_control('border_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['unit' => '%', 'top' => 50, 'right' => 50, 'bottom' => 50, 'left' => 50],
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $s = $this->get_settings_for_display();
        
        if (empty($s['items'])) return;
        
        $rotate = isset($s['rotate']['size']) ? absint($s['rotate']['size']) : 0;
        $items_layout = $s['items_layout'] ?? 'row';
        $flex_direction = ($items_layout === 'column') ? 'column' : 'row';
        ?>
        
        <div class="circle_number_widget_container">
            <?php $this->render_standard_header(); ?>
            
            <?php if (!empty($s['show_main_title']) && $s['show_main_title'] === 'yes' && !empty($s['main_title'])) : 
                $main_title_tag = !empty($s['main_title_tag']) ? $s['main_title_tag'] : 'h2';
            ?>
                <<?php echo esc_attr($main_title_tag); ?> class="ue-main-title"><?php echo wp_kses_post($s['main_title']); ?></<?php echo esc_attr($main_title_tag); ?>>
            <?php endif; ?>
            
            <?php if (!empty($s['show_main_subtitle']) && $s['show_main_subtitle'] === 'yes' && !empty($s['main_subtitle'])) : 
                $main_subtitle_tag = !empty($s['main_subtitle_tag']) ? $s['main_subtitle_tag'] : 'h3';
            ?>
                <<?php echo esc_attr($main_subtitle_tag); ?> class="ue-main-subtitle"><?php echo wp_kses_post($s['main_subtitle']); ?></<?php echo esc_attr($main_subtitle_tag); ?>>
            <?php endif; ?>
            
            <div class="number-box-items-wrapper" style="display:flex;flex-direction:<?php echo esc_attr($flex_direction); ?>;flex-wrap:wrap;">
                
                <?php foreach ($s['items'] as $index => $item) : ?>
                
                    <div class="number-box-single-item">
                        <?php if (!empty($item['linkable']) && $item['linkable'] === 'yes' && !empty($item['link']['url'])) : 
                            $link_key = 'link_' . $index;
                            $this->add_link_attributes($link_key, $item['link']);
                        ?>
                            <a <?php echo $this->get_render_attribute_string($link_key); ?> style="display:block;text-decoration:none;color:inherit;">
                        <?php endif; ?>
                        
                        <div class="circle_number_widget_layout layout-<?php echo esc_attr($s['layout']); ?>">
                            
                            <div class="ue-number-container"> 
                                <div class="circle_number_widget <?php echo esc_attr($s['hover_animation']); ?>" style="display:flex;align-items:center;justify-content:center;position:relative;transform:rotate(<?php echo esc_attr($rotate); ?>deg);">
                                    <div class="circle_number_text" style="transform:rotate(-<?php echo esc_attr($rotate); ?>deg);">
                                        <?php if (!empty($item['content_type']) && $item['content_type'] === 'icon') : ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php else : ?>
                                            <?php echo wp_kses_post($item['text']); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($s['layout'] === 'row') : ?>
                                <div class="ue-spacing"></div>
                            <?php endif; ?>
                            
                            <div class="ue-widget-content">
                                <?php if (!empty($item['show_title']) && $item['show_title'] === 'yes' && !empty($item['title'])) : 
                                    $title_tag = !empty($item['title_tag']) ? $item['title_tag'] : 'h3';
                                ?>
                                    <<?php echo esc_attr($title_tag); ?> class="ue-title"><?php echo wp_kses_post($item['title']); ?></<?php echo esc_attr($title_tag); ?>>
                                <?php endif; ?>
                                <?php if (!empty($item['show_description']) && $item['show_description'] === 'yes' && !empty($item['description'])) : ?>
                                    <div class="ue-description"><?php echo wp_kses_post($item['description']); ?></div>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                        
                        <?php if (!empty($item['linkable']) && $item['linkable'] === 'yes' && !empty($item['link']['url'])) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                
                <?php endforeach; ?>
                
            </div>
        </div>
        
        <style>
        .circle_number_widget{width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:24px;line-height:1}
        .circle_number_text{line-height:1;display:flex;align-items:center;justify-content:center}
        .circle_number_widget i{font-size:32px}
        .circle_number_widget svg{width:32px;height:32px;max-width:100%;max-height:100%}
        .number-box-items-wrapper{gap:30px;align-items:flex-start}
        .number-box-single-item{flex:1 1 auto}
        .circle_number_widget_layout{display:flex;gap:15px}
        .circle_number_widget_layout.layout-row{flex-direction:row;align-items:center}
        .circle_number_widget_layout.layout-column{flex-direction:column}
        .ue-spacing{width:15px;height:15px}
        .ue-number-container{display:flex}
        .circle_number_widget_layout.layout-column .ue-number-container{width:100%}
        .circle_number_widget_layout.layout-column .ue-spacing{display:none}
        </style>
        
        <?php
    }
}
