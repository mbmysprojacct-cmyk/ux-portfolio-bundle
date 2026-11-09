<?php
/**
 * Table of Contents Widget
 * Auto-generates or manually-built TOC with collapsible/sticky behavior
 */

if (!defined('ABSPATH')) exit;

class UX_Table_Of_Contents_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-table-of-contents'; }
    public function get_title() { return __('Table of Contents', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-table-of-contents'; }

    protected function register_controls() {

        $this->add_standard_header_section();

        // Source section
        $this->start_controls_section('source_section', [
            'label' => __('Source', 'ux-portfolio-optimized')
        ]);

        $this->add_control('source_mode', [
            'label' => __('Source', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'auto' => __('Auto-detect from headings', 'ux-portfolio-optimized'),
                'manual' => __('Manual list', 'ux-portfolio-optimized'),
            ],
            'default' => 'auto',
        ]);

        $this->add_control('scope', [
            'label' => __('Look for Headings In', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'selector' => __('Content selector', 'ux-portfolio-optimized'),
                'tabpanel' => __('Same tab as this TOC', 'ux-portfolio-optimized'),
                'page'     => __('Whole page', 'ux-portfolio-optimized'),
            ],
            'default' => 'selector',
            'description' => __('"Same tab" only reads headings inside the tab panel this TOC sits in.', 'ux-portfolio-optimized'),
            'condition' => ['source_mode' => 'auto'],
        ]);

        $this->add_control('content_selector', [
            'label' => __('Content Selector', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'article, .elementor-widget-theme-post-content, .entry-content',
            'description' => __('CSS selector(s) where headings should be detected. Comma-separated. The TOC searches all matching containers.', 'ux-portfolio-optimized'),
            'condition' => ['source_mode' => 'auto', 'scope' => 'selector'],
        ]);

        $this->add_control('heading_levels', [
            'label' => __('Heading Levels', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => [
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
            ],
            'default' => ['h2', 'h3'],
            'condition' => ['source_mode' => 'auto'],
        ]);

        $this->add_control('ux_headings', [
            'label' => __('Headings Inside UX Widgets', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'all'    => __('Include all', 'ux-portfolio-optimized'),
                'titles' => __('Widget titles only (nested)', 'ux-portfolio-optimized'),
                'skip'   => __('Skip', 'ux-portfolio-optimized'),
            ],
            'default' => 'all',
            'description' => __('"Widget titles only" lists each UX widget by its main title, indented under the section heading above it.', 'ux-portfolio-optimized'),
            'condition' => ['source_mode' => 'auto'],
        ]);

        $this->add_control('exclude_selector', [
            'label' => __('Exclude Selector', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '.no-toc',
            'description' => __('Headings matching or inside this selector are left out. Add the class no-toc to any heading or container to hide it.', 'ux-portfolio-optimized'),
            'condition' => ['source_mode' => 'auto'],
        ]);

        $label_repeater = new \Elementor\Repeater();

        $label_repeater->add_control('match_text', [
            'label' => __('Heading Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'description' => __('The heading as it appears on the page (case and spacing don\'t matter).', 'ux-portfolio-optimized'),
        ]);

        $label_repeater->add_control('toc_label', [
            'label' => __('TOC Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $this->add_control('label_overrides', [
            'label' => __('Label Overrides', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $label_repeater->get_controls(),
            'default' => [],
            'prevent_empty' => false,
            'title_field' => '{{{ toc_label }}}',
            'condition' => ['source_mode' => 'auto'],
        ]);
        // Manual repeater
        $manual_repeater = new \Elementor\Repeater();

        $manual_repeater->add_control('item_label', [
            'label' => __('Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Section',
        ]);

        $manual_repeater->add_control('item_anchor', [
            'label' => __('Anchor ID', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'section',
            'description' => __('The id attribute of the target element (without #).', 'ux-portfolio-optimized'),
        ]);

        $manual_repeater->add_control('item_level', [
            'label' => __('Indent Level', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
            'max' => 5,
        ]);

        $this->add_control('manual_items', [
            'label' => __('TOC Items', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $manual_repeater->get_controls(),
            'default' => [
                ['item_label' => 'Overview', 'item_anchor' => 'overview', 'item_level' => 1],
                ['item_label' => 'Process', 'item_anchor' => 'process', 'item_level' => 1],
                ['item_label' => 'Outcomes', 'item_anchor' => 'outcomes', 'item_level' => 1],
            ],
            'title_field' => '{{{ item_label }}}',
            'condition' => ['source_mode' => 'manual'],
        ]);

        $this->end_controls_section();

        // Behavior section
        $this->start_controls_section('behavior_section', [
            'label' => __('Behavior', 'ux-portfolio-optimized')
        ]);

        $this->add_control('toc_title', [
            'label' => __('TOC Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Contents',
        ]);

        $this->add_control('scroll_offset', [
            'label' => __('Scroll Offset', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 80,
            'description' => __('Pixel offset above the heading when scrolling to it (accounts for fixed headers).', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('highlight_active', [
            'label' => __('Highlight Current Section', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('toc_sticky', [
            'label' => __('Sticky', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Stays in view while its column scrolls. The parent column must be taller than the TOC and not use overflow: hidden.', 'ux-portfolio-optimized'),
            'selectors' => [
                '{{WRAPPER}}' => 'position: sticky; align-self: flex-start; z-index: 2;',
            ],
        ]);

        $this->add_responsive_control('sticky_top', [
            'label' => __('Sticky Top Offset', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 300]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['toc_sticky' => 'yes'],
        ]);

        $this->add_control('collapse_on', [
            'label' => __('Collapse Into Dropdown', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none'   => __('Never', 'ux-portfolio-optimized'),
                'tablet' => __('Tablet & mobile', 'ux-portfolio-optimized'),
                'mobile' => __('Mobile only', 'ux-portfolio-optimized'),
            ],
            'default' => 'none',
            'prefix_class' => 'ux-toc--collapse-',
            'description' => __('Below this size the list hides behind a "Contents" toggle, and Sticky turns off.', 'ux-portfolio-optimized'),
        ]);
        $this->add_responsive_control('max_height', [
            'label' => __('Max Height', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => ['min' => 100, 'max' => 1200],
                'vh' => ['min' => 20, 'max' => 95],
            ],
            'size_units' => ['px', 'vh'],
            'default' => ['size' => 600, 'unit' => 'px'],
            'tablet_default' => ['size' => 500, 'unit' => 'px'],
            'mobile_default' => ['size' => 400, 'unit' => 'px'],
            'description' => __('When the list exceeds this height it scrolls within the TOC. Set to 0 for no limit.', 'ux-portfolio-optimized'),
            'selectors' => [
                '{{WRAPPER}} .ux-toc__list' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
            ],
        ]);

        $this->add_control('scrollbar_style', [
            'label' => __('Scrollbar Style', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'default' => __('Default', 'ux-portfolio-optimized'),
                'thin'    => __('Thin', 'ux-portfolio-optimized'),
                'hidden'  => __('Auto-hide', 'ux-portfolio-optimized'),
            ],
            'default' => 'thin',
            'prefix_class' => 'ux-toc--scrollbar-',
        ]);

        $this->end_controls_section();

        // Drawer section
        // Style section
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('container_heading', [
            'label' => __('Container', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('bg_color', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('container_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'default' => ['top' => 16, 'right' => 20, 'bottom' => 16, 'left' => 20, 'unit' => 'px', 'isLinked' => false],
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('container_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 8],
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'container_border',
            'selector' => '{{WRAPPER}} .ux-toc',
        ]);

        $this->add_control('title_heading', [
            'label' => __('Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc__title, {{WRAPPER}} .ux-toc__toggle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .ux-toc__title, {{WRAPPER}} .ux-toc__toggle',
        ]);

        $this->add_control('items_heading', [
            'label' => __('Items', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('items_gap', [
            'label' => __('Gap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 8],
            'selectors' => [
                '{{WRAPPER}} .ux-toc__list' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('item_indent', [
            'label' => __('Nested Indent', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 16],
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => '--ux-toc-indent: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'items_typography',
            'selector' => '{{WRAPPER}} .ux-toc__link',
        ]);

        // Bullet style controls
        $this->add_control('bullet_style', [
            'label' => __('Bullet Style', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none'    => __('None', 'ux-portfolio-optimized'),
                'dot'     => __('Dot (filled)', 'ux-portfolio-optimized'),
                'outline' => __('Outline circle', 'ux-portfolio-optimized'),
                'square'  => __('Square', 'ux-portfolio-optimized'),
                'line'    => __('Vertical line', 'ux-portfolio-optimized'),
                'number'  => __('Number', 'ux-portfolio-optimized'),
            ],
            'default' => 'none',
            'prefix_class' => 'ux-toc--bullet-',
        ]);

        $this->add_control('bullet_size', [
            'label' => __('Bullet Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 4, 'max' => 24]],
            'default' => ['size' => 8],
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => '--ux-toc-bullet-size: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['bullet_style!' => 'none'],
        ]);

        $this->add_control('bullet_gap', [
            'label' => __('Bullet Gap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 4, 'max' => 30]],
            'default' => ['size' => 12],
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => '--ux-toc-bullet-gap: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['bullet_style!' => 'none'],
        ]);

        $this->start_controls_tabs('tabs_item_states');

        // NORMAL
        $this->start_controls_tab('tab_item_normal', ['label' => __('Normal', 'ux-portfolio-optimized')]);

        $this->add_control('item_color', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc .ux-toc__link' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('bullet_color', [
            'label' => __('Bullet Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc' => '--ux-toc-bullet-color: {{VALUE}};',
            ],
            'condition' => ['bullet_style!' => 'none'],
        ]);

        $this->end_controls_tab();

        // HOVER
        $this->start_controls_tab('tab_item_hover', ['label' => __('Hover', 'ux-portfolio-optimized')]);

        $this->add_control('item_color_hover', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc .ux-toc__link:hover,
                 {{WRAPPER}} .ux-toc .ux-toc__link:focus' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('bullet_color_hover', [
            'label' => __('Bullet Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc .ux-toc__item:hover' => '--ux-toc-bullet-color: {{VALUE}};',
            ],
            'condition' => ['bullet_style!' => 'none'],
        ]);

        $this->end_controls_tab();

        // ACTIVE (current section while scrolling)
        $this->start_controls_tab('tab_item_active', ['label' => __('Active', 'ux-portfolio-optimized')]);

        $this->add_control('item_color_active', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc .ux-toc__item.is-active > .ux-toc__link' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('bullet_color_active', [
            'label' => __('Bullet Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-toc .ux-toc__item.is-active' => '--ux-toc-bullet-color: {{VALUE}};',
            ],
            'condition' => ['bullet_style!' => 'none'],
        ]);

        $this->add_control('item_weight_active', [
            'label' => __('Font Weight', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''    => __('Default', 'ux-portfolio-optimized'),
                '400' => '400',
                '500' => '500',
                '600' => '600',
                '700' => '700',
            ],
            'default' => '600',
            'selectors' => [
                '{{WRAPPER}} .ux-toc .ux-toc__item.is-active > .ux-toc__link' => 'font-weight: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $mode = $s['source_mode'] ?? 'auto';
        $title = $s['toc_title'] ?? '';
        $scroll_offset = intval($s['scroll_offset'] ?? 80);

        $levels = !empty($s['heading_levels']) ? implode(',', $s['heading_levels']) : 'h2,h3';
        $content_selector = $s['content_selector'] ?? '.elementor-widget-theme-post-content, .entry-content, article';
        $scope = $s['scope'] ?? 'selector';
        if (!in_array($scope, ['selector', 'tabpanel', 'page'], true)) $scope = 'selector';
        $ux_headings = $s['ux_headings'] ?? 'all';
        if (!in_array($ux_headings, ['all', 'titles', 'skip'], true)) $ux_headings = 'all';
        $exclude = sanitize_text_field($s['exclude_selector'] ?? '');
        $label_map = [];
        if (!empty($s['label_overrides']) && is_array($s['label_overrides'])) {
            foreach ($s['label_overrides'] as $row) {
                $match = sanitize_text_field($row['match_text'] ?? '');
                $lbl = sanitize_text_field($row['toc_label'] ?? '');
                if ($match !== '' && $lbl !== '') $label_map[$match] = $lbl;
            }
        }
        $highlight = (($s['highlight_active'] ?? 'yes') === 'yes') ? 'yes' : 'no';
        $list_id = 'ux-toc-list-' . $this->get_id();        ?>

        <div class="ux-toc-wrapper">
            <?php $this->render_standard_header(); ?>

            <nav class="ux-toc"
                 aria-label="<?php echo esc_attr($title ?: __('Table of contents', 'ux-portfolio-optimized')); ?>"
                 data-source-mode="<?php echo esc_attr($mode); ?>"
                 data-content-selector="<?php echo esc_attr($content_selector); ?>"
                 data-heading-levels="<?php echo esc_attr($levels); ?>"
                 data-scroll-offset="<?php echo esc_attr($scroll_offset); ?>"
                 data-scope="<?php echo esc_attr($scope); ?>"
                 data-ux-headings="<?php echo esc_attr($ux_headings); ?>"
                 data-exclude="<?php echo esc_attr($exclude); ?>"
                 data-highlight="<?php echo esc_attr($highlight); ?>"
                 data-label-map="<?php echo esc_attr(wp_json_encode((object) $label_map)); ?>">

                <?php if ($title) : ?>
                    <h3 class="ux-toc__title"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>

                <button type="button" class="ux-toc__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr($list_id); ?>">
                    <span class="ux-toc__toggle-label"><?php echo esc_html($title ?: __('Contents', 'ux-portfolio-optimized')); ?></span>
                    <span class="ux-toc__chevron" aria-hidden="true"></span>
                </button>

                <ul class="ux-toc__list" id="<?php echo esc_attr($list_id); ?>" role="list">
                    <?php if ($mode === 'manual' && !empty($s['manual_items'])) : ?>
                        <?php foreach ($s['manual_items'] as $item) :
                            $label = $item['item_label'] ?? '';
                            $anchor = $item['item_anchor'] ?? '';
                            $level = max(1, intval($item['item_level'] ?? 1));
                            ?>
                            <li class="ux-toc__item ux-toc__item--level-<?php echo esc_attr($level); ?>">
                                <a href="#<?php echo esc_attr(sanitize_title($anchor)); ?>"
                                   class="ux-toc__link"
                                   data-anchor="<?php echo esc_attr(sanitize_title($anchor)); ?>">
                                    <?php echo esc_html($label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php // For auto mode, JS will populate the list ?>
                </ul>
            </nav>
        </div>

        <?php
    }
}
