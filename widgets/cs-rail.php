<?php
/**
 * Case Study Rail — the left icon rail (back to all work + case study views).
 * On phones it becomes a row of tabs across the top.
 */

if (!defined('ABSPATH')) exit;

class UX_Cs_Rail_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-cs-rail'; }
    public function get_title() { return __('Case Study Rail', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-navigation-vertical'; }
    public function get_keywords() { return ['case study', 'rail', 'navigation', 'tabs']; }
    public function get_style_depends() { return ['ux-cs-layout']; }

    private static function icons() {
        $a = 'width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
        return [
            'back'      => '<svg ' . $a . '><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>',
            'case'      => '<svg ' . $a . '><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>',
            'portfolio' => '<svg ' . $a . '><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>',
            'docs'      => '<svg ' . $a . '><path d="M20 7h-3a2 2 0 0 1-2-2V2"/><path d="M9 18a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h7l4 4v10a2 2 0 0 1-2 2Z"/><path d="M3 7.6v12.8A1.6 1.6 0 0 0 4.6 22h9.8"/></svg>',
        ];
    }

    protected function register_controls() {
        $t = 'ux-portfolio-optimized';
        $this->start_controls_section('back', ['label' => __('Back link', $t)]);
        $this->add_control('back_url', ['label' => __('Link', $t), 'type' => \Elementor\Controls_Manager::URL, 'dynamic' => ['active' => true],
            'description' => __('Blank = the case study archive.', $t)]);
        $this->add_control('back_label', ['label' => __('Label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('All work', $t)]);
        $this->add_control('back_desc', ['label' => __('Tooltip', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => __('Back to every Made by Mystie project', $t)]);
        $this->end_controls_section();

        $this->start_controls_section('views', ['label' => __('Views', $t)]);
        foreach (['case' => ['Case study', 'The story, section by section', 'yes'], 'portfolio' => ['Portfolio', 'Finished pieces from the project', ''], 'docs' => ['Documents', 'Briefs, decks and research files', '']] as $k => $d) {
            $this->add_control($k . '_show', ['label' => sprintf(__('Show %s', $t), $d[0]), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => $d[2], 'separator' => 'before']);
            $this->add_control($k . '_label', ['label' => __('Label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => $d[0], 'condition' => [$k . '_show' => 'yes']]);
            $this->add_control($k . '_desc', ['label' => __('Tooltip', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => $d[1], 'condition' => [$k . '_show' => 'yes']]);
            if ($k !== 'case') {
                $this->add_control($k . '_url', ['label' => __('Link', $t), 'type' => \Elementor\Controls_Manager::URL, 'dynamic' => ['active' => true], 'condition' => [$k . '_show' => 'yes']]);
            }
        }
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $ic = self::icons();
        $back = !empty($s['back_url']['url']) ? $s['back_url']['url'] : get_post_type_archive_link('case_study');
        if (!$back) $back = home_url('/');
        $uid = $this->get_id();
        $views = [];
        // Per-case-study overrides: post meta mbm_view_{key}_label / mbm_view_{key}_desc replace the template's labels.
        $pid = (int) get_queried_object_id();
        foreach (['case', 'portfolio', 'docs'] as $k) {
            if (($s[$k . '_show'] ?? '') !== 'yes') continue;
            // Per-case-study hide: post meta mbm_view_{key}_hide = 1 drops that view from the rail.
            if ($k !== 'case' && $pid && get_post_meta($pid, 'mbm_view_' . $k . '_hide', true)) continue;
            foreach (['label', 'desc'] as $f) {
                $m = $pid ? (string) get_post_meta($pid, 'mbm_view_' . $k . '_' . $f, true) : '';
                if ($m !== '') $s[$k . '_' . $f] = sanitize_text_field($m);
            }
            $url = $k === 'case' ? '#top' : (string) ($s[$k . '_url']['url'] ?? '');
            // A link to "#some-id" on this page opens that container as an in-page view (a tab).
            $pane = ($k !== 'case' && strpos($url, '#') === 0) ? sanitize_html_class(substr($url, 1)) : '';
            $views[$k] = [
                'label' => $s[$k . '_label'],
                'desc'  => $s[$k . '_desc'],
                'url'   => $url,
                'cur'   => $k === 'case',
                'tab'   => $k === 'case' || $pane !== '',
                'pane'  => $pane,
            ];
        }
        $tab_attrs = function ($k, $v) {
            if (!$v['tab']) return '';
            $a = ' role="tab" data-mcs-view="' . esc_attr($k) . '" aria-selected="' . ($v['cur'] ? 'true' : 'false') . '"';
            if ($v['pane'] !== '') $a .= ' aria-controls="' . esc_attr($v['pane']) . '"';
            return $a;
        };
        ?>
        <nav class="mcs-rail" aria-label="<?php esc_attr_e('Case study sections', 'ux-portfolio-optimized'); ?>">
            <div class="mcs-rail__item">
                <a class="mcs-rail__back" href="<?php echo esc_url($back); ?>" aria-label="<?php echo esc_attr($s['back_label']); ?>"><?php echo $ic['back']; // static SVG ?></a>
                <span class="mcs-tip" aria-hidden="true"><b><?php echo esc_html($s['back_label']); ?></b><span><?php echo esc_html($s['back_desc']); ?></span></span>
            </div>
            <div class="mcs-rail__list" role="tablist" aria-orientation="vertical">
                <?php foreach ($views as $k => $v) : $did = 'mcs-tip-' . $k . '-' . $uid; ?>
                <div role="presentation"><div class="mcs-rail__item">
                    <a class="mcs-rail__tab" href="<?php echo esc_url($v['url'] ?: '#'); ?>" aria-label="<?php echo esc_attr($v['label']); ?>" aria-describedby="<?php echo esc_attr($did); ?>"<?php echo $v['cur'] ? ' aria-current="page"' : ''; ?><?php echo $tab_attrs($k, $v); // escaped inside ?>>
                        <?php echo $ic[$k]; // static SVG ?>
                        <span class="mcs-sr" id="<?php echo esc_attr($did); ?>"><?php echo esc_html($v['desc']); ?></span>
                    </a>
                    <span class="mcs-tip" aria-hidden="true"><b><?php echo esc_html($v['label']); ?></b><span><?php echo esc_html($v['desc']); ?></span></span>
                </div></div>
                <?php endforeach; ?>
            </div>
        </nav>
        <nav class="mcs-tabs" role="tablist" aria-label="<?php esc_attr_e('Case study tabs', 'ux-portfolio-optimized'); ?>">
            <?php foreach ($views as $k => $v) : ?>
                <a class="mcs-tabs__t" href="<?php echo esc_url($v['url'] ?: '#'); ?>"<?php echo $v['cur'] ? ' aria-current="page"' : ''; ?><?php echo $tab_attrs($k, $v); // escaped inside ?>><?php echo esc_html($v['label']); ?></a>
            <?php endforeach; ?>
        </nav>
        <?php
    }
}
