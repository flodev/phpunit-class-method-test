<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

class TestListener implements PHPUnit_Framework_TestListener
{
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
//        printf("Error while running test '%s'.\n", $test->getName());
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
//        printf("Test '%s' failed.\n", $test->getName());
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
//        printf("Test '%s' is incomplete.\n", $test->getName());
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
//        printf("Test '%s' has been skipped.\n", $test->getName());
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
//        printf("Test '%s' started.\n", $test->getName());
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $coverage = $test->getTestResultObject()->getCodeCoverage();
        $coverage->start($test, false);

        $test->getTestResultObject()->getCodeCoverage()->append(
            array('/Users/florian/projects/phpunit-class-method-test/PHPUnit/TestClass.php' =>
                array(
                    26 => 1
                )
            )
        );
        $coverage->stop();
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
//        printf("TestSuite '%s' started.\n", $suite->getName());
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
//        printf("TestSuite '%s' ended.\n", $suite->getName());
    }
}