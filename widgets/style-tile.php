<?php
/**
 * Style Tile Widget
 * One or more style tiles (each a complete visual direction: colors, type, imagery,
 * UI elements, vibe words). Multi-tile tabs use the same icon+superscript pattern
 * as moodboard / storyboard.
 */

if (!defined('ABSPATH')) exit;

class UX_Style_Tile_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-style-tile'; }
    public function get_title() { return __('Style Tile', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-paint-brush'; }

    protected function register_controls() {

        $this->add_standard_header_section();

        // CONTENT — Style Tiles
        $this->start_controls_section('tiles_section', [
            'label' => __('Style Tiles', 'ux-portfolio-optimized'),
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('name', [
            'label' => __('Tile Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Direction A',
            'description' => __('Used as tab tooltip and for screen readers.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('tab_icon', [
            'label' => __('Tab Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'description' => __('Optional. Per-tile icon. If empty, the Default Tab Icon is used.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('composite_image', [
            'label' => __('Composite Image', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'description' => __('Optional. A single composed style tile image (e.g. exported from Figma). When set, viewers can toggle between this Image view and the structured Detail view.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('default_view', [
            'label' => __('Default View', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'detail' => __('Detail', 'ux-portfolio-optimized'),
                'image'  => __('Image', 'ux-portfolio-optimized'),
            ],
            'default' => 'detail',
            'description' => __('Which view shows first when the page loads. Only applies if a Composite Image is set.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('stage_gallery', [
            'label' => __('Stage Gallery', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::GALLERY,
            'separator' => 'before',
            'description' => __('Optional. Add multiple versions of this direction to show as a progression (e.g. paper sketch → digital draft → final). Each image\'s Caption (set in the Media Library) becomes its label. When one or more images are added here, the Image view shows this gallery instead of the single Composite Image above.', 'ux-portfolio-optimized'),
        ]);

        // Color palette
        $repeater->add_control('section_colors_heading', [
            'label' => __('Color Palette', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $repeater->add_control('colors', [
            'label' => __('Colors', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 6,
            'placeholder' => "Primary #ef4a8a\nAccent #d4a017\nDark #1a1a1a\nLight #f8f9fa",
            'description' => __('One color per line. Format: "Name #hex" (or just "#hex"). Examples: "Primary #ef4a8a", "Light Gray #f8f9fa", "#000".', 'ux-portfolio-optimized'),
        ]);

        // Typography
        $repeater->add_control('section_typography_heading', [
            'label' => __('Typography', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $repeater->add_control('heading_font', [
            'label' => __('Heading Font Family', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'Economica, sans-serif',
            'description' => __('Font name. Must be available on the page (via Elementor Pro fonts, Google Fonts plugin, theme, etc.).', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('heading_sample', [
            'label' => __('Heading Sample', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'The quick brown fox',
        ]);

        $repeater->add_control('body_font', [
            'label' => __('Body Font Family', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'Dosis, sans-serif',
        ]);

        $repeater->add_control('body_sample', [
            'label' => __('Body Sample', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => 'Body text sample. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        ]);

        // Imagery
        $repeater->add_control('section_imagery_heading', [
            'label' => __('Imagery', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $repeater->add_control('imagery', [
            'label' => __('Images', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::GALLERY,
            'description' => __('Up to ~6 images. Shown as a small representative grid.', 'ux-portfolio-optimized'),
        ]);

        // UI Elements
        $repeater->add_control('section_ui_heading', [
            'label' => __('UI Elements', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $repeater->add_control('button_label', [
            'label' => __('Button Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Call to Action',
        ]);

        $repeater->add_control('button_bg', [
            'label' => __('Button Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ef4a8a',
        ]);

        $repeater->add_control('button_text_color', [
            'label' => __('Button Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
        ]);

        $repeater->add_control('button_radius', [
            'label' => __('Button Corner Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 32]],
            'default' => ['size' => 6],
        ]);

        $repeater->add_control('link_color', [
            'label' => __('Link Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ef4a8a',
        ]);

        $repeater->add_control('link_sample', [
            'label' => __('Link Sample', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Link Sample',
        ]);

        // Vibe words
        $repeater->add_control('section_vibes_heading', [
            'label' => __('Vibe Words', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $repeater->add_control('vibe_words', [
            'label' => __('Vibe Words', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'placeholder' => 'Modern, Playful, Confident, Warm',
            'description' => __('Comma-separated. Rendered as pill-shaped tags.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('tiles', [
            'label' => __('Style Tiles', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['name' => 'Direction A'],
            ],
            'title_field' => '{{{ name }}}',
        ]);

        $this->add_control('default_tab_icon', [
            'label' => __('Default Tab Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'eicon-paint-brush',
                'library' => 'eicons',
            ],
            'description' => __('Used for any tile that doesn\'t set its own Tab Icon.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_all_tab', [
            'label' => __('Show "Compare" Tab', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Adds a tab that shows all composite images side by side for direct comparison. Only shows if at least one tile has a Composite Image set.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('all_label', [
            'label' => __('"Compare" Tab Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Compare', 'ux-portfolio-optimized'),
            'condition' => ['show_all_tab' => 'yes'],
        ]);

        $this->add_control('all_tab_icon', [
            'label' => __('"Compare" Tab Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'eicon-gallery-grid',
                'library' => 'eicons',
            ],
            'condition' => ['show_all_tab' => 'yes'],
        ]);

        $this->end_controls_section();

        // STYLE — General
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('section_label_color', [
            'label' => __('Section Label Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#666666',
            'selectors' => ['{{WRAPPER}} .ux-stile__section-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('swatch_size', [
            'label' => __('Color Swatch Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 40, 'max' => 120]],
            'default' => ['size' => 70],
            'selectors' => [
                '{{WRAPPER}} .ux-stile__swatch' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                '{{WRAPPER}} .ux-stile-tab__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ux-stile-tab__icon i' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.9);',
            ],
        ]);

        $this->start_controls_tabs('tab_icon_state_tabs');

        $this->start_controls_tab('tab_icon_normal', ['label' => __('Normal', 'ux-portfolio-optimized')]);
        $this->add_control('tab_icon_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-stile-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .ux-stile-tab__icon svg' => 'color: {{VALUE}};',
            ],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('tab_icon_hover', ['label' => __('Hover', 'ux-portfolio-optimized')]);
        $this->add_control('tab_icon_hover_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-stile-tab:hover .ux-stile-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .ux-stile-tab:hover .ux-stile-tab__icon svg' => 'color: {{VALUE}};',
            ],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('tab_icon_active', ['label' => __('Active', 'ux-portfolio-optimized')]);
        $this->add_control('tab_icon_active_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ux-stile-tab.is-active .ux-stile-tab__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .ux-stile-tab.is-active .ux-stile-tab__icon svg' => 'color: {{VALUE}};',
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
        return $slug !== '' ? $slug : 'tile';
    }

    /**
     * Parse the colors textarea: one per line, each line "Name #hex" or "#hex".
     * Returns array of ['name' => string, 'hex' => '#xxxxxx'].
     */
    private function parse_palette($input) {
        $input = trim((string) $input);
        if ($input === '') return array();
        $out = array();
        $lines = preg_split('/\r?\n/', $input);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            // Find a hex anywhere in the line.
            if (preg_match('/(#?([0-9a-f]{3}|[0-9a-f]{4}|[0-9a-f]{6}|[0-9a-f]{8}))\b/i', $line, $m, PREG_OFFSET_CAPTURE)) {
                $hex_raw = $m[1][0];
                $hex = ($hex_raw[0] === '#') ? $hex_raw : '#' . $hex_raw;
                $hex_offset = $m[1][1];
                $hex_len = strlen($hex_raw);
                // Everything outside the hex match is the name.
                $before = substr($line, 0, $hex_offset);
                $after  = substr($line, $hex_offset + $hex_len);
                $name = trim($before . ' ' . $after, " \t\n\r\0\x0B|,:-");
                $out[] = array('name' => $name, 'hex' => $hex);
            }
        }
        return $out;
    }

    private function parse_vibes($input) {
        $input = trim((string) $input);
        if ($input === '') return array();
        $parts = preg_split('/[,\r\n]+/', $input);
        $out = array();
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p !== '') $out[] = $p;
        }
        return $out;
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $tiles = $s['tiles'] ?? array();
        if (empty($tiles)) return;

        $widget_id = $this->get_id();
        $show_tabs = count($tiles) > 1;
        $default_icon = $s['default_tab_icon'] ?? null;

        // Compute unique slugs.
        $slugs = array();
        $used = array();
        foreach ($tiles as $i => $t) {
            $base = $this->slugify($t['name'] ?? ('tile-' . ($i + 1)));
            $slug = $base;
            $n = 2;
            while (isset($used[$slug])) {
                $slug = $base . '-' . $n;
                $n++;
            }
            $used[$slug] = true;
            $slugs[$i] = $slug;
        }
        $active_slug = $slugs[0];

        // Build the list of tiles that have a Composite Image (for the Compare/All tab).
        $composites = array();
        foreach ($tiles as $i => $t) {
            $comp = $t['composite_image'] ?? null;
            $url = '';
            if (!empty($comp)) {
                $url = $comp['url'] ?? '';
                if (empty($url) && !empty($comp['id'])) {
                    $url = wp_get_attachment_url($comp['id']);
                }
            }
            if (empty($url)) {
                $tstages = $this->get_tile_stages($t);
                if (!empty($tstages)) {
                    $url = $tstages[count($tstages) - 1]['url'];
                }
            }
            if (empty($url)) continue;
            $composites[] = array(
                'slug' => $slugs[$i],
                'name' => $t['name'] ?? ('Direction ' . ($i + 1)),
                'url'  => $url,
            );
        }

        $show_all_tab = $show_tabs
            && (($s['show_all_tab'] ?? 'yes') === 'yes')
            && count($composites) >= 1;
        $all_label = $s['all_label'] ?? __('Compare', 'ux-portfolio-optimized');
        $all_tab_icon = $s['all_tab_icon'] ?? null;

        if ($show_all_tab) {
            $active_slug = '__all__';
        }
        ?>

        <div class="ux-stile-wrapper" data-active-tile="<?php echo esc_attr($active_slug); ?>">
            <?php $this->render_standard_header(); ?>

            <?php if ($show_tabs) : ?>
                <div class="ux-stile-tabs" role="tablist">
                    <?php if ($show_all_tab) :
                        $all_has_icon = !empty($all_tab_icon) && (!empty($all_tab_icon['value']) || !empty($all_tab_icon['url']));
                        ?>
                        <button type="button"
                                class="ux-stile-tab<?php echo $active_slug === '__all__' ? ' is-active' : ''; ?>"
                                role="tab"
                                aria-selected="<?php echo $active_slug === '__all__' ? 'true' : 'false'; ?>"
                                aria-label="<?php echo esc_attr($all_label); ?>"
                                title="<?php echo esc_attr($all_label); ?>"
                                data-target="__all__"><span class="ux-stile-tab__icon"><?php
                                    if ($all_has_icon) {
                                        \Elementor\Icons_Manager::render_icon($all_tab_icon, ['aria-hidden' => 'true']);
                                    } else { ?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg><?php } ?></span><span class="ux-stile-tab__all-label"><?php echo esc_html($all_label); ?></span></button>
                    <?php endif; ?>
                    <?php foreach ($tiles as $i => $t) :
                        $slug = $slugs[$i];
                        $name = $t['name'] ?? ('Direction ' . ($i + 1));
                        $tab_num = $i + 1;
                        $tile_icon = $t['tab_icon'] ?? null;
                        $tile_has_icon = !empty($tile_icon) && (!empty($tile_icon['value']) || !empty($tile_icon['url']));
                        $effective_icon = $tile_has_icon ? $tile_icon : $default_icon;
                        $has_icon = !empty($effective_icon) && (!empty($effective_icon['value']) || !empty($effective_icon['url']));
                        ?>
                        <button type="button"
                                class="ux-stile-tab<?php echo $slug === $active_slug ? ' is-active' : ''; ?>"
                                role="tab"
                                aria-selected="<?php echo $slug === $active_slug ? 'true' : 'false'; ?>"
                                aria-label="<?php echo esc_attr($name); ?>"
                                title="<?php echo esc_attr($name); ?>"
                                data-target="<?php echo esc_attr($slug); ?>"><span class="ux-stile-tab__icon"><?php
                                    if ($has_icon) {
                                        \Elementor\Icons_Manager::render_icon($effective_icon, ['aria-hidden' => 'true']);
                                    } else { ?><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><circle cx="8" cy="10" r="1.2"/><circle cx="12" cy="7" r="1.2"/><circle cx="16" cy="10" r="1.2"/><circle cx="14" cy="14" r="1.2"/></svg><?php } ?></span><sup class="ux-stile-tab__num"><?php echo esc_html($tab_num); ?></sup></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php foreach ($tiles as $i => $t) :
                $slug = $slugs[$i];
                $is_active = ($slug === $active_slug);
                ?>
                <div class="ux-stile-container<?php echo $is_active ? '' : ' is-hidden'; ?>" data-tile="<?php echo esc_attr($slug); ?>">
                    <?php $this->render_tile($t, $widget_id . '-stile-stages-' . $slug); ?>
                </div>
            <?php endforeach; ?>

            <?php if ($show_all_tab) :
                $all_slideshow = $widget_id . '-stile-all';
                $is_all_active = ($active_slug === '__all__');
                ?>
                <div class="ux-stile-container ux-stile-container--all<?php echo $is_all_active ? '' : ' is-hidden'; ?>" data-tile="__all__">
                    <div class="ux-stile__all-gallery" style="--stile-all-count: <?php echo count($composites); ?>;">
                        <?php foreach ($composites as $idx => $c) : ?>
                            <figure class="ux-stile__all-item">
                                <a href="<?php echo esc_url($c['url']); ?>"
                                   class="ux-stile__all-link"
                                   data-elementor-open-lightbox="yes"
                                   data-elementor-lightbox-slideshow="<?php echo esc_attr($all_slideshow); ?>"
                                   data-elementor-lightbox-index="<?php echo esc_attr($idx); ?>"
                                   aria-label="<?php echo esc_attr($c['name']); ?>">
                                    <img src="<?php echo esc_url($c['url']); ?>" alt="<?php echo esc_attr($c['name']); ?>" loading="lazy">
                                </a>
                                <figcaption class="ux-stile__all-label"><?php echo esc_html($c['name']); ?></figcaption>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php
    }

    private function get_tile_stages($t) {
        $gallery = $t['stage_gallery'] ?? array();
        if (empty($gallery) || !is_array($gallery)) return array();
        $stages = array();
        foreach ($gallery as $item) {
            $id = $item['id'] ?? 0;
            $url = $item['url'] ?? '';
            if (empty($url) && !empty($id)) {
                $url = wp_get_attachment_url($id);
            }
            if (empty($url)) continue;
            $label = '';
            if (!empty($id)) {
                $label = trim((string) wp_get_attachment_caption($id));
            }
            $stages[] = array(
                'label' => $label,
                'url'   => $url,
            );
        }
        return $stages;
    }

    private function render_tile($t, $stages_slideshow = '') {
        $composite_url = '';
        $composite = $t['composite_image'] ?? null;
        if (!empty($composite)) {
            $composite_url = $composite['url'] ?? '';
            if (empty($composite_url) && !empty($composite['id'])) {
                $composite_url = wp_get_attachment_url($composite['id']);
            }
        }

        $stages = $this->get_tile_stages($t);
        $has_stages = !empty($stages);
        $has_image = !empty($composite_url) || $has_stages;
        $default_view = $t['default_view'] ?? 'detail';
        if (!$has_image) $default_view = 'detail';
        $image_active = ($default_view === 'image');
        ?>

        <?php if ($has_image) : ?>
            <div class="ux-stile__view-toggle" role="tablist" aria-label="<?php esc_attr_e('Choose view', 'ux-portfolio-optimized'); ?>">
                <button type="button" class="ux-stile__view-tab<?php echo $image_active ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo $image_active ? 'true' : 'false'; ?>" data-view="image"><?php esc_html_e('Image', 'ux-portfolio-optimized'); ?></button>
                <button type="button" class="ux-stile__view-tab<?php echo !$image_active ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo !$image_active ? 'true' : 'false'; ?>" data-view="detail"><?php esc_html_e('Detail', 'ux-portfolio-optimized'); ?></button>
            </div>

            <div class="ux-stile__view ux-stile__view--image<?php echo !$image_active ? ' is-hidden' : ''; ?>">
                <?php if ($has_stages) : ?>
                    <div class="ux-stile__stages" style="--stile-stage-count: <?php echo count($stages); ?>;">
                        <?php foreach ($stages as $sidx => $st) : ?>
                            <figure class="ux-stile__stage-item">
                                <a href="<?php echo esc_url($st['url']); ?>"
                                   class="ux-stile__stage-link"
                                   data-elementor-open-lightbox="yes"
                                   <?php if ($stages_slideshow !== '') : ?>data-elementor-lightbox-slideshow="<?php echo esc_attr($stages_slideshow); ?>" data-elementor-lightbox-index="<?php echo esc_attr($sidx); ?>"<?php endif; ?>
                                   aria-label="<?php echo esc_attr($st['label']); ?>">
                                    <img src="<?php echo esc_url($st['url']); ?>" alt="<?php echo esc_attr($st['label']); ?>" loading="lazy">
                                </a>
                                <?php if ($st['label'] !== '') : ?>
                                    <figcaption class="ux-stile__stage-label"><?php echo esc_html($st['label']); ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url($composite_url); ?>"
                       class="ux-stile__composite-link"
                       data-elementor-open-lightbox="yes"
                       aria-label="<?php esc_attr_e('View full size', 'ux-portfolio-optimized'); ?>">
                        <img src="<?php echo esc_url($composite_url); ?>" alt="" loading="lazy">
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="ux-stile__view ux-stile__view--detail<?php echo $image_active ? ' is-hidden' : ''; ?>">
            <?php $this->render_tile_detail($t); ?>
        </div>

        <?php
    }

    private function render_tile_detail($t) {
        $palette = $this->parse_palette($t['colors'] ?? '');
        $vibes = $this->parse_vibes($t['vibe_words'] ?? '');
        $imagery = $t['imagery'] ?? array();

        $heading_font = trim($t['heading_font'] ?? '');
        $heading_sample = trim($t['heading_sample'] ?? '');
        $body_font = trim($t['body_font'] ?? '');
        $body_sample = trim($t['body_sample'] ?? '');

        $button_label = trim($t['button_label'] ?? '');
        $button_bg = $t['button_bg'] ?? '';
        $button_text = $t['button_text_color'] ?? '';
        $button_radius = $t['button_radius']['size'] ?? 6;

        $link_color = $t['link_color'] ?? '';
        $link_sample = trim($t['link_sample'] ?? '');
        ?>

        <?php if (!empty($palette)) : ?>
            <section class="ux-stile__section ux-stile__palette">
                <div class="ux-stile__section-label"><?php esc_html_e('Color Palette', 'ux-portfolio-optimized'); ?></div>
                <div class="ux-stile__swatches">
                    <?php foreach ($palette as $c) : ?>
                        <div class="ux-stile__swatch-item">
                            <div class="ux-stile__swatch" style="background: <?php echo esc_attr($c['hex']); ?>;" title="<?php echo esc_attr($c['hex']); ?>"></div>
                            <?php if (!empty($c['name'])) : ?>
                                <div class="ux-stile__swatch-name"><?php echo esc_html($c['name']); ?></div>
                            <?php endif; ?>
                            <div class="ux-stile__swatch-hex"><?php echo esc_html(strtoupper($c['hex'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($heading_sample !== '' || $body_sample !== '') : ?>
            <section class="ux-stile__section ux-stile__typography">
                <div class="ux-stile__section-label"><?php esc_html_e('Typography', 'ux-portfolio-optimized'); ?></div>
                <?php if ($heading_sample !== '') :
                    $h_style = $heading_font !== '' ? 'font-family: ' . esc_attr($heading_font) . ';' : '';
                    ?>
                    <div class="ux-stile__type-heading"<?php echo $h_style !== '' ? ' style="' . $h_style . '"' : ''; ?>>
                        <?php echo esc_html($heading_sample); ?>
                    </div>
                    <?php if ($heading_font !== '') : ?>
                        <div class="ux-stile__type-meta"><?php echo esc_html($heading_font); ?></div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($body_sample !== '') :
                    $b_style = $body_font !== '' ? 'font-family: ' . esc_attr($body_font) . ';' : '';
                    ?>
                    <div class="ux-stile__type-body"<?php echo $b_style !== '' ? ' style="' . $b_style . '"' : ''; ?>>
                        <?php echo nl2br(esc_html($body_sample)); ?>
                    </div>
                    <?php if ($body_font !== '') : ?>
                        <div class="ux-stile__type-meta"><?php echo esc_html($body_font); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if (!empty($imagery)) : ?>
            <section class="ux-stile__section ux-stile__imagery">
                <div class="ux-stile__section-label"><?php esc_html_e('Imagery', 'ux-portfolio-optimized'); ?></div>
                <div class="ux-stile__image-grid">
                    <?php foreach ($imagery as $img) :
                        $img_url = $img['url'] ?? '';
                        if (empty($img_url) && !empty($img['id'])) {
                            $img_url = wp_get_attachment_url($img['id']);
                        }
                        if (empty($img_url)) continue;
                        ?>
                        <div class="ux-stile__image">
                            <img src="<?php echo esc_url($img_url); ?>" alt="" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($button_label !== '' || $link_sample !== '') : ?>
            <section class="ux-stile__section ux-stile__ui">
                <div class="ux-stile__section-label"><?php esc_html_e('UI Elements', 'ux-portfolio-optimized'); ?></div>
                <div class="ux-stile__ui-row">
                    <?php if ($button_label !== '') :
                        $btn_styles = array();
                        if ($button_bg !== '')   $btn_styles[] = 'background: ' . $button_bg;
                        if ($button_text !== '') $btn_styles[] = 'color: ' . $button_text;
                        $btn_styles[] = 'border-radius: ' . intval($button_radius) . 'px';
                        $btn_style_attr = ' style="' . esc_attr(implode('; ', $btn_styles)) . ';"';
                        ?>
                        <span class="ux-stile__button"<?php echo $btn_style_attr; ?>><?php echo esc_html($button_label); ?></span>
                    <?php endif; ?>

                    <?php if ($link_sample !== '') :
                        $link_style = $link_color !== '' ? ' style="color: ' . esc_attr($link_color) . ';"' : '';
                        ?>
                        <span class="ux-stile__link"<?php echo $link_style; ?>><?php echo esc_html($link_sample); ?></span>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if (!empty($vibes)) : ?>
            <section class="ux-stile__section ux-stile__vibes">
                <div class="ux-stile__section-label"><?php esc_html_e('Vibe Words', 'ux-portfolio-optimized'); ?></div>
                <div class="ux-stile__vibe-row">
                    <?php foreach ($vibes as $word) : ?>
                        <span class="ux-stile__vibe"><?php echo esc_html($word); ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
    }
}
