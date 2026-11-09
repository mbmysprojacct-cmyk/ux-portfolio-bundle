<?php
/**
 * Case Study Hero — eyebrow, title, subtitle, intro, facts row and lead image,
 * plus the lens filter status bar that the panel updates.
 *
 * Source "Post fields" reads the case study's Project Details (for use in the
 * single case study template); "This widget" uses the values typed here.
 */

if (!defined('ABSPATH')) exit;

class UX_Cs_Hero_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-cs-hero'; }
    public function get_title() { return __('Case Study Hero', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-header'; }
    public function get_keywords() { return ['case study', 'hero', 'title', 'intro']; }
    public function get_style_depends() { return ['ux-cs-layout']; }
    public function get_script_depends() { return ['ux-cs-layout']; }

    /** Post meta keys a fact can read from. */
    private static function fact_fields() {
        return [
            'custom'        => __('Custom text', 'ux-portfolio-optimized'),
            'mbm_client'    => __('Client', 'ux-portfolio-optimized'),
            'mbm_scope'     => __('Role / Scope', 'ux-portfolio-optimized'),
            'mbm_structure' => __('Structure', 'ux-portfolio-optimized'),
            'mbm_year'      => __('Year', 'ux-portfolio-optimized'),
            'mbm_kind'      => __('Kind', 'ux-portfolio-optimized'),
            'mbm_timeline'  => __('Timeline', 'ux-portfolio-optimized'),
        ];
    }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Hero', 'ux-portfolio-optimized')]);
        $this->add_control('source', ['label' => __('Content from', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'widget',
            'options' => ['post' => __('Post fields (for templates)', 'ux-portfolio-optimized'), 'widget' => __('This widget', 'ux-portfolio-optimized')],
            'description' => __('Post fields = Project Details and Hero Intro on the case study, plus its featured image.', 'ux-portfolio-optimized')]);
        $this->add_control('respect_hide', ['label' => __('Skip on posts marked "Hide the template hero"', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => ['source' => 'post']]);
        $this->add_control('eyebrow', ['label' => __('Eyebrow', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => __('Case study', 'ux-portfolio-optimized'), 'dynamic' => ['active' => true], 'condition' => ['source' => 'widget']]);
        $this->add_control('title', ['label' => __('Title', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => '', 'dynamic' => ['active' => true],
            'description' => __('Blank = the post title.', 'ux-portfolio-optimized'), 'condition' => ['source' => 'widget']]);
        $this->add_control('subtitle', ['label' => __('Subtitle', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '', 'dynamic' => ['active' => true], 'condition' => ['source' => 'widget']]);
        $this->add_control('intro', ['label' => __('Intro', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '', 'dynamic' => ['active' => true], 'condition' => ['source' => 'widget']]);
        $rep = new \Elementor\Repeater();
        $rep->add_control('label', ['label' => __('Label', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('Client', 'ux-portfolio-optimized')]);
        $rep->add_control('field', ['label' => __('Value from', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'custom', 'options' => self::fact_fields(),
            'description' => __('A post field is used when Content from = Post fields.', 'ux-portfolio-optimized')]);
        $rep->add_control('value', ['label' => __('Value', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => '', 'dynamic' => ['active' => true]]);
        $this->add_control('facts', ['label' => __('Facts', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $rep->get_controls(), 'title_field' => '{{{ label }}}', 'default' => []]);
        $this->end_controls_section();

        $this->start_controls_section('media', ['label' => __('Lead image', 'ux-portfolio-optimized')]);
        $this->add_control('show_media', ['label' => __('Show image', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes']);
        $this->add_control('image', ['label' => __('Image', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::MEDIA, 'dynamic' => ['active' => true], 'condition' => ['show_media' => 'yes', 'source' => 'widget']]);
        $this->add_control('placeholder', ['label' => __('Placeholder text', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true, 'default' => __('Lead image', 'ux-portfolio-optimized'), 'condition' => ['show_media' => 'yes'],
            'description' => __('Shown until there is an image (the featured image, with Post fields).', 'ux-portfolio-optimized')]);
        $this->end_controls_section();

        $this->start_controls_section('status', ['label' => __('Filter message', 'ux-portfolio-optimized')]);
        $this->add_control('show_all', ['label' => __('Reset button', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('Show everything', 'ux-portfolio-optimized')]);
        $this->end_controls_section();
    }

    /** Collects what to print, from the post or from the widget. */
    private function data(array $s) {
        $post_id = get_the_ID();
        if (($s['source'] ?? 'widget') === 'post' && $post_id) {
            $m = function ($k) use ($post_id) { return trim((string) get_post_meta($post_id, $k, true)); };
            $facts = [];
            foreach ((array) ($s['facts'] ?? []) as $f) {
                $field = $f['field'] ?? 'custom';
                $value = ($field === 'custom') ? ($f['value'] ?? '') : $m(sanitize_key($field));
                $facts[] = ['label' => $f['label'] ?? '', 'value' => $value];
            }
            $title = $m('mbm_hero_title');
            return [
                'eyebrow'  => $m('mbm_eyebrow'),
                'title'    => $title !== '' ? $title : get_the_title($post_id),
                'subtitle' => $m('mbm_subtitle'),
                'intro'    => (string) get_post_meta($post_id, 'mbm_intro', true),
                'facts'    => $facts,
                'image_id' => (int) get_post_thumbnail_id($post_id),
                'image_url'=> '',
            ];
        }
        $facts = [];
        foreach ((array) ($s['facts'] ?? []) as $f) $facts[] = ['label' => $f['label'] ?? '', 'value' => $f['value'] ?? ''];
        return [
            'eyebrow'  => $s['eyebrow'] ?? '',
            'title'    => !empty($s['title']) ? $s['title'] : get_the_title(),
            'subtitle' => $s['subtitle'] ?? '',
            'intro'    => $s['intro'] ?? '',
            'facts'    => $facts,
            'image_id' => (int) ($s['image']['id'] ?? 0),
            'image_url'=> (string) ($s['image']['url'] ?? ''),
        ];
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $is_post = ($s['source'] ?? 'widget') === 'post';
        $editing = class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ($is_post && ($s['respect_hide'] ?? '') === 'yes' && !$editing && get_post_meta(get_the_ID(), 'mbm_hide_hero', true)) {
            return;
        }
        $d = $this->data($s);
        $hid = 'cs-title-' . $this->get_id();
        ?>
        <div class="mcs-hero">
            <section class="mcs-hero__intro" aria-labelledby="<?php echo esc_attr($hid); ?>">
                <?php if ($d['eyebrow'] !== '') : ?><span class="mcs-label"><?php echo esc_html($d['eyebrow']); ?></span><?php endif; ?>
                <h1 class="mcs-hero__title" id="<?php echo esc_attr($hid); ?>"><?php echo esc_html($d['title']); ?></h1>
                <?php if ($d['subtitle'] !== '') : ?><p class="mcs-hero__sub"><?php echo esc_html($d['subtitle']); ?></p><?php endif; ?>
                <?php if (trim($d['intro']) !== '') : ?><div class="mcs-hero__lede"><?php echo wp_kses_post(wpautop($d['intro'])); ?></div><?php endif; ?>
                <?php $facts = array_filter($d['facts'], function ($f) { return $f['value'] !== ''; }); if ($facts) : ?>
                <dl class="mcs-facts">
                    <?php foreach ($facts as $f) : ?>
                        <div><dt><?php echo esc_html($f['label']); ?></dt><dd><?php echo esc_html($f['value']); ?></dd></div>
                    <?php endforeach; ?>
                </dl>
                <?php endif; ?>
            </section>
            <?php if (($s['show_media'] ?? '') === 'yes') : ?>
            <figure class="mcs-hero__media"><div class="mcs-ratio-16x9">
                <?php if ($d['image_id']) :
                    echo wp_get_attachment_image($d['image_id'], 'full', false, ['loading' => 'eager', 'decoding' => 'async', 'sizes' => '(min-width: 1100px) 880px, 100vw']);
                elseif ($d['image_url'] !== '') : ?>
                    <img src="<?php echo esc_url($d['image_url']); ?>" alt="">
                <?php else : ?>
                    <div class="mcs-slot"><span aria-hidden="true">▢</span><span><?php echo esc_html($s['placeholder']); ?></span></div>
                <?php endif; ?>
            </div></figure>
            <?php endif; ?>
            <div class="mcs-status" role="status" data-mcs-status hidden>
                <span class="mcs-status__text"></span>
                <button type="button" class="mcs-btn-line" data-mcs-showall><?php echo esc_html($s['show_all']); ?></button>
            </div>
        </div>
        <?php
    }
}
