<?php
/**
 * Post Type Taxonomy Tabs Manager
 *
 * @package     ArrayPress/Utils/TaxonomyTabs
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      ArrayPress
 */

declare( strict_types=1 );

namespace ArrayPress\WP;

defined( 'ABSPATH' ) || exit;

/**
 * Class Stasher
 *
 * Utility class for capturing and storing WordPress filter values for later use.
 * Allows storing parameters by position or with named keys.
 */
class Stasher {

	/**
	 * Storage for stashed values
	 *
	 * @var array
	 */
	private static array $stashed = [];

	/**
	 * Stash parameters from a filter
	 *
	 * @param string $filter        The filter hook to capture values from
	 * @param array  $param_names   Optional. Names for the parameters. If empty, parameters will be stored by position.
	 * @param string $mode          Optional. Storage mode: 'always', 'first', or 'empty'. Default 'always'.
	 * @param array  $required_args Optional. Names or indexes of parameters that must have non-empty values.
	 *
	 * @return void
	 */
	public static function stash(
		string $filter,
		array $param_names = [],
		string $mode = 'always',
		array $required_args = []
	): void {
		add_filter( $filter, function ( ...$args ) use ( $filter, $param_names, $mode, $required_args ) {
			// Check if we should store based on mode
			if (
				( $mode === 'first' && self::has( $filter ) ) ||
				( $mode === 'empty' && self::has( $filter ) )
			) {
				return $args[0];
			}

			// Convert args to either indexed or named array
			$values = empty( $param_names ) ? $args : array_combine( $param_names, $args );

			// Check required arguments
			foreach ( $required_args as $required ) {
				// Skip if the required arg doesn't exist or is empty
				if ( empty( $values[ $required ] ) ) {
					return $args[0];
				}
			}

			// Store values
			self::$stashed[ $filter ] = $values;

			return $args[0];
		}, 99999 );
	}

	/**
	 * Get stashed parameters from a filter
	 *
	 * @param string     $filter The filter to retrieve values for
	 * @param string|int $param  Optional. Specific parameter to retrieve (by name or index)
	 *
	 * @return mixed|null Array of all parameters, specific parameter value, or null if not found
	 */
	public static function get( string $filter, $param = null ) {
		if ( ! isset( self::$stashed[ $filter ] ) ) {
			return null;
		}

		if ( $param !== null ) {
			return self::$stashed[ $filter ][ $param ] ?? null;
		}

		return self::$stashed[ $filter ];
	}

	/**
	 * Get all stashed values
	 *
	 * @return array Array of all stashed values
	 */
	public static function get_all(): array {
		return self::$stashed;
	}

	/**
	 * List all stored filters
	 *
	 * @return array Array of filter names
	 */
	public static function list(): array {
		return array_keys( self::$stashed );
	}

	/**
	 * Check if a filter has stashed values
	 *
	 * @param string $filter The filter to check
	 *
	 * @return bool True if filter has stashed values, false otherwise
	 */
	public static function has( string $filter ): bool {
		return isset( self::$stashed[ $filter ] ) && ! empty( self::$stashed[ $filter ] );
	}

	/**
	 * Clear stashed values for a specific filter or all filters
	 *
	 * @param string $filter Optional. Specific filter to clear. If empty, clears all stashed values.
	 *
	 * @return void
	 */
	public static function clear( string $filter = '' ): void {
		if ( empty( $filter ) ) {
			self::$stashed = [];
		} else {
			unset( self::$stashed[ $filter ] );
		}
	}

}