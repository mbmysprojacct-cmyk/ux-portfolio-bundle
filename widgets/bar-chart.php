<?php
/**
 * Bar Chart Widget
 *
 * Simple, dependency-free bar chart for visualizing usability study data
 * (task success %, time on task, SUS scores, error counts, satisfaction, etc.).
 * CSS-driven bars with optional scroll-in animation. Vertical or horizontal.
 */

if (!defined('ABSPATH')) exit;

class UX_Bar_Chart_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-bar-chart'; }
    public function get_title() { return __('Bar Chart', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-skill-bar'; }
    public function get_keywords() {
        return ['chart', 'bar', 'graph', 'usability', 'data', 'metrics', 'ux'];
    }

    protected function register_controls() {

        // Standard header (title + description)
        $this->add_standard_header_section();

        // CONTENT — Data
        $this->start_controls_section('data_section', [
            'label' => __('Chart Data', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('chart_type', [
            'label' => __('Chart Type', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'simple',
            'options' => [
                'simple'  => __('Simple bars', 'ux-portfolio-optimized'),
                'stacked' => __('Stacked segments (composition)', 'ux-portfolio-optimized'),
            ],
            'description' => __('Simple = one value per bar. Stacked = each bar split into parts that sum to a total.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('orientation', [
            'label' => __('Orientation', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'vertical',
            'options' => [
                'vertical'   => __('Vertical bars', 'ux-portfolio-optimized'),
                'horizontal' => __('Horizontal bars', 'ux-portfolio-optimized'),
            ],
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('label', [
            'label' => __('Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Task', 'ux-portfolio-optimized'),
            'dynamic' => ['active' => true],
        ]);

        $repeater->add_control('value', [
            'label' => __('Value', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'dynamic' => ['active' => true],
        ]);

        $repeater->add_control('bar_color', [
            'label' => __('Bar Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'description' => __('Optional. Overrides the default bar color for this bar only.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('note', [
            'label' => __('Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'placeholder' => __('e.g. → prioritise mobile', 'ux-portfolio-optimized'),
            'description' => __('Optional. A short takeaway shown under this bar.', 'ux-portfolio-optimized'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('bars', [
            'label' => __('Bars', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'condition' => ['chart_type' => 'simple'],
            'default' => [
                ['label' => __('Task 1', 'ux-portfolio-optimized'), 'value' => 92],
                ['label' => __('Task 2', 'ux-portfolio-optimized'), 'value' => 78],
                ['label' => __('Task 3', 'ux-portfolio-optimized'), 'value' => 64],
                ['label' => __('Task 4', 'ux-portfolio-optimized'), 'value' => 88],
            ],
            'title_field' => '{{{ label }}}: {{{ value }}}',
        ]);

        // STACKED BAR: one flat list of segments; each is a slice of a single
        // vertical (or horizontal) stacked bar. Simple — no bar matching.
        $seg_rep = new \Elementor\Repeater();

        $seg_rep->add_control('seg_label', [
            'label' => __('Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Segment', 'ux-portfolio-optimized'),
            'label_block' => true,
        ]);

        $seg_rep->add_control('seg_value', [
            'label' => __('Value', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
        ]);

        $seg_rep->add_control('seg_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'description' => __('Optional. Falls back to a palette color by position.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('segments', [
            'label' => __('Segments', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $seg_rep->get_controls(),
            'condition' => ['chart_type' => 'stacked'],
            'default' => [
                ['seg_label' => __('East', 'ux-portfolio-optimized'), 'seg_value' => 16],
                ['seg_label' => __('Central', 'ux-portfolio-optimized'), 'seg_value' => 15],
                ['seg_label' => __('North', 'ux-portfolio-optimized'), 'seg_value' => 14],
                ['seg_label' => __('South', 'ux-portfolio-optimized'), 'seg_value' => 10],
                ['seg_label' => __('West', 'ux-portfolio-optimized'), 'seg_value' => 7],
            ],
            'title_field' => '{{{ seg_label }}}: {{{ seg_value }}}',
        ]);

        $this->add_control('max_value', [
            'label' => __('Max Value (scale)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 100,
            'description' => __('The value the full bar represents (e.g. 100 for percentages). Leave blank to scale to the largest value.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('value_suffix', [
            'label' => __('Value Suffix', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '%',
            'placeholder' => '% / s / pts',
        ]);

        $this->add_control('show_values', [
            'label' => __('Show Values', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_gridlines', [
            'label' => __('Show Gridlines', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('animate', [
            'label' => __('Animate on Scroll', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        // STYLE — Bars
        $this->start_controls_section('style_bars', [
            'label' => __('Bars', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('bar_color_default', [
            'label' => __('Bar Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT],
            'default' => '#ef4a8a',
            'selectors' => [
                '{{WRAPPER}} .ux-barchart__bar' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('track_color', [
            'label' => __('Track Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f1f1f4',
            'selectors' => [
                '{{WRAPPER}} .ux-barchart__track, {{WRAPPER}} .orientation-vertical .ux-barchart__bar-wrap' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('bar_thickness', [
            'label' => __('Bar Thickness', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => ['%' => ['min' => 10, 'max' => 100], 'px' => ['min' => 6, 'max' => 120]],
            'default' => ['unit' => '%', 'size' => 60],
            'selectors' => [
                '{{WRAPPER}} .ux-barchart' => '--bar-thickness: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('bar_radius', [
            'label' => __('Bar Corner Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 6],
            'selectors' => [
                '{{WRAPPER}} .ux-barchart__bar' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('bar_gap', [
            'label' => __('Gap Between Bars', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 4, 'max' => 60]],
            'default' => ['size' => 18],
            'selectors' => [
                '{{WRAPPER}} .ux-barchart' => '--bar-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('chart_height', [
            'label' => __('Chart Height', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range' => ['px' => ['min' => 160, 'max' => 700], 'vh' => ['min' => 20, 'max' => 90]],
            'default' => ['unit' => 'px', 'size' => 320],
            'selectors' => [
                '{{WRAPPER}} .orientation-vertical .ux-barchart__plot' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'description' => __('Applies to vertical orientation.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('gridline_color', [
            'label' => __('Gridline Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e6e6ea',
            'selectors' => [
                '{{WRAPPER}} .ux-barchart' => '--grid-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // STYLE — Labels & Values
        $this->start_controls_section('style_text', [
            'label' => __('Labels & Values', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('label_color', [
            'label' => __('Label Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .ux-barchart__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('label_typography', __('Label Typography', 'ux-portfolio-optimized'), '.ux-barchart__label');

        $this->add_control('value_color', [
            'label' => __('Value Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#555555',
            'selectors' => [
                '{{WRAPPER}} .ux-barchart__value' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('value_typography', __('Value Typography', 'ux-portfolio-optimized'), '.ux-barchart__value');

        $this->end_controls_section();
    }

    /** Default segment palette (used when a segment has no explicit color). */
    private function seg_palette($i) {
        $palette = array('#ef4a8a', '#3b82f6', '#d4a017', '#10b981', '#8b5cf6', '#f97316', '#06b6d4', '#64748b');
        return $palette[$i % count($palette)];
    }

    /** Render a single stacked bar (vertical or horizontal) with a scale axis. */
    private function render_stacked($s) {
        $segments = (!empty($s['segments']) && is_array($s['segments'])) ? $s['segments'] : array();
        if (empty($segments)) return;

        $orientation = ($s['orientation'] ?? 'vertical') === 'horizontal' ? 'horizontal' : 'vertical';
        $suffix = $s['value_suffix'] ?? '';
        $show_values = ($s['show_values'] ?? 'yes') === 'yes';
        $show_grid = ($s['show_gridlines'] ?? 'yes') === 'yes';
        $animate = ($s['animate'] ?? 'yes') === 'yes';

        // Total of all segments.
        $total = 0;
        foreach ($segments as $seg) $total += floatval($seg['seg_value'] ?? 0);
        if ($total <= 0) $total = 1;

        // Scale max: explicit Max Value, else the total (bar fills fully).
        $max_raw = $s['max_value'] ?? '';
        $scale_max = ($max_raw !== '' && floatval($max_raw) > 0) ? floatval($max_raw) : $total;
        if ($scale_max <= 0) $scale_max = 1;

        // The whole bar's length as a % of the plot (against the scale).
        $bar_len = ($total / $scale_max) * 100;
        if ($bar_len > 100) $bar_len = 100;

        // Build gridline ticks (0 .. scale_max), a handful of even steps.
        $ticks = array();
        $step_count = 7;
        for ($t = 0; $t <= $step_count; $t++) {
            $ticks[] = round($scale_max * $t / $step_count);
        }

        $classes = 'ux-barchart ux-stackbar orientation-' . $orientation;
        if ($show_grid) $classes .= ' show-grid';
        if ($animate) $classes .= ' can-animate';
        ?>
        <div class="ux-bar-chart-wrapper">
            <?php $this->render_standard_header(); ?>

            <div class="<?php echo esc_attr($classes); ?>">
                <div class="ux-stackbar__area">
                    <?php if ($show_grid) : ?>
                        <div class="ux-stackbar__axis">
                            <?php foreach (array_reverse($ticks) as $tick) : ?>
                                <div class="ux-stackbar__tick"><span class="ux-stackbar__ticklabel"><?php echo esc_html($tick . $suffix); ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="ux-stackbar__plot">
                        <div class="ux-stackbar__col">
                            <div class="ux-stackbar__fill" style="<?php echo $orientation === 'vertical' ? 'height' : 'width'; ?>: <?php echo esc_attr($bar_len); ?>%;">
                                <?php
                                // Vertical bars read bottom-to-top, so render in reverse
                                // so the first segment sits at the bottom.
                                $ordered = $orientation === 'vertical' ? array_reverse($segments) : $segments;
                                $count = count($segments);
                                foreach ($ordered as $oi => $seg) :
                                    $val = floatval($seg['seg_value'] ?? 0);
                                    $lbl = trim($seg['seg_label'] ?? '');
                                    // Segment size as a share of the FILL (relative to total).
                                    $within = ($total > 0) ? ($val / $total * 100) : 0;
                                    $color = trim($seg['seg_color'] ?? '');
                                    if ($color === '') {
                                        // Keep palette index stable to original order.
                                        $orig_index = $orientation === 'vertical' ? ($count - 1 - $oi) : $oi;
                                        $color = $this->seg_palette($orig_index);
                                    }
                                    $dim = $orientation === 'vertical' ? 'height' : 'width';
                                    ?>
                                    <div class="ux-stackbar__seg" style="<?php echo $dim; ?>: <?php echo esc_attr($within); ?>%; background: <?php echo esc_attr($color); ?>;">
                                        <span class="ux-stackbar__seglabel"><?php echo esc_html($lbl); ?><?php if ($show_values && $val) : ?> <span class="ux-stackbar__segval"><?php echo esc_html($val . $suffix); ?></span><?php endif; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


    protected function render() {
        $s = $this->get_settings_for_display();

        // Stacked segments mode routes to its own renderer.
        if (($s['chart_type'] ?? 'simple') === 'stacked') {
            $this->render_stacked($s);
            return;
        }

        $bars = $s['bars'] ?? [];
        if (empty($bars)) return;

        $orientation = ($s['orientation'] ?? 'vertical') === 'horizontal' ? 'horizontal' : 'vertical';
        $suffix = $s['value_suffix'] ?? '';
        $show_values = ($s['show_values'] ?? 'yes') === 'yes';
        $show_grid = ($s['show_gridlines'] ?? 'yes') === 'yes';
        $animate = ($s['animate'] ?? 'yes') === 'yes';

        // Determine scale max
        $values = array();
        foreach ($bars as $b) {
            $values[] = floatval($b['value'] ?? 0);
        }
        $max_raw = $s['max_value'] ?? '';
        $max = ($max_raw !== '' && floatval($max_raw) > 0) ? floatval($max_raw) : (count($values) ? max($values) : 0);
        if ($max <= 0) $max = 1;

        $classes = 'ux-barchart orientation-' . $orientation;
        if ($show_grid) $classes .= ' show-grid';
        if ($animate) $classes .= ' can-animate';
        ?>

        <div class="ux-bar-chart-wrapper">
            <?php $this->render_standard_header(); ?>

            <div class="<?php echo esc_attr($classes); ?>">
                <?php if ($orientation === 'vertical') : ?>
                    <div class="ux-barchart__plot">
                        <div class="ux-barchart__bars">
                            <?php foreach ($bars as $b) :
                                $val = floatval($b['value'] ?? 0);
                                $pct = max(0, min(100, ($val / $max) * 100));
                                $color = trim($b['bar_color'] ?? '');
                                $bar_style = '--bar-size: ' . $pct . '%;';
                                if ($color !== '') $bar_style .= ' background: ' . esc_attr($color) . ';';
                                ?>
                                <div class="ux-barchart__col">
                                    <div class="ux-barchart__bar-wrap">
                                        <?php if ($show_values) : ?>
                                            <span class="ux-barchart__value"><?php echo esc_html($b['value'] ?? '0'); ?><?php echo esc_html($suffix); ?></span>
                                        <?php endif; ?>
                                        <div class="ux-barchart__bar" style="<?php echo $bar_style; ?>"></div>
                                    </div>
                                    <div class="ux-barchart__label"><?php echo esc_html($b['label'] ?? ''); ?></div>
                                    <?php if (trim((string) ($b['note'] ?? '')) !== '') : ?>
                                        <div class="ux-barchart__note"><?php echo esc_html($b['note']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="ux-barchart__rows">
                        <?php foreach ($bars as $b) :
                            $val = floatval($b['value'] ?? 0);
                            $pct = max(0, min(100, ($val / $max) * 100));
                            $color = trim($b['bar_color'] ?? '');
                            $bar_style = '--bar-size: ' . $pct . '%;';
                            if ($color !== '') $bar_style .= ' background: ' . esc_attr($color) . ';';
                            ?>
                            <div class="ux-barchart__row">
                                <div class="ux-barchart__label"><?php echo esc_html($b['label'] ?? ''); ?></div>
                                <div class="ux-barchart__track">
                                    <div class="ux-barchart__bar" style="<?php echo $bar_style; ?>"></div>
                                    <?php if ($show_values) : ?>
                                        <span class="ux-barchart__value"><?php echo esc_html($b['value'] ?? '0'); ?><?php echo esc_html($suffix); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (trim((string) ($b['note'] ?? '')) !== '') : ?>
                                    <div class="ux-barchart__note"><?php echo esc_html($b['note']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
    }
}
