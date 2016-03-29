<?php namespace Neomerx\Tests\LimoncelloIlluminate;

/**
 * @package Neomerx\LimoncelloIlluminate\Tests
 */
trait HomePageTestTrait
{
    use TestCaseTrait;

    /**
     * @return void
     */
    public function testExample()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals($response->getContent(), 'JSON API Neomerx Demo Application');
    }
}
