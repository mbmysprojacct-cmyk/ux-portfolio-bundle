<?php
/**
 * Accordion Opener Link Widget
 * Links/buttons that open specific items in an Accordion widget on the page.
 * Class/file name kept for backward compatibility with existing instances.
 */

if (!defined('ABSPATH')) exit;

class UX_Accordion_Link_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-accordion-link'; }
    public function get_title() { return __('Accordion Opener Link', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-anchor'; }

    protected function register_controls() {

        $this->start_controls_section('content_section', [
            'label' => __('Content', 'ux-portfolio-optimized')
        ]);

        $this->add_control('section_title', [
            'label' => __('Section Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);

        $this->add_control('section_description', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => '',
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('link_text', [
            'label' => __('Link Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Open Accordion Item',
        ]);

        $repeater->add_control('accordion_widget_id', [
            'label' => __('Accordion Widget CSS ID', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('CSS ID from the target Accordion widget (Advanced > CSS ID).', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('accordion_item_number', [
            'label' => __('Accordion Item Number', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
            'description' => __('Which item to open (1, 2, 3...).', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('target_page', [
            'label' => __('Target Page URL (Optional)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'placeholder' => '/faq',
            'description' => __('Leave empty for same page.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('link_type', [
            'label' => __('Display As', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'link',
            'options' => [
                'link' => __('Text Link', 'ux-portfolio-optimized'),
                'button' => __('Button', 'ux-portfolio-optimized'),
            ],
        ]);

        $repeater->add_control('item_icon', [
            'label' => __('Custom Icon (Optional)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
        ]);

        $this->add_control('links', [
            'label' => __('Links', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['link_text' => 'Open Accordion Item 1', 'accordion_item_number' => 1],
            ],
            'title_field' => '{{{ link_text }}}',
        ]);

        $this->add_control('links_layout', [
            'label' => __('Links Layout', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'vertical',
            'options' => [
                'vertical' => __('Vertical', 'ux-portfolio-optimized'),
                'horizontal' => __('Horizontal', 'ux-portfolio-optimized'),
            ],
            'prefix_class' => 'ux-acc-opener--%s-',
        ]);

        $this->add_control('list_style', [
            'label' => __('List Style', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => __('None', 'ux-portfolio-optimized'),
                'bullet' => __('Bullet Points', 'ux-portfolio-optimized'),
                'numbered' => __('Numbered', 'ux-portfolio-optimized'),
                'custom' => __('Custom Icon', 'ux-portfolio-optimized'),
            ],
        ]);

        $this->add_control('custom_icon', [
            'label' => __('Custom Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'condition' => ['list_style' => 'custom'],
        ]);

        $this->end_controls_section();

        // Style
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        // Section title
        $this->add_control('title_heading', [
            'label' => __('Section Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .ux-acc-opener__title',
        ]);

        // Description
        $this->add_control('desc_heading', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('desc_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__description' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'desc_typography',
            'selector' => '{{WRAPPER}} .ux-acc-opener__description',
        ]);

        // Links
        $this->add_control('links_heading', [
            'label' => __('Links', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('links_gap', [
            'label' => __('Gap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 10],
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__list' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('link_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('link_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'link_typography',
            'selector' => '{{WRAPPER}} .ux-acc-opener__link',
        ]);

        $this->start_controls_tabs('tabs_link_states');

        // Normal
        $this->start_controls_tab('tab_link_normal', ['label' => __('Normal', 'ux-portfolio-optimized')]);

        $this->add_control('link_color', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('link_bg', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link' => 'background: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'link_border',
            'selector' => '{{WRAPPER}} .ux-acc-opener__link',
        ]);

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab('tab_link_hover', ['label' => __('Hover', 'ux-portfolio-optimized')]);

        $this->add_control('link_color_hover', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link:hover, {{WRAPPER}} .ux-acc-opener__link:focus-visible' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('link_bg_hover', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link:hover, {{WRAPPER}} .ux-acc-opener__link:focus-visible' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('link_border_hover', [
            'label' => __('Border Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-acc-opener__link:hover, {{WRAPPER}} .ux-acc-opener__link:focus-visible' => 'border-color: {{VALUE}};'],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control('icon_color', [
            'label' => __('Icon Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .ux-acc-opener__icon, {{WRAPPER}} .ux-acc-opener__icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        if (empty($s['links'])) return;

        $list_style = $s['list_style'] ?? 'none';
        $use_list = in_array($list_style, ['bullet', 'numbered', 'custom']);
        $list_tag = $list_style === 'numbered' ? 'ol' : 'ul';
        $wrapper_class = 'ux-acc-opener ux-acc-opener--list-' . $list_style;
        ?>

        <div class="<?php echo esc_attr($wrapper_class); ?>">
            <?php if (!empty($s['section_title'])) : ?>
                <h3 class="ux-acc-opener__title"><?php echo esc_html($s['section_title']); ?></h3>
            <?php endif; ?>

            <?php if (!empty($s['section_description'])) : ?>
                <p class="ux-acc-opener__description"><?php echo esc_html($s['section_description']); ?></p>
            <?php endif; ?>

            <<?php echo $use_list ? esc_html($list_tag) : 'div'; ?> class="ux-acc-opener__list">
                <?php foreach ($s['links'] as $index => $item) :
                    $accordion_widget_id = sanitize_key($item['accordion_widget_id'] ?? '');
                    $item_number = absint($item['accordion_item_number'] ?? 1);
                    $target_page = esc_url_raw($item['target_page'] ?? '');
                    $link_text = $item['link_text'] ?? '';

                    if (empty($accordion_widget_id) || empty($link_text)) continue;

                    $href = $target_page
                        ? $target_page . '#accordion-' . $accordion_widget_id . '-item-' . $item_number
                        : '#accordion-' . $accordion_widget_id . '-item-' . $item_number;

                    $link_type = $item['link_type'] ?? 'link';
                    $link_class = 'ux-acc-opener__link';
                    if ($link_type === 'button') $link_class .= ' ux-acc-opener__link--button';

                    $individual_icon = !empty($item['item_icon']['value']) ? $item['item_icon'] : null;
                    $global_icon = ($list_style === 'custom' && !empty($s['custom_icon']['value'])) ? $s['custom_icon'] : null;
                    $icon_to_render = $individual_icon ?: $global_icon;

                    $item_tag = $use_list ? 'li' : 'div';
                    ?>
                    <<?php echo esc_html($item_tag); ?> class="ux-acc-opener__item">
                        <?php if ($icon_to_render) : ?>
                            <span class="ux-acc-opener__icon">
                                <?php \Elementor\Icons_Manager::render_icon($icon_to_render, ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo esc_attr($href); ?>"
                           class="<?php echo esc_attr($link_class); ?>"
                           data-accordion-widget="<?php echo esc_attr($accordion_widget_id); ?>"
                           data-accordion-item="<?php echo esc_attr($item_number); ?>"
                           <?php if ($target_page) : ?>data-accordion-url="<?php echo esc_attr($target_page); ?>"<?php endif; ?>>
                            <?php echo esc_html($link_text); ?>
                        </a>
                    </<?php echo esc_html($item_tag); ?>>
                <?php endforeach; ?>
            </<?php echo $use_list ? esc_html($list_tag) : 'div'; ?>>
        </div>

        <?php
    }
}
