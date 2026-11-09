<?php
/**
 * Storyboard Widget - Optimized
 * Visual storyboard with frames
 */

if (!defined('ABSPATH')) exit;

class UX_Storyboard_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-storyboard'; }
    public function get_title() { return __('Storyboard', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-gallery-grid'; }
    
    protected function register_controls() {
        
        $this->add_standard_header_section();
        
        $this->start_controls_section('frames_section', [
            'label' => __('Frames', 'ux-portfolio-optimized')
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('frame_number', [
            'label' => __('Frame Number', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '1',
        ]);
        
        $repeater->add_control('description', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Frame description',
        ]);
        
        $repeater->add_control('image', [
            'label' => __('Image', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
        ]);

        $repeater->add_control('storyboard', [
            'label' => __('Storyboard', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Type a Storyboard name (defined in the Storyboards panel) to put this frame in that tab. Leave blank for the default/first storyboard.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('frames', [
            'label' => __('Frames', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['frame_number' => '1', 'description' => 'Opening scene'],
                ['frame_number' => '2', 'description' => 'Action'],
            ],
            'title_field' => 'Frame {{{ frame_number }}}{{{ storyboard ? " — " + storyboard : "" }}}',
        ]);

        $this->end_controls_section();

        // Storyboards (tabs)
        $this->start_controls_section('storyboards_section', [
            'label' => __('Storyboards', 'ux-portfolio-optimized'),
        ]);

        $sb_repeater = new \Elementor\Repeater();

        $sb_repeater->add_control('name', [
            'label' => __('Storyboard Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main',
            'description' => __('Used as the tab label. Match this in each frame\'s Storyboard field.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('storyboards', [
            'label' => __('Storyboards', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $sb_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ name }}}',
            'description' => __('Optional. Define multiple storyboards. Tabs appear when more than one exists. Leave empty for a single untabbed storyboard.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('tab_icon', [
            'label' => __('Tab Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'eicon-image-rollover',
                'library' => 'eicons',
            ],
            'description' => __('Icon shown on each storyboard tab. Pick from the library or upload your own SVG.', 'ux-portfolio-optimized'),
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'selectors' => [
                '{{WRAPPER}} .storyboard-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
                '{{WRAPPER}} .storyboard-tab__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .storyboard-tab__icon i' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.9);',
            ],
        ]);

        $this->start_controls_tabs('tab_icon_state_tabs');

        // Normal state
        $this->start_controls_tab('tab_icon_normal', [
            'label' => __('Normal', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('tab_icon_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .storyboard-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .storyboard-tab__icon svg' => 'color: {{VALUE}};',
            ],
            'description' => __('Works for icon-library icons and SVGs that use currentColor. Uploaded SVGs with hardcoded colors won\'t change.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab('tab_icon_hover', [
            'label' => __('Hover', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('tab_icon_hover_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .storyboard-tab:hover .storyboard-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .storyboard-tab:hover .storyboard-tab__icon svg' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        // Active state
        $this->start_controls_tab('tab_icon_active', [
            'label' => __('Active', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('tab_icon_active_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .storyboard-tab.is-active .storyboard-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .storyboard-tab.is-active .storyboard-tab__icon svg' => 'color: {{VALUE}};',
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

        if (empty($s['frames'])) return;

        $widget_id = $this->get_id();

        // Build the ordered list of storyboards (slug => display name).
        $sb_names = array();
        $sb_def = $s['storyboards'] ?? [];
        if (!empty($sb_def) && is_array($sb_def)) {
            foreach ($sb_def as $sb) {
                $name = trim($sb['name'] ?? '');
                if ($name === '') continue;
                $slug = $this->slugify($name);
                if (!isset($sb_names[$slug])) {
                    $sb_names[$slug] = $name;
                }
            }
        }

        // Default slug for frames that don't specify a storyboard.
        $default_slug = '';
        if (!empty($sb_names)) {
            $defined_keys = array_keys($sb_names);
            $default_slug = $defined_keys[0];
        }

        // Group frames by storyboard slug.
        $groups = array();
        foreach ($s['frames'] as $frame) {
            $sb_name = trim($frame['storyboard'] ?? '');
            if ($sb_name !== '') {
                $slug = $this->slugify($sb_name);
                if (!isset($sb_names[$slug])) {
                    // Frame references a storyboard not in the panel — register it so it still shows.
                    $sb_names[$slug] = $sb_name;
                }
            } else {
                if ($default_slug !== '') {
                    $slug = $default_slug;
                } else {
                    $slug = 'main';
                    if (!isset($sb_names[$slug])) {
                        $sb_names[$slug] = 'Main';
                    }
                }
            }
            if (!isset($groups[$slug])) $groups[$slug] = array();
            $groups[$slug][] = $frame;
        }

        // Drop any storyboard slugs that ended up with no frames.
        $slugs = array_values(array_filter(array_keys($sb_names), function($sl) use ($groups) {
            return isset($groups[$sl]) && !empty($groups[$sl]);
        }));

        if (empty($slugs)) return;

        $active_slug = $slugs[0];
        $show_tabs = count($slugs) > 1;
        ?>

        <div class="storyboard-wrapper" data-active-storyboard="<?php echo esc_attr($active_slug); ?>">
            <?php $this->render_standard_header(); ?>

            <?php if ($show_tabs) :
                $tab_icon = $s['tab_icon'] ?? null;
                $has_icon = !empty($tab_icon) && (!empty($tab_icon['value']) || !empty($tab_icon['url']));
                ?>
                <div class="storyboard-tabs" role="tablist">
                    <?php foreach ($slugs as $tab_i => $slug) :
                        $tab_num = $tab_i + 1;
                        ?>
                        <button type="button"
                                class="storyboard-tab<?php echo $slug === $active_slug ? ' is-active' : ''; ?>"
                                role="tab"
                                aria-selected="<?php echo $slug === $active_slug ? 'true' : 'false'; ?>"
                                aria-label="<?php echo esc_attr($sb_names[$slug]); ?>"
                                title="<?php echo esc_attr($sb_names[$slug]); ?>"
                                data-target="<?php echo esc_attr($slug); ?>"><span class="storyboard-tab__icon"><?php
                                    if ($has_icon) {
                                        \Elementor\Icons_Manager::render_icon($tab_icon, ['aria-hidden' => 'true']);
                                    } else { ?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="14" height="11" rx="1"/><rect x="7" y="9" width="14" height="11" rx="1"/></svg><?php } ?></span><sup class="storyboard-tab__num"><?php echo esc_html($tab_num); ?></sup></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php foreach ($slugs as $slug) :
                $is_active = ($slug === $active_slug);
                $slideshow_id = $widget_id . '-' . $slug;
                ?>
                <div class="storyboard-container<?php echo $is_active ? '' : ' is-hidden'; ?>"
                     data-storyboard="<?php echo esc_attr($slug); ?>">
                    <div class="storyboard-grid">
                        <?php foreach ($groups[$slug] as $i => $frame) :
                            $img_url = $frame['image']['url'] ?? '';
                            if (empty($img_url) && !empty($frame['image']['id'])) {
                                $img_url = wp_get_attachment_url($frame['image']['id']);
                            }
                            ?>
                            <div class="story-frame">
                                <div class="frame-number"><?php echo esc_html($frame['frame_number']); ?></div>
                                <div class="frame-image">
                                    <?php if (!empty($img_url)) : ?>
                                        <a href="<?php echo esc_url($img_url); ?>"
                                           class="frame-image-link"
                                           data-elementor-open-lightbox="yes"
                                           data-elementor-lightbox-slideshow="<?php echo esc_attr($slideshow_id); ?>"
                                           data-elementor-lightbox-index="<?php echo esc_attr($i); ?>"
                                           aria-label="<?php echo esc_attr(sprintf(__('View frame %s', 'ux-portfolio-optimized'), $frame['frame_number'])); ?>">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="" loading="lazy">
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="frame-desc"><?php echo esc_html($frame['description']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
    }
}
