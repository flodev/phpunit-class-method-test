<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 *
 */
namespace PHPUnit\Framework\ClassMethodTest;

use PHPUnit\Framework\ClassMethodTest\CodeCoverage as MethodTestCoverage;

class TestListener implements \PHPUnit_Framework_TestListener
{
    public function __construct()
    {
//        $refl = new ReflectionClass('\PHPUnit\Framework\ClassMethodTest\ClassGenerator');
//        $this->classGeneratorPath = $refl->getFileName();
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
//        printf("Error while running test '%s'.\n", $test->getName());
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
//        printf("Test '%s' failed.\n", $test->getName());
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
//        printf("Test '%s' is incomplete.\n", $test->getName());
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
//        printf("Test '%s' has been skipped.\n", $test->getName());
    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {
//        printf("Test '%s' started.\n", $test->getName());
    }

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
        } else {
            echo "\n\n~~~~ " . __METHOD__ . " Run without xdebug ~~~~\n\n";
        }
    }

    private function deleteTmpFile()
    {
//        MethodTestCoverage::getFilePath();
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
//        printf("TestSuite '%s' started.\n", $suite->getName());
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
//        printf("TestSuite '%s' ended.\n", $suite->getName());
    }
}