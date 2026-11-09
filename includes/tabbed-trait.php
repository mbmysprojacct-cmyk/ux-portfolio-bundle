<?php
/**
 * UX_Tabbed_Trait
 *
 * Drop-in tabbing for any widget whose items are grouped by a name (the pattern
 * used across SWOT, Empathy, Persona, Statements, Affinity, and the F&B matrix).
 *
 * It provides:
 *   - add_tab_controls()  — registers the Enable / Show Tabs / Default controls
 *   - bucket_by()         — groups an items array by a field into name => rows[]
 *   - open_tab_shell()    — prints the wrapper, tablist and opens the loop
 *   - tab_panel_open()    — prints one panel's opening tag + optional heading
 *   - close_tab_shell()   — closes the wrapper
 *
 * The markup it prints matches the generic JS handler ([data-ux-tabs] /
 * [data-ux-tab] / [data-ux-panel]) and the shared .ux-tabs / .ux-panel CSS, so
 * a new tabbed widget needs no bespoke JS or CSS. See UX-SCAFFOLDING.md for a
 * full worked example.
 *
 * PHP 7.4 safe: no arrow functions, no array_key_first / str_contains, etc.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait UX_Tabbed_Trait {

	/**
	 * Register the standard tabbing controls. Call inside register_controls(),
	 * usually in its own section.
	 *
	 * @param string $noun   Singular label shown in control text, e.g. 'Version'.
	 * @param string $prefix Control-id prefix so multiple uses don't collide.
	 */
	protected function add_tab_controls( $noun = 'Version', $prefix = 'tab' ) {
		$this->add_control(
			$prefix . '_enable',
			array(
				'label'       => sprintf( /* translators: %s: item name, e.g. Map */ __( 'Enable %ss', 'ux-portfolio-optimized' ), $noun ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => sprintf( /* translators: %s: item name, lowercase */ __( 'Show several %ss, switchable by tabs.', 'ux-portfolio-optimized' ), strtolower( $noun ) ),
			)
		);

		$this->add_control(
			$prefix . '_show_tabs',
			array(
				'label'       => __( 'Show Tabs', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array( $prefix . '_enable' => 'yes' ),
				'description' => __( 'Turn off to stack every group down the page instead of tabbing.', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			$prefix . '_default',
			array(
				'label'       => sprintf( /* translators: %s: item name */ __( 'Default %s', 'ux-portfolio-optimized' ), $noun ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					$prefix . '_enable'    => 'yes',
					$prefix . '_show_tabs' => 'yes',
				),
				'description' => sprintf( /* translators: %s: item name, lowercase */ __( 'Name of the %s to open first. Blank = the first one.', 'ux-portfolio-optimized' ), strtolower( $noun ) ),
			)
		);
	}

	/**
	 * Bucket a repeater array by one of its fields, preserving first-seen order.
	 * Rows with an empty value fall under $untitled (or a single bucket keyed
	 * '__all__' when $enabled is false).
	 *
	 * @return array name => array of rows
	 */
	protected function bucket_by( $items, $field, $enabled, $untitled = null ) {
		if ( $untitled === null ) {
			$untitled = __( 'Untitled', 'ux-portfolio-optimized' );
		}
		$buckets = array();
		foreach ( $items as $row ) {
			$name = $enabled ? trim( $row[ $field ] ?? '' ) : '';
			if ( $name === '' ) {
				$name = $enabled ? $untitled : '__all__';
			}
			if ( ! isset( $buckets[ $name ] ) ) {
				$buckets[ $name ] = array();
			}
			$buckets[ $name ][] = $row;
		}
		return $buckets;
	}

	/**
	 * Resolve which bucket index should be active from a default-name setting.
	 */
	protected function resolve_active( $names, $default_name ) {
		$default_name = trim( (string) $default_name );
		if ( $default_name === '' ) {
			return 0;
		}
		foreach ( $names as $i => $n ) {
			if ( strcasecmp( $n, $default_name ) === 0 ) {
				return $i;
			}
		}
		return 0;
	}

	/**
	 * Print the wrapper opening tag + (optionally) the tablist.
	 * Returns nothing; call close_tab_shell() after your panel loop.
	 *
	 * @param array  $names   Bucket names in order.
	 * @param bool   $tabbed  Whether to show the tablist.
	 * @param int    $active  Active index.
	 * @param string $wrapper_class Extra class(es) for the wrapper.
	 * @param bool   $render_header Whether to call render_standard_header().
	 */
	protected function open_tab_shell( $names, $tabbed, $active, $wrapper_class = '', $render_header = true ) {
		$cls = trim( 'ux-tabbed-wrapper ' . $wrapper_class );
		echo '<div class="' . esc_attr( $cls ) . '" data-ux-tabs data-widget-id="' . esc_attr( $this->get_id() ) . '">';

		if ( $render_header && method_exists( $this, 'render_standard_header' ) ) {
			$this->render_standard_header();
		}

		if ( $tabbed ) {
			echo '<div class="ux-tabs" role="tablist">';
			foreach ( $names as $i => $n ) {
				$on = ( $i === $active );
				echo '<button type="button" class="ux-tabs__tab' . ( $on ? ' is-active' : '' ) . '"'
					. ' role="tab" aria-selected="' . ( $on ? 'true' : 'false' ) . '"'
					. ' data-ux-tab="' . esc_attr( $i ) . '">' . esc_html( $n ) . '</button>';
			}
			echo '</div>';
		}
	}

	/**
	 * Print one panel's opening tag, plus a heading when stacked (not tabbed).
	 * Pair each call with a literal `</div>` after you render the panel body.
	 */
	protected function tab_panel_open( $index, $name, $tabbed, $active, $enabled ) {
		$hidden = $tabbed && ( $index !== $active );
		echo '<div class="ux-panel' . ( $hidden ? ' is-hidden' : '' ) . '" data-ux-panel="' . esc_attr( $index ) . '">';
		if ( ! $tabbed && $enabled && $name !== '__all__' ) {
			echo '<h3 class="ux-panel__name">' . esc_html( $name ) . '</h3>';
		}
	}

	protected function close_tab_shell() {
		echo '</div>';
	}
}
