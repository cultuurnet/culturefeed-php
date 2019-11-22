<?php

class CultureFeed_ICultureFeedDecoratorBaseTest extends PHPUnit_Framework_TestCase
{
    public function testInheritingClassCanBeInstantiated()
    {
        $subject = new CultureFeed_ICultureFeedDecoratorBaseTestImplementation(
            $this->createMock('\ICultureFeed')
        );
        $this->assertInstanceOf('CultureFeed_ICultureFeedDecoratorBase', $subject);
    }
}

class CultureFeed_ICultureFeedDecoratorBaseTestImplementation extends CultureFeed_ICultureFeedDecoratorBase
{
}
