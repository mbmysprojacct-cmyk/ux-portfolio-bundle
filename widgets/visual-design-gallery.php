<?php
/**
 * Visual Design Gallery Widget - Optimized
 * Lightbox image gallery
 */

if (!defined('ABSPATH')) exit;

class UX_Visual_Design_Gallery_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-visual-design-gallery'; }
    public function get_title() { return __('Visual Design Gallery', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-gallery-masonry'; }
    
    protected function register_controls() {
        
        $this->add_standard_header_section();
        
        $this->start_controls_section('gallery_section', [
            'label' => __('Gallery', 'ux-portfolio-optimized')
        ]);
        
        $this->add_control('images', [
            'label' => __('Images', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::GALLERY,
            'default' => [],
        ]);
        
        $this->add_control('tile_ratio', [
            'label' => __('Tile Shape', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['1 / 1' => __('Square', 'ux-portfolio-optimized'), '4 / 5' => __('Portrait 4:5', 'ux-portfolio-optimized'), '2 / 3' => __('Poster 2:3', 'ux-portfolio-optimized'), '4 / 3' => __('Landscape 4:3', 'ux-portfolio-optimized'), 'auto' => __('Natural (no crop)', 'ux-portfolio-optimized')],
            'default' => '1 / 1',
        ]);

        $this->add_control('tile_fit', [
            'label' => __('Image Fit', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['cover' => __('Fill (crop)', 'ux-portfolio-optimized'), 'contain' => __('Fit (no crop)', 'ux-portfolio-optimized')],
            'default' => 'cover',
            'condition' => ['tile_ratio!' => 'auto'],
        ]);

        $this->add_control('show_captions', [
            'label' => __('Show Captions', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Uses each image’s Caption from the Media Library, or its title if there is no caption.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('lightbox', [
            'label' => __('Enable Lightbox', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('hover_scroll', [
            'label' => __('Scroll on Hover', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('For tall images like full-page screenshots. Each tile shows the top of the image, and hovering (or tabbing to it) scrolls to the bottom. Stays still on touch screens and for visitors who prefer reduced motion.', 'ux-portfolio-optimized'),
            'condition' => ['tile_ratio!' => 'auto'],
        ]);

        $this->add_control('hover_scroll_speed', [
            'label' => __('Scroll Speed (px per second)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 100, 'max' => 1200, 'step' => 50]],
            'default' => ['unit' => 'px', 'size' => 400],
            'description' => __('Taller images take longer, so every tile scrolls at the same pace.', 'ux-portfolio-optimized'),
            'condition' => ['tile_ratio!' => 'auto', 'hover_scroll' => 'yes'],
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ],
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'selectors' => [
                '{{WRAPPER}} .gallery-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ]);
        
        $this->add_description_alignment();
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $s = $this->get_settings_for_display();
        
        if (empty($s['images'])) return;
        
        $lightbox = ($s['lightbox'] ?? 'yes') === 'yes';
        ?>
        
        <div class="gallery-wrapper">
            <?php $this->render_standard_header(); ?>
            
            <?php
            $ratio = in_array(($s['tile_ratio'] ?? '1 / 1'), ['1 / 1', '4 / 5', '2 / 3', '4 / 3', 'auto'], true) ? $s['tile_ratio'] : '1 / 1';
            $fit = ($s['tile_fit'] ?? 'cover') === 'contain' ? 'contain' : 'cover';
            $captions = ($s['show_captions'] ?? '') === 'yes';
            $slideshow = 'gallery-' . $this->get_id();
            $scroll = ($s['hover_scroll'] ?? '') === 'yes' && $ratio !== 'auto';
            $speed = isset($s['hover_scroll_speed']['size']) ? max(50, min(2000, (int) $s['hover_scroll_speed']['size'])) : 400;
            if ($scroll) $fit = 'cover';
            ?>
            <div class="gallery-grid<?php echo $ratio === 'auto' ? ' is-natural' : ''; ?><?php echo $scroll ? ' is-hover-scroll' : ''; ?>"<?php if ($scroll) echo ' data-scroll-speed="' . esc_attr($speed) . '"'; ?> style="--ux-gal-ratio: <?php echo esc_attr($ratio); ?>; --ux-gal-fit: <?php echo esc_attr($fit); ?>;">
                <?php foreach ($s['images'] as $image) :
                    $id = absint($image['id'] ?? 0);
                    $url = $image['url'] ?? '';
                    if ($url === '' && $id) $url = (string) wp_get_attachment_url($id);
                    if ($url === '') continue;
                    $alt = $id ? trim((string) get_post_meta($id, '_wp_attachment_image_alt', true)) : '';
                    $cap = '';
                    if ($captions && $id) {
                        $cap = trim((string) wp_get_attachment_caption($id));
                        if ($cap === '') $cap = trim((string) get_the_title($id));
                    }
                    ?>
                    <figure class="gallery-item">
                        <div class="gallery-media">
                            <?php if ($lightbox) : ?>
                                <a href="<?php echo esc_url($url); ?>" data-elementor-lightbox-slideshow="<?php echo esc_attr($slideshow); ?>"<?php echo $cap !== '' ? ' data-elementor-lightbox-title="' . esc_attr($cap) . '"' : ''; ?>>
                                    <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async">
                            <?php endif; ?>
                        </div>
                        <?php if ($cap !== '') : ?><figcaption class="gallery-caption"><?php echo esc_html($cap); ?></figcaption><?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
                <style>
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .gallery-item { margin: 0; min-width: 0; }
        .gallery-media {
            aspect-ratio: var(--ux-gal-ratio, 1 / 1);
            overflow: hidden;
            border-radius: 8px;
            background: #f0f0f0;
        }
        .gallery-grid.is-natural .gallery-media { aspect-ratio: auto; }
        .gallery-caption { margin-top: 6px; font-size: 13px; line-height: 1.35; }
        .gallery-media img {
            width: 100%;
            height: 100%;
            object-fit: var(--ux-gal-fit, cover);
            display: block;
            transition: transform 0.3s;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        /* Scroll on hover (v2.20.0) */
        .gallery-grid.is-hover-scroll .gallery-media img { object-fit: cover; object-position: 50% 0; }
        .gallery-grid.is-hover-scroll .gallery-item:hover img { transform: none; }
        @media (hover: hover) and (pointer: fine) {
            .gallery-grid.is-hover-scroll .gallery-media img { transition: object-position 0.6s ease-out; }
            .gallery-grid.is-hover-scroll .gallery-item:hover img,
            .gallery-grid.is-hover-scroll .gallery-item:focus-within img {
                object-position: 50% 100%;
                transition: object-position var(--ux-scroll-dur, 6s) linear;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .gallery-grid.is-hover-scroll .gallery-media img { transition: none !important; object-position: 50% 0 !important; }
        }
        </style>
        
        <?php
    }
}
