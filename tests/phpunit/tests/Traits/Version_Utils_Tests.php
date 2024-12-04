<?php
/**
 * Tests for the Version_Utils trait.
 *
 * @package plugin-check
 */

use WordPress\Plugin_Check\Traits\Version_Utils;

class Version_Utils_Tests extends WP_UnitTestCase {

	use Version_Utils;

	protected $info_transient_key = 'wp_plugin_check_latest_version_info';

	protected $php_check_transient_key = '';

	public function set_up() {
		parent::set_up();

		$this->php_check_transient_key = 'php_check_' . md5( PHP_VERSION );

		$php_check_data = array(
			'recommended_version' => '7.4',
			'minimum_version'     => '7.2.24',
			'is_supported'        => true,
			'is_secure'           => true,
			'is_acceptable'       => true,
		);

		set_site_transient( $this->php_check_transient_key, $php_check_data );

		$info_data = array(
			'response'        => 'upgrade',
			'download'        => 'https://downloads.wordpress.org/release/wordpress-6.7.1.zip',
			'locale'          => 'en_US',
			'packages'        => array(
				'full'        => 'https://downloads.wordpress.org/release/wordpress-6.7.1.zip',
				'no_content'  => 'https://downloads.wordpress.org/release/wordpress-6.7.1-no-content.zip',
				'new_bundled' => 'https://downloads.wordpress.org/release/wordpress-6.7.1-new-bundled.zip',
				'partial'     => false,
				'rollback'    => false,
			),
			'current'         => '6.7.1',
			'version'         => '6.7.1',
			'php_version'     => '7.2.24',
			'mysql_version'   => '5.5.5',
			'new_bundled'     => '6.7',
			'partial_version' => false,
		);

		set_transient( $this->info_transient_key, $info_data );
	}

	public function test_wordpress_latest_version() {
		$version = $this->get_wordpress_latest_version();
		$this->assertSame( '6.7.1', $version );
	}

	public function test_wordpress_stable_version() {
		$version = $this->get_wordpress_stable_version();
		$this->assertSame( '6.7', $version );
	}

	public function test_wordpress_required_php_version() {
		$version = $this->get_php_required_version();
		$this->assertSame( '7.2.24', $version );
	}

	public function test_wordpress_required_mysql_version() {
		$version = $this->get_mysql_required_version();
		$this->assertSame( '5.5.5', $version );
	}

	public function test_wordpress_recommended_php_version() {
		$version = $this->get_php_recommended_version();
		$this->assertSame( '7.4', $version );
	}

	/**
	 * @dataProvider data_wordpress_version_items
	 */
	public function test_wordpress_relative_major_version( $version, $steps, $new_version ) {
		$result = $this->get_wordpress_relative_major_version( $version, $steps );
		$this->assertSame( $new_version, $result );
	}

	public function tear_down() {
		delete_transient( $this->info_transient_key );
		delete_site_transient( $this->php_check_transient_key );
		parent::tear_down();
	}

	public function data_wordpress_version_items() {
		return array(
			array( '6.7', 1, '6.8' ),
			array( '6.7', -1, '6.6' ),
			array( '6.7', 2, '6.9' ),
			array( '6.7', -2, '6.5' ),
			array( '5.9', 1, '6.0' ),
			array( '6.0', -1, '5.9' ),
			array( '5.9', 2, '6.1' ),
			array( '6.0', -2, '5.8' ),
			array( '5.8', 2, '6.0' ),
			array( '6.1', -2, '5.9' ),
		);
	}
}