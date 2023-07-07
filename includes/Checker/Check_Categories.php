<?php
/**
 * Class WordPress\Plugin_Check\Checker\Check_Categories
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Checker;

/**
 * Abstract Check Runner class.
 *
 * @since n.e.x.t
 */
class Check_Categories {

	// Constants for available categories.
	const CATEGORY_GENERAL       = 'general';
	const CATEGORY_PLUGIN_REPO   = 'plugin_repo';
	const CATEGORY_SECURITY      = 'security';
	const CATEGORY_PERFORMANCE   = 'performance';
	const CATEGORY_ACCESSIBILITY = 'accessibility';

	/**
	 * Returns an array of available categories.
	 *
	 * @since n.e.x.t
	 *
	 * @return array An array of available categories.
	 */
	public static function get_categories() {
		static $categories = '';
		if ( ! $categories ) {
			$constants = ( new \ReflectionClass( __CLASS__ ) )->getConstants();

			/**
			 * List of categories.
			 *
			 * @var string[] $categories
			 */
			$categories = array_values(
				array_filter(
					$constants,
					static function( $key ) {
						return strpos( $key, 'CATEGORY_' ) === 0;
					},
					ARRAY_FILTER_USE_KEY
				)
			);
		}

		return $categories;
	}

	/**
	 * Returns an array of checks.
	 *
	 * @since n.e.x.t
	 *
	 * @param array $checks     An array of Check instances.
	 * @param array $categories An array of available categories.
	 * @return array Filtered $checks list.
	 */
	public static function filter_checks_by_categories( array $checks, array $categories ) {
		return array_filter(
			$checks,
			static function( $check ) use ( $categories ) {
				return in_array( $check->get_category(), $categories, true );
			}
		);
	}
}