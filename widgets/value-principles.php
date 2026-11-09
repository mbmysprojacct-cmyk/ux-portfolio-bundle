<?php
/**
 * Value → Principles Widget
 *
 * Shows how design principles were derived from value propositions. Each item
 * pairs a value proposition with the design principle(s) it produced, plus an
 * optional rationale ("so we…") line that explains the translation. Laid out as
 * linked cards — side by side on desktop, stacked on mobile — so it reads well
 * responsively instead of breaking like a table.
 */

if (!defined('ABSPATH')) exit;

class UX_Value_Principles_Widget extends UX_Base_Widget {

    use UX_Tabbed_Trait;

    public function get_name() { return 'ux-value-principles'; }
    public function get_title() { return __('Value → Principles', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-flow'; }
    public function get_keywords() {
        return ['value', 'principles', 'design', 'proposition', 'translation', 'mapping', 'ux'];
    }

    protected function register_controls() {

        $this->add_standard_header_section();

        // CONTENT — Pairings
        $this->start_controls_section('pairs_section', [
            'label' => __('Value → Principle Pairings', 'ux-portfolio-optimized'),
        ]);

        $rep = new \Elementor\Repeater();

        $rep->add_control('category', [
            'label' => __('Category', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. Brand', 'ux-portfolio-optimized'),
            'description' => __('Pairings sharing a category appear together under that tab. Only used when Categories are enabled below.', 'ux-portfolio-optimized'),
        ]);

        $rep->add_control('value_label', [
            'label' => __('Value Side Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Value Proposition', 'ux-portfolio-optimized'),
        ]);

        $rep->add_control('value_text', [
            'label' => __('Value Proposition', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => 'Design that doesn\'t scream — but still sings',
        ]);

        $rep->add_control('principle_label', [
            'label' => __('Principle Side Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Design Principle', 'ux-portfolio-optimized'),
        ]);

        $rep->add_control('principle_text', [
            'label' => __('Design Principle(s)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => "Use tone, not volume\nLet whitespace carry the mood",
            'description' => __('One principle per line. A single line shows as a sentence; multiple lines show as a list.', 'ux-portfolio-optimized'),
        ]);

        $rep->add_control('rationale', [
            'label' => __('Rationale (optional)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
            'placeholder' => __('so we… / which is why…', 'ux-portfolio-optimized'),
            'description' => __('Optional. Explains the translation where the pairing isn\'t self-evident. Leave blank to hide.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('pairs', [
            'label' => __('Pairings', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $rep->get_controls(),
            'default' => [
                [
                    'category' => 'Brand',
                    'value_text' => 'Design that doesn\'t scream — but still sings',
                    'principle_text' => "Use tone, not volume\nLet whitespace carry the mood",
                    'rationale' => 'so the brand stands out in a noisy market without shouting',
                ],
                [
                    'category' => 'Brand',
                    'value_text' => 'Rooted, not themed',
                    'principle_text' => 'Honor heritage without costume or kitsch',
                    'rationale' => '',
                ],
                [
                    'category' => 'Website',
                    'value_text' => 'A bar that looks the way good music feels',
                    'principle_text' => "Give every element rhythm and texture\nType and signage set the tempo",
                    'rationale' => 'so the visual system carries emotional cues end to end',
                ],
            ],
            'title_field' => '{{{ category ? category + " — " : "" }}}{{{ value_text }}}',
        ]);

        $this->end_controls_section();

        // CONTENT — Categories (tabs)
        $this->start_controls_section('categories_section', [
            'label' => __('Categories', 'ux-portfolio-optimized'),
        ]);

        $this->add_tab_controls('Category', 'cat');

        $this->end_controls_section();

        // CONTENT — Labels & connector
        $this->start_controls_section('labels_section', [
            'label' => __('Labels & Connector', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_side_labels', [
            'label' => __('Show Side Labels', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('The small "Value Proposition" / "Design Principle" tags on each card.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('rationale_label', [
            'label' => __('Rationale Prefix', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('So we', 'ux-portfolio-optimized'),
            'description' => __('Small label before the rationale line. Leave blank for none.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('connector', [
            'label' => __('Connector', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'arrow',
            'options' => [
                'arrow' => __('Arrow', 'ux-portfolio-optimized'),
                'plus'  => __('Plus / led to', 'ux-portfolio-optimized'),
                'none'  => __('None', 'ux-portfolio-optimized'),
            ],
        ]);

        $this->end_controls_section();

        // STYLE — Cards
        $this->start_controls_section('style_cards', [
            'label' => __('Cards', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('value_heading', [
            'label' => __('Value Card', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('value_bg', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#faf0f5',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-value' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('value_accent', [
            'label' => __('Accent', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT],
            'default' => '#ef4a8a',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-value' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .ux-vp-value .ux-vp-side-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('principle_heading', [
            'label' => __('Principle Card', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('principle_bg', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f0f6fa',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-principle' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('principle_accent', [
            'label' => __('Accent', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY],
            'default' => '#3b82f6',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-principle' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .ux-vp-principle .ux-vp-side-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_shared_heading', [
            'label' => __('Both Cards', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_text_color', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-card-text' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('card_typography', __('Text Typography', 'ux-portfolio-optimized'), '.ux-vp-card-text');

        $this->add_control('card_radius', [
            'label' => __('Corner Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 24]],
            'default' => ['size' => 12],
            'selectors' => [
                '{{WRAPPER}} .ux-vp-card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 8, 'max' => 40]],
            'default' => ['size' => 20],
            'selectors' => [
                '{{WRAPPER}} .ux-vp-card' => 'padding: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('row_gap', [
            'label' => __('Gap Between Pairings', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 8, 'max' => 48]],
            'default' => ['size' => 20],
            'selectors' => [
                '{{WRAPPER}} .ux-vp-list' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // STYLE — Rationale & connector
        $this->start_controls_section('style_extras', [
            'label' => __('Rationale & Connector', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('rationale_color', [
            'label' => __('Rationale Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-rationale' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('rationale_typography', __('Rationale Typography', 'ux-portfolio-optimized'), '.ux-vp-rationale');

        $this->add_control('connector_color', [
            'label' => __('Connector Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9ca3af',
            'selectors' => [
                '{{WRAPPER}} .ux-vp-connector' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /** Connector glyph between the two cards. */
    private function connector_glyph($type) {
        if ($type === 'none') return '';
        if ($type === 'plus') return '+';
        return '→';
    }

    /** Renders one value→principle pairing. */
    private function render_pair($pair, $show_labels, $glyph, $rationale_prefix) {
        $value_text = trim($pair['value_text'] ?? '');
        $principle_raw = $pair['principle_text'] ?? '';
        $rationale = trim($pair['rationale'] ?? '');
        $value_label = trim($pair['value_label'] ?? '');
        $principle_label = trim($pair['principle_label'] ?? '');

        // One principle per line; 1 line = sentence, 2+ = list.
        $principles = array_values(array_filter(array_map('trim', explode("\n", (string) $principle_raw)), function($p) {
            return $p !== '';
        }));
        if (empty($value_text) && empty($principles)) return;
        ?>
        <div class="ux-vp-pair">
            <div class="ux-vp-card ux-vp-value">
                <?php if ($show_labels && $value_label !== '') : ?>
                    <span class="ux-vp-side-label"><?php echo esc_html($value_label); ?></span>
                <?php endif; ?>
                <div class="ux-vp-card-text"><?php echo esc_html($value_text); ?></div>
            </div>

            <?php if ($glyph !== '') : ?>
                <div class="ux-vp-connector" aria-hidden="true"><?php echo esc_html($glyph); ?></div>
            <?php endif; ?>

            <div class="ux-vp-card ux-vp-principle">
                <?php if ($show_labels && $principle_label !== '') : ?>
                    <span class="ux-vp-side-label"><?php echo esc_html($principle_label); ?></span>
                <?php endif; ?>
                <?php if (count($principles) > 1) : ?>
                    <ul class="ux-vp-card-text ux-vp-principle-list">
                        <?php foreach ($principles as $p) : ?>
                            <li><?php echo esc_html($p); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif (count($principles) === 1) : ?>
                    <div class="ux-vp-card-text"><?php echo esc_html($principles[0]); ?></div>
                <?php endif; ?>

                <?php if ($rationale !== '') : ?>
                    <div class="ux-vp-rationale">
                        <?php if ($rationale_prefix !== '') : ?>
                            <span class="ux-vp-rationale-prefix"><?php echo esc_html($rationale_prefix); ?></span>
                        <?php endif; ?>
                        <?php echo esc_html($rationale); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if (empty($s['pairs'])) return;

        $show_labels = ($s['show_side_labels'] ?? 'yes') === 'yes';
        $connector = $s['connector'] ?? 'arrow';
        $glyph = $this->connector_glyph($connector);
        $rationale_prefix = trim($s['rationale_label'] ?? '');

        // Bucket by category using the shared tabbing trait.
        $enabled = ($s['cat_enable'] ?? '') === 'yes';
        $buckets = $this->bucket_by($s['pairs'], 'category', $enabled);
        $names = array_keys($buckets);
        $tabbed = $enabled && (($s['cat_show_tabs'] ?? 'yes') === 'yes') && count($names) > 1;
        $active = $this->resolve_active($names, $s['cat_default'] ?? '');

        $this->open_tab_shell($names, $tabbed, $active, 'ux-vp-wrapper ux-vp-connector-' . $connector);
        foreach ($names as $i => $n) {
            $this->tab_panel_open($i, $n, $tabbed, $active, $enabled);
            echo '<div class="ux-vp-list">';
            foreach ($buckets[$n] as $pair) {
                $this->render_pair($pair, $show_labels, $glyph, $rationale_prefix);
            }
            echo '</div>';
            echo '</div>'; // close panel
        }
        $this->close_tab_shell();
    }
}
