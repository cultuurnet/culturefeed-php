<?php
/**
 * @file
 */

abstract class CultureFeed_ReportStatus {

  public function inProgress() {
    return FALSE;
  }

  public function completed() {
    return FALSE;
  }

  /**
   * @param CultureFeed_Response $response
   *
   * @return CultureFeed_ReportStatus
   */
  public static function createFromResponse(CultureFeed_Response $response) {
    switch ($response->getMessage()) {
      case 'Export completed.':
        return new CultureFeed_ReportStatusCompleted();
      case 'Export in progress.':
        return new CultureFeed_ReportStatusInProgress();
      default:
        throw new RuntimeException(
          'Invalid report status response with message: ' . $response->getMessage()
        );
    }
  }

} 
