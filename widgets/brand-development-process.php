<?php
/**
 * Brand Development Process Widget - Optimized
 * Step-by-step accordion with numbers and content
 */

if (!defined('ABSPATH')) exit;

class UX_Brand_Development_Process_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-brand-development-process'; }
    public function get_title() { return __('Brand Development Process', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-accordion'; }
    
    protected function register_controls() {
        
        $this->add_standard_header_section();
        
        // Steps
        $this->start_controls_section('steps_section', [
            'label' => __('Steps', 'ux-portfolio-optimized')
        ]);
        
        $this->add_control('allow_multiple', [
            'label' => __('Allow Multiple Open', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
        ]);
        
        $this->add_control('icon_style', [
            'label' => __('Toggle Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'chevron',
            'options' => [
                'chevron' => __('Chevron', 'ux-portfolio-optimized'),
                'plus' => __('Plus/Minus', 'ux-portfolio-optimized'),
                'arrow' => __('Arrow', 'ux-portfolio-optimized'),
            ],
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('step_number', [
            'label' => __('Step Number', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '01',
        ]);
        
        $repeater->add_control('step_title', [
            'label' => __('Step Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Brand Discovery',
        ]);
        
        $repeater->add_control('step_description', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Brief overview of this step',
        ]);
        
        $repeater->add_control('step_content', [
            'label' => __('Step Content', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => '<p>Add your step content here.</p>',
        ]);

        // Build a list of available saved templates for the dropdown.
        $template_options = ['' => __('— None (use WYSIWYG above) —', 'ux-portfolio-optimized')];
        $templates = get_posts([
            'post_type'      => 'elementor_library',
            'posts_per_page' => 100,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'no_found_rows'  => true,
        ]);
        foreach ($templates as $tpl) {
            $template_options[$tpl->ID] = $tpl->post_title !== '' ? $tpl->post_title : sprintf('#%d', $tpl->ID);
        }

        $repeater->add_control('content_template', [
            'label' => __('Use Saved Template (drag-drop widgets)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => $template_options,
            'default' => '',
            'description' => __('Optional. Pick an Elementor saved template — full drag-drop widget editing in the Templates editor. When set, it replaces the WYSIWYG above for this step. Create new templates via Templates → Saved Templates.', 'ux-portfolio-optimized'),
        ]);
        
        $repeater->add_control('default_open', [
            'label' => __('Open by Default', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
        ]);
        
        $this->add_control('steps', [
            'label' => __('Steps', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['step_number' => '01', 'step_title' => 'Brand Discovery', 'step_description' => 'Understanding your business'],
                ['step_number' => '02', 'step_title' => 'Strategy Development', 'step_description' => 'Creating the brand strategy'],
            ],
            'title_field' => '{{{ step_number }}} - {{{ step_title }}}',
        ]);
        
        $this->end_controls_section();
        
        // Style
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('number_color', [
            'label' => __('Number Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY],
            'default' => '#3b82f6',
            'selectors' => ['{{WRAPPER}} .bsa-number' => 'color: {{VALUE}}'],
        ]);
        
        $this->add_control('title_color', [
            'label' => __('Title Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#1e293b',
            'selectors' => ['{{WRAPPER}} .bsa-title' => 'color: {{VALUE}}'],
        ]);
        
        $this->add_control('card_bg', [
            'label' => __('Card Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .bsa-card' => 'background-color: {{VALUE}}'],
        ]);
        
        $this->add_description_alignment();
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $s = $this->get_settings_for_display();
        $id = $this->get_id();
        
        if (empty($s['steps'])) {
            echo '<p>Add steps in widget settings.</p>';
            return;
        }
        
        $allow_multiple = ($s['allow_multiple'] ?? '') === 'yes' ? 'true' : 'false';
        $icon_style = in_array($s['icon_style'] ?? 'chevron', ['chevron', 'plus', 'arrow']) ? $s['icon_style'] : 'chevron';
        ?>
        
        <div class="brand-dev-wrapper">
            <?php $this->render_standard_header(); ?>
            
            <div class="brand-stepper-accordion bsa-<?php echo esc_attr($id); ?>" data-allow-multiple="<?php echo esc_attr($allow_multiple); ?>">
                <?php foreach ($s['steps'] as $index => $step) : 
                    $num = esc_html($step['step_number'] ?? sprintf('%02d', $index + 1));
                    $title = esc_html($step['step_title'] ?? 'Step');
                    $desc = esc_html($step['step_description'] ?? '');
                    $open = ($step['default_open'] ?? '') === 'yes';
                    $content = $step['step_content'] ?? '';
                ?>
                    <div class="bsa-card<?php echo $open ? ' active' : ''; ?>">
                        <div class="bsa-header">
                            <div class="bsa-number"><?php echo $num; ?></div>
                            <div class="bsa-text">
                                <h3 class="bsa-title"><?php echo $title; ?></h3>
                                <?php if ($desc) : ?>
                                    <p class="bsa-desc"><?php echo $desc; ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="bsa-icon bsa-icon-<?php echo esc_attr($icon_style); ?>">
                                <?php 
                                if ($icon_style === 'plus') {
                                    echo '<span class="bsa-plus">+</span><span class="bsa-minus">−</span>';
                                } elseif ($icon_style === 'arrow') {
                                    echo '<span class="bsa-arrow">→</span>';
                                } else {
                                    echo '<span class="bsa-chevron">▼</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="bsa-content"<?php echo $open ? ' style="display:block;"' : ''; ?>>
                            <div class="bsa-inner"><?php
                                $template_id = absint($step['content_template'] ?? 0);
                                if ($template_id > 0 && class_exists('\Elementor\Plugin')) {
                                    $frontend = \Elementor\Plugin::$instance->frontend;
                                    if (method_exists($frontend, 'get_builder_content_for_display')) {
                                        echo $frontend->get_builder_content_for_display($template_id);
                                    } else {
                                        echo do_shortcode(wp_kses_post($content));
                                    }
                                } else {
                                    echo do_shortcode(wp_kses_post($content));
                                }
                            ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
        .bsa-<?php echo esc_attr($id); ?>{display:flex;flex-direction:column;gap:15px}
        .bsa-<?php echo esc_attr($id); ?> .bsa-card{background:#fff;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;transition:all .3s}
        .bsa-<?php echo esc_attr($id); ?> .bsa-card.active{border-color:#3b82f6;box-shadow:0 4px 12px rgba(59,130,246,.1)}
        .bsa-<?php echo esc_attr($id); ?> .bsa-header{display:flex;align-items:center;gap:20px;padding:24px;cursor:pointer}
        .bsa-<?php echo esc_attr($id); ?> .bsa-header:hover{background:#f8fafc}
        .bsa-<?php echo esc_attr($id); ?> .bsa-number{font-size:36px;font-weight:700;color:#3b82f6;line-height:1;min-width:55px}
        .bsa-<?php echo esc_attr($id); ?> .bsa-text{flex:1}
        .bsa-<?php echo esc_attr($id); ?> .bsa-title{margin:0 0 8px;font-size:22px;font-weight:600;color:#1e293b}
        .bsa-<?php echo esc_attr($id); ?> .bsa-desc{margin:0;font-size:15px;color:#64748b;line-height:1.5}
        .bsa-<?php echo esc_attr($id); ?> .bsa-icon{font-size:24px;color:#64748b;transition:all .3s;min-width:30px;text-align:center}
        .bsa-<?php echo esc_attr($id); ?> .bsa-card.active .bsa-chevron{transform:rotate(180deg);display:inline-block}
        .bsa-<?php echo esc_attr($id); ?> .bsa-card.active .bsa-arrow{transform:rotate(90deg);display:inline-block}
        .bsa-<?php echo esc_attr($id); ?> .bsa-plus{display:block}
        .bsa-<?php echo esc_attr($id); ?> .bsa-minus{display:none}
        .bsa-<?php echo esc_attr($id); ?> .bsa-card.active .bsa-plus{display:none}
        .bsa-<?php echo esc_attr($id); ?> .bsa-card.active .bsa-minus{display:block}
        .bsa-<?php echo esc_attr($id); ?> .bsa-content{display:none;border-top:1px solid #e2e8f0;background:#fafbfc}
        .bsa-<?php echo esc_attr($id); ?> .bsa-inner{padding:24px}
        @media (max-width:768px){
            .bsa-<?php echo esc_attr($id); ?> .bsa-number{font-size:28px;min-width:45px}
            .bsa-<?php echo esc_attr($id); ?> .bsa-header{padding:16px;gap:15px}
            .bsa-<?php echo esc_attr($id); ?> .bsa-title{font-size:18px}
            .bsa-<?php echo esc_attr($id); ?> .bsa-inner{padding:16px}
        }
        </style>
        
        <script>
        (function(){
            var a=document.querySelector('.bsa-<?php echo esc_js($id); ?>');
            if(!a)return;
            var m=a.getAttribute('data-allow-multiple')==='true';
            a.addEventListener('click',function(e){
                var h=e.target.closest('.bsa-header');
                if(!h)return;
                var c=h.closest('.bsa-card');
                var t=c.querySelector('.bsa-content');
                var active=c.classList.contains('active');
                if(!m){
                    a.querySelectorAll('.bsa-card').forEach(function(x){
                        if(x!==c){
                            x.classList.remove('active');
                            x.querySelector('.bsa-content').style.display='none';
                        }
                    });
                }
                if(active){
                    c.classList.remove('active');
                    t.style.display='none';
                }else{
                    c.classList.add('active');
                    t.style.display='block';
                }
            });
        })();
        </script>
        
        <?php
    }
}
