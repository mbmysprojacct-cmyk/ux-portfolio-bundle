<?php
/**
 * Mockup Flow Widget
 * Sequence of high-fidelity mockup screens connected by arrows, telling a
 * user-flow story. Per-screen caption + label, lightbox on click,
 * multi-flow tabs.
 */

if (!defined('ABSPATH')) exit;

class UX_Mockup_Flow_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-mockup-flow'; }
    public function get_title() { return __('Mockup Flow', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-slider-album'; }

    protected function register_controls() {

        $this->add_standard_header_section();

        // CONTENT — Screens
        $this->start_controls_section('screens_section', [
            'label' => __('Screens', 'ux-portfolio-optimized'),
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('image', [
            'label' => __('Screen Mockup', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
        ]);

        $repeater->add_control('label', [
            'label' => __('Step Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Step',
            'description' => __('Short heading shown below the screen.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('caption', [
            'label' => __('Caption', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 3,
            'description' => __('Brief description of what happens on this screen.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('flow', [
            'label' => __('Flow', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Type a Flow name (defined in the Flows panel) to put this screen in that tab. Leave blank for the default/first flow.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('screens', [
            'label' => __('Screens', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['label' => 'Step 1'],
                ['label' => 'Step 2'],
                ['label' => 'Step 3'],
            ],
            'title_field' => '{{{ label }}}{{{ flow ? " — " + flow : "" }}}',
        ]);

        $this->end_controls_section();

        // CONTENT — Flows
        $this->start_controls_section('flows_section', [
            'label' => __('Flows', 'ux-portfolio-optimized'),
        ]);

        $flow_repeater = new \Elementor\Repeater();

        $flow_repeater->add_control('name', [
            'label' => __('Flow Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main',
            'description' => __('Used as the tab tooltip. Match this in each screen\'s Flow field.', 'ux-portfolio-optimized'),
        ]);

        $flow_repeater->add_control('tab_icon', [
            'label' => __('Tab Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'description' => __('Optional. Per-flow icon. Falls back to the Default Tab Icon.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('flows', [
            'label' => __('Flows', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $flow_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ name }}}',
            'description' => __('Optional. Define multiple flows. Tabs appear when more than one exists.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('default_tab_icon', [
            'label' => __('Default Tab Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'eicon-slider-album',
                'library' => 'eicons',
            ],
            'description' => __('Used for any flow that doesn\'t set its own Tab Icon.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('display_mode', [
            'label' => __('Display Mode', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'tabs'      => __('Tabs (one flow visible at a time)', 'ux-portfolio-optimized'),
                'accordion' => __('Accordion (collapsible sections)', 'ux-portfolio-optimized'),
            ],
            'default' => 'tabs',
            'description' => __('Only applies when there\'s more than one flow.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('default_expanded', [
            'label' => __('Default Expanded', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none'  => __('None (all collapsed)', 'ux-portfolio-optimized'),
                'first' => __('First Only', 'ux-portfolio-optimized'),
                'all'   => __('All', 'ux-portfolio-optimized'),
            ],
            'default' => 'first',
            'condition' => ['display_mode' => 'accordion'],
        ]);

        $this->end_controls_section();

        // CONTENT — Layout
        $this->start_controls_section('layout_section', [
            'label' => __('Layout', 'ux-portfolio-optimized'),
        ]);

        $this->add_responsive_control('screen_width', [
            'label' => __('Screen Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 160, 'max' => 600]],
            'default' => ['size' => 280],
            'tablet_default' => ['size' => 240],
            'mobile_default' => ['size' => 280],
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__screen' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('gap', [
            'label' => __('Gap Between Screens', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 80]],
            'default' => ['size' => 16],
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__row' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('show_arrows', [
            'label' => __('Show Connectors', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('image_radius', [
            'label' => __('Image Corner Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 12],
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__image-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('image_shadow', [
            'label' => __('Image Shadow', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'selectors_dictionary' => [
                '' => 'none',
                'yes' => '0 10px 30px rgba(0, 0, 0, 0.12)',
            ],
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__image-wrap' => 'box-shadow: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // STYLE — General
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('connector_color', [
            'label' => __('Connector Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#cbd5e1',
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__connector' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('label_color', [
            'label' => __('Label Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('caption_color', [
            'label' => __('Caption Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mockup-flow__caption' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_description_alignment();

        $this->end_controls_section();

        // STYLE — Tab Icon
        $this->start_controls_section('tab_icon_style_section', [
            'label' => __('Tab Icon', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('tab_icon_size', [
            'label' => __('Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 12, 'max' => 48]],
            'default' => ['size' => 20],
            'selectors' => [
                '{{WRAPPER}} .mockup-flow-tab__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mockup-flow-tab__icon i' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.9);',
            ],
        ]);

        $this->start_controls_tabs('tab_icon_state_tabs');

        $this->start_controls_tab('tab_icon_normal', ['label' => __('Normal', 'ux-portfolio-optimized')]);
        $this->add_control('tab_icon_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mockup-flow-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mockup-flow-tab__icon svg' => 'color: {{VALUE}};',
            ],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('tab_icon_hover', ['label' => __('Hover', 'ux-portfolio-optimized')]);
        $this->add_control('tab_icon_hover_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mockup-flow-tab:hover .mockup-flow-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mockup-flow-tab:hover .mockup-flow-tab__icon svg' => 'color: {{VALUE}};',
            ],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('tab_icon_active', ['label' => __('Active', 'ux-portfolio-optimized')]);
        $this->add_control('tab_icon_active_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mockup-flow-tab.is-active .mockup-flow-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mockup-flow-tab.is-active .mockup-flow-tab__icon svg' => 'color: {{VALUE}};',
            ],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function slugify($text) {
        $slug = strtolower(trim((string) $text));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug !== '' ? $slug : 'default';
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if (empty($s['screens'])) return;

        $widget_id = $this->get_id();
        $show_arrows = ($s['show_arrows'] ?? 'yes') === 'yes';

        // Build flow names + per-flow metadata.
        $flow_names = array();
        $flow_meta = array();
        $flows_def = $s['flows'] ?? [];
        if (!empty($flows_def) && is_array($flows_def)) {
            foreach ($flows_def as $fl) {
                $name = trim($fl['name'] ?? '');
                if ($name === '') continue;
                $slug = $this->slugify($name);
                if (!isset($flow_names[$slug])) {
                    $flow_names[$slug] = $name;
                    $flow_meta[$slug] = array('tab_icon' => $fl['tab_icon'] ?? null);
                }
            }
        }

        $default_slug = '';
        if (!empty($flow_names)) {
            $defined_keys = array_keys($flow_names);
            $default_slug = $defined_keys[0];
        }

        // Group screens by flow slug.
        $groups = array();
        foreach ($s['screens'] as $screen) {
            $fl_name = trim($screen['flow'] ?? '');
            if ($fl_name !== '') {
                $slug = $this->slugify($fl_name);
                if (!isset($flow_names[$slug])) {
                    $flow_names[$slug] = $fl_name;
                    $flow_meta[$slug] = array('tab_icon' => null);
                }
            } else {
                if ($default_slug !== '') {
                    $slug = $default_slug;
                } else {
                    $slug = 'main';
                    if (!isset($flow_names[$slug])) {
                        $flow_names[$slug] = 'Main';
                        $flow_meta[$slug] = array('tab_icon' => null);
                    }
                }
            }
            if (!isset($groups[$slug])) $groups[$slug] = array();
            $groups[$slug][] = $screen;
        }

        $slugs = array_values(array_filter(array_keys($flow_names), function($sl) use ($groups) {
            return isset($groups[$sl]) && !empty($groups[$sl]);
        }));

        if (empty($slugs)) return;

        $active_slug = $slugs[0];
        $show_tabs = count($slugs) > 1;
        $default_icon = $s['default_tab_icon'] ?? null;
        $display_mode = $s['display_mode'] ?? 'tabs';
        $default_expanded = $s['default_expanded'] ?? 'first';

        // Accordion mode only applies when there are multiple flows.
        $use_accordion = ($display_mode === 'accordion' && $show_tabs);
        ?>

        <div class="mockup-flow-wrapper" data-active-flow="<?php echo esc_attr($active_slug); ?>" data-display-mode="<?php echo esc_attr($display_mode); ?>">
            <?php $this->render_standard_header(); ?>

            <?php if ($use_accordion) : ?>
                <div class="mockup-flow-accordion">
                    <?php foreach ($slugs as $i => $slug) :
                        $slideshow_id = $widget_id . '-' . $slug;
                        $name = $flow_names[$slug];

                        $is_expanded = false;
                        if ($default_expanded === 'all') {
                            $is_expanded = true;
                        } elseif ($default_expanded === 'first' && $i === 0) {
                            $is_expanded = true;
                        }

                        $flow_icon = $flow_meta[$slug]['tab_icon'] ?? null;
                        $flow_has_icon = !empty($flow_icon) && (!empty($flow_icon['value']) || !empty($flow_icon['url']));
                        $effective = $flow_has_icon ? $flow_icon : $default_icon;
                        $has_icon = !empty($effective) && (!empty($effective['value']) || !empty($effective['url']));
                        ?>
                        <div class="mockup-flow-accordion__item<?php echo $is_expanded ? ' is-expanded' : ''; ?>" data-flow="<?php echo esc_attr($slug); ?>">
                            <button type="button"
                                    class="mockup-flow-accordion__header"
                                    aria-expanded="<?php echo $is_expanded ? 'true' : 'false'; ?>"
                                    aria-controls="mockup-flow-panel-<?php echo esc_attr($widget_id . '-' . $slug); ?>">
                                <?php if ($has_icon) : ?>
                                    <span class="mockup-flow-accordion__icon"><?php
                                        \Elementor\Icons_Manager::render_icon($effective, ['aria-hidden' => 'true']);
                                    ?></span>
                                <?php endif; ?>
                                <span class="mockup-flow-accordion__label"><?php echo esc_html($name); ?></span>
                                <span class="mockup-flow-accordion__chevron" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                            </button>
                            <div class="mockup-flow-accordion__panel" id="mockup-flow-panel-<?php echo esc_attr($widget_id . '-' . $slug); ?>">
                                <div class="mockup-flow-accordion__inner">
                                    <?php $this->render_screens_row($groups[$slug], $slideshow_id, $show_arrows); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>

                <?php if ($show_tabs) : ?>
                    <div class="mockup-flow-tabs" role="tablist">
                        <?php foreach ($slugs as $tab_i => $slug) :
                            $tab_num = $tab_i + 1;
                            $proto_icon = $flow_meta[$slug]['tab_icon'] ?? null;
                            $proto_has = !empty($proto_icon) && (!empty($proto_icon['value']) || !empty($proto_icon['url']));
                            $effective = $proto_has ? $proto_icon : $default_icon;
                            $has_icon = !empty($effective) && (!empty($effective['value']) || !empty($effective['url']));
                            ?>
                            <button type="button"
                                    class="mockup-flow-tab<?php echo $slug === $active_slug ? ' is-active' : ''; ?>"
                                    role="tab"
                                    aria-selected="<?php echo $slug === $active_slug ? 'true' : 'false'; ?>"
                                    aria-label="<?php echo esc_attr($flow_names[$slug]); ?>"
                                    title="<?php echo esc_attr($flow_names[$slug]); ?>"
                                    data-target="<?php echo esc_attr($slug); ?>"><span class="mockup-flow-tab__icon"><?php
                                        if ($has_icon) {
                                            \Elementor\Icons_Manager::render_icon($effective, ['aria-hidden' => 'true']);
                                        } else { ?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="6" height="16" rx="1"/><rect x="10" y="4" width="6" height="16" rx="1"/><rect x="18" y="4" width="4" height="16" rx="1"/></svg><?php } ?></span><sup class="mockup-flow-tab__num"><?php echo esc_html($tab_num); ?></sup></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($slugs as $slug) :
                    $is_active = ($slug === $active_slug);
                    $slideshow_id = $widget_id . '-' . $slug;
                    ?>
                    <div class="mockup-flow-container<?php echo $is_active ? '' : ' is-hidden'; ?>" data-flow="<?php echo esc_attr($slug); ?>">
                        <?php $this->render_screens_row($groups[$slug], $slideshow_id, $show_arrows); ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <?php
    }

    private function render_screens_row($group, $slideshow_id, $show_arrows) {
        $group_count = count($group);
        ?>
        <div class="mockup-flow__row">
            <?php foreach ($group as $i => $screen) :
                $img_url = $screen['image']['url'] ?? '';
                if (empty($img_url) && !empty($screen['image']['id'])) {
                    $img_url = wp_get_attachment_url($screen['image']['id']);
                }
                $label = trim($screen['label'] ?? '');
                $caption = trim($screen['caption'] ?? '');
                ?>
                <figure class="mockup-flow__screen">
                    <?php if (!empty($img_url)) : ?>
                        <a href="<?php echo esc_url($img_url); ?>"
                           class="mockup-flow__image-wrap"
                           data-elementor-open-lightbox="yes"
                           data-elementor-lightbox-slideshow="<?php echo esc_attr($slideshow_id); ?>"
                           data-elementor-lightbox-index="<?php echo esc_attr($i); ?>"
                           aria-label="<?php echo esc_attr($label !== '' ? $label : sprintf(__('Screen %d', 'ux-portfolio-optimized'), $i + 1)); ?>">
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($label); ?>" loading="lazy">
                        </a>
                    <?php endif; ?>

                    <figcaption class="mockup-flow__meta">
                        <?php if ($label !== '') : ?>
                            <div class="mockup-flow__label"><?php echo esc_html($label); ?></div>
                        <?php endif; ?>
                        <?php if ($caption !== '') : ?>
                            <div class="mockup-flow__caption"><?php echo nl2br(esc_html($caption)); ?></div>
                        <?php endif; ?>
                    </figcaption>
                </figure>

                <?php if ($show_arrows && $i < $group_count - 1) : ?>
                    <div class="mockup-flow__connector" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"/></svg></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
