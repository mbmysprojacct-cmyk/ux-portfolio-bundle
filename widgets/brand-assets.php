<?php
/**
 * Brand Assets Widget - Optimized
 * Display brand colors, fonts, logos
 */

if (!defined('ABSPATH')) exit;

class UX_Brand_Assets_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-brand-assets'; }
    public function get_title() { return __('Brand Assets', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-info-box'; }
    
    protected function register_controls() {
        
        $this->add_standard_header_section();
        
        // Logos
        $this->start_controls_section('logos_section', [
            'label' => __('Brand Logos', 'ux-portfolio-optimized')
        ]);

        $this->add_control('logos_label', [
            'label' => __('Section Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Brand Logos', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('logos_note', [
            'label' => __('Set Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'placeholder' => __('e.g. Decorative at large sizes, clear at small ones', 'ux-portfolio-optimized'),
            'description' => __('Optional. One line on the rule or reasoning behind this set, shown under the label.', 'ux-portfolio-optimized'),
        ]);
        
        $this->add_control('show_logos', [
            'label' => __('Show Logos Section', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Turn off to hide this whole section, even if it has logos.', 'ux-portfolio-optimized'),
        ]);

        $logo_repeater = new \Elementor\Repeater();

        $logo_repeater->add_control('direction', [
            'label' => __('Direction', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. Direction 1', 'ux-portfolio-optimized'),
            'description' => __('Items sharing a direction appear together under that tab. Only used when Directions are enabled below.', 'ux-portfolio-optimized'),
        ]);

$logo_repeater->add_control('image', [
            'label' => __('Logo Image', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'description' => __('PNG or SVG recommended. SVG scales best.', 'ux-portfolio-optimized'),
        ]);
        
        $logo_repeater->add_control('name', [
            'label' => __('Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Primary Logo',
            'description' => __('E.g. "Primary Logo", "Wordmark", "Mark", "Inverted".', 'ux-portfolio-optimized'),
        ]);
        
        $logo_repeater->add_control('usage', [
            'label' => __('Usage Notes', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'description' => __('Optional. E.g. "Use on light backgrounds. Min width 80px."', 'ux-portfolio-optimized'),
        ]);
        
        $logo_repeater->add_control('background', [
            'label' => __('Tile Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'light'       => __('Light (default)', 'ux-portfolio-optimized'),
                'dark'        => __('Dark', 'ux-portfolio-optimized'),
                'transparent' => __('Transparent', 'ux-portfolio-optimized'),
                'custom'      => __('Custom color', 'ux-portfolio-optimized'),
            ],
            'default' => 'light',
        ]);
        
        $logo_repeater->add_control('background_custom', [
            'label' => __('Custom Background Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'condition' => ['background' => 'custom'],
        ]);
        
        $logo_repeater->add_control('svg_markup', [
            'label' => __('SVG Markup (alternative)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 6,
            'placeholder' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">...</svg>',
            'description' => __('Optional. Paste SVG markup directly here. Useful when your site doesn\'t allow SVG file uploads. When filled, this takes priority over the Logo Image above. Uploaded SVG files (above) are also auto-inlined when detected.', 'ux-portfolio-optimized'),
        ]);
        
        $this->add_control('logos', [
            'label' => __('Logos', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $logo_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ name }}}',
        ]);
        
        $this->end_controls_section();
        
        // Colors
        $this->start_controls_section('colors_section', [
            'label' => __('Brand Colors', 'ux-portfolio-optimized')
        ]);

        $this->add_control('colors_label', [
            'label' => __('Section Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Brand Colors', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('colors_note', [
            'label' => __('Set Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'placeholder' => __('e.g. Every text pairing passes WCAG AA', 'ux-portfolio-optimized'),
            'description' => __('Optional. One line on the rule or reasoning behind this set, shown under the label.', 'ux-portfolio-optimized'),
        ]);
        
        $this->add_control('show_colors', [
            'label' => __('Show Colors Section', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Turn off to hide this whole section, even if it has colors.', 'ux-portfolio-optimized'),
        ]);

        $color_repeater = new \Elementor\Repeater();

        $color_repeater->add_control('direction', [
            'label' => __('Direction', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. Direction 1', 'ux-portfolio-optimized'),
            'description' => __('Items sharing a direction appear together under that tab. Only used when Directions are enabled below.', 'ux-portfolio-optimized'),
        ]);

$color_repeater->add_control('color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#3b82f6',
        ]);
        
        $color_repeater->add_control('name', [
            'label' => __('Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Primary',
        ]);
        
        $color_repeater->add_control('hex', [
            'label' => __('Hex Code (optional)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);

        $color_repeater->add_control('usage', [
            'label' => __('Usage Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'description' => __('Optional. Where this color is used, or why it is in the palette.', 'ux-portfolio-optimized'),
        ]);
        
        $this->add_control('colors', [
            'label' => __('Colors', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $color_repeater->get_controls(),
            'default' => [
                ['color' => '#3b82f6', 'name' => 'Primary'],
                ['color' => '#10b981', 'name' => 'Secondary'],
            ],
            'title_field' => '{{{ name }}}',
        ]);
        
        $this->end_controls_section();
        
        // Fonts
        $this->start_controls_section('fonts_section', [
            'label' => __('Brand Fonts', 'ux-portfolio-optimized')
        ]);

        $this->add_control('fonts_label', [
            'label' => __('Section Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Typography', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('fonts_note', [
            'label' => __('Set Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'placeholder' => __('e.g. One job per typeface', 'ux-portfolio-optimized'),
            'description' => __('Optional. One line on the rule or reasoning behind this set, shown under the label.', 'ux-portfolio-optimized'),
        ]);
        
        $this->add_control('show_fonts', [
            'label' => __('Show Fonts Section', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Turn off to hide this whole section, even if it has fonts.', 'ux-portfolio-optimized'),
        ]);

        $font_repeater = new \Elementor\Repeater();

        $font_repeater->add_control('direction', [
            'label' => __('Direction', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('e.g. Direction 1', 'ux-portfolio-optimized'),
            'description' => __('Items sharing a direction appear together under that tab. Only used when Directions are enabled below.', 'ux-portfolio-optimized'),
        ]);

$font_repeater->add_control('name', [
            'label' => __('Font Family', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::FONT,
            'default' => 'Inter',
            'description' => __('Pick a font. Google Fonts and Elementor Pro Custom Fonts auto-load on the page. System fonts work natively. For fonts loaded via theme or custom CSS only, use the Custom Override below.', 'ux-portfolio-optimized'),
        ]);

        $font_repeater->add_control('name_custom', [
            'label' => __('Custom Override', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'Delirium, sans-serif',
            'description' => __('Optional. If your font isn\'t in the dropdown but is loaded via your theme/plugin/CSS, type its name here. This overrides the picker for both display and font-family.', 'ux-portfolio-optimized'),
        ]);

        $font_repeater->add_control('display_label', [
            'label' => __('Display Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'label_block' => true,
            'placeholder' => __('Leave blank to show the font name', 'ux-portfolio-optimized'),
            'description' => __('Optional. Overrides the shown name — e.g. show "Support / Body" instead of "LucindasSupport". Does not change which font is used.', 'ux-portfolio-optimized'),
        ]);

        $font_repeater->add_control('name_size', [
            'label' => __('Name Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', '%', 'vw', 'vh'],
            'range' => [
                'px' => ['min' => 16, 'max' => 120],
                'em' => ['min' => 0.5, 'max' => 8, 'step' => 0.05],
                'rem' => ['min' => 0.5, 'max' => 8, 'step' => 0.05],
                '%' => ['min' => 50, 'max' => 400],
                'vw' => ['min' => 1, 'max' => 15, 'step' => 0.1],
                'vh' => ['min' => 1, 'max' => 15, 'step' => 0.1],
            ],
            'default' => ['unit' => 'px', 'size' => 24],
        ]);
        
        $font_repeater->add_control('usage', [
            'label' => __('Usage', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Headings',
        ]);

        $font_repeater->add_control('show_specimen', [
            'label' => __('Show Type Specimen', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Show sample text set in this font, below the name.', 'ux-portfolio-optimized'),
        ]);

        $font_repeater->add_control('specimen_text', [
            'label' => __('Specimen Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('The quick brown fox jumps over the lazy dog', 'ux-portfolio-optimized'),
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_size', [
            'label' => __('Specimen Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', '%', 'vw', 'vh'],
            'range' => [
                'px' => ['min' => 16, 'max' => 160],
                'em' => ['min' => 0.5, 'max' => 10, 'step' => 0.05],
                'rem' => ['min' => 0.5, 'max' => 10, 'step' => 0.05],
                '%' => ['min' => 50, 'max' => 600],
                'vw' => ['min' => 1, 'max' => 20, 'step' => 0.1],
                'vh' => ['min' => 1, 'max' => 20, 'step' => 0.1],
            ],
            'default' => ['unit' => 'px', 'size' => 36],
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_weight', [
            'label' => __('Specimen Weight', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '400',
            'options' => [
                '300' => __('Light (300)', 'ux-portfolio-optimized'),
                '400' => __('Regular (400)', 'ux-portfolio-optimized'),
                '500' => __('Medium (500)', 'ux-portfolio-optimized'),
                '600' => __('Semibold (600)', 'ux-portfolio-optimized'),
                '700' => __('Bold (700)', 'ux-portfolio-optimized'),
            ],
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('show_glyphs', [
            'label' => __('Show Aa / Alphabet Row', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'condition' => ['show_specimen' => 'yes'],
            'description' => __('Adds an uppercase + lowercase + numerals line above the sample.', 'ux-portfolio-optimized'),
        ]);

        $font_repeater->add_control('glyph_size', [
            'label' => __('Alphabet Row Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', '%', 'vw', 'vh'],
            'range' => [
                'px' => ['min' => 12, 'max' => 80],
                'em' => ['min' => 0.5, 'max' => 6, 'step' => 0.05],
                'rem' => ['min' => 0.5, 'max' => 6, 'step' => 0.05],
                '%' => ['min' => 50, 'max' => 400],
                'vw' => ['min' => 1, 'max' => 12, 'step' => 0.1],
                'vh' => ['min' => 1, 'max' => 12, 'step' => 0.1],
            ],
            'default' => ['unit' => 'px', 'size' => 18],
            'condition' => ['show_specimen' => 'yes', 'show_glyphs' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_line_height', [
            'label' => __('Line Height', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['em' => ['min' => 0.8, 'max' => 3, 'step' => 0.05]],
            'default' => ['unit' => 'em', 'size' => 1.3],
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_letter_spacing', [
            'label' => __('Letter Spacing', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => -3, 'max' => 15, 'step' => 0.1],
                'em' => ['min' => -0.2, 'max' => 1, 'step' => 0.01],
            ],
            'default' => ['unit' => 'px', 'size' => 0],
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_transform', [
            'label' => __('Text Transform', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none'       => __('Default', 'ux-portfolio-optimized'),
                'uppercase'  => __('UPPERCASE', 'ux-portfolio-optimized'),
                'lowercase'  => __('lowercase', 'ux-portfolio-optimized'),
                'capitalize' => __('Capitalize', 'ux-portfolio-optimized'),
            ],
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_style', [
            'label' => __('Font Style', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'normal',
            'options' => [
                'normal'  => __('Normal', 'ux-portfolio-optimized'),
                'italic'  => __('Italic', 'ux-portfolio-optimized'),
            ],
            'condition' => ['show_specimen' => 'yes'],
        ]);

        $font_repeater->add_control('specimen_color', [
            'label' => __('Sample Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'condition' => ['show_specimen' => 'yes'],
            'description' => __('Optional. Defaults to the dark brand text color.', 'ux-portfolio-optimized'),
        ]);
        
        $this->add_control('fonts', [
            'label' => __('Fonts', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $font_repeater->get_controls(),
            'default' => [
                ['name' => 'Inter', 'usage' => 'Headings'],
            ],
            'title_field' => '{{{ name }}}',
        ]);
        
        $this->add_control('font_css_urls', [
            'label' => __('Font Stylesheets', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 3,
            'placeholder' => 'https://use.typekit.net/abc1234.css',
            'description' => __('Optional. One URL per line, for fonts not in the picker. Only Adobe Fonts (use.typekit.net) and Google Fonts (fonts.googleapis.com) URLs are loaded. Adobe kits must list this site’s domain.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();
        
        // ICONS: a uniform icon set (menu, web, wayfinding), shown at one size.
        $this->start_controls_section('icons_section', [
            'label' => __('Icons', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_icons', [
            'label' => __('Show Icons', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('icons_title', [
            'label' => __('Section Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Icon Set', 'ux-portfolio-optimized'),
            'condition' => ['show_icons' => 'yes'],
        ]);

        $this->add_control('icons_note', [
            'label' => __('Set Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'placeholder' => __('e.g. 2px line, round caps, 24px grid', 'ux-portfolio-optimized'),
            'description' => __('Optional. One line describing the set’s rules, shown under the label.', 'ux-portfolio-optimized'),
            'condition' => ['show_icons' => 'yes'],
        ]);

        $icon_repeater = new \Elementor\Repeater();

        $icon_repeater->add_control('name', [
            'label' => __('Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);

        $icon_repeater->add_control('image', [
            'label' => __('Icon', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'media_types' => ['image', 'svg'],
        ]);

        $this->add_control('icons', [
            'label' => __('Icons', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $icon_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ name }}}',
            'condition' => ['show_icons' => 'yes'],
        ]);

        $this->add_control('show_icon_names', [
            'label' => __('Show Names', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => ['show_icons' => 'yes'],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => __('Icon Size', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 16, 'max' => 120]],
            'default' => ['unit' => 'px', 'size' => 48],
            'selectors' => ['{{WRAPPER}} .brand-icon-glyph' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
            'condition' => ['show_icons' => 'yes'],
        ]);

        $this->add_responsive_control('icon_columns', [
            'label' => __('Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['4' => '4', '5' => '5', '6' => '6', '8' => '8', '10' => '10'],
            'default' => '8',
            'tablet_default' => '6',
            'mobile_default' => '4',
            'selectors' => ['{{WRAPPER}} .brand-icon-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));'],
            'condition' => ['show_icons' => 'yes'],
        ]);

        $this->end_controls_section();
        // SUPPORTING GRAPHICS: stickers, icons, patterns and other brand illustrations,
        // grouped under their own sub-headings.
        $this->start_controls_section('graphics_section', [
            'label' => __('Supporting Graphics', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_graphics', [
            'label' => __('Show Supporting Graphics', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('graphics_title', [
            'label' => __('Section Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Supporting Graphics', 'ux-portfolio-optimized'),
            'condition' => ['show_graphics' => 'yes'],
        ]);

        $this->add_control('graphics_note', [
            'label' => __('Set Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'description' => __('Optional. One line on the rule or reasoning behind this set, shown under the label.', 'ux-portfolio-optimized'),
            'condition' => ['show_graphics' => 'yes'],
        ]);

        $graphics_repeater = new \Elementor\Repeater();

        $graphics_repeater->add_control('group', [
            'label' => __('Group', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Stickers', 'ux-portfolio-optimized'),
            'description' => __('Items with the same group name appear together, e.g. Stickers, Icons, Patterns.', 'ux-portfolio-optimized'),
        ]);

        $graphics_repeater->add_control('group_note', [
            'label' => __('Group Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
            'description' => __('Optional. Shown once under the group name. Fill it on any one item in the group.', 'ux-portfolio-optimized'),
        ]);

        $graphics_repeater->add_control('name', [
            'label' => __('Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);

        $graphics_repeater->add_control('image', [
            'label' => __('Image', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'media_types' => ['image', 'svg'],
        ]);

        $graphics_repeater->add_control('usage', [
            'label' => __('Usage Note', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
        ]);

        $graphics_repeater->add_control('tile', [
            'label' => __('Tile', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'contain' => __('Fit inside a square tile', 'ux-portfolio-optimized'),
                'pattern' => __('Fill the tile (for patterns)', 'ux-portfolio-optimized'),
            ],
            'default' => 'contain',
        ]);

        $this->add_control('graphics', [
            'label' => __('Graphics', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $graphics_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ group }}} · {{{ name }}}',
            'condition' => ['show_graphics' => 'yes'],
        ]);

        $this->add_control('graphics_lightbox', [
            'label' => __('Open larger view on click', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_graphics' => 'yes'],
        ]);

        $this->add_responsive_control('graphics_columns', [
            'label' => __('Columns', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['3' => '3', '4' => '4', '5' => '5', '6' => '6'],
            'default' => '5',
            'tablet_default' => '4',
            'mobile_default' => '3',
            'selectors' => ['{{WRAPPER}} .brand-graphics-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));'],
            'condition' => ['show_graphics' => 'yes'],
        ]);

        $this->end_controls_section();
        // COLLAPSE: show the first N sections, the rest behind a toggle.
        $this->start_controls_section('collapse_section', [
            'label' => __('Collapse', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('collapsible', [
            'label' => __('Collapse Extra Sections', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'description' => __('Shows the first sections and hides the rest behind a toggle. Not used when Directions are on.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('visible_sections', [
            'label' => __('Sections Shown', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5,
            'step' => 1,
            'default' => 1,
            'description' => __('Counts the sections that have content, in order: Logos, Colors, Fonts, Icons, Supporting Graphics.', 'ux-portfolio-optimized'),
            'condition' => ['collapsible' => 'yes'],
        ]);

        $this->add_control('collapse_more_label', [
            'label' => __('Show More Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Show {hidden} more', 'ux-portfolio-optimized'),
            'description' => __('{hidden} = hidden sections. {names} = their labels, e.g. “Icons & Stickers”.', 'ux-portfolio-optimized'),
            'condition' => ['collapsible' => 'yes'],
        ]);

        $this->add_control('collapse_less_label', [
            'label' => __('Show Less Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Show less', 'ux-portfolio-optimized'),
            'description' => __('Same placeholders as above.', 'ux-portfolio-optimized'),
            'condition' => ['collapsible' => 'yes'],
        ]);

        $this->end_controls_section();
        // DIRECTIONS: tab between multiple brand directions. Each logo/color/font
        // is tagged with a Direction name; items sharing a name appear together.
        $this->start_controls_section('directions_section', [
            'label' => __('Brand Directions', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('enable_directions', [
            'label' => __('Enable Directions', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Show several brand directions, switchable by tabs. Give each logo, color, and font a Direction name — items sharing a name appear together under that tab.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('show_direction_tabs', [
            'label' => __('Show Tabs', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['enable_directions' => 'yes'],
            'description' => __('Turn off to stack every direction down the page instead of tabbing between them.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('default_direction', [
            'label' => __('Default Direction', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'condition' => ['enable_directions' => 'yes', 'show_direction_tabs' => 'yes'],
            'description' => __('Name of the direction to open first. Leave blank for the first one.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);        
        $this->add_description_alignment();
        
        $this->end_controls_section();
    }
    
    /** True if at least one logo row has an image or SVG markup. */
    private function has_renderable_logos($logos) {
        if (empty($logos) || !is_array($logos)) return false;
        foreach ($logos as $logo) {
            $img = !empty($logo['image']['url']) || !empty($logo['image']['id']);
            $svg = trim($logo['svg_markup'] ?? '') !== '';
            if ($img || $svg) return true;
        }
        return false;
    }

    /** True if at least one color row has a hex value. */
    private function has_renderable_colors($colors) {
        if (empty($colors) || !is_array($colors)) return false;
        foreach ($colors as $color) {
            if (!empty($color['hex'])) return true;
        }
        return false;
    }

    /** True if at least one font row has an effective name. */
    private function has_renderable_fonts($fonts) {
        if (empty($fonts) || !is_array($fonts)) return false;
        foreach ($fonts as $font) {
            $picker = trim($font['name'] ?? '');
            $custom = trim($font['name_custom'] ?? '');
            if (($custom !== '' ? $custom : $picker) !== '') return true;
        }
        return false;
    }

    /** Allowlist a CSS length unit from user input; fall back to px. */
    private function safe_unit($unit) {
        $allowed = array('px', 'em', 'rem', '%', 'vw', 'vh');
        return in_array($unit, $allowed, true) ? $unit : 'px';
    }

    /**
     * Render the three brand sections (logos, colors, fonts) for a given set of
     * items. Called once with all items when Directions are off, or once per
     * direction (with that direction's filtered items) when Directions are on.
     */
    private function render_sections($s, $logos, $colors, $fonts) {
        $widget_id = $this->get_id();
        ?>
            <?php if (($s['show_logos'] ?? 'yes') === 'yes' && $this->has_renderable_logos($logos)) :
                $widget_id = $this->get_id();
                $logo_slideshow = $widget_id . '-logos';
                ?>
                <div class="brand-section">
                    <?php $this->section_head($s, 'logos', 'Brand Logos'); ?>
                    <div class="logo-grid">
                        <?php
                        $lightbox_index = 0;
                        foreach ($logos as $logo) :
                            $img_url = $logo['image']['url'] ?? '';
                            if (empty($img_url) && !empty($logo['image']['id'])) {
                                $img_url = wp_get_attachment_url($logo['image']['id']);
                            }
                            $svg_markup_provided = trim($logo['svg_markup'] ?? '') !== '';
                            $has_image = !empty($img_url);
                            $is_svg = $this->logo_is_svg($logo);
                            // Every uploaded logo opens in the lightbox, SVGs included (v2.20.1).
                            // cs-layout.css puts SVGs on a sized panel so they read against the
                            // dark overlay; logos on a dark tile get a dark panel (?ux-on-dark=1).
                            $use_lightbox = $has_image;

                            // Skip if neither image nor pasted markup
                            if (!$has_image && !$svg_markup_provided) continue;

                            $bg = $logo['background'] ?? 'light';
                            $bg_inline = '';
                            if ($bg === 'custom' && !empty($logo['background_custom'])) {
                                $bg_inline = ' style="background: ' . esc_attr($logo['background_custom']) . '; --ux-tile-bg: ' . esc_attr($logo['background_custom']) . ';"';
                            }
                            $name = trim($logo['name'] ?? '');
                            $usage = trim($logo['usage'] ?? '');

                            $tile_classes = 'logo-image-wrap bg-' . esc_attr($bg);
                            $aria = $name !== '' ? $name : sprintf(__('Logo %d', 'ux-portfolio-optimized'), $lightbox_index + 1);
                            ?>
                            <div class="logo-item">
                                <?php if ($use_lightbox) : ?>
                                    <?php $lb_url = $img_url . (($is_svg && in_array($bg, array('dark', 'custom'), true)) ? '?ux-on-dark=1' : ''); ?>
                                    <a href="<?php echo esc_url($lb_url); ?>"
                                       class="<?php echo $tile_classes; ?>"<?php echo $bg_inline; ?>
                                       data-elementor-open-lightbox="yes"
                                       data-elementor-lightbox-title="<?php echo esc_attr($name); ?>"
                                       data-elementor-lightbox-slideshow="<?php echo esc_attr($logo_slideshow); ?>"
                                       data-elementor-lightbox-index="<?php echo esc_attr($lightbox_index); ?>"
                                       aria-label="<?php echo esc_attr($aria); ?>">
                                        <?php $this->render_logo_visual($logo); ?>
                                    </a>
                                    <?php $lightbox_index++; ?>
                                <?php else : ?>
                                    <div class="<?php echo $tile_classes; ?>"<?php echo $bg_inline; ?> aria-label="<?php echo esc_attr($aria); ?>">
                                        <?php $this->render_logo_visual($logo); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($name !== '') : ?>
                                    <div class="logo-name"><?php echo esc_html($name); ?></div>
                                <?php endif; ?>
                                <?php if ($usage !== '') : ?>
                                    <div class="logo-usage"><?php echo esc_html($usage); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (($s['show_colors'] ?? 'yes') === 'yes' && $this->has_renderable_colors($colors)) : ?>
                <div class="brand-section">
                    <?php $this->section_head($s, 'colors', 'Brand Colors'); ?>
                    <div class="color-swatches">
                        <?php foreach ($colors as $color) : ?>
                            <div class="color-swatch">
                                <div class="swatch-box" style="background: <?php echo esc_attr($color['color']); ?>;"></div>
                                <div class="swatch-name"><?php echo esc_html($color['name']); ?></div>
                                <?php if (!empty($color['hex'])) : ?>
                                    <div class="swatch-hex"><?php echo esc_html($color['hex']); ?></div>
                                <?php else : ?>
                                    <div class="swatch-hex"><?php echo esc_html($color['color']); ?></div>
                                <?php endif; ?>
                                <?php if (trim((string) ($color['usage'] ?? '')) !== '') : ?>
                                    <div class="swatch-usage"><?php echo esc_html($color['usage']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (($s['show_fonts'] ?? 'yes') === 'yes' && $this->has_renderable_fonts($fonts)) : ?>
                <div class="brand-section">
                    <?php $this->section_head($s, 'fonts', 'Typography'); ?>
                    <div class="font-list">
                        <?php foreach ($fonts as $font) :
                            $picker_name = trim($font['name'] ?? '');
                            $custom_name = trim($font['name_custom'] ?? '');
                            $effective_name = $custom_name !== '' ? $custom_name : $picker_name;
                            if ($effective_name === '') continue;
                            // Fonts are enqueued up front in render() (see top); nothing to do here.
                            $font_style = ' style="font-family: ' . esc_attr($effective_name) . ';"';
                            ?>
                            <div class="font-item<?php echo (($font['show_specimen'] ?? '') === 'yes') ? ' has-specimen' : ''; ?>">
                                <?php
                                $display_label = trim($font['display_label'] ?? '');
                                $shown_name = $display_label !== '' ? $display_label : $effective_name;
                                $name_size = isset($font['name_size']['size']) ? floatval($font['name_size']['size']) : 24;
                                $name_unit = $this->safe_unit($font['name_size']['unit'] ?? 'px');
                                // The name always renders in the actual font; label only changes the text.
                                $name_style = 'font-family: ' . esc_attr($effective_name) . '; font-size: ' . esc_attr($name_size) . esc_attr($name_unit) . ';';
                                ?>
                                <div class="font-item-head">
                                    <div class="font-name" style="<?php echo $name_style; ?>"><?php echo esc_html($shown_name); ?></div>
                                    <div class="font-usage"><?php echo esc_html($font['usage']); ?></div>
                                </div>
                                <?php if (($font['show_specimen'] ?? '') === 'yes') :
                                    $spec_text = trim($font['specimen_text'] ?? '');
                                    $spec_size = isset($font['specimen_size']['size']) ? floatval($font['specimen_size']['size']) : 36;
                                    $spec_size_unit = $this->safe_unit($font['specimen_size']['unit'] ?? 'px');
                                    $spec_weight = $font['specimen_weight'] ?? '400';
                                    $show_glyphs = ($font['show_glyphs'] ?? '') === 'yes';
                                    $spec_lh = isset($font['specimen_line_height']['size']) ? floatval($font['specimen_line_height']['size']) : 1.3;
                                    $spec_ls = isset($font['specimen_letter_spacing']['size']) ? floatval($font['specimen_letter_spacing']['size']) : 0;
                                    $spec_ls_unit = ($font['specimen_letter_spacing']['unit'] ?? 'px') === 'em' ? 'em' : 'px';
                                    $spec_transform = $font['specimen_transform'] ?? 'none';
                                    $spec_fstyle = $font['specimen_style'] ?? 'normal';
                                    $spec_color = trim($font['specimen_color'] ?? '');
                                    $glyph_size = isset($font['glyph_size']['size']) ? floatval($font['glyph_size']['size']) : 18;
                                    $glyph_unit = $this->safe_unit($font['glyph_size']['unit'] ?? 'px');
                                    // Sample text: full styling (weight, style, spacing, transform).
                                    $spec_base = 'font-family: ' . esc_attr($effective_name) . ';'
                                        . ' font-weight: ' . esc_attr($spec_weight) . ';'
                                        . ' font-style: ' . esc_attr($spec_fstyle) . ';'
                                        . ' letter-spacing: ' . esc_attr($spec_ls) . esc_attr($spec_ls_unit) . ';'
                                        . ' text-transform: ' . esc_attr($spec_transform) . ';';
                                    $sample_style = $spec_base
                                        . ' font-size: ' . esc_attr($spec_size) . esc_attr($spec_size_unit) . ';'
                                        . ' line-height: ' . esc_attr($spec_lh) . 'em;'
                                        . ($spec_color !== '' ? ' color: ' . esc_attr($spec_color) . ';' : '');
                                    // Alphabet row: just family + its own size. No transform/letter-spacing
                                    // (those cramp or alter the reference glyph line).
                                    $glyph_style = 'font-family: ' . esc_attr($effective_name) . ';'
                                        . ' font-weight: ' . esc_attr($spec_weight) . ';'
                                        . ' font-size: ' . esc_attr($glyph_size) . esc_attr($glyph_unit) . ';';
                                    ?>
                                    <div class="font-specimen">
                                        <?php if ($show_glyphs) : ?>
                                            <div class="font-glyphs" style="<?php echo $glyph_style; ?>">ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>abcdefghijklmnopqrstuvwxyz<br>0123456789</div>
                                        <?php endif; ?>
                                        <?php if ($spec_text !== '') : ?>
                                            <div class="font-sample" style="<?php echo $sample_style; ?>"><?php echo esc_html($spec_text); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        // Enqueue any picker-selected fonts as early as possible in render — before
        // any markup is echoed — so Elementor prints the font <link>/@font-face for
        // Google Fonts and Pro Custom Fonts. Doing this up front (not mid-markup)
        // is more reliable on the front end, where a late enqueue can miss the
        // window Elementor uses to output fonts. Custom-override fonts are assumed
        // to be loaded by the user's own setup, so they're skipped.
        foreach (preg_split('/\s+/', (string) ($s['font_css_urls'] ?? ''), -1, PREG_SPLIT_NO_EMPTY) as $css_url) {
            $css_url = esc_url_raw($css_url, ['https']);
            $host = (string) wp_parse_url($css_url, PHP_URL_HOST);
            if ($css_url !== '' && in_array($host, ['use.typekit.net', 'fonts.googleapis.com'], true)) {
                wp_enqueue_style('ux-ba-fonts-' . md5($css_url), $css_url, [], null);
            }
        }
        if (($s['show_fonts'] ?? 'yes') === 'yes' && !empty($s['fonts']) && is_array($s['fonts'])
            && class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::instance()->frontend)) {
            foreach ($s['fonts'] as $font) {
                $picker_name = trim($font['name'] ?? '');
                $custom_name = trim($font['name_custom'] ?? '');
                if ($custom_name === '' && $picker_name !== '') {
                    \Elementor\Plugin::instance()->frontend->enqueue_font($picker_name);
                }
            }
        }
        ?>
        
        <div class="brand-assets-wrapper">
            <?php $this->render_standard_header(); ?>
            
            <?php
            $all_logos  = (!empty($s['logos'])  && is_array($s['logos']))  ? $s['logos']  : array();
            $all_colors = (!empty($s['colors']) && is_array($s['colors'])) ? $s['colors'] : array();
            $all_fonts  = (!empty($s['fonts'])  && is_array($s['fonts']))  ? $s['fonts']  : array();

            $use_directions = ($s['enable_directions'] ?? '') === 'yes';

            if (!$use_directions) {
                // Single set — original behavior.
                ob_start();
                $this->render_sections($s, $all_logos, $all_colors, $all_fonts);
                $this->render_icons($s);
                $this->render_graphics($s);
                echo $this->collapse_sections((string) ob_get_clean(), $s); // markup built and escaped above
            } else {
                // Bucket each item type by its Direction name, preserving first-seen
                // order. Two passes: (1) collect the NAMED directions across all item
                // types so tabs come only from names you actually typed; (2) place each
                // item, sending untagged items to the first named direction rather than
                // spawning a phantom "Direction 1" tab.
                $dir_order = array();
                $collect_names = function($items) use (&$dir_order) {
                    foreach ($items as $it) {
                        $d = trim($it['direction'] ?? '');
                        if ($d !== '' && !in_array($d, $dir_order, true)) $dir_order[] = $d;
                    }
                };
                $collect_names($all_logos);
                $collect_names($all_colors);
                $collect_names($all_fonts);

                // If nothing was named at all, fall back to a single default bucket.
                if (empty($dir_order)) $dir_order[] = __('Direction 1', 'ux-portfolio-optimized');

                // Untagged items go to the first (named) direction.
                $fallback_dir = $dir_order[0];

                $logos_by = array(); $colors_by = array(); $fonts_by = array();
                $bucket = function($items, &$by) use ($fallback_dir) {
                    foreach ($items as $it) {
                        $d = trim($it['direction'] ?? '');
                        if ($d === '') $d = $fallback_dir;
                        if (!isset($by[$d])) $by[$d] = array();
                        $by[$d][] = $it;
                    }
                };
                $bucket($all_logos, $logos_by);
                $bucket($all_colors, $colors_by);
                $bucket($all_fonts, $fonts_by);

                $show_tabs = ($s['show_direction_tabs'] ?? 'yes') === 'yes' && count($dir_order) > 1;
                $default = trim($s['default_direction'] ?? '');
                $active = ($default !== '' && in_array($default, $dir_order, true)) ? $default : $dir_order[0];
                $wid = esc_attr($this->get_id());
                ?>
                <div class="brand-directions" data-ux-brandtabs data-widget-id="<?php echo $wid; ?>">
                    <?php if ($show_tabs) : ?>
                        <div class="ux-tabs" role="tablist">
                            <?php foreach ($dir_order as $di => $dname) : ?>
                                <button type="button" class="ux-tabs__tab<?php echo ($dname === $active) ? ' is-active' : ''; ?>" role="tab" data-bd-tab="<?php echo esc_attr($di); ?>"><?php echo esc_html($dname); ?></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($dir_order as $di => $dname) :
                        $hidden = ($show_tabs && $dname !== $active) ? ' is-hidden' : '';
                        ?>
                        <div class="brand-direction-panel<?php echo $hidden; ?>" data-bd-panel="<?php echo esc_attr($di); ?>">
                            <?php if (!$show_tabs && count($dir_order) > 1) : ?>
                                <h3 class="brand-direction-name"><?php echo esc_html($dname); ?></h3>
                            <?php endif; ?>
                            <?php
                            $this->render_sections(
                                $s,
                                isset($logos_by[$dname]) ? $logos_by[$dname] : array(),
                                isset($colors_by[$dname]) ? $colors_by[$dname] : array(),
                                isset($fonts_by[$dname]) ? $fonts_by[$dname] : array()
                            );
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
            }
            if ($use_directions) {
                $this->render_icons($s);
                $this->render_graphics($s);
            }
            ?>
        </div>
        
        <style>
        .swatch-usage { margin-top: 4px; font-size: 12px; line-height: 1.35; color: #666; }
        .ba-collapse [hidden] { display: none !important; }
        .ba-toggle { margin-top: 16px; background: none; border: 0; padding: 4px 0; font: inherit; font-size: 14px; text-decoration: underline; text-underline-offset: 3px; cursor: pointer; color: inherit; }
        .brand-section-note { margin: -4px 0 12px; font-size: 13px; line-height: 1.45; color: #666; }
        .brand-icon-grid { display: grid; grid-template-columns: repeat(8, minmax(0, 1fr)); gap: 12px; }
        .brand-icon { margin: 0; min-width: 0; display: flex; flex-direction: column; align-items: center; gap: 6px; text-align: center; }
        .brand-icon-glyph { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }
        .brand-icon-glyph img { max-width: 100%; max-height: 100%; width: auto; height: auto; display: block; }
        .brand-icon figcaption { font-size: 12px; line-height: 1.3; word-break: break-word; }
        .brand-graphics-group + .brand-graphics-group { margin-top: 18px; }
        .brand-graphics-group-name { font-size: 13px; font-weight: 600; margin: 0 0 8px; }
        .brand-graphics-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 12px; }
        .brand-graphic { margin: 0; min-width: 0; }
        .brand-graphic-tile { aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; padding: 12px; background: #f8f9fa; border-radius: 8px; overflow: hidden; }
        .brand-graphic-tile img { max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; display: block; }
        .brand-graphic-tile.is-pattern { padding: 0; }
        .brand-graphic-tile.is-pattern img { width: 100%; height: 100%; max-width: none; max-height: none; object-fit: cover; }
        .brand-graphic figcaption { margin-top: 6px; font-size: 13px; line-height: 1.35; }
        .brand-graphic-usage { display: block; font-size: 12px; color: #666; }

        .brand-section {
            margin-bottom: 40px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .color-swatches {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }
        .color-swatch {
            text-align: center;
        }
        .swatch-box {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
        }
        .swatch-name {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .swatch-hex {
            font-size: 13px;
            color: #666;
            font-family: monospace;
        }
        .font-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .font-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .font-item.has-specimen {
            display: block;
        }
        .font-item.has-specimen .font-item-head {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 14px;
        }
        .font-name {
            font-size: 24px;
            font-weight: normal;
        }
        .font-usage {
            color: #666;
            font-size: 14px;
        }
        .font-specimen {
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
        }
        .font-glyphs {
            line-height: 1.7;
            color: #6b7280;
            margin-bottom: 12px;
            word-break: break-all;
        }
        .font-sample {
            color: #1a1a1a;
            line-height: 1.3;
            word-break: break-word;
        }
        .logo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
        }
        .logo-item {
            display: flex;
            flex-direction: column;
        }
        .logo-image-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 16 / 10;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 12px;
            text-decoration: none;
            border: 1px solid #e0e0e0;
            line-height: 0;
        }
        a.logo-image-wrap { cursor: zoom-in; }
        .logo-image-wrap.bg-light       { background: #f8f9fa; }
        .logo-image-wrap.bg-dark        { background: #1a1a1a; border-color: #333; }
        .logo-image-wrap.bg-transparent { background: transparent; border-style: dashed; }
        .logo-image-wrap img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .logo-image-wrap svg {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            display: block;
        }
        .logo-name {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .logo-usage {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
        }
        </style>
        
        <?php
    }

    /** Section label (editable, falls back to the default) plus optional set note. */
    private function section_head($s, $key, $default) {
        $label = isset($s[$key . '_label']) ? trim((string) $s[$key . '_label']) : $default;
        $note = trim((string) ($s[$key . '_note'] ?? ''));
        if ($label !== '') echo '<h3 class="section-title">' . esc_html($label) . '</h3>';
        if ($note !== '') echo '<p class="brand-section-note">' . esc_html($note) . '</p>';
    }

    /**
     * Hide every top-level .brand-section after the first N and add a toggle.
     * Works on markup this widget already rendered and escaped.
     */
    private function collapse_sections($html, $s) {
        if (($s['collapsible'] ?? '') !== 'yes') return $html;
        $keep = max(1, absint($s['visible_sections'] ?? 1));
        $parts = preg_split('/(?=<div class="brand-section[ "])/', $html);
        $head = array_shift($parts);
        if (count($parts) <= $keep) return $html;
        $uid = 'ba-more-' . $this->get_id();
        $names = [];
        foreach ($parts as $i => $p) {
            if ($i < $keep) continue;
            if (preg_match('/<h3 class="section-title">(.*?)<\/h3>/s', $p, $m)) $names[] = html_entity_decode(wp_strip_all_tags($m[1]), ENT_QUOTES, 'UTF-8');
            $parts[$i] = preg_replace('/^<div class="brand-section/', '<div hidden data-ba-more class="brand-section ba-more', $p, 1);
        }
        $hidden = count($parts) - $keep;
        $names_txt = count($names) > 1 ? implode(', ', array_slice($names, 0, -1)) . ' & ' . end($names) : implode('', $names);
        $more = strtr((string) ($s['collapse_more_label'] ?? 'Show {hidden} more'), ['{hidden}' => (string) $hidden, '{names}' => $names_txt]);
        $less = strtr((string) ($s['collapse_less_label'] ?? 'Show less'), ['{hidden}' => (string) $hidden, '{names}' => $names_txt]);
        $out = $head . '<div class="ba-collapse" id="' . esc_attr($uid) . '">' . implode('', $parts) . '</div>';
        $out .= '<button type="button" class="ba-toggle" aria-expanded="false" aria-controls="' . esc_attr($uid) . '" data-more="' . esc_attr($more) . '" data-less="' . esc_attr($less) . '">' . esc_html($more) . '</button>';
        return $out;
    }
    /**
     * Icon set: one uniform size, optional names. Items without an image are skipped.
     */
    private function render_icons($s) {
        if (($s['show_icons'] ?? '') !== 'yes' || empty($s['icons']) || !is_array($s['icons'])) return;
        $items = [];
        foreach ($s['icons'] as $ic) {
            $url = $ic['image']['url'] ?? '';
            if ($url === '' && !empty($ic['image']['id'])) $url = (string) wp_get_attachment_image_url(absint($ic['image']['id']), 'thumbnail');
            if ($url !== '') $items[] = ['url' => $url, 'item' => $ic];
        }
        if (!$items) return;
        $label = trim((string) ($s['icons_title'] ?? ''));
        $note = trim((string) ($s['icons_note'] ?? ''));
        $show_names = ($s['show_icon_names'] ?? 'yes') === 'yes';
        ?>
        <div class="brand-section brand-icons">
            <?php if ($label !== '') : ?><h3 class="section-title"><?php echo esc_html($label); ?></h3><?php endif; ?>
            <?php if ($note !== '') : ?><p class="brand-section-note brand-icons-note"><?php echo esc_html($note); ?></p><?php endif; ?>
            <div class="brand-icon-grid">
                <?php foreach ($items as $it) :
                    $ic = $it['item'];
                    $name = trim((string) ($ic['name'] ?? ''));
                    $alt = trim((string) ($ic['image']['alt'] ?? ''));
                    if ($alt === '' && !empty($ic['image']['id'])) $alt = (string) get_post_meta(absint($ic['image']['id']), '_wp_attachment_image_alt', true);
                    if ($alt === '') $alt = $name;
                    ?>
                    <figure class="brand-icon">
                        <span class="brand-icon-glyph"><img src="<?php echo esc_url($it['url']); ?>" alt="<?php echo esc_attr($show_names && $name !== '' ? '' : $alt); ?>" loading="lazy" decoding="async"></span>
                        <?php if ($show_names && $name !== '') : ?><figcaption><?php echo esc_html($name); ?></figcaption><?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    /**
     * Supporting graphics (stickers, icons, patterns), grouped by their Group field
     * in first-seen order. Items without an image are skipped.
     */
    private function render_graphics($s) {
        if (($s['show_graphics'] ?? '') !== 'yes' || empty($s['graphics']) || !is_array($s['graphics'])) return;
        $groups = [];
        foreach ($s['graphics'] as $g) {
            $url = $g['image']['url'] ?? '';
            if ($url === '' && !empty($g['image']['id'])) $url = (string) wp_get_attachment_image_url(absint($g['image']['id']), 'medium');
            if ($url === '') continue;
            $key = trim((string) ($g['group'] ?? ''));
            $groups[$key][] = ['url' => $url, 'item' => $g];
        }
        if (!$groups) return;
        $lightbox = ($s['graphics_lightbox'] ?? 'yes') === 'yes';
        $slideshow = 'ba-graphics-' . $this->get_id();
        $label = trim((string) ($s['graphics_title'] ?? ''));
        ?>
        <div class="brand-section brand-graphics">
            <?php if ($label !== '') : ?><h3 class="section-title"><?php echo esc_html($label); ?></h3><?php endif; ?>
            <?php if (trim((string) ($s['graphics_note'] ?? '')) !== '') : ?><p class="brand-section-note"><?php echo esc_html($s['graphics_note']); ?></p><?php endif; ?>
            <?php foreach ($groups as $gname => $items) : ?>
                <div class="brand-graphics-group">
                    <?php if ($gname !== '' && count($groups) > 1) : ?><h4 class="brand-graphics-group-name"><?php echo esc_html($gname); ?></h4><?php endif; ?>
                    <?php $gnote = ''; foreach ($items as $gi) { $gn = trim((string) ($gi['item']['group_note'] ?? '')); if ($gn !== '') { $gnote = $gn; break; } } ?>
                    <?php if ($gnote !== '') : ?><p class="brand-section-note brand-graphics-group-note"><?php echo esc_html($gnote); ?></p><?php endif; ?>
                    <div class="brand-graphics-grid">
                        <?php foreach ($items as $it) :
                            $g = $it['item'];
                            $name = trim((string) ($g['name'] ?? ''));
                            $alt = trim((string) ($g['image']['alt'] ?? ''));
                            if ($alt === '' && !empty($g['image']['id'])) $alt = (string) get_post_meta(absint($g['image']['id']), '_wp_attachment_image_alt', true);
                            if ($alt === '') $alt = $name;
                            $usage = trim((string) ($g['usage'] ?? ''));
                            $is_pattern = ($g['tile'] ?? 'contain') === 'pattern';
                            ?>
                            <figure class="brand-graphic">
                                <div class="brand-graphic-tile<?php echo $is_pattern ? ' is-pattern' : ''; ?>">
                                    <?php if ($lightbox) :
                                        $full = !empty($g['image']['id']) ? (string) wp_get_attachment_url(absint($g['image']['id'])) : '';
                                        if ($full === '') $full = $it['url'];
                                        ?>
                                        <a class="brand-graphic-link" href="<?php echo esc_url($full); ?>" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="<?php echo esc_attr($slideshow); ?>" data-elementor-lightbox-title="<?php echo esc_attr($name !== '' ? $name : $alt); ?>" data-elementor-lightbox-description="<?php echo esc_attr($usage); ?>" aria-label="<?php echo esc_attr(sprintf(__('View larger: %s', 'ux-portfolio-optimized'), $name !== '' ? $name : $alt)); ?>">
                                            <img src="<?php echo esc_url($it['url']); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async">
                                        </a>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url($it['url']); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async">
                                    <?php endif; ?>
                                </div>
                                <?php if ($name !== '' || $usage !== '') : ?>
                                    <figcaption>
                                        <?php if ($name !== '') : ?><span class="brand-graphic-name"><?php echo esc_html($name); ?></span><?php endif; ?>
                                        <?php if ($usage !== '') : ?><span class="brand-graphic-usage"><?php echo esc_html($usage); ?></span><?php endif; ?>
                                    </figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    private function sanitize_svg_markup($svg) {
        return self::kses_svg($svg); // allowlist cleaning, see UX_Base_Widget::kses_svg()
    }

    private function logo_is_svg($logo) {
        if (trim($logo['svg_markup'] ?? '') !== '') return true;
        $img_id = $logo['image']['id'] ?? 0;
        if (!empty($img_id) && get_post_mime_type($img_id) === 'image/svg+xml') return true;
        $img_url = $logo['image']['url'] ?? '';
        if (!empty($img_url) && stripos($img_url, '.svg') !== false) return true;
        return false;
    }

    private function render_logo_visual($logo) {
        // Priority 1: pasted SVG markup
        $svg_markup = trim($logo['svg_markup'] ?? '');
        if ($svg_markup !== '' && stripos($svg_markup, '<svg') !== false) {
            echo $this->sanitize_svg_markup($svg_markup);
            return;
        }

        $img_url = $logo['image']['url'] ?? '';
        $img_id  = $logo['image']['id']  ?? 0;
        if (empty($img_url) && !empty($img_id)) {
            $img_url = wp_get_attachment_url($img_id);
        }
        if (empty($img_url)) return;

        // Priority 2: uploaded SVG file — inline its contents
        $is_svg = false;
        if (!empty($img_id)) {
            $mime = get_post_mime_type($img_id);
            if ($mime === 'image/svg+xml') $is_svg = true;
        }
        if (!$is_svg && stripos($img_url, '.svg') !== false) {
            $is_svg = true;
        }
        if ($is_svg && !empty($img_id)) {
            $file = get_attached_file($img_id);
            if ($file && is_readable($file) && filesize($file) < 200000) {
                $contents = @file_get_contents($file);
                if ($contents !== false && stripos($contents, '<svg') !== false) {
                    echo $this->sanitize_svg_markup($contents);
                    return;
                }
            }
        }

        // Priority 3: render as <img>
        $alt = trim($logo['name'] ?? '');
        printf(
            '<img src="%s" alt="%s" loading="lazy">',
            esc_url($img_url),
            esc_attr($alt)
        );
    }
}
