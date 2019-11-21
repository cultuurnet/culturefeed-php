<?php

use PHPUnit\Framework\TestCase;

class BackwardsCompatiblePHPUnitTestCase extends TestCase
{
    public function getMock($className)
    {
        return $this->createMock($className);
    }

    public function setExpectedException($className, $message = null, $code = null)
    {
        $this->expectException($className);

        if ($message) {
            $this->expectExceptionMessage($message);
        }

        if ($code) {
            $this->expectExceptionCode($code);
        }
    }
}
