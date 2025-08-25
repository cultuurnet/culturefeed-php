<?php

use PHPUnit\Framework\TestCase;

class CultureFeed_Uitpas_FinancialOverviewExportAPITest extends TestCase {

  public function testGetFinancialOverviewReportPeriods() {
    $oauth_client_stub = $this->createMock('CultureFeed_OAuthClient');

    $balie_consumer_key = 'e52efb7f-2eab-47a5-9cf3-9e7413ffd942';

    $xml = file_get_contents(__DIR__ . '/data/financial_overview_reports/periods.xml');

    $oauth_client_stub
      ->method('authenticatedGetAsXml')
      ->with('uitpas/report/financialoverview/organiser/periods', array(
          'balieConsumerKey' => $balie_consumer_key,
        ))
      ->willReturn($xml);

    $cf = new CultureFeed($oauth_client_stub);

    $result = $cf->uitpas()->getFinancialOverviewReportPeriods($balie_consumer_key);

    $this->assertEquals(
      [
        new CultureFeed_Uitpas_Calendar_Period(
          1585692000,
          1593554399
        ),
        new CultureFeed_Uitpas_Calendar_Period(
          1577833200,
          1585691999
        ),
        new CultureFeed_Uitpas_Calendar_Period(
          1569880800,
          1577833199
        ),
      ],
      $result
    );
  }
}
