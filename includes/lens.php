<?php
/**
 * Case study lenses.
 *
 * Adds a "Case Study Lens" setting to every widget and container (Advanced tab)
 * and prints it as data-lens="ux brand" on the element wrapper. The Lens Switch
 * widget reads those attributes to filter the page. Elementor's newer atomic
 * containers can carry the same attribute via their built-in Attributes setting.
 *
 * Lens options come from the `ux_bundle_lenses` filter (key => label).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UX_Lens {

	private static $done = false;

	public static function init() {
		if ( self::$done ) {
			return;
		}
		self::$done = true;
		add_action( 'elementor/element/common/_section_style/after_section_end', array( __CLASS__, 'add_controls' ), 10, 2 );
		add_action( 'elementor/element/container/section_layout/after_section_end', array( __CLASS__, 'add_controls' ), 10, 2 );
		add_action( 'elementor/frontend/widget/before_render', array( __CLASS__, 'render_attribute' ) );
		add_action( 'elementor/frontend/container/before_render', array( __CLASS__, 'render_attribute' ) );
	}

	/** @return array<string,string> lens key => plain-text label */
	public static function lenses() {
		$defaults = array(
			'ux'      => __( 'UX research & strategy', 'ux-portfolio-optimized' ),
			'ui'      => __( 'Product & UI', 'ux-portfolio-optimized' ),
			'brand'   => __( 'Brand & identity', 'ux-portfolio-optimized' ),
			'systems' => __( 'Design systems', 'ux-portfolio-optimized' ),
		);
		$lenses   = apply_filters( 'ux_bundle_lenses', $defaults );
		$out      = array();
		if ( is_array( $lenses ) ) {
			foreach ( $lenses as $key => $label ) {
				$key = sanitize_key( $key );
				if ( $key === '' || $key === 'all' ) {
					continue;
				}
				$out[ $key ] = wp_strip_all_tags( html_entity_decode( (string) $label, ENT_QUOTES, 'UTF-8' ) );
			}
		}
		return $out ?: $defaults;
	}

	/** Keep only known lens keys. */
	public static function clean( $value ) {
		if ( is_string( $value ) ) {
			$value = preg_split( '/[\s,]+/', $value );
		}
		if ( ! is_array( $value ) ) {
			return array();
		}
		$known = self::lenses();
		$out   = array();
		foreach ( $value as $key ) {
			$key = sanitize_key( (string) $key );
			if ( $key !== '' && isset( $known[ $key ] ) ) {
				$out[ $key ] = $key;
			}
		}
		return array_values( $out );
	}

	public static function add_controls( $element, $args ) {
		$element->start_controls_section(
			'ux_lens_section',
			array(
				'label' => __( 'Case Study Lens', 'ux-portfolio-optimized' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			)
		);
		$element->add_control(
			'ux_lens',
			array(
				'label'       => __( 'Show for lenses', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => self::lenses(),
				'default'     => array(),
				'description' => __( 'Leave empty to always show. When a visitor picks a lens in the Lens Switch, elements tagged only for other lenses are hidden.', 'ux-portfolio-optimized' ),
			)
		);
		$element->add_control(
			'ux_key',
			array(
				'label'        => __( 'Key moment (TL;DR)', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'description'  => __( 'Lists this element as a jump link in the TL;DR card.', 'ux-portfolio-optimized' ),
			)
		);
		$element->add_control(
			'ux_key_label',
			array(
				'label'       => __( 'Key moment label', 'ux-portfolio-optimized' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Uses the first heading if empty', 'ux-portfolio-optimized' ),
				'condition'   => array( 'ux_key' => 'yes' ),
			)
		);
		$element->end_controls_section();
	}

	public static function render_attribute( $element ) {
		if ( ! method_exists( $element, 'get_settings' ) ) {
			return;
		}
		$keys = self::clean( $element->get_settings( 'ux_lens' ) );
		if ( $keys ) {
			$element->add_render_attribute( '_wrapper', 'data-lens', implode( ' ', $keys ) );
		}
		if ( $element->get_settings( 'ux_key' ) === 'yes' ) {
			$label = trim( wp_strip_all_tags( (string) $element->get_settings( 'ux_key_label' ) ) );
			$element->add_render_attribute( '_wrapper', 'data-key-moment', $label !== '' ? $label : '1' );
		}
	}
}
