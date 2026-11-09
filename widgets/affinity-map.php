<?php
/**
 * Affinity Map Widget - Optimized
 * Group and organize ideas/notes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Affinity_Map_Widget extends UX_Base_Widget {

	public function get_name() {
		return 'ux-affinity-map'; }
	public function get_title() {
		return __( 'Affinity Map', 'ux-portfolio-optimized' ); }
	public function get_icon() {
		return 'eicon-posts-grid'; }

	protected function register_controls() {

		$this->add_standard_header_section();

		$this->start_controls_section(
			'groups_section',
			array(
				'label' => __( 'Groups', 'ux-portfolio-optimized' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'version',
			array(
				'label'       => __( 'Version', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => __( 'e.g. Round 1', 'ux-portfolio-optimized' ),
				'description' => __( 'Groups sharing a version name appear together under that tab. Only used when Versions are enabled below.', 'ux-portfolio-optimized' ),
			)
		);

		$repeater->add_control(
			'group_title',
			array(
				'label'   => __( 'Group Title', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Category',
			)
		);

		$repeater->add_control(
			'group_color',
			array(
				'label'   => __( 'Group Color', 'ux-portfolio-optimized' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#fbbf24',
			)
		);

		$repeater->add_control(
			'notes',
			array(
				'label'       => __( 'Notes (one per line)', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'default'     => "Note 1\nNote 2\nNote 3",
				'description' => __( 'One note per line. To give a single note its own color, add a hex code after a pipe — e.g. "Users miss the filter | #ef4444". Notes without one use the Group Color.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'groups',
			array(
				'label'       => __( 'Groups', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'group_title' => 'User Needs',
						'group_color' => '#fbbf24',
					),
					array(
						'group_title' => 'Pain Points',
						'group_color' => '#f87171',
					),
				),
				'title_field' => '{{{ version ? version + " — " : "" }}}{{{ group_title }}}',
			)
		);

		$this->end_controls_section();

		// CONTENT — Versions / iterations
		$this->start_controls_section(
			'versions_section',
			array(
				'label' => __( 'Versions', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'enable_versions',
			array(
				'label'       => __( 'Enable Versions', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Show several iterations of the map, switchable by tabs. Give each group above a Version name — groups sharing a name appear together.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_tabs',
			array(
				'label'       => __( 'Show Tabs', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array( 'enable_versions' => 'yes' ),
				'description' => __( 'Turn off to stack every version down the page instead of tabbing between them.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'default_version',
			array(
				'label'       => __( 'Default Version', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					'enable_versions' => 'yes',
					'show_tabs'       => 'yes',
				),
				'description' => __( 'Name of the version to open first. Leave blank for the first one.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		// CONTENT — Load more
		$this->start_controls_section(
			'load_more_section',
			array(
				'label' => __( 'Load More', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'enable_load_more',
			array(
				'label'       => __( 'Limit Notes Per Group', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Show only the first few notes in each group, with a button to reveal the rest. Keeps every group visible while shortening the map.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'notes_limit',
			array(
				'label'       => __( 'Notes Shown', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 5,
				'min'         => 1,
				'condition'   => array( 'enable_load_more' => 'yes' ),
				'description' => __( 'How many notes to show per group before the button.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'less_text',
			array(
				'label'       => __( 'Collapse Text', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Show less', 'ux-portfolio-optimized' ),
				'condition'   => array( 'enable_load_more' => 'yes' ),
				'description' => __( 'The expand button reads "+N more" automatically.', 'ux-portfolio-optimized' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'display_style',
			array(
				'label'       => __( 'Item Style', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'notes',
				'options'     => array(
					'notes' => __( 'Sticky notes (stacked)', 'ux-portfolio-optimized' ),
					'dots'  => __( 'Inline · dot-separated', 'ux-portfolio-optimized' ),
					'pills' => __( 'Pills (rounded chips)', 'ux-portfolio-optimized' ),
				),
				'description' => __( 'How items appear under each group heading.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'ux-portfolio-optimized' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'selectors'      => array(
					'{{WRAPPER}} .affinity-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_description_alignment();

		$this->end_controls_section();
	}

	/**
	 * Returns '#1a1a1a' or '#ffffff' depending on which contrasts better
	 * against the given hex background (WCAG relative luminance).
	 */
	private function readable_text_color( $hex ) {
		$hex = ltrim( (string) $hex, '#' );
		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		if ( strlen( $hex ) !== 6 ) {
			return '#1a1a1a';
		}
		$r   = hexdec( substr( $hex, 0, 2 ) ) / 255;
		$g   = hexdec( substr( $hex, 2, 2 ) ) / 255;
		$b   = hexdec( substr( $hex, 4, 2 ) ) / 255;
		$lum = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
		return $lum > 0.6 ? '#1a1a1a' : '#ffffff';
	}

	/**
	 * Splits a note line into text + optional color override.
	 * Syntax: "Note text | #ef4444" — the trailing hex overrides the group color.
	 * Only hex values are accepted, so nothing unsafe can reach the inline style.
	 */
	private function parse_note( $line ) {
		$line  = trim( $line );
		$color = '';
		if ( preg_match( '/^(.*?)\s*\|\s*(#[0-9a-fA-F]{3,8})\s*$/', $line, $m ) ) {
			$text = trim( $m[1] );
			if ( $text !== '' ) {
				$line  = $text;
				$color = $m[2];
			}
		}
		return array(
			'text'  => $line,
			'color' => $color,
		);
	}

	/** Renders one group card. */
	private function render_group( $group, $limit = 0, $less_text = '' ) {
		$color            = ! empty( $group['group_color'] ) ? $group['group_color'] : '#fbbf24';
		$title            = trim( $group['group_title'] ?? '' );
		$raw_notes        = $group['notes'] ?? '';
		$notes            = array_values(
			array_filter(
				array_map( 'trim', explode( "\n", $raw_notes ) ),
				function ( $n ) {
					return $n !== '';
				}
			)
		);
		$title_text_color = $this->readable_text_color( $color );

		$total        = count( $notes );
		$limited      = ( $limit > 0 && $total > $limit );
		$hidden_count = $limited ? ( $total - $limit ) : 0;
		?>
		<div class="affinity-group">
			<?php if ( $title !== '' ) : ?>
				<h3 class="group-title" style="background: <?php echo esc_attr( $color ); ?>; color: <?php echo esc_attr( $title_text_color ); ?>;">
					<?php echo esc_html( $title ); ?>
				</h3>
			<?php endif; ?>
			<?php if ( ! empty( $notes ) ) : ?>
				<div class="group-notes">
					<?php
					foreach ( $notes as $ni => $note ) :
						$parsed     = $this->parse_note( $note );
						$note_color = $parsed['color'] !== '' ? $parsed['color'] : $color;
						$over       = $limited && ( $ni >= $limit );
						?>
						<div class="sticky-note<?php echo $over ? ' is-over-limit' : ''; ?>" style="--note-color: <?php echo esc_attr( $note_color ); ?>;">
							<?php echo esc_html( $parsed['text'] ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php
				if ( $limited ) :
					$more_label = sprintf(
						/* translators: %d: number of additional notes */
						__( '+%d more', 'ux-portfolio-optimized' ),
						$hidden_count
					);
					$less_label = ( $less_text !== '' ) ? $less_text : __( 'Show less', 'ux-portfolio-optimized' );
					?>
					<button type="button" class="affinity-more"
							aria-expanded="false"
							data-more="<?php echo esc_attr( $more_label ); ?>"
							data-less="<?php echo esc_attr( $less_label ); ?>"><?php echo esc_html( $more_label ); ?></button>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$display_style = $s['display_style'] ?? 'notes';

		if ( empty( $s['groups'] ) ) {
			return;
		}

		$use_versions = ( $s['enable_versions'] ?? '' ) === 'yes';

		$limit     = ( ( $s['enable_load_more'] ?? '' ) === 'yes' )
			? max( 1, absint( $s['notes_limit'] ?? 5 ) )
			: 0;
		$less_text = $s['less_text'] ?? '';

		// Bucket groups by version name, preserving first-seen order.
		$versions = array();
		foreach ( $s['groups'] as $group ) {
			$vname = $use_versions ? trim( $group['version'] ?? '' ) : '';
			if ( $vname === '' ) {
				$vname = $use_versions
					? __( 'Untitled', 'ux-portfolio-optimized' )
					: '__single__';
			}
			if ( ! isset( $versions[ $vname ] ) ) {
				$versions[ $vname ] = array();
			}
			$versions[ $vname ][] = $group;
		}

		$names  = array_keys( $versions );
		$tabbed = $use_versions && ( ( $s['show_tabs'] ?? 'yes' ) === 'yes' ) && count( $names ) > 1;

		// Which tab opens first.
		$active          = 0;
		$default_version = trim( $s['default_version'] ?? '' );
		if ( $tabbed && $default_version !== '' ) {
			foreach ( $names as $i => $n ) {
				if ( strcasecmp( $n, $default_version ) === 0 ) {
					$active = $i;
					break;
				}
			}
		}
		?>

		<div class="affinity-map-wrapper" data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php $this->render_standard_header(); ?>

			<?php if ( $tabbed ) : ?>
				<div class="affinity-tabs" role="tablist">
					<?php
					foreach ( $names as $i => $n ) :
						$is_on = ( $i === $active );
						?>
						<button type="button"
								class="affinity-tab<?php echo $is_on ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $is_on ? 'true' : 'false'; ?>"
								data-affinity-tab="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $n ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			foreach ( $names as $i => $n ) :
				$hidden = $tabbed && ( $i !== $active );
				?>
				<div class="affinity-panel<?php echo $hidden ? ' is-hidden' : ''; ?>" data-affinity-panel="<?php echo esc_attr( $i ); ?>">
					<?php if ( ! $tabbed && $use_versions && $n !== '__single__' ) : ?>
						<h3 class="affinity-panel__name"><?php echo esc_html( $n ); ?></h3>
					<?php endif; ?>

					<div class="affinity-grid affinity-style-<?php echo esc_attr( $display_style ); ?>">
						<?php foreach ( $versions[ $n ] as $group ) : ?>
							<?php $this->render_group( $group, $limit, $less_text ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}
}
