<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */
namespace Tests;

use PHPUnit\Framework\ClassMethodTest\Build as BuildClassMethodTest;

class ExporterTest extends \PHPUnit_Framework_TestCase
{
    public function testExportImages()
    {
        $proxy = BuildClassMethodTest::from('\TestObjects\Exporter')
                ->testMethod('exportImages')
                ->testMethod('exportNews')
                ->mockClassProperty('db1', $this->getDbMock())
//                ->mockClassProperty('db2', $this->getDbMock())
                ->create();

        $proxy->createInstance();
        $proxy->exec('exportImages');
        $proxy->exec('exportNews');
    }

    private function getDbMock()
    {
        $mock = $this->getMockBuilder('\TestObjects\DbAdapter')->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method('exec');
        return $mock;
    }
}
