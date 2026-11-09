<?php
/**
 * Features & Benefits Matrix Widget
 *
 * A Feature -> Benefit -> Persona-alignment register (the FAB matrix). Each row
 * pairs a feature with the benefit it delivers and the personas it serves.
 * The persona column uses aligned icons + optional labels rather than a cramped
 * strip of emoji, so it reads cleanly however many personas map to a feature.
 */

if (!defined('ABSPATH')) exit;

class UX_Features_Benefits_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-features-benefits'; }
    public function get_title() { return __('Features & Benefits Matrix', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-table'; }
    public function get_keywords() {
        return ['features', 'benefits', 'matrix', 'fab', 'persona', 'alignment', 'ux'];
    }

    protected function register_controls() {

        $this->add_standard_header_section();

        // CONTENT — Personas (the legend the alignment column draws from)
        $this->start_controls_section('personas_section', [
            'label' => __('Personas', 'ux-portfolio-optimized'),
        ]);

        $persona_rep = new \Elementor\Repeater();

        $persona_rep->add_control('persona_key', [
            'label' => __('Key', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'placeholder' => __('e.g. lena', 'ux-portfolio-optimized'),
            'description' => __('Short id you type into a row\'s Personas field (comma-separated).', 'ux-portfolio-optimized'),
        ]);

        $persona_rep->add_control('persona_name', [
            'label' => __('Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
        ]);

        $persona_rep->add_control('persona_icon', [
            'label' => __('Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
        ]);

        $persona_rep->add_control('persona_image', [
            'label' => __('Image', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'description' => __('Optional. A small avatar — replaces the icon if set.', 'ux-portfolio-optimized'),
        ]);

        $persona_rep->add_control('persona_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ef4a8a',
        ]);

        $this->add_control('personas', [
            'label' => __('Personas', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $persona_rep->get_controls(),
            'default' => [
                ['persona_key' => 'p1', 'persona_name' => 'Persona 1', 'persona_color' => '#ef4a8a'],
                ['persona_key' => 'p2', 'persona_name' => 'Persona 2', 'persona_color' => '#d4a017'],
            ],
            'title_field' => '{{{ persona_name || persona_key }}}',
        ]);

        $this->add_control('show_legend', [
            'label' => __('Show Persona Legend', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('A key above the table mapping each icon to its persona.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('align_display', [
            'label' => __('Alignment Column Shows', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'icon_label',
            'options' => [
                'icon_label' => __('Icon + name', 'ux-portfolio-optimized'),
                'icon'       => __('Icon only', 'ux-portfolio-optimized'),
                'label'      => __('Name only (pill)', 'ux-portfolio-optimized'),
            ],
        ]);

        $this->end_controls_section();

        // CONTENT — Rows
        $this->start_controls_section('rows_section', [
            'label' => __('Rows', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('feature_heading_text', [
            'label' => __('Feature Column Heading', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Feature', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('benefit_heading_text', [
            'label' => __('Benefit Column Heading', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Benefit to Users', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('align_heading_text', [
            'label' => __('Alignment Column Heading', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Persona Alignment', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_align_column', [
            'label' => __('Show Alignment Column', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $row_rep = new \Elementor\Repeater();

        $row_rep->add_control('version', [
            'label' => __('Version', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. Brand', 'ux-portfolio-optimized'),
            'description' => __('Rows sharing a version name appear together under that tab. Only used when Versions are enabled below.', 'ux-portfolio-optimized'),
        ]);

        $row_rep->add_control('feature', [
            'label' => __('Feature', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
        ]);

        $row_rep->add_control('benefit', [
            'label' => __('Benefit', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
        ]);

        $row_rep->add_control('row_personas', [
            'label' => __('Personas', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. p1, p2', 'ux-portfolio-optimized'),
            'description' => __('Comma-separated persona keys from the Personas list above.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('rows', [
            'label' => __('Rows', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $row_rep->get_controls(),
            'default' => [
                ['version' => 'Brand', 'feature' => 'Timeless logo with musical cues', 'benefit' => 'Feels emotionally grounded, not trendy', 'row_personas' => 'p1, p2'],
                ['version' => 'Brand', 'feature' => 'Vintage-modern typography pairing', 'benefit' => 'Signals taste and depth without being showy', 'row_personas' => 'p1'],
                ['version' => 'Website', 'feature' => 'One-tap save to a trip board', 'benefit' => 'Keeps recommendations in one place', 'row_personas' => 'p2'],
            ],
            'title_field' => '{{{ version ? version + " — " : "" }}}{{{ feature }}}',
        ]);

        $this->end_controls_section();

        // CONTENT — Versions
        $this->start_controls_section('versions_section', [
            'label' => __('Versions', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('enable_versions', [
            'label' => __('Enable Versions', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Show several matrices (e.g. Brand and Website), switchable by tabs. Give each row above a Version name — rows sharing a name appear together.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_tabs', [
            'label' => __('Show Tabs', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['enable_versions' => 'yes'],
            'description' => __('Turn off to stack every version down the page instead of tabbing between them.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('default_version', [
            'label' => __('Default Version', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'condition' => ['enable_versions' => 'yes', 'show_tabs' => 'yes'],
            'description' => __('Name of the version to open first. Leave blank for the first one.', 'ux-portfolio-optimized'),
        ]);

        $title_rep = new \Elementor\Repeater();

        $title_rep->add_control('vt_version', [
            'label' => __('Version', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'placeholder' => __('e.g. Brand', 'ux-portfolio-optimized'),
            'description' => __('Must match a Version name used on the rows above.', 'ux-portfolio-optimized'),
        ]);

        $title_rep->add_control('vt_title', [
            'label' => __('Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. Brand identity system', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('version_titles', [
            'label' => __('Version Titles', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $title_rep->get_controls(),
            'default' => [],
            'title_field' => '{{{ vt_version }}}{{{ vt_title ? " → " + vt_title : "" }}}',
            'condition' => ['enable_versions' => 'yes'],
            'description' => __('Optional. Give any version a fuller heading shown above its table — separate from the short tab label. Versions you leave out just use their name.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();

        // STYLE — Table
        $this->start_controls_section('style_table', [
            'label' => __('Table', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('header_bg', [
            'label' => __('Header Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table thead th' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('header_color', [
            'label' => __('Header Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table thead th' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('header_typography', __('Header Typography', 'ux-portfolio-optimized'), '.ux-fb-table thead th');

        $this->add_control('row_bg', [
            'label' => __('Row Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table tbody td' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('row_alt_bg', [
            'label' => __('Alternate Row Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f8f9fa',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table tbody tr:nth-child(even) td' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('cell_color', [
            'label' => __('Cell Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#374151',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table tbody td' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_typography_control('cell_typography', __('Cell Typography', 'ux-portfolio-optimized'), '.ux-fb-table tbody td');

        $this->add_control('feature_bold', [
            'label' => __('Bold Feature Column', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table tbody td.ux-fb-feature' => 'font-weight: 700;',
            ],
        ]);

        $this->add_control('border_color', [
            'label' => __('Border Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e0e0e0',
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table th' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .ux-fb-table td' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('cell_padding', [
            'label' => __('Cell Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 4, 'max' => 40]],
            'default' => ['size' => 14],
            'selectors' => [
                '{{WRAPPER}} .ux-fb-table th' => 'padding: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ux-fb-table td' => 'padding: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('icon_size', [
            'label' => __('Alignment Icon Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 16, 'max' => 64]],
            'default' => ['size' => 32],
            'selectors' => [
                '{{WRAPPER}} .ux-fb-persona' => '--fb-icon-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /** Parse a comma/space separated key list into an array of trimmed keys. */
    private function parse_keys($raw) {
        $out = array();
        foreach (preg_split('/[,\s]+/', (string) $raw) as $k) {
            $k = trim($k);
            if ($k !== '') $out[] = $k;
        }
        return $out;
    }

    /** Renders the aligned persona markers for one row. */
    private function render_persona_markers($keys, $lookup, $display) {
        if (empty($keys)) return;
        ?>
        <div class="ux-fb-persona-list">
            <?php foreach ($keys as $key) :
                if (!isset($lookup[$key])) continue;
                $p = $lookup[$key];
                $color = !empty($p['persona_color']) ? $p['persona_color'] : '#ef4a8a';
                $name = trim($p['persona_name'] ?? $key);

                $img = $p['persona_image']['url'] ?? '';
                if (empty($img) && !empty($p['persona_image']['id'])) {
                    $img = wp_get_attachment_url($p['persona_image']['id']);
                }
                $icon = $p['persona_icon'] ?? null;
                $has_icon = is_array($icon) && !empty($icon['value']);

                if ($display === 'label') : ?>
                    <span class="ux-fb-persona-pill" style="--fb-color: <?php echo esc_attr($color); ?>;"><?php echo esc_html($name); ?></span>
                <?php else : ?>
                    <span class="ux-fb-persona" title="<?php echo esc_attr($name); ?>" style="--fb-color: <?php echo esc_attr($color); ?>;">
                        <span class="ux-fb-persona-media">
                            <?php if (!empty($img)) : ?>
                                <img class="ux-fb-persona-img" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
                            <?php elseif ($has_icon) : ?>
                                <?php \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                            <?php else : ?>
                                <span class="ux-fb-persona-dot" aria-hidden="true"></span>
                            <?php endif; ?>
                        </span>
                        <?php if ($display === 'icon_label') : ?>
                            <span class="ux-fb-persona-name"><?php echo esc_html($name); ?></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /** Renders one matrix table for a subset of rows. */
    private function render_table($rows, $lookup, $show_align, $display, $feature_h, $benefit_h, $align_h) {
        ?>
        <div class="ux-fb-scroll">
            <table class="ux-fb-table">
                <thead>
                    <tr>
                        <th class="ux-fb-feature"><?php echo esc_html($feature_h); ?></th>
                        <th class="ux-fb-benefit"><?php echo esc_html($benefit_h); ?></th>
                        <?php if ($show_align) : ?>
                            <th class="ux-fb-align"><?php echo esc_html($align_h); ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) :
                        $keys = $this->parse_keys($row['row_personas'] ?? '');
                        ?>
                        <tr>
                            <td class="ux-fb-feature"><?php echo esc_html($row['feature'] ?? ''); ?></td>
                            <td class="ux-fb-benefit"><?php echo esc_html($row['benefit'] ?? ''); ?></td>
                            <?php if ($show_align) : ?>
                                <td class="ux-fb-align">
                                    <?php $this->render_persona_markers($keys, $lookup, $display); ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if (empty($s['rows'])) return;

        // Build persona lookup by key.
        $lookup = array();
        if (!empty($s['personas'])) {
            foreach ($s['personas'] as $p) {
                $key = trim($p['persona_key'] ?? '');
                if ($key !== '') $lookup[$key] = $p;
            }
        }

        $show_align = ($s['show_align_column'] ?? 'yes') === 'yes';
        $show_legend = ($s['show_legend'] ?? 'yes') === 'yes' && !empty($lookup);
        $display = $s['align_display'] ?? 'icon_label';

        $feature_h = $s['feature_heading_text'] ?? __('Feature', 'ux-portfolio-optimized');
        $benefit_h = $s['benefit_heading_text'] ?? __('Benefit to Users', 'ux-portfolio-optimized');
        $align_h = $s['align_heading_text'] ?? __('Persona Alignment', 'ux-portfolio-optimized');

        // Bucket rows by version, preserving first-seen order.
        $use_versions = ($s['enable_versions'] ?? '') === 'yes';
        $versions = array();
        foreach ($s['rows'] as $row) {
            $v = $use_versions ? trim($row['version'] ?? '') : '';
            if ($v === '') {
                $v = $use_versions ? __('Untitled', 'ux-portfolio-optimized') : '__all__';
            }
            if (!isset($versions[$v])) $versions[$v] = array();
            $versions[$v][] = $row;
        }

        // Optional per-version display titles (shown above the table).
        $title_lookup = array();
        if ($use_versions && !empty($s['version_titles'])) {
            foreach ($s['version_titles'] as $vt) {
                $vn = trim($vt['vt_version'] ?? '');
                $tt = trim($vt['vt_title'] ?? '');
                if ($vn !== '' && $tt !== '') $title_lookup[$vn] = $tt;
            }
        }

        $names = array_keys($versions);
        $tabbed = $use_versions && (($s['show_tabs'] ?? 'yes') === 'yes') && count($names) > 1;

        // Which tab opens first.
        $active = 0;
        $default_version = trim($s['default_version'] ?? '');
        if ($tabbed && $default_version !== '') {
            foreach ($names as $i => $n) {
                if (strcasecmp($n, $default_version) === 0) {
                    $active = $i;
                    break;
                }
            }
        }
        ?>

        <div class="ux-fb-wrapper" data-widget-id="<?php echo esc_attr($this->get_id()); ?>">
            <?php $this->render_standard_header(); ?>

            <?php if ($show_legend) : ?>
                <div class="ux-fb-legend">
                    <?php foreach ($lookup as $key => $p) :
                        $color = !empty($p['persona_color']) ? $p['persona_color'] : '#ef4a8a';
                        $name = trim($p['persona_name'] ?? $key);
                        $img = $p['persona_image']['url'] ?? '';
                        if (empty($img) && !empty($p['persona_image']['id'])) {
                            $img = wp_get_attachment_url($p['persona_image']['id']);
                        }
                        $icon = $p['persona_icon'] ?? null;
                        $has_icon = is_array($icon) && !empty($icon['value']);
                        ?>
                        <span class="ux-fb-legend-item" style="--fb-color: <?php echo esc_attr($color); ?>;">
                            <span class="ux-fb-persona-media">
                                <?php if (!empty($img)) : ?>
                                    <img class="ux-fb-persona-img" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
                                <?php elseif ($has_icon) : ?>
                                    <?php \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                                <?php else : ?>
                                    <span class="ux-fb-persona-dot" aria-hidden="true"></span>
                                <?php endif; ?>
                            </span>
                            <span class="ux-fb-legend-name"><?php echo esc_html($name); ?></span>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($tabbed) : ?>
                <div class="ux-fb-tabs" role="tablist">
                    <?php foreach ($names as $i => $n) :
                        $is_on = ($i === $active);
                        ?>
                        <button type="button"
                                class="ux-fb-tab<?php echo $is_on ? ' is-active' : ''; ?>"
                                role="tab"
                                aria-selected="<?php echo $is_on ? 'true' : 'false'; ?>"
                                data-fb-tab="<?php echo esc_attr($i); ?>"><?php echo esc_html($n); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php foreach ($names as $i => $n) :
                $hidden = $tabbed && ($i !== $active);
                ?>
                <div class="ux-fb-panel<?php echo $hidden ? ' is-hidden' : ''; ?>" data-fb-panel="<?php echo esc_attr($i); ?>">
                    <?php
                    // A per-version title shows above the table. In tabbed mode it captions
                    // the active table; in stacked mode it heads each section. Falls back to
                    // the version name only when stacked (tabs already show the name).
                    $panel_title = '';
                    if (isset($title_lookup[$n])) {
                        $panel_title = $title_lookup[$n];
                    } elseif (!$tabbed && $use_versions && $n !== '__all__') {
                        $panel_title = $n;
                    }
                    ?>
                    <?php if ($panel_title !== '') : ?>
                        <h3 class="ux-fb-panel__name"><?php echo esc_html($panel_title); ?></h3>
                    <?php endif; ?>
                    <?php $this->render_table($versions[$n], $lookup, $show_align, $display, $feature_h, $benefit_h, $align_h); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
    }
}
