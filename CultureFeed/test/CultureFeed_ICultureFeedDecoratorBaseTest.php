<?php
/**
 * @file
 */

class CultureFeed_ICultureFeedDecoratorBaseTest extends PHPUnit_Framework_TestCase {

    public function testInheritingClassCanBeInstantiated() {
        $subject = new CultureFeed_ICultureFeedDecoratorBaseTestImplementation(
            $this->getMock('\ICultureFeed')
        );
    }
}

class CultureFeed_ICultureFeedDecoratorBaseTestImplementation extends CultureFeed_ICultureFeedDecoratorBase {

}
