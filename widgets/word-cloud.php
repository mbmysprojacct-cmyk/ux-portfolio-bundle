<?php
/**
 * Word Cloud Widget
 *
 * Dependency-free tag cloud for usability research — visualize theme or
 * keyword frequency from interviews, surveys, and feedback. Each word is
 * sized by its weight (frequency/importance). Pure CSS/PHP, no library.
 */

if (!defined('ABSPATH')) exit;

class UX_Word_Cloud_Widget extends UX_Base_Widget {

    public function get_name() { return 'ux-word-cloud'; }
    public function get_title() { return __('Word Cloud', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-tags'; }
    public function get_keywords() {
        return ['word', 'cloud', 'tag', 'keywords', 'themes', 'usability', 'ux'];
    }

    protected function register_controls() {

        $this->add_standard_header_section();

        // CONTENT — Words
        $this->start_controls_section('data_section', [
            'label' => __('Words', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('input_mode', [
            'label' => __('Input Mode', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'manual',
            'options' => [
                'manual' => __('Enter words manually', 'ux-portfolio-optimized'),
                'text'   => __('Paste text (auto-count)', 'ux-portfolio-optimized'),
            ],
            'description' => __('Paste raw survey answers and the cloud is built automatically from word frequency.', 'ux-portfolio-optimized'),
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('word', [
            'label' => __('Word', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Theme', 'ux-portfolio-optimized'),
            'dynamic' => ['active' => true],
        ]);

        $repeater->add_control('weight', [
            'label' => __('Weight', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 5,
            'min' => 1,
            'description' => __('How often this came up. Higher = larger.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'description' => __('Optional. Overrides the color for this word.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('link', [
            'label' => __('Link', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('words', [
            'label' => __('Words', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['word' => __('Intuitive', 'ux-portfolio-optimized'), 'weight' => 9],
                ['word' => __('Confusing nav', 'ux-portfolio-optimized'), 'weight' => 7],
                ['word' => __('Fast', 'ux-portfolio-optimized'), 'weight' => 6],
                ['word' => __('Trustworthy', 'ux-portfolio-optimized'), 'weight' => 5],
                ['word' => __('Cluttered', 'ux-portfolio-optimized'), 'weight' => 4],
                ['word' => __('Helpful', 'ux-portfolio-optimized'), 'weight' => 8],
                ['word' => __('Overwhelming', 'ux-portfolio-optimized'), 'weight' => 3],
                ['word' => __('Clear', 'ux-portfolio-optimized'), 'weight' => 6],
            ],
            'title_field' => '{{{ word }}} ({{{ weight }}})',
            'condition' => ['input_mode' => 'manual'],
        ]);

        $this->add_control('raw_text', [
            'label' => __('Survey / Answer Text', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 10,
            'default' => '',
            'placeholder' => __('Paste your open-ended survey responses here…', 'ux-portfolio-optimized'),
            'condition' => ['input_mode' => 'text'],
        ]);

        $this->add_control('max_words', [
            'label' => __('Max Words', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 40,
            'min' => 1,
            'condition' => ['input_mode' => 'text'],
            'description' => __('Show only the most frequent words, up to this many.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('min_word_length', [
            'label' => __('Ignore Words Shorter Than', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min' => 1,
            'condition' => ['input_mode' => 'text'],
            'description' => __('Skip very short words (character count).', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('extra_stopwords', [
            'label' => __('Extra Words to Ignore', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => '',
            'placeholder' => __('e.g. app, website, product', 'ux-portfolio-optimized'),
            'condition' => ['input_mode' => 'text'],
            'description' => __('Common words (the, and, is…) are already removed. Add your own, separated by commas or spaces.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('align', [
            'label' => __('Alignment', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'center',
            'options' => [
                'center' => __('Center', 'ux-portfolio-optimized'),
                'left'   => __('Left', 'ux-portfolio-optimized'),
                'right'  => __('Right', 'ux-portfolio-optimized'),
            ],
        ]);

        $this->add_control('color_mode', [
            'label' => __('Color Mode', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'single',
            'options' => [
                'single' => __('Single color', 'ux-portfolio-optimized'),
                'multi'  => __('Custom palette', 'ux-portfolio-optimized'),
            ],
            'description' => __('Single: one color (set in the Style tab). Custom palette: words cycle through the colors below. Per-word colors always win.', 'ux-portfolio-optimized'),
        ]);

        $palette_repeater = new \Elementor\Repeater();
        $palette_repeater->add_control('palette_color', [
            'label' => __('Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ef4a8a',
        ]);

        $this->add_control('palette', [
            'label' => __('Palette Colors', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $palette_repeater->get_controls(),
            'default' => [
                ['palette_color' => '#ef4a8a'],
                ['palette_color' => '#d4a017'],
                ['palette_color' => '#1a1a1a'],
            ],
            'condition' => ['color_mode' => 'multi'],
            'title_field' => '{{{ palette_color }}}',
            'description' => __('Add a few colors to match this project\'s brand — words cycle through them.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();

        // STYLE
        $this->start_controls_section('style_section', [
            'label' => __('Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('layout', [
            'label' => __('Layout', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'packed',
            'options' => [
                'packed'  => __('Packed (scattered)', 'ux-portfolio-optimized'),
                'flowing' => __('Flowing (inline)', 'ux-portfolio-optimized'),
            ],
            'description' => __('Packed fits words together with some rotated, like a classic word cloud. Flowing lays them out in centered lines.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('rotate', [
            'label' => __('Rotate Some Words', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['layout' => 'packed'],
            'description' => __('Turn some words vertical and diagonal for a denser, more organic look.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('min_font', [
            'label' => __('Smallest Word', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 8, 'max' => 48]],
            'default' => ['unit' => 'px', 'size' => 14],
        ]);

        $this->add_control('max_font', [
            'label' => __('Largest Word', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 20, 'max' => 140]],
            'default' => ['unit' => 'px', 'size' => 52],
        ]);

        $this->add_control('word_color', [
            'label' => __('Word Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT],
            'default' => '#1a1a1a',
            'condition' => ['color_mode' => 'single'],
            'selectors' => [
                '{{WRAPPER}} .ux-wordcloud__word' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('hover_color', [
            'label' => __('Hover Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'global' => ['default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_ACCENT],
            'default' => '#ef4a8a',
            'selectors' => [
                '{{WRAPPER}} .ux-wordcloud__word:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('word_gap', [
            'label' => __('Spacing', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 4, 'max' => 50]],
            'default' => ['size' => 16],
            'selectors' => [
                '{{WRAPPER}} .ux-wordcloud' => '--wc-gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_typography_control('word_typography', __('Typography', 'ux-portfolio-optimized'), '.ux-wordcloud__word');

        $this->end_controls_section();
    }

    private function get_stopwords() {
        return array('the','a','an','and','or','but','if','then','so','because','as','of','at','by','for','with','about','against','between','into','through','during','before','after','above','below','to','from','up','down','in','out','on','off','over','under','again','further','is','are','was','were','be','been','being','have','has','had','having','do','does','did','doing','would','should','could','i','you','he','she','it','we','they','them','his','her','its','our','their','this','that','these','those','am','my','your','me','him','us','who','whom','which','what','when','where','why','how','all','any','both','each','few','more','most','other','some','such','no','nor','not','only','own','same','than','too','very','just','can','will','also','get','got','one','there','theyre','dont','didnt','cant','im','ive','really','thing','things','way','lot','much','many','make','made','use','used','using');
    }

    private function words_from_text($s) {
        $raw = (string) ($s['raw_text'] ?? '');
        if (trim($raw) === '') return array();

        $min_len = max(1, absint($s['min_word_length'] ?? 3));
        $max_words = max(1, absint($s['max_words'] ?? 40));

        $stop = array_flip($this->get_stopwords());
        $extra = trim($s['extra_stopwords'] ?? '');
        if ($extra !== '') {
            $extra_low = function_exists('mb_strtolower') ? mb_strtolower($extra) : strtolower($extra);
            foreach (preg_split('#[\s,]+#', $extra_low) as $ew) {
                $ew = trim($ew);
                if ($ew !== '') $stop[$ew] = true;
            }
        }

        preg_match_all('#[\p{L}][\p{L}\'\-]*#u', $raw, $tokens);
        $counts = array();
        $display = array();
        foreach ($tokens[0] as $rawtok) {
            $tok = trim($rawtok, "'-");
            if ($tok === '') continue;
            $key = function_exists('mb_strtolower') ? mb_strtolower($tok) : strtolower($tok);
            $len = function_exists('mb_strlen') ? mb_strlen($key) : strlen($key);
            if ($len < $min_len) continue;
            if (isset($stop[$key])) continue;
            if (!isset($counts[$key])) { $counts[$key] = 0; $display[$key] = $tok; }
            $counts[$key]++;
        }
        if (empty($counts)) return array();

        arsort($counts);
        $counts = array_slice($counts, 0, $max_words, true);

        $words = array();
        foreach ($counts as $key => $cnt) {
            $words[] = array('word' => $display[$key], 'weight' => $cnt, 'color' => '', 'link' => array());
        }
        return $words;
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        $input_mode = ($s['input_mode'] ?? 'manual') === 'text' ? 'text' : 'manual';
        if ($input_mode === 'text') {
            $words = $this->words_from_text($s);
        } else {
            $words = $s['words'] ?? [];
        }
        if (empty($words)) return;

        $align = $s['align'] ?? 'center';
        $multi = ($s['color_mode'] ?? 'single') === 'multi';
        $layout = ($s['layout'] ?? 'packed') === 'flowing' ? 'flowing' : 'packed';
        $rotate = ($s['rotate'] ?? 'yes') === 'yes';

        $min_font = isset($s['min_font']['size']) ? floatval($s['min_font']['size']) : 14;
        $max_font = isset($s['max_font']['size']) ? floatval($s['max_font']['size']) : 52;
        if ($max_font < $min_font) {
            $tmp = $max_font; $max_font = $min_font; $min_font = $tmp;
        }

        // Weight range for scaling
        $weights = array();
        foreach ($words as $w) {
            $weights[] = floatval($w['weight'] ?? 0);
        }
        $min_w = count($weights) ? min($weights) : 0;
        $max_w = count($weights) ? max($weights) : 0;

        $palette = array();
        if (!empty($s['palette']) && is_array($s['palette'])) {
            foreach ($s['palette'] as $pc) {
                $c = trim($pc['palette_color'] ?? '');
                if ($c !== '') $palette[] = $c;
            }
        }
        if (empty($palette)) {
            $palette = array('#ef4a8a', '#d4a017', '#1a1a1a', '#3b82f6', '#10b981', '#8b5cf6');
        }

        ?>
        <div class="ux-word-cloud-wrapper">
            <?php $this->render_standard_header(); ?>

            <div class="ux-wordcloud align-<?php echo esc_attr($align); ?> layout-<?php echo esc_attr($layout); ?>" data-rotate="<?php echo $rotate ? 'on' : 'off'; ?>">
                <?php
                $i = 0;
                foreach ($words as $w) :
                    $text = trim($w['word'] ?? '');
                    if ($text === '') { $i++; continue; }
                    $weight = floatval($w['weight'] ?? 0);

                    // Linear scale weight -> font size
                    if ($max_w > $min_w) {
                        $font = $min_font + (($weight - $min_w) / ($max_w - $min_w)) * ($max_font - $min_font);
                    } else {
                        $font = ($min_font + $max_font) / 2;
                    }
                    $font = round($font, 1);

                    // Color priority: per-word > palette (multi) > CSS default (single)
                    $color = trim($w['color'] ?? '');
                    if ($color === '' && $multi) {
                        $color = $palette[$i % count($palette)];
                    }
                    $style = 'font-size: ' . $font . 'px;';
                    if ($color !== '') $style .= ' color: ' . esc_attr($color) . ';';

                    // Optional link
                    $url = $w['link']['url'] ?? '';
                    $link_attrs = '';
                    if ($url !== '') {
                        $link_attrs = ' href="' . esc_url($url) . '"';
                        if (!empty($w['link']['is_external'])) $link_attrs .= ' target="_blank"';
                        $rel = array();
                        if (!empty($w['link']['is_external'])) $rel[] = 'noopener';
                        if (!empty($w['link']['nofollow'])) $rel[] = 'nofollow';
                        if (!empty($rel)) $link_attrs .= ' rel="' . esc_attr(implode(' ', $rel)) . '"';
                    }
                    $tag = ($url !== '') ? 'a' : 'span';
                    ?>
                    <<?php echo $tag; ?> class="ux-wordcloud__word" style="<?php echo $style; ?>"<?php echo $link_attrs; ?>><?php echo esc_html($text); ?></<?php echo $tag; ?>>
                <?php
                    $i++;
                endforeach;
                ?>
            </div>
        </div>
        <?php
    }
}
