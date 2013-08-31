<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 *
 */
namespace ClassMethodTest;

use ClassMethodTest\CodeCoverage as MethodTestCoverage;

class TestListener implements \PHPUnit_Framework_TestListener
{
    public function __construct() {}

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time) {}

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}

    public function startTest(\PHPUnit_Framework_Test $test) {}

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $testResult = $test->getTestResultObject();

        if (empty($testResult)) {
            return;
        }
        /**
         * @todo decide whether xdebug is used and testlistener is registered,
         * then use tmp file instead of eval
         * cleanup tmp file in test listener ... ?
         */


        if ($testResult->getCollectCodeCoverageInformation()) {
            $coverage = $testResult->getCodeCoverage();
            $methodTestCoverage = new MethodTestCoverage($coverage);

            if ($methodTestCoverage->hasCoverage()) {
                $coverage->start($test, false);
                $testResult->getCodeCoverage()->append(
                    $methodTestCoverage->getCoverage()
                );
                $coverage->stop();
            }

            $this->deleteTmpFile();
        }
    }

    private function deleteTmpFile()
    {
        $path = ParseInfo::getInstance()->getGeneratedClassFilePath();
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite) {}

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) {}
}