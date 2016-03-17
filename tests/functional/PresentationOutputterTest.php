<?php

/**
 * Class PresentationOutputterTest
 *
 * {vendor/bin/codecept run functional PresentationOutputterTest.php}
 */
class PresentationOutputterTest extends \Codeception\TestCase\Test
{
    /**
     * @var \FunctionalTester
     */
    protected $tester;

    public function _after()
    {}

    public function _before()
    {}

    // tests

    /**
     * @test
     *
     * vendor/bin/codecept run functional PresentationOutputterTest.php:OutputterOutputsSomething
     */
    public function OutputterOutputsSomething()
    {
        // Dependencies
        $outputter = new \Presentation\Outputter($this->tester->getPathServiceStub());

        // Given
        $message = 'Hello world!';

        // When

        // Then
        $this->assertEquals($message, $outputter->output($message));
    }

    /**
     * @test
     *
     * vendor/bin/codecept run functional PresentationOutputterTest.php:OutputterReturnsTemplatesPath
     */
    public function OutputterReturnsTemplatesPath()
    {
        // Dependencies
        $outputter = new \Presentation\Outputter($this->tester->getPathServiceStub());

        // Given

        // When

        // Then
        $this->assertEquals("craft/templates", $outputter->getTemplatesPath());
    }
}
