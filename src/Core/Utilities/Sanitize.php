<?php
/**
 * LearnDash Sanitization class.
 *
 * @since 4.8.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Utilities;

use InvalidArgumentException;

/**
 * A helper class to sanitize various types of data.
 *
 * @since 4.8.0
 */
class Sanitize {
	/**
	 * Sanitize array recursively.
	 *
	 * @since 4.8.0
	 *
	 * @throws InvalidArgumentException Throws an exception when $sanitize_fn argument is invalid.
	 *
	 * @param array<mixed>    $array       Array in key value pair.
	 * @param string|callable $sanitize_fn Sanitization function name or callable to sanitize the array value.
	 *
	 * @return array<mixed>
	 */
	public static function array( array $array, $sanitize_fn = 'sanitize_text_field' ): array {
		if ( ! is_callable( $sanitize_fn ) ) {
			throw new InvalidArgumentException( 'Sanitization function or callback is invalid.' );
		}

		return array_map(
			function( $value ) use ( $sanitize_fn ) {
				return is_array( $value )
					? self::array( $value )
					: call_user_func( $sanitize_fn, $value );
			},
			$array
		);
	}
}
