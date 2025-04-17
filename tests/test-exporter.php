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
}
