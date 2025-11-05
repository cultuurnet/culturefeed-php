<?php

use PHPUnit\Framework\TestCase;

/**
 * @file
 * Testing methods for the CultureFeed_Activity class.
 */

class CultureFeed_ActivityTest extends TestCase {

  /**
   * Test if the calendar date property is correctly added to the post data.
   */
  public function testCalendarDateToPostData(): void {

    $activity = new CultureFeed_Activity();

    $result = $activity->toPostData();
    $this->assertFALSE(array_key_exists('calendarDate', $result));

    $activity->calendarDate = '2011-07-25T22:03Z';
    $result = $activity->toPostData();
    $this->assertTRUE(array_key_exists('calendarDate', $result));
  }
}

