<?php

namespace Helper;

use Codeception\TestCase;
use Codeception\Util\Stub;

class Craft extends \Codeception\Module
{
    /**
     * @var array Containing service stubs
     */
    protected $serviceStubs = [];

    public function _before(TestCase $test)
    {
        $this->serviceStubs['path'] = Stub::make('\Craft\PathService', [
            'getTemplatesPath' => function() {
                return 'craft/templates';
            }
        ]);

        parent::_before($test);
    }

    /**
     * @return \Craft\PathService
     */
    public function getPathServiceStub()
    {
        return $this->serviceStubs['path'];
    }

}
