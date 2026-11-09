<?php
/**
 * Case Study Keep Reading — a break inside a case study section. On the live
 * page, every widget below it stays hidden until the visitor clicks
 * "keep reading". In the editor everything stays visible.
 */

if (!defined('ABSPATH')) exit;

class UX_Cs_More_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-cs-more'; }
    public function get_title() { return __('Case Study Keep Reading', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-toggle'; }
    public function get_keywords() { return ['case study', 'keep reading', 'more', 'toggle', 'collapse']; }
    public function get_style_depends() { return ['ux-cs-layout']; }
    public function get_script_depends() { return ['ux-cs-layout']; }

    protected function register_controls() {
        $t = 'ux-portfolio-optimized';
        $this->start_controls_section('content', ['label' => __('Keep reading', $t)]);
        $this->add_control('help', ['type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => esc_html__('Widgets placed below this one, in the same section, are hidden until the visitor opens them.', $t),
            'content_classes' => 'elementor-descriptor']);
        $this->add_control('more_label', ['label' => __('Open label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'keep reading ↓']);
        $this->add_control('less_label', ['label' => __('Close label', $t), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'less ↑']);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $editing = class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ($editing) {
            echo '<div class="mcs-more-cut"><span>' . esc_html__('Keep reading — widgets below are hidden until opened', 'ux-portfolio-optimized') . '</span></div>';
            return;
        }
        ?>
        <button type="button" class="mcs-more" aria-expanded="false"
                data-more="<?php echo esc_attr($s['more_label']); ?>" data-less="<?php echo esc_attr($s['less_label']); ?>"><?php echo esc_html($s['more_label']); ?></button>
        <?php
    }
}
