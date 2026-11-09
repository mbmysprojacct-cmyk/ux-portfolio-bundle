<?php
/**
 * Annotated Design Widget
 *
 * Annotate one or more design renders (logo SVGs/images) with numbered hotspots.
 * Each hotspot opens a panel: the design concern, the client's original note it
 * responds to, and the resolution. Optionally flip each stage between the
 * client's sketch and the render.
 *
 * MULTIPLE DESIGNS WITHOUT NESTED REPEATERS: uses TWO FLAT top-level repeaters —
 * a "Designs" list (each with its own image/sketch/background) and a "Hotspots"
 * list (each tagged with which design it belongs to). At render they're bucketed
 * into tabs by design. Elementor does not support nested repeaters, so this
 * flat + linked structure is the safe way to do per-design groups.
 */

if (!defined('ABSPATH')) exit;

class UX_Annotated_Design_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-annotated-design'; }
    public function get_title() { return __('Annotated Design', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-info-circle'; }
    public function get_keywords() {
        return ['annotated', 'design', 'hotspot', 'logo', 'annotation', 'sketch', 'render', 'ux'];
    }

    protected function register_controls() {

        $this->add_standard_header_section();

        // CONTENT — Designs (flat repeater; each row = one design + its image)
        $this->start_controls_section('designs_section', [
            'label' => __('Designs', 'ux-portfolio-optimized'),
        ]);

        $design = new \Elementor\Repeater();

        $design->add_control('name', [
            'label' => __('Design Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Design 1',
            'label_block' => true,
            'description' => __('Shown as the tab label. Hotspots reference this name.', 'ux-portfolio-optimized'),
        ]);

        $design->add_control('render_image', [
            'label' => __('Rendered Design (SVG/PNG)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'description' => __('The final render. SVG stays crisp at any size.', 'ux-portfolio-optimized'),
        ]);

        $design->add_control('sketch_image', [
            'label' => __("Client Sketch / Reference", 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'description' => __('Optional. Enables a Sketch / Render toggle for this design.', 'ux-portfolio-optimized'),
        ]);

        $design->add_control('stage_bg', [
            'label' => __('Stage Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'light',
            'options' => [
                'light'  => __('Light', 'ux-portfolio-optimized'),
                'dark'   => __('Dark', 'ux-portfolio-optimized'),
                'custom' => __('Custom', 'ux-portfolio-optimized'),
            ],
        ]);

        $design->add_control('stage_bg_custom', [
            'label' => __('Custom Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f3f4f6',
            'condition' => ['stage_bg' => 'custom'],
        ]);

        $this->add_control('designs', [
            'label' => __('Designs', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $design->get_controls(),
            'default' => [
                ['name' => 'Design 1'],
            ],
            'title_field' => '{{{ name }}}',
        ]);

        $this->end_controls_section();

        // CONTENT — Hotspots (flat repeater; each row tagged with a design name)
        $this->start_controls_section('hotspots_section', [
            'label' => __('Hotspots', 'ux-portfolio-optimized'),
        ]);

        $hotspot = new \Elementor\Repeater();

        $hotspot->add_control('design', [
            'label' => __('Belongs to Design', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Design 1',
            'label_block' => true,
            'description' => __('Type the Design Name this hotspot sits on (must match a design above).', 'ux-portfolio-optimized'),
        ]);

        $hotspot->add_control('h_title', [
            'label' => __('Concern Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
        ]);

        $hotspot->add_control('h_concern', [
            'label' => __('Design Concern', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => '',
            'description' => __('Your concern about this part of the mark.', 'ux-portfolio-optimized'),
        ]);

        $hotspot->add_control('h_note', [
            'label' => __("Client's Note", 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
            'description' => __('The original note or sketch instruction this responds to.', 'ux-portfolio-optimized'),
        ]);

        $hotspot->add_control('h_resolution', [
            'label' => __('Resolution', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
            'description' => __('How you resolved it (optional).', 'ux-portfolio-optimized'),
        ]);

        $hotspot->add_control('h_x', [
            'label' => __('Horizontal %', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['%' => ['min' => 0, 'max' => 100]],
            'default' => ['unit' => '%', 'size' => 50],
        ]);

        $hotspot->add_control('h_y', [
            'label' => __('Vertical %', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['%' => ['min' => 0, 'max' => 100]],
            'default' => ['unit' => '%', 'size' => 50],
        ]);

        $this->add_control('hotspots', [
            'label' => __('Hotspots', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $hotspot->get_controls(),
            'default' => [],
            'title_field' => '{{{ design }}}: {{{ h_title || "Concern" }}}',
        ]);

        $this->end_controls_section();

        // CONTENT — Behavior
        $this->start_controls_section('behavior_section', [
            'label' => __('Behavior', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_tabs', [
            'label' => __('Show Tabs', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('With multiple designs, switch between them with tabs. Off = stack them down the page.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('hotspot_trigger', [
            'label' => __('Open Panel On', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'click',
            'options' => [
                'click' => __('Click / tap', 'ux-portfolio-optimized'),
                'hover' => __('Hover', 'ux-portfolio-optimized'),
            ],
            'description' => __('Click is best on touch devices.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('numbered', [
            'label' => __('Numbered Hotspots', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Show 1, 2, 3… on the dots so panels can reference them.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('sketch_label', [
            'label' => __('Sketch Toggle Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Sketch', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('render_label', [
            'label' => __('Render Toggle Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Render', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();

        // STYLE — Stage
        $this->start_controls_section('style_stage', [
            'label' => __('Stage', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('stage_height', [
            'label' => __('Stage Height', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 200, 'max' => 700]],
            'default' => ['size' => 400],
            'selectors' => [
                '{{WRAPPER}} .ux-ad-stage' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('stage_radius', [
            'label' => __('Corner Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 24]],
            'default' => ['size' => 12],
            'selectors' => [
                '{{WRAPPER}} .ux-ad-stage' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('light_bg', [
            'label' => __('Light Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f8f9fa',
            'selectors' => ['{{WRAPPER}} .ux-ad-bg-light' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('dark_bg', [
            'label' => __('Dark Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => ['{{WRAPPER}} .ux-ad-bg-dark' => 'background: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // STYLE — Hotspots & panel
        $this->start_controls_section('style_hotspots', [
            'label' => __('Hotspots & Panel', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('dot_color', [
            'label' => __('Hotspot Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT],
            'default' => '#ef4a8a',
            'selectors' => [
                '{{WRAPPER}} .ux-ad-dot' => 'background: {{VALUE}};',
                '{{WRAPPER}} .ux-ad-panel-num' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('dot_size', [
            'label' => __('Hotspot Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 18, 'max' => 48]],
            'default' => ['size' => 28],
            'selectors' => [
                '{{WRAPPER}} .ux-ad-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('panel_bg', [
            'label' => __('Panel Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .ux-ad-panel' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('panel_title_color', [
            'label' => __('Panel Title Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .ux-ad-panel-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('panel_text_color', [
            'label' => __('Panel Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#374151',
            'selectors' => [
                '{{WRAPPER}} .ux-ad-panel-body' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('panel_typography', __('Panel Text Typography', 'ux-portfolio-optimized'), '.ux-ad-panel-body');

        $this->end_controls_section();
    }

    /** Resolve a media field to a URL. */
    private function media_url($field) {
        if (!empty($field['url'])) return $field['url'];
        if (!empty($field['id'])) return wp_get_attachment_url($field['id']);
        return '';
    }

    /** Stage background class + inline style. */
    private function stage_bg_attrs($bg, $custom) {
        $class = 'ux-ad-bg-light';
        $style = '';
        if ($bg === 'dark') {
            $class = 'ux-ad-bg-dark';
        } elseif ($bg === 'custom') {
            $class = 'ux-ad-bg-custom';
            $style = ' style="background: ' . esc_attr($custom !== '' ? $custom : '#f3f4f6') . ';"';
        }
        return array('class' => $class, 'style' => $style);
    }

    /** Renders one design's stage with its hotspots. */
    private function render_stage($design, $hotspots, $trigger, $numbered, $sketch_label, $render_label) {
        $render_img = $this->media_url($design['render_image'] ?? array());
        $sketch_img = $this->media_url($design['sketch_image'] ?? array());
        $has_toggle = ($render_img !== '' && $sketch_img !== '');
        $name = trim($design['name'] ?? '');
        $bg = $this->stage_bg_attrs($design['stage_bg'] ?? 'light', $design['stage_bg_custom'] ?? '');

        if ($render_img === '' && $sketch_img === '') {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="ux-ad-empty">' . esc_html(sprintf(__('Design "%s": add a Rendered Design image in the Designs panel.', 'ux-portfolio-optimized'), $name)) . '</div>';
            }
            return;
        }
        ?>
        <div class="ux-ad-design" data-trigger="<?php echo esc_attr($trigger); ?>">
            <?php if ($has_toggle) : ?>
                <div class="ux-ad-designhead">
                    <div class="ux-ad-toggle" role="group" aria-label="<?php echo esc_attr(sprintf(/* translators: 1: first view label, 2: second view label */ __('%1$s or %2$s view', 'ux-portfolio-optimized'), $render_label, $sketch_label)); ?>">
                        <button type="button" class="ux-ad-toggle-btn is-active" data-show="render" aria-pressed="true"><?php echo esc_html($render_label); ?></button>
                        <button type="button" class="ux-ad-toggle-btn" data-show="sketch" aria-pressed="false"><?php echo esc_html($sketch_label); ?></button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="ux-ad-stage <?php echo esc_attr($bg['class']); ?>"<?php echo $bg['style']; ?>>
                <?php if ($render_img !== '') : ?>
                    <img class="ux-ad-img ux-ad-img-render is-active" src="<?php echo esc_url($render_img); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
                <?php endif; ?>
                <?php if ($sketch_img !== '') : ?>
                    <img class="ux-ad-img ux-ad-img-sketch<?php echo $render_img === '' ? ' is-active' : ''; ?>" src="<?php echo esc_url($sketch_img); ?>" alt="<?php echo esc_attr($name); ?> sketch" loading="lazy">
                <?php endif; ?>

                <?php foreach ($hotspots as $idx => $h) :
                    $x = isset($h['h_x']['size']) ? floatval($h['h_x']['size']) : 50;
                    $y = isset($h['h_y']['size']) ? floatval($h['h_y']['size']) : 50;
                    $h_title = trim($h['h_title'] ?? '');
                    $concern = trim($h['h_concern'] ?? '');
                    $note = trim($h['h_note'] ?? '');
                    $resolution = trim($h['h_resolution'] ?? '');
                    $num = $idx + 1;
                    ?>
                    <div class="ux-ad-hotspot" style="left: <?php echo esc_attr($x); ?>%; top: <?php echo esc_attr($y); ?>%;">
                        <button type="button" class="ux-ad-dot" aria-expanded="false" aria-label="<?php echo esc_attr($h_title !== '' ? $h_title : sprintf(__('Concern %d', 'ux-portfolio-optimized'), $num)); ?>">
                            <?php if ($numbered) echo esc_html($num); ?>
                        </button>
                        <div class="ux-ad-panel" role="dialog">
                            <?php if ($h_title !== '') : ?>
                                <div class="ux-ad-panel-title">
                                    <?php if ($numbered) : ?><span class="ux-ad-panel-num"><?php echo esc_html($num); ?></span><?php endif; ?>
                                    <?php echo esc_html($h_title); ?>
                                </div>
                            <?php endif; ?>
                            <div class="ux-ad-panel-body">
                                <?php if ($concern !== '') : ?>
                                    <div class="ux-ad-panel-concern"><?php echo esc_html($concern); ?></div>
                                <?php endif; ?>
                                <?php if ($note !== '') : ?>
                                    <div class="ux-ad-panel-row"><span class="ux-ad-panel-label"><?php esc_html_e("Client's note", 'ux-portfolio-optimized'); ?></span><?php echo esc_html($note); ?></div>
                                <?php endif; ?>
                                <?php if ($resolution !== '') : ?>
                                    <div class="ux-ad-panel-row"><span class="ux-ad-panel-label"><?php esc_html_e('Resolution', 'ux-portfolio-optimized'); ?></span><?php echo esc_html($resolution); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $designs = (!empty($s['designs']) && is_array($s['designs'])) ? $s['designs'] : array();
        if (empty($designs)) return;

        $all_hotspots = (!empty($s['hotspots']) && is_array($s['hotspots'])) ? $s['hotspots'] : array();

        $trigger = ($s['hotspot_trigger'] ?? 'click') === 'hover' ? 'hover' : 'click';
        $numbered = ($s['numbered'] ?? 'yes') === 'yes';
        $sketch_label = $s['sketch_label'] ?? __('Sketch', 'ux-portfolio-optimized');
        $render_label = $s['render_label'] ?? __('Render', 'ux-portfolio-optimized');
        $show_tabs = ($s['show_tabs'] ?? 'yes') === 'yes' && count($designs) > 1;

        // Group hotspots by their design name (flat -> per-design buckets).
        $by_design = array();
        foreach ($all_hotspots as $h) {
            $key = trim($h['design'] ?? '');
            if (!isset($by_design[$key])) $by_design[$key] = array();
            $by_design[$key][] = $h;
        }

        $wid = esc_attr($this->get_id());

        $this->render_standard_header();

        echo '<div class="ux-ad-wrapper" data-ux-adtabs data-widget-id="' . $wid . '">';

        if ($show_tabs) {
            echo '<div class="ux-tabs" role="tablist">';
            foreach ($designs as $i => $d) {
                $nm = trim($d['name'] ?? ('Design ' . ($i + 1)));
                $on = ($i === 0) ? ' is-active' : '';
                echo '<button type="button" class="ux-tabs__tab' . $on . '" role="tab" data-ad-tab="' . $i . '">' . esc_html($nm) . '</button>';
            }
            echo '</div>';
        }

        foreach ($designs as $i => $d) {
            $nm = trim($d['name'] ?? '');
            $hs = isset($by_design[$nm]) ? $by_design[$nm] : array();
            $hidden = ($show_tabs && $i !== 0) ? ' is-hidden' : '';
            echo '<div class="ux-ad-panelwrap' . $hidden . '" data-ad-panel="' . $i . '">';
            if (!$show_tabs && count($designs) > 1 && $nm !== '') {
                echo '<h3 class="ux-ad-groupname">' . esc_html($nm) . '</h3>';
            }
            $this->render_stage($d, $hs, $trigger, $numbered, $sketch_label, $render_label);
            echo '</div>';
        }

        echo '</div>';
    }
}
