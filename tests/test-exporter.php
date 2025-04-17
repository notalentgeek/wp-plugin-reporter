<?php
/**
 * Tests for the Plugin_Reporter_Exporter class
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/tests
 */

/**
 * Test class for Plugin_Reporter_Exporter
 */
class Test_Plugin_Reporter_Exporter extends WP_UnitTestCase {

    /**
     * The exporter object.
     *
     * @var Plugin_Reporter_Exporter
     */
    private $exporter;

    /**
     * Set up the test fixture.
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        // Make sure exporter class is loaded
        require_once dirname( dirname( __FILE__ ) ) . '/includes/class-plugin-reporter-exporter.php';
        $this->exporter = new Plugin_Reporter_Exporter();
    }

    /**
     * Test that we can get plugin data.
     *
     * @return void
     */
    public function test_get_plugin_data() {
        $data = $this->exporter->get_plugin_data();

        // Check that we got an array
        $this->assertTrue( is_array( $data ) );

        // Check that we have at least one plugin (this plugin)
        $this->assertGreaterThanOrEqual( 1, count( $data ) );

        // Check that the first plugin has the expected keys
        if ( count( $data ) > 0 ) {
            $first_plugin = $data[0];
            $this->assertArrayHasKey( 'name', $first_plugin );
            $this->assertArrayHasKey( 'version', $first_plugin );
            $this->assertArrayHasKey( 'status', $first_plugin );
            $this->assertArrayHasKey( 'description', $first_plugin );
            $this->assertArrayHasKey( 'author', $first_plugin );
            $this->assertArrayHasKey( 'plugin_uri', $first_plugin );
            $this->assertArrayHasKey( 'plugin_path', $first_plugin );
        }
    }

    /**
     * Test that we can export plugin data as CSV.
     *
     * @return void
     */
    public function test_export_as_csv() {
        $csv = $this->exporter->export_as_csv();

        // Check that we got a string
        $this->assertTrue( is_string( $csv ) );

        // Check that the CSV contains expected headers
        $this->assertStringContainsString( 'Name', $csv );
        $this->assertStringContainsString( 'Version', $csv );
        $this->assertStringContainsString( 'Status', $csv );
    }

    /**
     * Test that we can export plugin data as JSON.
     *
     * @return void
     */
    public function test_export_as_json() {
        $json = $this->exporter->export_as_json();

        // Check that we got a string
        $this->assertTrue( is_string( $json ) );

        // Check that the JSON is valid
        $data = json_decode( $json, true );
        $this->assertNotNull( $data );
        $this->assertTrue( is_array( $data ) );

        // Check that the JSON contains at least one plugin
        $this->assertGreaterThanOrEqual( 1, count( $data ) );
    }

    /**
     * Tear down the test fixture.
     *
     * @return void
     */
    public function tearDown(): void {
        parent::tearDown();
        $this->exporter = null;
    }

    /**
     * Test the file size calculation functionality
     */
    public function test_calculate_plugin_size() {
        // Create a mock plugin path
        $plugin_path = 'plugin-reporter/plugin-reporter.php';

        // Get the actual plugin directory to test with a real example
        $exporter = new Plugin_Reporter_Exporter();
        $size_data = $exporter->calculate_plugin_size($plugin_path);

        // Assert that the structure is correct
        $this->assertIsArray($size_data);
        $this->assertArrayHasKey('total_size', $size_data);
        $this->assertArrayHasKey('subdirectories', $size_data);
        $this->assertArrayHasKey('human_readable_size', $size_data);

        // The size should be a non-negative number
        $this->assertGreaterThanOrEqual(0, $size_data['total_size']);

        // Subdirectories should be an array
        $this->assertIsArray($size_data['subdirectories']);

        // Human readable size should be a string
        $this->assertIsString($size_data['human_readable_size']);
    }

    /**
     * Test the file size formatting function
     */
    public function test_format_file_size() {
        $exporter = new Plugin_Reporter_Exporter();

        // Test bytes
        $this->assertEquals('0 bytes', $exporter->format_file_size(0));
        $this->assertEquals('1 byte', $exporter->format_file_size(1));
        $this->assertEquals('500 bytes', $exporter->format_file_size(500));

        // Test kilobytes
        $this->assertEquals('1.00 KB', $exporter->format_file_size(1024));
        $this->assertEquals('1.50 KB', $exporter->format_file_size(1536));

        // Test megabytes
        $this->assertEquals('1.00 MB', $exporter->format_file_size(1048576));
        $this->assertEquals('2.50 MB', $exporter->format_file_size(2621440));

        // Test gigabytes
        $this->assertEquals('1.00 GB', $exporter->format_file_size(1073741824));
        $this->assertEquals('2.50 GB', $exporter->format_file_size(2684354560));
    }

    /**
     * Test get_plugin_data with size information
     */
    public function test_get_plugin_data_with_size_info() {
        // Create a mock for Plugin_Reporter_Update_Info if needed
        // Then create the exporter
        $exporter = new Plugin_Reporter_Exporter();

        // Call with size info parameter
        $plugins = $exporter->get_plugin_data(true, true);

        // Verify structure of first plugin
        $this->assertIsArray($plugins);
        $this->assertNotEmpty($plugins);

        // If plugins exist, check size fields are present
        if (!empty($plugins)) {
            $this->assertArrayHasKey('size_bytes', $plugins[0]);
            $this->assertArrayHasKey('size_human', $plugins[0]);
            $this->assertArrayHasKey('subdirectory_sizes', $plugins[0]);
        }
    }

    /**
     * Test export methods include size information
     */
    public function test_export_includes_size_info() {
        $exporter = new Plugin_Reporter_Exporter();

        // Test CSV export
        $csv = $exporter->export_as_csv(true);
        $this->assertIsString($csv);
        $this->assertStringContainsString('Size', $csv); // Header should include Size

        // Test JSON export
        $json = $exporter->export_as_json(true);
        $this->assertIsString($json);
        $this->assertStringContainsString('size_bytes', $json);
        $this->assertStringContainsString('size_human', $json);
    }
}

