<?php
/**
 * Logo Grid Widget
 * Display client/brand logos in a responsive grid.
 */

if (!defined('ABSPATH')) exit;

class UX_Logo_Grid_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-logo-grid'; }
    public function get_title() { return __('Logo Grid', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-gallery-grid'; }

    protected function register_controls() {

        $this->add_standard_header_section();

        // Content
        $this->start_controls_section('logos_section', [
            'label' => __('Logos', 'ux-portfolio-optimized'),
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('image', [
            'label' => __('Logo', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
        ]);

        $repeater->add_control('name', [
            'label' => __('Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Used as the image alt text and accessible label if no Alt Text Override is set.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('alt_override', [
            'label' => __('Alt Text Override', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Overrides the auto-derived alt text from Name.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('link', [
            'label' => __('Link', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => __('Optional URL', 'ux-portfolio-optimized'),
            'description' => __('Manual URL. Leave blank if using "Linked Post" below.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('linked_post', [
            'label' => __('Linked Post/Page', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => $this->get_linkable_posts(),
            'default' => '',
            'description' => __('Pick an internal post or page. Overrides the manual Link above if set. URL updates automatically if the slug changes.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('logos', [
            'label' => __('Logos', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'name' => 'Brand 1'],
                ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'name' => 'Brand 2'],
                ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'name' => 'Brand 3'],
                ['image' => ['url' => \Elementor\Utils::get_placeholder_image_src()], 'name' => 'Brand 4'],
            ],
            'title_field' => '{{{ name || "Logo" }}}',
        ]);

        $this->end_controls_section();

        // STYLE — Layout
        $this->start_controls_section('layout_section', [
            'label' => __('Layout', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_description_alignment();

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
            'default' => '4',
            'tablet_default' => '3',
            'mobile_default' => '2',
            'selectors' => [
                '{{WRAPPER}} .logo-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ]);

        $this->add_responsive_control('gap', [
            'label' => __('Gap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 20],
            'selectors' => ['{{WRAPPER}} .logo-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('logo_align', [
            'label' => __('Logo Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-h-align-left'],
                'center'     => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-h-align-center'],
                'flex-end'   => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-h-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .logo-item' => 'justify-content: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // STYLE — Logos
        $this->start_controls_section('logo_style_section', [
            'label' => __('Logos', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('logo_max_height', [
            'label' => __('Max Height', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 30, 'max' => 200]],
            'default' => ['size' => 80],
            'selectors' => [
                '{{WRAPPER}} .logo-item img' => 'max-height: {{SIZE}}{{UNIT}};',
            ],
            'description' => __('Keeps logos at a consistent size even when source images vary.', 'ux-portfolio-optimized'),
        ]);

        $this->add_responsive_control('logo_max_width', [
            'label' => __('Max Width', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 50, 'max' => 400], '%' => ['min' => 10, 'max' => 100]],
            'size_units' => ['px', '%'],
            'default' => ['size' => 100, 'unit' => '%'],
            'selectors' => [
                '{{WRAPPER}} .logo-item img' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('grayscale', [
            'label' => __('Grayscale Logos', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('When on, logos render desaturated and gain full color on hover.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('grayscale_opacity', [
            'label' => __('Default Opacity', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 20, 'max' => 100, 'step' => 5]],
            'default' => ['size' => 70],
            'selectors' => [
                '{{WRAPPER}} .logo-grid.is-grayscale .logo-item img' => 'opacity: calc({{SIZE}} / 100);',
            ],
            'condition' => ['grayscale' => 'yes'],
        ]);

        $this->add_control('hover_lift', [
            'label' => __('Lift on Hover', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Logo nudges up slightly when hovered.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_title', [
            'label' => __('Show Title', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Displays each logo\'s Name as a visible title. Logos without a Name are skipped.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('title_position', [
            'label' => __('Title Position', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'below' => __('Below Logo', 'ux-portfolio-optimized'),
                'above' => __('Above Logo', 'ux-portfolio-optimized'),
            ],
            'default' => 'below',
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->end_controls_section();

        // STYLE — Title
        $this->start_controls_section('title_style_section', [
            'label' => __('Title', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_title' => 'yes'],
        ]);

        $this->add_control('title_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .logo-item__title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .logo-item__title',
        ]);

        $this->add_responsive_control('title_gap', [
            'label' => __('Gap from Logo', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 8],
            'selectors' => ['{{WRAPPER}} .logo-item' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('title_align', [
            'label' => __('Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left'   => ['title' => __('Left', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-center'],
                'right'  => ['title' => __('Right', 'ux-portfolio-optimized'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .logo-item__title' => 'text-align: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // STYLE — Card (optional background)
        $this->start_controls_section('card_section', [
            'label' => __('Card', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('show_card', [
            'label' => __('Show Card', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Wraps each logo in a card with background, border, and padding.', 'ux-portfolio-optimized'),
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20, 'unit' => 'px', 'isLinked' => true],
            'selectors' => [
                '{{WRAPPER}} .logo-grid.has-cards .logo-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => ['show_card' => 'yes'],
        ]);

        $this->add_control('card_bg', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .logo-grid.has-cards .logo-item' => 'background: {{VALUE}};'],
            'condition' => ['show_card' => 'yes'],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 8],
            'selectors' => ['{{WRAPPER}} .logo-grid.has-cards .logo-item' => 'border-radius: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_card' => 'yes'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .logo-grid.has-cards .logo-item',
            'condition' => ['show_card' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    /**
     * Build a dropdown of linkable posts/pages for the SELECT2 control.
     * Returns an associative array: post_id => "Title (post_type)".
     */
    private function get_linkable_posts() {
        $options = ['' => __('— None —', 'ux-portfolio-optimized')];

        $post_types = get_post_types(['public' => true], 'objects');
        unset($post_types['attachment']);

        foreach ($post_types as $pt) {
            $posts = get_posts([
                'post_type'      => $pt->name,
                'posts_per_page' => 100,
                'post_status'    => 'publish',
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]);
            foreach ($posts as $post) {
                $options[$post->ID] = sprintf('%s (%s)', $post->post_title ?: __('(no title)', 'ux-portfolio-optimized'), $pt->labels->singular_name);
            }
        }

        return $options;
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        if (empty($s['logos'])) return;

        $show_title = ($s['show_title'] ?? '') === 'yes';
        $title_position = $s['title_position'] ?? 'below';

        $grid_classes = ['logo-grid'];
        if (($s['show_card'] ?? '') === 'yes') $grid_classes[] = 'has-cards';
        if (($s['grayscale'] ?? '') === 'yes') $grid_classes[] = 'is-grayscale';
        if (($s['hover_lift'] ?? 'yes') === 'yes') $grid_classes[] = 'has-hover-lift';
        if ($show_title) $grid_classes[] = 'has-titles title-' . $title_position;
        ?>

        <div class="logo-grid-wrapper">
            <?php $this->render_standard_header(); ?>

            <div class="<?php echo esc_attr(implode(' ', $grid_classes)); ?>">
                <?php foreach ($s['logos'] as $logo) :
                    // Get URL from either direct URL or by looking up the attachment ID.
                    // Elementor stores SVGs and some other media types with just an ID.
                    $img_url = $logo['image']['url'] ?? '';
                    if (empty($img_url) && !empty($logo['image']['id'])) {
                        $img_url = wp_get_attachment_url($logo['image']['id']);
                    }
                    if (empty($img_url)) continue;

                    $name = trim($logo['name'] ?? '');

                    // Alt text: override > name > empty
                    $alt = trim($logo['alt_override'] ?? '');
                    if ($alt === '') {
                        $alt = $name !== '' ? $name : '';
                    }

                    $link_url = '';
                    $link_external = false;
                    $link_nofollow = false;

                    // Linked post wins (internal, dynamic URL via get_permalink)
                    if (!empty($logo['linked_post'])) {
                        $permalink = get_permalink((int) $logo['linked_post']);
                        if ($permalink) {
                            $link_url = $permalink;
                        }
                    }

                    // Fall back to manual link if no linked post
                    if (empty($link_url)) {
                        $link_url = $logo['link']['url'] ?? '';
                        $link_external = !empty($logo['link']['is_external']);
                        $link_nofollow = !empty($logo['link']['nofollow']);
                    }

                    // Build image markup once
                    ob_start();
                    if (!empty($link_url)) {
                        $target = $link_external ? ' target="_blank"' : '';
                        $rel_parts = [];
                        if ($link_external) $rel_parts[] = 'noopener';
                        if ($link_nofollow) $rel_parts[] = 'nofollow';
                        $rel = $rel_parts ? ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"' : '';
                        echo '<a href="' . esc_url($link_url) . '"' . $target . $rel . '>';
                        echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($alt) . '" loading="lazy">';
                        echo '</a>';
                    } else {
                        echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($alt) . '" loading="lazy">';
                    }
                    $img_markup = ob_get_clean();

                    $has_title = $show_title && $name !== '';
                    ?>
                    <div class="logo-item">
                        <?php if ($has_title && $title_position === 'above') : ?>
                            <span class="logo-item__title"><?php echo esc_html($name); ?></span>
                        <?php endif; ?>

                        <div class="logo-item__image"><?php echo $img_markup; ?></div>

                        <?php if ($has_title && $title_position === 'below') : ?>
                            <span class="logo-item__title"><?php echo esc_html($name); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
    }
}
