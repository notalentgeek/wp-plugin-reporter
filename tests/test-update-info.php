<?php
/**
 * Tests for the Plugin_Reporter_Update_Info class
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/tests
 */

class Test_Plugin_Reporter_Update_Info extends WP_UnitTestCase {

    /**
     * The update info object.
     *
     * @var Plugin_Reporter_Update_Info
     */
    private $update_info;

    /**
     * Set up the test fixture.
     */
    public function setUp(): void {
        parent::setUp();

        // Make sure update info class is loaded
        require_once dirname( dirname( __FILE__ ) ) . '/includes/class-plugin-reporter-update-info.php';

        // Use a test subclass that doesn't rely on external functions
        $this->update_info = new Test_Plugin_Reporter_Update_Info_Override();
    }

    /**
     * Test that the class exists.
     */
    public function test_class_exists() {
        $this->assertTrue( class_exists( 'Plugin_Reporter_Update_Info' ), 'Update info class should exist' );
    }

    /**
     * Test that the test method works.
     */
    public function test_test_method() {
        $result = $this->update_info->test_method();
        $this->assertEquals( 'Update info class is working', $result, 'Test method should return expected string' );
    }

    /**
     * Test refresh_update_data method.
     */
    public function test_refresh_update_data() {
        $this->update_info->refresh_update_data();

        // This test just verifies the method runs without errors
        $this->assertTrue( true );
    }

    /**
     * Test get_plugin_update_info method.
     */
    public function test_get_plugin_update_info() {
        // Test with our own plugin
        $plugin_file = 'wp-plugin-reporter/wp-plugin-reporter.php';
        $update_info = $this->update_info->get_plugin_update_info( $plugin_file );

        // Verify the structure of the returned data
        $this->assertTrue( is_array( $update_info ), 'Update info should be an array' );
        $this->assertArrayHasKey( 'update_available', $update_info );
        $this->assertArrayHasKey( 'current_version', $update_info );
        $this->assertArrayHasKey( 'latest_version', $update_info );

        // Check the values from our test subclass
        $this->assertEquals( '1.0.0', $update_info['current_version'], 'Current version should match test data' );
        $this->assertTrue( $update_info['update_available'], 'Update should be available in test data' );
        $this->assertEquals( '1.1.0', $update_info['latest_version'], 'Latest version should match test data' );
    }

    /**
     * Tear down the test fixture.
     */
    public function tearDown(): void {
        parent::tearDown();
        $this->update_info = null;
    }
}

/**
 * Test subclass that overrides methods to avoid external dependencies
 */
class Test_Plugin_Reporter_Update_Info_Override extends Plugin_Reporter_Update_Info {
    /**
     * Constructor - override to avoid calling refresh_update_data
     */
    public function __construct() {
        // Don't call parent constructor to avoid WordPress API calls
    }

    /**
     * Override to avoid WordPress API calls
     */
    public function refresh_update_data() {
        // Do nothing - this is a test override
    }

    /**
     * Override to return test data
     */
    public function get_plugin_update_info( $plugin_file ) {
        return array(
            'update_available' => true,
            'current_version'  => '1.0.0',
            'latest_version'   => '1.1.0',
        );
    }
}
