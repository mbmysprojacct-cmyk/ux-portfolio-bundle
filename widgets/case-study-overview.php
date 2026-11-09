<?php
/**
 * Case Study Overview Widget - Full Original
 */

if (!defined('ABSPATH')) exit;

class UX_Case_Study_Overview_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-case-study-overview'; }
    public function get_title() { return __('UX Case Study', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-portfolio'; }
    
    protected function register_controls() {
        
        $this->start_controls_section('content_section', ['label' => 'Content']);
        
        $this->add_control('title', [
            'label' => 'Title', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'Project Title',
            'dynamic' => ['active' => true]
        ]);
        
        $this->add_control('description', [
            'label' => 'Description', 
            'type' => \Elementor\Controls_Manager::TEXTAREA, 
            'default' => 'Project description',
            'dynamic' => ['active' => true]
        ]);
        
        $this->add_control('use_featured_image', [
            'label' => 'Use Featured Image', 
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => 'Yes',
            'label_off' => 'No'
        ]);
        
        $this->add_control('image_position', [
            'label' => 'Image Position',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'top',
            'options' => [
                'top' => 'Top',
                'left' => 'Left',
                'right' => 'Right',
                'none' => 'None'
            ]
        ]);
        
        $this->add_control('image_height', [
            'label' => 'Image Height',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 600, 'step' => 10]],
            'default' => ['size' => 250],
            'condition' => ['image_position' => 'top']
        ]);
        
        $this->add_control('image_width', [
            'label' => 'Image Width',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => ['%' => ['min' => 20, 'max' => 60, 'step' => 5]],
            'default' => ['size' => 40],
            'condition' => ['image_position' => ['left', 'right']]
        ]);
        
        $this->add_control('image_width_tablet', [
            'label' => 'Image Width (Tablet)',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => ['%' => ['min' => 20, 'max' => 80, 'step' => 5]],
            'default' => ['size' => 50],
            'condition' => ['image_position' => ['left', 'right']]
        ]);
        
        $this->add_control('image_width_mobile', [
            'label' => 'Image Width (Mobile)',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => ['%' => ['min' => 100, 'max' => 100, 'step' => 1]],
            'default' => ['size' => 100],
            'condition' => ['image_position' => ['left', 'right']]
        ]);
        
        $this->add_control('image_height_tablet', [
            'label' => 'Image Height (Tablet)',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 500, 'step' => 10]],
            'default' => ['size' => 200],
            'condition' => ['image_position' => 'top']
        ]);
        
        $this->add_control('image_height_mobile', [
            'label' => 'Image Height (Mobile)',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 400, 'step' => 10]],
            'default' => ['size' => 200],
            'condition' => ['image_position' => 'top']
        ]);
        
        $this->add_control('image_object_fit', [
            'label' => 'Image Fit',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover' => 'Cover',
                'contain' => 'Contain',
                'fill' => 'Fill'
            ]
        ]);
        
        $this->add_control('image_focal_point_x', [
            'label' => 'Focal Point X',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => ['%' => ['min' => 0, 'max' => 100, 'step' => 1]],
            'default' => ['size' => 50],
            'condition' => ['image_object_fit' => 'cover']
        ]);
        
        $this->add_control('image_focal_point_y', [
            'label' => 'Focal Point Y',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => ['%' => ['min' => 0, 'max' => 100, 'step' => 1]],
            'default' => ['size' => 50],
            'condition' => ['image_object_fit' => 'cover']
        ]);
        
        $this->end_controls_section();

        $this->start_controls_section('layout_section', ['label' => 'Layout']);
        
        $this->add_control('overview_alignment', [
            'label' => 'Overview Alignment',
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right']
            ],
            'default' => 'left'
        ]);
        
        $this->add_control('overview_columns', [
            'label' => 'Overview Columns',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '2',
            'options' => [
                '1' => '1 Column',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns'
            ]
        ]);
        
        $this->add_control('overview_vertical_align', [
            'label' => 'Vertical Alignment',
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'start' => ['title' => 'Top', 'icon' => 'eicon-v-align-top'],
                'center' => ['title' => 'Middle', 'icon' => 'eicon-v-align-middle'],
                'end' => ['title' => 'Bottom', 'icon' => 'eicon-v-align-bottom']
            ],
            'default' => 'start'
        ]);
        
        $this->end_controls_section();

        $this->start_controls_section('client_section', ['label' => 'Client']);
        
        $this->add_control('client_label', [
            'label' => 'Label', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'Client'
        ]);
        
        $this->add_control('client_icon', [
            'label' => 'Icon',
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid']
        ]);
        
        $this->add_control('client_name', [
            'label' => 'Client Name', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'Company Name',
            'dynamic' => ['active' => true]
        ]);
        
        $this->add_control('client_url', [
            'label' => 'Client URL',
            'type' => \Elementor\Controls_Manager::URL
        ]);
        
        $this->end_controls_section();

        $this->start_controls_section('role_section', ['label' => 'Role']);
        
        $this->add_control('role_label', [
            'label' => 'Label', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'Role'
        ]);
        
        $this->add_control('role_icon', [
            'label' => 'Icon',
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-user', 'library' => 'fa-solid']
        ]);
        
        $this->add_control('role_name', [
            'label' => 'Role Name', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'UX Designer',
            'dynamic' => ['active' => true]
        ]);
        
        $this->add_control('role_url', [
            'label' => 'Role URL',
            'type' => \Elementor\Controls_Manager::URL
        ]);
        
        $this->end_controls_section();

        $this->start_controls_section('scope_section', ['label' => 'Scope']);
        
        $this->add_control('scope_label', [
            'label' => 'Label', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'Duration'
        ]);
        
        $this->add_control('scope_icon', [
            'label' => 'Icon',
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-calendar', 'library' => 'fa-solid']
        ]);
        
        $this->add_control('scope_name', [
            'label' => 'Scope Value', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => '3 months',
            'dynamic' => ['active' => true]
        ]);
        
        $this->add_control('scope_url', [
            'label' => 'Scope URL',
            'type' => \Elementor\Controls_Manager::URL
        ]);
        
        $this->end_controls_section();

        $this->start_controls_section('tools_section', ['label' => 'Tools']);
        
        $this->add_control('tools_label', [
            'label' => 'Label', 
            'type' => \Elementor\Controls_Manager::TEXT, 
            'default' => 'Tools'
        ]);
        
        $this->add_control('tools_icon', [
            'label' => 'Icon',
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-tools', 'library' => 'fa-solid']
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('tool_name', [
            'label' => 'Tool Name',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Figma',
            'description' => 'Separate multiple tools with commas',
            'dynamic' => ['active' => true]
        ]);
        
        $repeater->add_control('tool_url', [
            'label' => 'Tool URL',
            'type' => \Elementor\Controls_Manager::URL
        ]);
        
        $this->add_control('tools_list', [
            'label' => 'Tools List',
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['tool_name' => 'Figma'],
                ['tool_name' => 'Sketch']
            ],
            'title_field' => '{{{ tool_name }}}'
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $s = $this->get_settings_for_display();
        
        $title = wp_kses_post($s['title']);
        $description = wp_kses_post($s['description']);
        $image_url = '';
        
        if (isset($s['use_featured_image']) && $s['use_featured_image'] === 'yes') {
            $post_id = get_the_ID();
            if (!$post_id && class_exists('\Elementor\Plugin')) {
                $document = \Elementor\Plugin::$instance->documents->get_current();
                if ($document) {
                    $post_id = $document->get_main_id();
                }
            }
            
            if ($post_id) {
                $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
                if ($thumbnail_url) {
                    $image_url = esc_url($thumbnail_url);
                }
            }
        }
        
        $image_position = sanitize_key($s['image_position'] ?? 'top');
        $image_height = absint($s['image_height']['size'] ?? 250);
        $image_width = absint($s['image_width']['size'] ?? 40);
        $image_width_tablet = absint($s['image_width_tablet']['size'] ?? 50);
        $image_width_mobile = absint($s['image_width_mobile']['size'] ?? 100);
        $image_height_tablet = absint($s['image_height_tablet']['size'] ?? 200);
        $image_height_mobile = absint($s['image_height_mobile']['size'] ?? 200);
        $image_fit = sanitize_key($s['image_object_fit'] ?? 'cover');
        $focal_x = absint($s['image_focal_point_x']['size'] ?? 50);
        $focal_y = absint($s['image_focal_point_y']['size'] ?? 50);
        $overview_align = sanitize_key($s['overview_alignment'] ?? 'left');
        $overview_cols = sanitize_key($s['overview_columns'] ?? '2');
        $overview_valign = sanitize_key($s['overview_vertical_align'] ?? 'start');
        $client_name = wp_kses_post($s['client_name']);
        $client_url = esc_url($s['client_url']['url'] ?? '');
        $role_name = wp_kses_post($s['role_name']);
        $role_url = esc_url($s['role_url']['url'] ?? '');
        $scope_name = wp_kses_post($s['scope_name']);
        $scope_url = esc_url($s['scope_url']['url'] ?? '');
        
        $tools = [];
        if (!empty($s['tools_list'])) {
            foreach ($s['tools_list'] as $tool) {
                $tool_name = wp_kses_post($tool['tool_name'] ?? '');
                $tool_url = esc_url($tool['tool_url']['url'] ?? '');
                $tool_items = array_map('trim', explode(',', $tool_name));
                foreach ($tool_items as $item) {
                    if (!empty($item)) {
                        $tools[] = ['name' => $item, 'url' => $tool_url];
                    }
                }
            }
        }
        ?>
        <style>
        .cs-wrap{max-width:100%;margin:0;font-family:system-ui,sans-serif}
        .cs-card{background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);overflow:hidden;height:100%}
        .cs-card.layout-left,.cs-card.layout-right{display:flex;align-items:stretch}
        .cs-card.layout-right{flex-direction:row-reverse}
        .cs-img{display:block;object-fit:<?php echo esc_attr($image_fit); ?>;object-position:<?php echo esc_attr($focal_x); ?>% <?php echo esc_attr($focal_y); ?>%}
        .cs-card.layout-top .cs-img{width:100%;height:<?php echo esc_attr($image_height); ?>px}
        .cs-card.layout-left .cs-img,.cs-card.layout-right .cs-img{width:<?php echo esc_attr($image_width); ?>%;height:auto;min-height:100%;flex-shrink:0}
        .cs-body{padding:20px;flex:1;display:flex;flex-direction:column;justify-content:<?php echo esc_attr($overview_valign === 'start' ? 'flex-start' : ($overview_valign === 'end' ? 'flex-end' : 'center')); ?>;min-width:0;overflow:hidden}
        .cs-title{font-size:24px;font-weight:700;margin:0 0 10px}
        .cs-desc{color:#666;margin:0 0 20px}
        .cs-grid{display:grid;grid-template-columns:repeat(<?php echo esc_attr($overview_cols); ?>,1fr);gap:15px;text-align:<?php echo esc_attr($overview_align); ?>}
        .cs-field{display:flex;gap:8px;align-items:start;justify-content:<?php echo $overview_align === 'center' ? 'center' : ($overview_align === 'right' ? 'flex-end' : 'flex-start'); ?>}
        .cs-icon{width:18px;height:18px;flex-shrink:0;margin-top:2px;display:flex;align-items:center;justify-content:center;color:#666;order:<?php echo $overview_align === 'right' ? '1' : '0'; ?>}
        .cs-icon i{font-size:16px}
        .cs-icon svg{width:100%;height:100%;stroke:#666;fill:none}
        .cs-lbl{font-size:11px;text-transform:uppercase;color:#999;margin-bottom:3px}
        .cs-val{font-weight:500;color:#333}
        .cs-link{color:#0066cc;text-decoration:none}
        .cs-link:hover{text-decoration:underline}
        .cs-tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:5px}
        .cs-tag{padding:4px 10px;background:#f0f0f0;border-radius:4px;font-size:13px}
        @media(max-width:1024px){
        .cs-card.layout-top .cs-img{height:<?php echo esc_attr($image_height_tablet); ?>px}
        .cs-card.layout-left .cs-img,.cs-card.layout-right .cs-img{width:<?php echo esc_attr($image_width_tablet); ?>%}
        .cs-grid{grid-template-columns:repeat(2,1fr)}
        }
        @media(max-width:600px){
        .cs-card.layout-top .cs-img{height:<?php echo esc_attr($image_height_mobile); ?>px}
        .cs-card.layout-left,.cs-card.layout-right{flex-direction:column}
        .cs-card.layout-left .cs-img,.cs-card.layout-right .cs-img{width:100%;height:<?php echo esc_attr($image_height_mobile); ?>px;min-height:auto}
        .cs-grid{grid-template-columns:1fr}
        }
        </style>
        
        <div class="cs-wrap">
            <div class="cs-card layout-<?php echo esc_attr($image_position); ?>">
                <?php if (!empty($image_url) && $image_position !== 'none'): ?>
                <img src="<?php echo esc_url($image_url); ?>" class="cs-img" alt="<?php echo esc_attr($title); ?>">
                <?php endif; ?>
                
                <div class="cs-body">
                    <h2 class="cs-title"><?php echo wp_kses_post($title); ?></h2>
                    <p class="cs-desc"><?php echo wp_kses_post($description); ?></p>
                    
                    <div class="cs-grid">
                        <div class="cs-field">
                            <div class="cs-icon">
                                <?php \Elementor\Icons_Manager::render_icon($s['client_icon'], ['aria-hidden' => 'true']); ?>
                            </div>
                            <div>
                                <div class="cs-lbl"><?php echo esc_html($s['client_label']); ?></div>
                                <?php if (!empty($client_url)): ?>
                                <a href="<?php echo esc_url($client_url); ?>" class="cs-link cs-val"><?php echo wp_kses_post($client_name); ?></a>
                                <?php else: ?>
                                <div class="cs-val"><?php echo wp_kses_post($client_name); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="cs-field">
                            <div class="cs-icon">
                                <?php \Elementor\Icons_Manager::render_icon($s['role_icon'], ['aria-hidden' => 'true']); ?>
                            </div>
                            <div>
                                <div class="cs-lbl"><?php echo esc_html($s['role_label']); ?></div>
                                <?php if (!empty($role_url)): ?>
                                <a href="<?php echo esc_url($role_url); ?>" class="cs-link cs-val"><?php echo wp_kses_post($role_name); ?></a>
                                <?php else: ?>
                                <div class="cs-val"><?php echo wp_kses_post($role_name); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="cs-field">
                            <div class="cs-icon">
                                <?php \Elementor\Icons_Manager::render_icon($s['scope_icon'], ['aria-hidden' => 'true']); ?>
                            </div>
                            <div>
                                <div class="cs-lbl"><?php echo esc_html($s['scope_label']); ?></div>
                                <?php if (!empty($scope_url)): ?>
                                <a href="<?php echo esc_url($scope_url); ?>" class="cs-link cs-val"><?php echo wp_kses_post($scope_name); ?></a>
                                <?php else: ?>
                                <div class="cs-val"><?php echo wp_kses_post($scope_name); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="cs-field">
                            <div class="cs-icon">
                                <?php \Elementor\Icons_Manager::render_icon($s['tools_icon'], ['aria-hidden' => 'true']); ?>
                            </div>
                            <div>
                                <div class="cs-lbl"><?php echo esc_html($s['tools_label']); ?></div>
                                <div class="cs-tags">
                                    <?php foreach ($tools as $tool): ?>
                                    <?php if (!empty($tool['url'])): ?>
                                    <a href="<?php echo esc_url($tool['url']); ?>" class="cs-tag cs-link"><?php echo esc_html($tool['name']); ?></a>
                                    <?php else: ?>
                                    <span class="cs-tag"><?php echo esc_html($tool['name']); ?></span>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
