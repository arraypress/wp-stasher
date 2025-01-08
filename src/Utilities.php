<?php
/**
 * Stasher Utility Functions
 *
 * @package     ArrayPress\WP\Stasher
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

use ArrayPress\WP\Stasher;

if ( ! function_exists( 'stash_filter' ) ):
	/**
	 * Helper function to stash filter parameters
	 *
	 * @param string $filter        The filter hook to capture values from
	 * @param array  $param_names   Optional. Names for the parameters
	 * @param string $mode          Optional. Storage mode: 'always', 'first', or 'empty'
	 * @param array  $required_args Optional. Names or indexes of parameters that must have non-empty values
	 *
	 * @return void
	 */
	function stash_filter( string $filter, array $param_names = [], string $mode = 'always', array $required_args = [] ): void {
		Stasher::stash( $filter, $param_names, $mode, $required_args );
	}
endif;

if ( ! function_exists( 'get_stashed' ) ):
	/**
	 * Helper function to retrieve stashed filter values
	 *
	 * @param string     $filter The filter to retrieve values for
	 * @param string|int $param  Optional. Specific parameter to retrieve (by name or index)
	 *
	 * @return mixed|null Array of all parameters, specific parameter value, or null if not found
	 */
	function get_stashed( string $filter, $param = null ) {
		return Stasher::get( $filter, $param );
	}
endif;

if ( ! function_exists( 'has_stashed' ) ):
	/**
	 * Helper function to check if a filter has stashed values
	 *
	 * @param string $filter The filter to check
	 *
	 * @return bool True if filter has stashed values, false otherwise
	 */
	function has_stashed( string $filter ): bool {
		return Stasher::has( $filter );
	}
endif;

if ( ! function_exists( 'clear_stashed' ) ):
	/**
	 * Helper function to clear stashed values
	 *
	 * @param string $filter Optional. Specific filter to clear. If empty, clears all stashed values.
	 *
	 * @return void
	 */
	function clear_stashed( string $filter = '' ): void {
		Stasher::clear( $filter );
	}
endif;

if ( ! function_exists( 'get_all_stashed' ) ):
	/**
	 * Helper function to get all stashed values
	 *
	 * @return array Array of all stashed values
	 */
	function get_all_stashed(): array {
		return Stasher::get_all();
	}
endif;

if ( ! function_exists( 'list_stashed' ) ):
	/**
	 * Helper function to list all stored filters
	 *
	 * @return array Array of filter names
	 */
	function list_stashed(): array {
		return Stasher::list();
	}
endif;