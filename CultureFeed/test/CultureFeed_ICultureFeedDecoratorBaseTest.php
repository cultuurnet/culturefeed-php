<?php

use PHPUnit\Framework\TestCase;

class CultureFeed_ICultureFeedDecoratorBaseTest extends TestCase
{
    public function testInheritingClassCanBeInstantiated(): void
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
