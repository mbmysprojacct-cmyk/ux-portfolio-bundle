<?php
/**
 * Base Widget Class
 * Shared helper methods for all UX Portfolio widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class UX_Base_Widget extends \Elementor\Widget_Base {

	public function get_categories() {
		return array( 'ux-portfolio' );
	}

	/**
	 * Add standard title and description fields
	 */
	protected function add_standard_header_section() {
		$this->start_controls_section(
			'header_section',
			array(
				'label' => __( 'Header', 'ux-portfolio-optimized' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __( 'Title', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => __( 'Show Description', 'ux-portfolio-optimized' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'     => __( 'Description', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::WYSIWYG,
				'default'   => '',
				'condition' => array( 'show_description' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add standard description alignment in style tab
	 */
	protected function add_description_alignment() {
		$this->add_responsive_control(
			'description_align',
			array(
				'label'     => __( 'Description Alignment', 'ux-portfolio-optimized' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'ux-portfolio-optimized' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .widget-description' => 'text-align: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Render standard header HTML
	 */
	protected function render_standard_header() {
		$s = $this->get_settings_for_display();

		if ( $s['show_title'] === 'yes' && ! empty( $s['title'] ) ) {
			echo '<h2 class="widget-title">' . esc_html( $s['title'] ) . '</h2>';
		}

		if ( $s['show_description'] === 'yes' && ! empty( $s['description'] ) ) {
			echo '<div class="widget-description">' . wp_kses_post( $s['description'] ) . '</div>';
		}
	}

	/**
	 * Add color control with simplified syntax
	 */
	protected function add_color( $id, $label, $selector, $property = 'color', $default = '' ) {
		$this->add_control(
			$id,
			array(
				'label'     => $label,
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => $property . ': {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Add responsive number control (slider)
	 */
	protected function add_responsive_number( $id, $label, $selector, $property, $default = null, $min = 0, $max = 500 ) {
		$this->add_responsive_control(
			$id,
			array(
				'label'      => $label,
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => $min,
						'max' => $max,
					),
				),
				'default'    => $default ? array(
					'size' => $default,
					'unit' => 'px',
				) : null,
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => $property . ': {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Add typography control
	 */
	protected function add_typography_control( $id, $label, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => $id,
				'label'    => $label,
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Add background control
	 */
	protected function add_background_control( $id, $label, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => $id,
				'label'    => $label,
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Add border control
	 */
	protected function add_border_control( $id, $label, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => $id,
				'label'    => $label,
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Add box shadow control
	 */
	protected function add_shadow_control( $id, $label, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $id,
				'label'    => $label,
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Add padding control
	 */
	protected function add_padding_control( $id, $label, $selector ) {
		$this->add_responsive_control(
			$id,
			array(
				'label'      => $label,
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Add margin control
	 */
	protected function add_margin_control( $id, $label, $selector ) {
		$this->add_responsive_control(
			$id,
			array(
				'label'      => $label,
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Add border radius control
	 */
	protected function add_border_radius( $id, $label, $selector, $default = null ) {
		$this->add_responsive_control(
			$id,
			array(
				'label'      => $label,
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => $default,
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Render icon safely
	 */
	protected function render_icon( $icon ) {
		if ( ! empty( $icon['value'] ) ) {
			\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		}
	}

	/**
	 * Clean SVG markup with an allowlist before printing it inline.
	 * Keeps shapes, text, gradients, masks and <style>; drops scripts, event
	 * handlers, foreignObject, external images and any non-allowlisted tag.
	 */
	protected static function kses_svg( $svg ) {
		$svg = (string) $svg;
		$svg = preg_replace( '/<\?xml[^>]*\?>/i', '', $svg );
		$svg = preg_replace( '/<!DOCTYPE[^>]*>/i', '', $svg );
		$svg = preg_replace( '/<!--.*?-->/s', '', $svg );

		$common  = array_fill_keys(
			array(
				'id',
				'class',
				'style',
				'fill',
				'fill-rule',
				'fill-opacity',
				'stroke',
				'stroke-width',
				'stroke-linecap',
				'stroke-linejoin',
				'stroke-miterlimit',
				'stroke-dasharray',
				'stroke-dashoffset',
				'stroke-opacity',
				'opacity',
				'transform',
				'clip-path',
				'clip-rule',
				'mask',
				'filter',
				'display',
				'visibility',
				'color',
				'font-family',
				'font-size',
				'font-weight',
				'font-style',
				'text-anchor',
				'dominant-baseline',
				'letter-spacing',
				'vector-effect',
				'shape-rendering',
				'aria-hidden',
				'aria-label',
				'role',
				'focusable',
			),
			true
		);
		$el      = function ( $extra = array() ) use ( $common ) {
			return array_merge( $common, array_fill_keys( $extra, true ) );
		};
		$allowed = array(
			'svg'            => $el( array( 'xmlns', 'xmlns:xlink', 'viewbox', 'width', 'height', 'preserveaspectratio', 'version', 'x', 'y' ) ),
			'g'              => $el(),
			'defs'           => $el(),
			'title'          => $el(),
			'desc'           => $el(),
			'symbol'         => $el( array( 'viewbox', 'preserveaspectratio' ) ),
			'use'            => $el( array( 'href', 'x', 'y', 'width', 'height' ) ),
			'path'           => $el( array( 'd', 'pathlength' ) ),
			'circle'         => $el( array( 'cx', 'cy', 'r' ) ),
			'ellipse'        => $el( array( 'cx', 'cy', 'rx', 'ry' ) ),
			'rect'           => $el( array( 'x', 'y', 'width', 'height', 'rx', 'ry' ) ),
			'line'           => $el( array( 'x1', 'y1', 'x2', 'y2' ) ),
			'polyline'       => $el( array( 'points' ) ),
			'polygon'        => $el( array( 'points' ) ),
			'text'           => $el( array( 'x', 'y', 'dx', 'dy', 'textlength' ) ),
			'tspan'          => $el( array( 'x', 'y', 'dx', 'dy' ) ),
			'lineargradient' => $el( array( 'x1', 'y1', 'x2', 'y2', 'gradientunits', 'gradienttransform', 'spreadmethod' ) ),
			'radialgradient' => $el( array( 'cx', 'cy', 'r', 'fx', 'fy', 'gradientunits', 'gradienttransform', 'spreadmethod' ) ),
			'stop'           => $el( array( 'offset', 'stop-color', 'stop-opacity' ) ),
			'clippath'       => $el( array( 'clippathunits' ) ),
			'mask'           => $el( array( 'x', 'y', 'width', 'height', 'maskunits', 'maskcontentunits' ) ),
			'pattern'        => $el( array( 'x', 'y', 'width', 'height', 'patternunits', 'patterncontentunits', 'patterntransform', 'viewbox' ) ),
			'style'          => array( 'type' => true ),
		);

		// Let inline style="" keep SVG paint properties (kses strips unknown CSS properties).
		$svg_css = function ( $props ) {
			return array_merge(
				$props,
				array(
					'fill',
					'fill-opacity',
					'fill-rule',
					'stroke',
					'stroke-width',
					'stroke-linecap',
					'stroke-linejoin',
					'stroke-opacity',
					'stroke-dasharray',
					'stop-color',
					'stop-opacity',
					'opacity',
					'isolation',
					'mix-blend-mode',
				)
			);
		};
		add_filter( 'safe_style_css', $svg_css );
		$clean = wp_kses( $svg, $allowed, array( 'http', 'https' ) );
		remove_filter( 'safe_style_css', $svg_css );

		// kses lowercases attribute names; restore SVG's camelCase ones.
		$camel = array(
			'viewBox',
			'preserveAspectRatio',
			'gradientUnits',
			'gradientTransform',
			'spreadMethod',
			'clipPathUnits',
			'maskUnits',
			'maskContentUnits',
			'patternUnits',
			'patternContentUnits',
			'patternTransform',
			'pathLength',
			'textLength',
		);
		foreach ( $camel as $name ) {
			$clean = preg_replace( '/(\s)' . strtolower( $name ) . '(\s*=)/', '$1' . $name . '$2', $clean );
		}

		// <use> may only point at fragments inside this SVG.
		$clean = preg_replace( '/(<use\b[^>]*?)\s+href\s*=\s*(["\'])(?!#)[^"\']*\2/i', '$1', $clean );

		return stripos( $clean, '<svg' ) !== false ? trim( $clean ) : '';
	}

	/**
	 * Get unique ID for widget instance
	 */
	protected function get_widget_id() {
		return 'ux-' . $this->get_name() . '-' . $this->get_id();
	}
}
