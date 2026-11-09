<?php
/**
 * Sitemap Widget - Optimized with Original Design
 * Preserved: Tree structure, connector lines, scroll buttons, collapsible
 */

if (!defined('ABSPATH')) exit;

class UX_Sitemap_Widget extends UX_Base_Widget {
    
    public function get_name() { return 'ux-sitemap'; }
    public function get_title() { return __('Sitemap', 'ux-portfolio-optimized'); }
    public function get_icon() { return 'eicon-sitemap'; }
    
    protected function register_controls() {
        
        $this->add_standard_header_section();
        
        // Nodes
        $this->start_controls_section('nodes_section', [
            'label' => __('Nodes', 'ux-portfolio-optimized')
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('label', [
            'label' => __('Label', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Node',
        ]);
        
        $repeater->add_control('parent_label', [
            'label' => __('Parent', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Type the Label of the parent node. Leave blank for a root node. (Recommended way to set hierarchy.)', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('parent', [
            'label' => __('Parent (Legacy Number)', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'description' => __('Older method. Only used if Parent (above) is blank. 0 = root, 1 = child of first node, etc.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('section', [
            'label' => __('Section', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Type a Section name (defined in the Sections panel) to color-group this node.', 'ux-portfolio-optimized'),
        ]);

        $repeater->add_control('sitemap', [
            'label' => __('Sitemap', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Optional. Type a Sitemap name (defined in the Sitemaps panel) to put this node in that sitemap\'s tab. Leave blank for the default/first sitemap.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('nodes', [
            'label' => __('Nodes', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['label' => 'Home', 'parent_label' => '', 'parent' => 0],
                ['label' => 'Products', 'parent_label' => 'Home', 'parent' => 0],
                ['label' => 'About', 'parent_label' => 'Home', 'parent' => 0],
            ],
            'title_field' => '{{{ label }}}',
        ]);
        
        $this->end_controls_section();
        
        // Sitemaps (tabs)
        $this->start_controls_section('sitemaps_section', [
            'label' => __('Sitemaps', 'ux-portfolio-optimized'),
        ]);

        $sm_repeater = new \Elementor\Repeater();

        $sm_repeater->add_control('name', [
            'label' => __('Sitemap Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Main',
            'description' => __('Used as the tab label. Match this in each node\'s Sitemap field.', 'ux-portfolio-optimized'),
        ]);

        $this->add_control('sitemaps', [
            'label' => __('Sitemaps', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $sm_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ name }}}',
            'description' => __('Optional. Define multiple sitemaps (e.g., "Site Map", "App Map"). Tabs appear when more than one exists. Leave empty for a single untabbed sitemap.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();
        
        // Sections (color groups)
        $this->start_controls_section('sections_section', [
            'label' => __('Sections', 'ux-portfolio-optimized'),
        ]);

        $sec_repeater = new \Elementor\Repeater();

        $sec_repeater->add_control('section_name', [
            'label' => __('Section Name', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Section',
            'description' => __('Match this in a node\'s Section field to color it.', 'ux-portfolio-optimized'),
        ]);

        $sec_repeater->add_control('section_bg', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
        ]);

        $sec_repeater->add_control('section_text', [
            'label' => __('Text Color', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
        ]);

        $this->add_control('sections', [
            'label' => __('Sections', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $sec_repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ section_name }}}',
            'description' => __('Optional. Define color groups (e.g., "Account", "Shop"), then assign nodes to them via each node\'s Section field.', 'ux-portfolio-optimized'),
        ]);

        $this->end_controls_section();
        
        // Layout
        $this->start_controls_section('layout_section', [
            'label' => __('Layout', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('desktop_layout', [
            'label' => __('Layout', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'tree' => __('Tree (Top to Bottom)', 'ux-portfolio-optimized'),
                'list' => __('List (Collapsible)', 'ux-portfolio-optimized'),
            ],
            'default' => 'tree',
            'tablet_default' => 'list',
            'mobile_default' => 'list',
        ]);

        $this->add_control('default_expanded', [
            'label' => __('Default Expanded', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => __('Yes', 'ux-portfolio-optimized'),
            'label_off' => __('No', 'ux-portfolio-optimized'),
            'description' => __('When the sitemap is shown as a collapsible list (small screens or List layout), should children start expanded or collapsed?', 'ux-portfolio-optimized'),
        ]);
        
        $this->end_controls_section();
        
        // Node Style
        $this->start_controls_section('node_style', [
            'label' => __('Node Style', 'ux-portfolio-optimized'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_responsive_control('node_padding', [
            'label' => __('Padding', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .tree-node' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('node_bg', [
            'label' => __('Background', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .tree-node' => 'background: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('node_border_radius', [
            'label' => __('Border Radius', 'ux-portfolio-optimized'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .tree-node' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'node_typography',
            'selector' => '{{WRAPPER}} .tree-node',
        ]);
        
        $this->add_description_alignment();
        
        $this->end_controls_section();
    }
    
    private $section_map = array();
    private $default_expanded = true;

    private function slugify($text) {
        $slug = strtolower(trim((string) $text));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug !== '' ? $slug : 'default';
    }

    /**
     * Build a tree array for one group of nodes.
     * $group_nodes: indexed array of node-data arrays (in order).
     * Uses local 1-based ids. Prefers parent_label, falls back to numeric parent.
     * Returns: parent_id => array of ['id' => localId, 'data' => node]
     */
    private function build_tree($group_nodes) {
        $label_to_id = array();
        foreach ($group_nodes as $local => $node) {
            $lbl = strtolower(trim($node['label'] ?? ''));
            if ($lbl !== '' && !isset($label_to_id[$lbl])) {
                $label_to_id[$lbl] = $local + 1;
            }
        }

        $tree = array();
        foreach ($group_nodes as $local => $node) {
            $local_id = $local + 1;
            $parent_id = 0;

            $parent_label = trim($node['parent_label'] ?? '');
            if ($parent_label !== '') {
                $plkey = strtolower($parent_label);
                if (isset($label_to_id[$plkey]) && $label_to_id[$plkey] !== $local_id) {
                    $parent_id = $label_to_id[$plkey];
                } else {
                    $parent_id = 0;
                }
            } else {
                $parent_id = absint($node['parent'] ?? 0);
            }

            if (!isset($tree[$parent_id])) $tree[$parent_id] = array();
            $tree[$parent_id][] = array('id' => $local_id, 'data' => $node);
        }

        return $tree;
    }

    private function render_node($node, $tree) {
        $node_id = $node['id'];
        $has_children = isset($tree[$node_id]) && !empty($tree[$node_id]);

        // Resolve optional section color for this node (inline style — no <style> block).
        $node_style = '';
        $section_raw = trim($node['data']['section'] ?? '');
        if ($section_raw !== '') {
            $skey = strtolower($section_raw);
            if (isset($this->section_map[$skey])) {
                $sec = $this->section_map[$skey];
                if (!empty($sec['bg'])) {
                    $node_style .= 'background-color:' . $sec['bg'] . ';';
                }
                if (!empty($sec['text'])) {
                    $node_style .= 'color:' . $sec['text'] . ';';
                }
            }
        }
        $item_classes = 'tree-item';
        if ($has_children && $this->default_expanded) {
            $item_classes .= ' expanded';
        }
        ?>
        <div class="<?php echo esc_attr($item_classes); ?>" data-node-id="<?php echo esc_attr($node_id); ?>">
            <div class="tree-node"<?php if ($node_style !== '') echo ' style="' . esc_attr($node_style) . '"'; ?>>
                <?php if ($has_children) : ?>
                    <span class="expand-btn">▶</span>
                <?php endif; ?>
                <span><?php echo esc_html($node['data']['label']); ?></span>
            </div>
            <?php if ($has_children) : ?>
                <div class="tree-children">
                    <?php foreach ($tree[$node_id] as $child) {
                        $this->render_node($child, $tree);
                    } ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    protected function render() {
        $s = $this->get_settings_for_display();
        $nodes = $s['nodes'] ?? [];
        
        if (empty($nodes)) {
            echo '<p>Please add nodes.</p>';
            return;
        }
        
        $layout = sanitize_key($s['desktop_layout'] ?? 'tree');
        if (!in_array($layout, ['tree', 'list'], true)) $layout = 'tree';

        $this->default_expanded = (($s['default_expanded'] ?? 'yes') === 'yes');
        
        // Build section color map: lowercased section name => bg/text colors.
        $this->section_map = array();
        $sections = $s['sections'] ?? [];
        if (!empty($sections) && is_array($sections)) {
            foreach ($sections as $sec) {
                $name = strtolower(trim($sec['section_name'] ?? ''));
                if ($name === '') continue;
                if (!isset($this->section_map[$name])) {
                    $this->section_map[$name] = array(
                        'bg'   => $sec['section_bg'] ?? '',
                        'text' => $sec['section_text'] ?? '',
                    );
                }
            }
        }
        
        // Build the ordered list of sitemaps (slug => display name).
        $sitemap_names = array();
        $sitemaps_def = $s['sitemaps'] ?? [];
        if (!empty($sitemaps_def) && is_array($sitemaps_def)) {
            foreach ($sitemaps_def as $sm) {
                $name = trim($sm['name'] ?? '');
                if ($name === '') continue;
                $slug = $this->slugify($name);
                if (!isset($sitemap_names[$slug])) {
                    $sitemap_names[$slug] = $name;
                }
            }
        }

        // Determine the default slug for nodes that don't specify a sitemap.
        $default_slug = '';
        if (!empty($sitemap_names)) {
            $defined_keys = array_keys($sitemap_names);
            $default_slug = $defined_keys[0];
        }

        // Group nodes by sitemap slug.
        $groups = array();
        foreach ($nodes as $node) {
            $sm_name = trim($node['sitemap'] ?? '');
            if ($sm_name !== '') {
                $slug = $this->slugify($sm_name);
                if (!isset($sitemap_names[$slug])) {
                    // Node references a sitemap not in the panel — register it so it still shows.
                    $sitemap_names[$slug] = $sm_name;
                }
            } else {
                if ($default_slug !== '') {
                    $slug = $default_slug;
                } else {
                    $slug = 'default';
                    if (!isset($sitemap_names[$slug])) {
                        $sitemap_names[$slug] = 'Main';
                    }
                }
            }
            if (!isset($groups[$slug])) $groups[$slug] = array();
            $groups[$slug][] = $node;
        }

        $slugs = array_keys($sitemap_names);
        // Drop any sitemap slugs that ended up with no nodes.
        $slugs = array_values(array_filter($slugs, function($sl) use ($groups) {
            return isset($groups[$sl]) && !empty($groups[$sl]);
        }));

        if (empty($slugs)) {
            echo '<p>' . esc_html__('Please add nodes.', 'ux-portfolio-optimized') . '</p>';
            return;
        }

        $active_slug = $slugs[0];
        $show_tabs = count($slugs) > 1;
        ?>
        
        <div class="ux-sitemap-wrapper" data-active-sitemap="<?php echo esc_attr($active_slug); ?>">
            <?php $this->render_standard_header(); ?>

            <?php if ($show_tabs) : ?>
                <div class="sitemap-tabs" role="tablist">
                    <?php foreach ($slugs as $slug) : ?>
                        <button type="button"
                                class="sitemap-tab<?php echo $slug === $active_slug ? ' is-active' : ''; ?>"
                                role="tab"
                                aria-selected="<?php echo $slug === $active_slug ? 'true' : 'false'; ?>"
                                data-target="<?php echo esc_attr($slug); ?>"><?php echo esc_html($sitemap_names[$slug]); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php foreach ($slugs as $slug) :
                $tree = $this->build_tree($groups[$slug]);
                $is_active = ($slug === $active_slug);
                ?>
                <div class="ux-sitemap-scroll layout-<?php echo esc_attr($layout); ?><?php echo $is_active ? '' : ' is-hidden'; ?>"
                     data-sitemap="<?php echo esc_attr($slug); ?>">

                    <?php if ($layout === 'tree') : ?>
                        <span class="scroll-fade scroll-fade-left" aria-hidden="true"></span>
                        <span class="scroll-fade scroll-fade-right" aria-hidden="true"></span>
                        <button class="scroll-btn scroll-left" aria-label="Scroll left" style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 6 9 12 15 18"/></svg></button>
                        <button class="scroll-btn scroll-right" aria-label="Scroll right" style="display:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 6 15 12 9 18"/></svg></button>
                    <?php endif; ?>

                    <div class="ux-sitemap-tree layout-<?php echo esc_attr($layout); ?>">
                        <svg class="sibling-connections"></svg>

                        <?php
                        if (isset($tree[0])) {
                            foreach ($tree[0] as $root_node) {
                                $this->render_node($root_node, $tree);
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php
    }
}
