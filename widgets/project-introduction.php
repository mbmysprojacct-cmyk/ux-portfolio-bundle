<?php
/**
 * Icon Box Widget - Optimized (Simple Working Version)
 */

if (!defined('ABSPATH')) exit;

class UX_Project_Introduction_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-project-introduction'; }
    public function get_title() { return __('Icon Box', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-star'; }
    
    protected function register_controls() {
        
        $this->start_controls_section('content_section', ['label' => __('Content', 'ux-portfolio-optimized')]);
        
        $this->add_control('show_main_title', [
            'label' => __('Show Main Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no'
        ]);
        
        $this->add_control('main_title', [
            'label' => __('Main Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main Title',
            'condition' => ['show_main_title' => 'yes']
        ]);
        
        $this->add_control('main_title_tag', [
            'label' => __('Main Title HTML Tag', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6'],
            'default' => 'h2',
            'condition' => ['show_main_title' => 'yes']
        ]);
        
        $this->add_control('show_main_subtitle', [
            'label' => __('Show Main Subtitle', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no'
        ]);
        
        $this->add_control('main_subtitle', [
            'label' => __('Main Subtitle', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main Subtitle',
            'condition' => ['show_main_subtitle' => 'yes']
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('media_type', [
            'label' => __('Media Type', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'icon' => __('Icon', 'ux-portfolio-optimized'),
                'image' => __('Image', 'ux-portfolio-optimized')
            ]
        ]);
        
        $repeater->add_control('icon', [
            'label' => __('Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-star', 'library' => 'fa-solid'],
            'condition' => ['media_type' => 'icon']
        ]);
        
        $repeater->add_control('image', [
            'label' => __('Image', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            'condition' => ['media_type' => 'image']
        ]);

        $repeater->add_control('image_lightbox', [
            'label' => __('Open in Lightbox', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Click the image to open it in Elementor\'s default lightbox. Overrides the item\'s outer link if both are enabled.', 'ux-portfolio-optimized'),
            'condition' => ['media_type' => 'image']
        ]);
        
        $repeater->add_control('image_span_columns', [
            'label' => __('Span Full Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => ['media_type' => 'image']
        ]);
        
        $repeater->add_responsive_control('span_rows', [
            'label' => __('Span Rows', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 10,
            'default' => 1,
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}' => 'grid-row: span {{VALUE}};'
            ],
            'condition' => ['media_type' => 'image']
        ]);
        
        $repeater->add_responsive_control('span_columns_count', [
            'label' => __('Span Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 10,
            'default' => 1,
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}' => 'grid-column: span {{VALUE}};'
            ],
            'condition' => ['media_type' => 'image']
        ]);
        
        $repeater->add_responsive_control('image_focal_point', [
            'label' => __('Focal Point', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['%'],
            'default' => ['unit' => '%', 'top' => 50, 'left' => 50],
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}} .image-box img' => 'object-position: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}};'
            ],
            'condition' => ['media_type' => 'image']
        ]);
        
        $repeater->add_control('image_aspect_ratio', [
            'label' => __('Aspect Ratio', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'custom',
            'options' => [
                'custom' => __('Custom', 'ux-portfolio-optimized'),
                '1/1' => __('Square (1:1)', 'ux-portfolio-optimized'),
                '4/3' => __('Standard (4:3)', 'ux-portfolio-optimized'),
                '3/4' => __('Portrait (3:4)', 'ux-portfolio-optimized'),
                '16/9' => __('Widescreen (16:9)', 'ux-portfolio-optimized'),
                '9/16' => __('Vertical (9:16)', 'ux-portfolio-optimized')
            ],
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}} .image-box' => 'aspect-ratio: {{VALUE}}; height: auto;'
            ],
            'condition' => ['media_type' => 'image']
        ]);
        
        $repeater->add_control('show_title', [
            'label' => __('Show Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes'
        ]);
        
        $repeater->add_control('title', [
            'label' => __('Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Your Title',
            'condition' => ['show_title' => 'yes']
        ]);
        
        $repeater->add_control('title_tag', [
            'label' => __('Title HTML Tag', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'div' => 'div', 'span' => 'span', 'p' => 'p'
            ],
            'default' => 'h3',
            'condition' => ['show_title' => 'yes']
        ]);
        
        $repeater->add_control('show_description', [
            'label' => __('Show Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes'
        ]);
        
        $repeater->add_control('description', [
            'label' => __('Description', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => 'Your description text goes here',
            'condition' => ['show_description' => 'yes']
        ]);
        
        $repeater->add_control('linkable', [
            'label' => __('Make Linkable', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no'
        ]);
        
        $repeater->add_control('link', [
            'label' => __('Link', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'condition' => ['linkable' => 'yes']
        ]);
        
        $this->add_control('items', [
            'label' => __('Icon Box Items', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['media_type' => 'icon', 'icon' => ['value' => 'fas fa-box', 'library' => 'fa-solid'], 'title' => 'Product', 'description' => 'Product description'],
                ['media_type' => 'icon', 'icon' => ['value' => 'fas fa-calendar', 'library' => 'fa-solid'], 'title' => 'Duration', 'description' => 'Project duration'],
                ['media_type' => 'icon', 'icon' => ['value' => 'fas fa-exclamation-triangle', 'library' => 'fa-solid'], 'title' => 'Problem', 'description' => 'Problem description'],
                ['media_type' => 'icon', 'icon' => ['value' => 'fas fa-bullseye', 'library' => 'fa-solid'], 'title' => 'Goal', 'description' => 'Goal description']
            ],
            'title_field' => '{{{ title }}}'
        ]);
        
        $this->end_controls_section();
        
        // LAYOUT
        $this->start_controls_section('layout_section', ['label' => __('Layout', 'ux-portfolio-optimized')]);
        
        $this->add_control('layout', [
            'label' => __('Item Layout', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'column',
            'options' => [
                'row' => __('Horizontal', 'ux-portfolio-optimized'),
                'column' => __('Vertical', 'ux-portfolio-optimized')
            ]
        ]);
        
        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 12,
            'default' => 4,
            'tablet_default' => 2,
            'mobile_default' => 1,
            'selectors' => [
                '{{WRAPPER}} .number-box-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);'
            ]
        ]);
        
        $this->add_responsive_control('row_gap', [
            'label' => __('Row Gap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'default' => ['size' => 20],
            'selectors' => ['{{WRAPPER}} .number-box-container' => 'row-gap: {{SIZE}}{{UNIT}};']
        ]);
        
        $this->add_responsive_control('column_gap', [
            'label' => __('Column Gap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'default' => ['size' => 20],
            'selectors' => ['{{WRAPPER}} .number-box-container' => 'column-gap: {{SIZE}}{{UNIT}};']
        ]);
        
        $this->add_responsive_control('icon_align', [
            'label' => __('Icon/Image Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-text-align-left'
                ],
                'center' => [
                    'title' => __('Center', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-text-align-center'
                ],
                'flex-end' => [
                    'title' => __('Right', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-text-align-right'
                ]
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .ue-number-container' => 'justify-content: {{VALUE}};'
            ]
        ]);
        
        $this->add_responsive_control('content_align', [
            'label' => __('Content Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-text-align-left'
                ],
                'center' => [
                    'title' => __('Center', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-text-align-center'
                ],
                'right' => [
                    'title' => __('Right', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-text-align-right'
                ]
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .number-box-single-title' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .number-box-single-description' => 'text-align: {{VALUE}};'
            ]
        ]);

        $this->add_responsive_control('vertical_align', [
            'label' => __('Vertical Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Top', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-v-align-top'
                ],
                'center' => [
                    'title' => __('Middle', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-v-align-middle'
                ],
                'flex-end' => [
                    'title' => __('Bottom', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-v-align-bottom'
                ],
                'space-between' => [
                    'title' => __('Space Between', 'ux-portfolio-optimized'),
                    'icon' => 'eicon-v-align-stretch'
                ]
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .circle_number_widget_layout' => '--ux-icon-box-valign: {{VALUE}};'
            ]
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Icon Box
        $this->start_controls_section('style_icon_box_section', [
            'label' => __('Icon Box', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE
        ]);
        
        $this->add_responsive_control('box_size', [
            'label' => __('Box Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 50, 'max' => 300]],
            'default' => ['unit' => 'px', 'size' => 80],
            'selectors' => [
                '{{WRAPPER}} .circle-icon-box' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
            ]
        ]);
        
        $this->add_control('box_background_color', [
            'label' => __('Background Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f0f0f0',
            'selectors' => ['{{WRAPPER}} .circle-icon-box' => 'background-color: {{VALUE}};']
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'box_border',
            'selector' => '{{WRAPPER}} .circle-icon-box'
        ]);
        
        $this->add_responsive_control('box_border_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['unit' => '%', 'top' => 50, 'right' => 50, 'bottom' => 50, 'left' => 50],
            'selectors' => [
                '{{WRAPPER}} .circle-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ]
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Icon
        $this->start_controls_section('style_icon_section', [
            'label' => __('Icon', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE
        ]);
        
        $this->add_responsive_control('icon_size', [
            'label' => __('Icon Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 10, 'max' => 200]],
            'default' => ['size' => 48],
            'selectors' => [
                '{{WRAPPER}} .circle-icon-box i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .circle-icon-box svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .circle-icon-box' => 'font-size: {{SIZE}}{{UNIT}};'
            ]
        ]);
        
        $this->end_controls_section();
        
        // STYLE TAB - Image
        $this->start_controls_section('style_image_section', [
            'label' => __('Image', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE
        ]);
        
        $this->add_responsive_control('image_box_size', [
            'label' => __('Image Box Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => ['min' => 50, 'max' => 800],
                '%' => ['min' => 10, 'max' => 100],
                'vw' => ['min' => 10, 'max' => 100]
            ],
            'selectors' => [
                '{{WRAPPER}} .image-box' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; flex-shrink: 0;'
            ]
        ]);
        
        $this->add_responsive_control('image_max_width', [
            'label' => __('Image Max Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range' => [
                'px' => ['min' => 50, 'max' => 1200],
                '%' => ['min' => 10, 'max' => 100],
                'vw' => ['min' => 10, 'max' => 100]
            ],
            'default' => ['unit' => 'px', 'size' => 400],
            'selectors' => [
                '{{WRAPPER}} .image-box' => 'max-width: {{SIZE}}{{UNIT}};'
            ]
        ]);
        
        $this->add_control('image_background_color', [
            'label' => __('Background Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .image-box' => 'background-color: {{VALUE}};']
        ]);
        
        $this->add_responsive_control('image_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .image-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ]
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'image_border',
            'selector' => '{{WRAPPER}} .image-box'
        ]);
        
        $this->add_responsive_control('image_border_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .image-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .image-box img' => 'border-radius: inherit;'
            ]
        ]);
        
        $this->add_control('image_object_fit', [
            'label' => __('Object Fit', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'cover',
            'options' => [
                'cover' => __('Cover', 'ux-portfolio-optimized'),
                'contain' => __('Contain', 'ux-portfolio-optimized'),
                'fill' => __('Fill', 'ux-portfolio-optimized'),
                'none' => __('None', 'ux-portfolio-optimized')
            ],
            'selectors' => [
                '{{WRAPPER}} .image-box img' => 'object-fit: {{VALUE}};'
            ]
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $s = $this->get_settings_for_display();
        
        if (empty($s['items'])) return;
        ?>
        
        <div class="widget-wrapper">
        
        <div class="icon-box-widget-container">
            
            <?php if ($s['show_main_title'] === 'yes' && $s['main_title']) : 
                $title_tag = $s['main_title_tag'] ?? 'h2';
                ?>
                <<?php echo esc_attr($title_tag); ?> class="widget-main-title">
                    <?php echo esc_html($s['main_title']); ?>
                </<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>
            
            <?php if ($s['show_main_subtitle'] === 'yes' && $s['main_subtitle']) : ?>
                <div class="widget-main-subtitle"><?php echo esc_html($s['main_subtitle']); ?></div>
            <?php endif; ?>
            
            <div class="number-box-container">
                
                <?php foreach ($s['items'] as $item) : 
                    $title_tag = $item['title_tag'] ?? 'h3';
                    $span_full = ($item['image_span_columns'] === 'yes') ? ' span-columns' : '';
                    ?>
                    
                    <div class="number-box-single-item elementor-repeater-item-<?php echo esc_attr($item['_id']); ?><?php echo $span_full; ?>">
                        
                        <?php 
                        $wrapper_tag = 'div';
                        $wrapper_attrs = '';
                        $img_lightbox = ($item['image_lightbox'] ?? '') === 'yes'
                                        && ($item['media_type'] ?? '') === 'image'
                                        && !empty($item['image']['url']);
                        if ($item['linkable'] === 'yes' && !empty($item['link']['url']) && !$img_lightbox) {
                            $wrapper_tag = 'a';
                            $wrapper_attrs = 'href="' . esc_url($item['link']['url']) . '"';
                            if (!empty($item['link']['is_external'])) {
                                $wrapper_attrs .= ' target="_blank"';
                            }
                            if (!empty($item['link']['nofollow'])) {
                                $wrapper_attrs .= ' rel="nofollow"';
                            }
                        }
                        ?>
                        
                        <<?php echo $wrapper_tag; ?> <?php echo $wrapper_attrs; ?> class="circle_number_widget_layout layout-<?php echo esc_attr($s['layout'] ?? 'column'); ?>">
                            
                            <div class="ue-number-container">
                                
                                <?php if ($item['media_type'] === 'icon' && !empty($item['icon']['value'])) : ?>
                                    <div class="circle-icon-box">
                                        <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                <?php elseif ($item['media_type'] === 'image' && !empty($item['image']['url'])) : ?>
                                    <div class="image-box">
                                        <?php if ($img_lightbox) : ?>
                                            <a href="<?php echo esc_url($item['image']['url']); ?>"
                                               data-elementor-open-lightbox="yes"
                                               data-elementor-lightbox-slideshow="ux-icon-box-<?php echo esc_attr($this->get_id()); ?>"
                                               data-elementor-lightbox-title="<?php echo esc_attr($item['title'] ?? ''); ?>">
                                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>">
                                            </a>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                            
                            <?php if (($item['show_title'] === 'yes' && $item['title']) || ($item['show_description'] === 'yes' && $item['description'])) : ?>
                                <div class="number-box-single-content">
                                    <?php if ($item['show_title'] === 'yes' && $item['title']) : ?>
                                        <<?php echo esc_attr($title_tag); ?> class="number-box-single-title">
                                            <?php echo esc_html($item['title']); ?>
                                        </<?php echo esc_attr($title_tag); ?>>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['show_description'] === 'yes' && $item['description']) : ?>
                                        <div class="number-box-single-description">
                                            <?php echo wp_kses_post($item['description']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                        </<?php echo $wrapper_tag; ?>>
                        
                    </div>
                    
                <?php endforeach; ?>
                
            </div>
            
        </div>
        </div>
        
        <style>
        .number-box-container{display:grid;width:100%}
        .number-box-single-item.span-columns{grid-column:1/-1!important}
        .circle-icon-box{width:80px;height:80px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .circle_number_widget_layout{display:flex;height:100%;justify-content:var(--ux-icon-box-valign,center)}
        .circle_number_widget_layout.layout-column{flex-direction:column;align-items:center}
        .circle_number_widget_layout.layout-row{flex-direction:row;align-items:flex-start;gap:16px;text-align:left}
        .circle_number_widget_layout.layout-row .ue-number-container{width:auto;flex-shrink:0}
        .circle_number_widget_layout.layout-row .number-box-single-content{flex:1 1 auto;min-width:0}
        .ue-number-container{display:flex;width:100%}
        .image-box{width:100%;overflow:hidden;position:relative}
        .image-box img{width:100%;height:100%;display:block;object-fit:cover}
        </style>
        
        <?php
    }
}
