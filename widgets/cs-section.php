<?php
/**
 * Case Study Section — one numbered section of a case study story:
 * number, label, lenses, heading, summary, visual, and a "keep reading" body.
 */

if (!defined('ABSPATH')) exit;

class UX_Cs_Section_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-cs-section'; }
    public function get_title() { return __('Case Study Section', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-post-content'; }
    public function get_keywords() { return ['case study', 'section', 'story', 'keep reading', 'lens']; }
    public function get_style_depends() { return ['ux-cs-layout']; }
    public function get_script_depends() { return ['ux-cs-layout']; }

    public static function lens_options() {
        $l = (class_exists('UX_Lens') && method_exists('UX_Lens', 'lenses')) ? UX_Lens::lenses() : [];
        if (empty($l)) $l = ['ux' => 'UX', 'ui' => 'UI', 'brand' => 'Brand', 'systems' => 'Systems'];
        return $l;
    }

    /** Short label for buttons and section meta: ux → UX, brand → Brand. Filterable. */
    public static function short_name($key, $name = '') {
        $short = strlen($key) <= 3 ? strtoupper($key) : ucfirst($key);
        return apply_filters('ux_bundle_lens_short', $short, $key, $name);
    }

    protected function register_controls() {
        $t = 'ux-portfolio-optimized';

        $this->start_controls_section('meta', ['label' => __('Section', $t)]);
        $this->add_control('number', ['label' => __('Number', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '01']);
        $this->add_control('anchor', [
            'label' => __('Anchor ID', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '',
            'description' => __('Used in links like #cs-s01. Leave blank to use the number.', $t),
        ]);
        $this->add_control('label', ['label' => __('Label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('Client work', $t),
            'description' => __('For example "Client work" or "Independent continuation".', $t)]);
        $this->add_control('lenses', [
            'label' => __('Lenses', $t), 'type' => \Elementor\Controls_Manager::SELECT2, 'multiple' => true,
            'options' => ['all' => __('All roles', $t)] + self::lens_options(), 'default' => ['all'],
            'description' => __('Which "Hiring for" filters show this section.', $t),
        ]);
        $this->add_control('lens_text', ['label' => __('Lens text (optional)', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '',
            'description' => __('Overrides the small text at the top right. Blank = built from the lenses.', $t)]);
        $this->add_control('short', ['label' => __('Contents label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '',
            'description' => __('Short name in "On this page". Blank = the heading.', $t)]);
        $this->end_controls_section();

        $this->start_controls_section('content', ['label' => __('Content', $t)]);
        $this->add_control('title', ['label' => __('Heading', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => __('Section heading.', $t), 'dynamic' => ['active' => true]]);
        $this->add_control('summary', ['label' => __('Summary', $t), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '', 'dynamic' => ['active' => true]]);
        $this->add_control('visual', [
            'label' => __('Visual (HTML)', $t), 'type' => \Elementor\Controls_Manager::CODE, 'language' => 'html', 'rows' => 14, 'default' => '',
            'description' => __('Figure markup using the mcs- figure classes. Images, lists and captions are allowed; scripts are removed.', $t),
        ]);
        $this->end_controls_section();

        $this->start_controls_section('more', ['label' => __('Keep reading', $t)]);
        $this->add_control('body', ['label' => __('Longer text', $t), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '', 'dynamic' => ['active' => true]]);
        $this->add_control('more_label', ['label' => __('Open label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'keep reading ↓']);
        $this->add_control('less_label', ['label' => __('Close label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'less ↑']);
        $this->end_controls_section();
    }

    /** Allowed markup for the visual field: post HTML plus figure/svg basics. */
    public static function visual_kses() {
        $a = wp_kses_allowed_html('post');
        $common = ['class' => true, 'style' => true, 'aria-hidden' => true, 'aria-label' => true, 'role' => true, 'id' => true];
        foreach (['figure', 'figcaption', 'ol', 'ul', 'li', 'span', 'div', 'strong', 'em', 'p', 'img', 'blockquote', 'dl', 'dt', 'dd', 'small'] as $tag) {
            $a[$tag] = array_merge(isset($a[$tag]) ? $a[$tag] : [], $common);
        }
        $a['img'] = array_merge($a['img'], ['src' => true, 'alt' => true, 'width' => true, 'height' => true, 'loading' => true, 'decoding' => true, 'srcset' => true, 'sizes' => true]);
        return $a;
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $num = isset($s['number']) ? trim((string) $s['number']) : '';
        $anchor = !empty($s['anchor']) ? sanitize_title($s['anchor']) : ('s' . sanitize_title($num !== '' ? $num : $this->get_id()));
        $sec_id = 'cs-' . $anchor;
        $h_id = 'h-' . $anchor;
        $b_id = 'body-' . $anchor;

        $opts = self::lens_options();
        $lenses = array_values(array_filter(array_map('sanitize_key', (array) ($s['lenses'] ?? []))));
        if (empty($lenses)) $lenses = ['all'];
        if (!empty($s['lens_text'])) {
            $lens_text = $s['lens_text'];
        } elseif (in_array('all', $lenses, true)) {
            $lens_text = __('All roles', 'ux-portfolio-optimized');
        } else {
            $names = [];
            foreach ($lenses as $k) { if (isset($opts[$k])) $names[] = self::short_name($k, $opts[$k]); }
            $lens_text = implode(' · ', $names);
        }

        $title = isset($s['title']) ? $s['title'] : '';
        $short = !empty($s['short']) ? $s['short'] : wp_strip_all_tags($title);
        $body = isset($s['body']) ? trim((string) $s['body']) : '';
        $visual = isset($s['visual']) ? trim((string) $s['visual']) : '';
        ?>
        <section class="mcs-sec" id="<?php echo esc_attr($sec_id); ?>" aria-labelledby="<?php echo esc_attr($h_id); ?>"
                 data-lens="<?php echo esc_attr(implode(' ', $lenses)); ?>"
                 data-n="<?php echo esc_attr($num); ?>" data-short="<?php echo esc_attr($short); ?>">
            <div class="mcs-sec__meta">
                <?php if ($num !== '') : ?><span class="mcs-sec__n"><?php echo esc_html($num); ?></span><?php endif; ?>
                <?php if (!empty($s['label'])) : ?><span class="mcs-label"><?php echo esc_html($s['label']); ?></span><?php endif; ?>
                <?php if ($lens_text !== '') : ?><span class="mcs-label mcs-label--sm mcs-label--muted mcs-sec__lens"><?php echo esc_html($lens_text); ?></span><?php endif; ?>
            </div>
            <h2 class="mcs-sec__title" id="<?php echo esc_attr($h_id); ?>"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($s['summary'])) : ?><div class="mcs-sec__sum"><?php echo wp_kses_post($s['summary']); ?></div><?php endif; ?>
            <?php if ($visual !== '') : ?><div class="mcs-visual"><?php echo wp_kses($visual, self::visual_kses()); ?></div><?php endif; ?>
            <?php if ($body !== '') : ?>
                <div class="mcs-sec__body" id="<?php echo esc_attr($b_id); ?>" hidden><?php echo wp_kses_post($body); ?></div>
                <button type="button" class="mcs-more" aria-expanded="false" aria-controls="<?php echo esc_attr($b_id); ?>"
                        data-more="<?php echo esc_attr($s['more_label']); ?>" data-less="<?php echo esc_attr($s['less_label']); ?>"><?php echo esc_html($s['more_label']); ?></button>
            <?php endif; ?>
        </section>
        <?php
    }
}
