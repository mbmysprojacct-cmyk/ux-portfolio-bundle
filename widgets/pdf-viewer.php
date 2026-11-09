<?php
/**
 * PDF Viewer Widget — PDF.js-based
 * Renders PDFs to canvas with custom UI: zoom, pan (drag when zoomed),
 * page navigation, and present (fullscreen).
 */

if (!defined('ABSPATH')) exit;

class UX_Pdf_Viewer_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-pdf-viewer'; }
    public function get_title() { return __('PDF Viewer', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-document-file'; }

    protected function register_controls() {

        $this->add_standard_header_section();

        $this->start_controls_section('content_section', [
            'label' => __('Content', 'ux-portfolio-optimized')
        ]);

        $this->add_control('pdf_file', [
            'label' => __('PDF File', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'media_types' => ['application/pdf'],
        ]);

        $this->add_responsive_control('height', [
            'label' => __('Height', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [
                'px' => ['min' => 300, 'max' => 1200],
                'vh' => ['min' => 30, 'max' => 95],
            ],
            'size_units' => ['px', 'vh'],
            'default' => ['size' => 600, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .ux-pdf__viewport' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('page_layout', [
            'label' => __('Page Layout', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'single',
            'options' => [
                'single' => __('Single page', 'ux-portfolio-optimized'),
                'spread' => __('Spread (two pages)', 'ux-portfolio-optimized'),
            ],
            'description' => __('Spread shows two pages side by side like an open book; navigation moves two pages at a time.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();

        // Controls section
        $this->start_controls_section('controls_section', [
            'label' => __('Controls', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_zoom', [
            'label' => __('Show Zoom Buttons', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_pages', [
            'label' => __('Show Page Navigation', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_fullscreen', [
            'label' => __('Show Present Button', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Toggles fullscreen presentation mode.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();

        // Style
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_description_alignment();

        $this->add_control('container_heading', [
            'label' => __('Container', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('viewport_bg', [
            'label' => __('Viewport Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-pdf__viewport' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('container_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 8],
            'selectors' => ['{{WRAPPER}} .ux-pdf__viewport' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'container_border',
            'selector' => '{{WRAPPER}} .ux-pdf__viewport',
        ]);

        $this->add_control('controls_heading', [
            'label' => __('Buttons', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('controls_color', [
            'label' => __('Text/Icon Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-pdf__btn, {{WRAPPER}} .ux-pdf__page-indicator' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('controls_bg', [
            'label' => __('Button Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-pdf__btn' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('controls_bg_hover', [
            'label' => __('Button Background (Hover)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ux-pdf__btn:hover:not([disabled]), {{WRAPPER}} .ux-pdf__btn:focus-visible:not([disabled])' => 'background: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        if (empty($s['pdf_file']['url'])) {
            echo '<p>' . esc_html__('Please select a PDF file.', 'ux-portfolio-optimized') . '</p>';
            return;
        }

        $url = esc_url($s['pdf_file']['url']);
        $show_zoom = ($s['show_zoom'] ?? 'yes') === 'yes';
        $show_pages = ($s['show_pages'] ?? 'yes') === 'yes';
        $show_fullscreen = ($s['show_fullscreen'] ?? 'yes') === 'yes';
        $page_layout = ($s['page_layout'] ?? 'single') === 'spread' ? 'spread' : 'single';
        ?>

        <div class="ux-pdf-wrapper">
            <?php $this->render_standard_header(); ?>

            <div class="ux-pdf" data-pdf-url="<?php echo esc_attr($url); ?>" data-layout="<?php echo esc_attr($page_layout); ?>">
                <div class="ux-pdf__viewport">
                    <div class="ux-pdf__loading">
                        <?php esc_html_e('Loading PDF…', 'ux-portfolio-optimized'); ?>
                    </div>
                    <canvas class="ux-pdf__canvas" aria-label="<?php esc_attr_e('PDF page', 'ux-portfolio-optimized'); ?>"></canvas>
                </div>

                <div class="ux-pdf__controls">
                    <?php if ($show_pages) : ?>
                        <button type="button" class="ux-pdf__btn ux-pdf__btn--prev" disabled aria-label="<?php esc_attr_e('Previous page', 'ux-portfolio-optimized'); ?>" title="<?php esc_attr_e('Previous page', 'ux-portfolio-optimized'); ?>">
                            <svg viewBox="0 0 16 16" width="14" height="14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M10 2L4 8l6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <span class="ux-pdf__page-indicator" aria-live="polite">
                            <span class="ux-pdf__current-page">–</span> / <span class="ux-pdf__total-pages">–</span>
                        </span>

                        <button type="button" class="ux-pdf__btn ux-pdf__btn--next" disabled aria-label="<?php esc_attr_e('Next page', 'ux-portfolio-optimized'); ?>" title="<?php esc_attr_e('Next page', 'ux-portfolio-optimized'); ?>">
                            <svg viewBox="0 0 16 16" width="14" height="14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M6 2l6 6-6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    <?php endif; ?>

                    <?php if ($show_zoom) : ?>
                        <button type="button" class="ux-pdf__btn ux-pdf__btn--zoom-out" aria-label="<?php esc_attr_e('Zoom out', 'ux-portfolio-optimized'); ?>" title="<?php esc_attr_e('Zoom out', 'ux-portfolio-optimized'); ?>">
                            <svg viewBox="0 0 16 16" width="14" height="14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M5 7h4M11 11l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>

                        <button type="button" class="ux-pdf__btn ux-pdf__btn--zoom-fit" aria-label="<?php esc_attr_e('Fit to width', 'ux-portfolio-optimized'); ?>" title="<?php esc_attr_e('Fit to width', 'ux-portfolio-optimized'); ?>">
                            <svg viewBox="0 0 16 16" width="14" height="14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M2 4h12M2 12h12M4 4v8M12 4v8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                            </svg>
                        </button>

                        <button type="button" class="ux-pdf__btn ux-pdf__btn--zoom-in" aria-label="<?php esc_attr_e('Zoom in', 'ux-portfolio-optimized'); ?>" title="<?php esc_attr_e('Zoom in', 'ux-portfolio-optimized'); ?>">
                            <svg viewBox="0 0 16 16" width="14" height="14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M5 7h4M7 5v4M11 11l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    <?php endif; ?>

                    <?php if ($show_fullscreen) : ?>
                        <button type="button" class="ux-pdf__btn ux-pdf__btn--fullscreen" aria-label="<?php esc_attr_e('Present', 'ux-portfolio-optimized'); ?>" title="<?php esc_attr_e('Present (fullscreen)', 'ux-portfolio-optimized'); ?>">
                            <svg viewBox="0 0 16 16" width="14" height="14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M2 6V2h4M14 6V2h-4M2 10v4h4M14 10v4h-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
    }
}
