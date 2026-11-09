<?php
/**
 * Case Study Footer — signature plus previous / next case study cards.
 */

if (!defined('ABSPATH')) exit;

class UX_Cs_Footer_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-cs-footer'; }
    public function get_title() { return __('Case Study Footer', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-footer'; }
    public function get_keywords() { return ['case study', 'footer', 'next', 'previous', 'navigation', 'signature']; }
    public function get_style_depends() { return ['ux-cs-layout']; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Footer', 'ux-portfolio-optimized')]);
        $this->add_control('signature', ['label' => __('Signature', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'made by Mystie']);
        $this->add_control('show_prev', ['label' => __('Show previous card', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('prev_label', ['label' => __('Previous label', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('← Previous case study', 'ux-portfolio-optimized'), 'condition' => ['show_prev' => 'yes']]);
        $this->add_control('next_label', ['label' => __('Next label', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('Next case study →', 'ux-portfolio-optimized')]);
        $this->add_control('show_kind', ['label' => __('Show project type', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('show_date', ['label' => __('Show date', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes']);
        $this->add_control('next_url', ['label' => __('Next link override', 'ux-portfolio-optimized'), 'type' => \Elementor\Controls_Manager::URL, 'dynamic' => ['active' => true],
            'description' => __('Blank = the next case study by date, wrapping to the first. When set, the next card becomes a plain link.', 'ux-portfolio-optimized')]);
        $this->end_controls_section();
    }

    /**
     * Adjacent case study by date, wrapping around the ends.
     *
     * @param bool $previous True for the older post.
     * @return WP_Post|null
     */
    private function adjacent($previous) {
        $post = get_post();
        if (!$post) return null;
        $adj = get_adjacent_post(false, '', $previous);
        if ($adj instanceof WP_Post) return $adj;
        $ids = get_posts([
            'post_type'    => $post->post_type,
            'post_status'  => 'publish',
            'numberposts'  => 1,
            'orderby'      => 'date',
            'order'        => $previous ? 'DESC' : 'ASC',
            'post__not_in' => [$post->ID],
            'fields'       => 'ids',
        ]);
        return $ids ? get_post($ids[0]) : null;
    }

    /**
     * Project type line: the Kind field, or the first work type.
     */
    private function kind($post) {
        $kind = (string) get_post_meta($post->ID, 'mbm_kind', true);
        if ($kind !== '') return $kind;
        $terms = get_the_terms($post, 'work_type');
        return ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
    }

    private function card($post, $label, $rel, array $s) {
        if (!$post instanceof WP_Post) {
            echo '<span class="mcs-pn__spacer" aria-hidden="true"></span>';
            return;
        }
        $kind = 'yes' === $s['show_kind'] ? $this->kind($post) : '';
        ?>
        <a class="mcs-pn__card mcs-pn__card--<?php echo esc_attr($rel); ?>" href="<?php echo esc_url(get_permalink($post)); ?>" rel="<?php echo esc_attr($rel); ?>">
            <span class="mcs-pn__body">
                <span class="mcs-pn__title"><?php echo esc_html(wp_strip_all_tags(get_post_field('post_title', $post))) /* raw title: no "Protected:" prefix */; ?></span>
                <?php if ($kind !== '') : ?><span class="mcs-pn__kind"><?php echo esc_html($kind); ?></span><?php endif; ?>
            </span>
            <span class="mcs-pn__foot">
                <span class="mcs-pn__label"><?php echo esc_html($label); ?></span>
                <?php if ('yes' === $s['show_date']) : ?><span class="mcs-pn__date"><?php echo esc_html(get_the_date('M Y', $post)); ?></span><?php endif; ?>
            </span>
        </a>
        <?php
    }

    protected function render() {
        $s          = $this->get_settings_for_display();
        $prev_label = trim((string) ($s['prev_label'] ?? '')) !== '' ? $s['prev_label'] : __('← Previous case study', 'ux-portfolio-optimized');
        $next_label = trim((string) ($s['next_label'] ?? '')) !== '' ? $s['next_label'] : __('Next case study →', 'ux-portfolio-optimized');
        $override   = !empty($s['next_url']['url']) ? $s['next_url']['url'] : '';
        $prev       = 'yes' === ($s['show_prev'] ?? 'yes') ? $this->adjacent(true) : null;
        $next       = $override ? null : $this->adjacent(false);
        if ($prev && $next && $prev->ID === $next->ID) $prev = null; // only two case studies
        ?>
        <div class="mcs-foot">
            <?php if (!empty($s['signature'])) : ?><span class="mcs-foot__sig"><?php echo esc_html($s['signature']); ?></span><?php endif; ?>
            <?php if ($override) : ?><a class="mcs-foot__next" href="<?php echo esc_url($override); ?>"><?php echo esc_html($next_label); ?></a><?php endif; ?>
        </div>
        <?php if ($prev || $next) : ?>
        <nav class="mcs-pn" aria-label="<?php esc_attr_e('More case studies', 'ux-portfolio-optimized'); ?>">
            <?php $this->card($prev, $prev_label, 'prev', $s); ?>
            <?php $this->card($next, $next_label, 'next', $s); ?>
        </nav>
        <?php endif;
    }
}