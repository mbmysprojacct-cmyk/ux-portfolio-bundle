<?php
/**
 * Global helpers for the UX Portfolio Bundle.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Escape a single CSS value (a color, size, font name) for an inline style or
 * a <style> block. Removes anything that could end the declaration or rule
 * (; { } < > \ comments, line breaks) and refuses values that load or run
 * something (url(), expression(), @import, javascript:). Returns '' when the
 * value is refused, so the property falls back to the stylesheet.
 *
 * @param mixed $value Raw setting value.
 * @return string Value safe to print inside a style attribute.
 */
function ux_portfolio_esc_css( $value ) {
	if ( ! is_scalar( $value ) ) {
		return '';
	}
	$value = preg_replace( '#[;{}<>\\\\\r\n]|/\*|\*/#', '', (string) $value );
	if ( preg_match( '#url\s*\(|expression\s*\(|@import|javascript\s*:#i', $value ) ) {
		return '';
	}
	return esc_attr( trim( $value ) );
}
