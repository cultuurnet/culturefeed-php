<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResultTest extends PHPUnit_Framework_TestCase {

  protected $dataDir;

  protected function setUp() {
    $this->dataDir = dirname(__FILE__) . '/data/eventactions';
  }

  /**
   * @param string $file
   * @return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult
   */
  protected function loadSample($file) {
    $xml = file_get_contents($this->dataDir. '/' . $file);
    $xml_element = new CultureFeed_SimpleXMLElement($xml);
    return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult::createFromXML($xml_element);
  }

  public function testCreateFromXML() {
    $result = $this->loadSample('post.xml');

    $expectedPassholder = new CultureFeed_Uitpas_Passholder();
    $expectedPassholder->registrationBalieConsumerKey = '47B6FA21-ACB1-EA8F-2C231182C7DD0A19';
    $expectedPassholder->name = 'ryopl';
    $expectedPassholder->firstName = 'fatru';
    $expectedPassholder->nationality = 'ituliexhul';
    $expectedPassholder->dateOfBirth = 515721600;
    $expectedPassholder->gender = 'MALE';
    $expectedPassholder->street = 'umrop 8';
    $expectedPassholder->verified = false;
    $expectedPassholder->points = 10;
    $expectedPassholder->numberOfCheckins = 4;
    $expectedPassholder->inszNumberHash = 'f29f5e1a8289e6453fdfa6a51a01778a71adf70e';
    $expectedPassholder->gsm = '0485/55.62.24';
    $expectedPassholder->city = 'Aalst';
    $expectedPassholder->postalCode = '9300';

    $expectedPassholder->uitIdUser = new CultureFeed_Uitpas_Passholder_UitIdUser();
    $expectedPassholder->uitIdUser->id = 'eb0cc9fa-bf36-4898-8fce-a20ad040f3df';

    $passholderCardSystemData = new CultureFeed_Uitpas_Passholder_CardSystemSpecific();
    $passholderCardSystemData->cardSystem = new CultureFeed_Uitpas_CardSystem();
    $passholderCardSystemData->cardSystem->id = '1';
    $passholderCardSystemData->cardSystem->name = 'UiTPAS Regio Aalst';

    $passholderCardSystemData->currentCard = new CultureFeed_Uitpas_Passholder_Card();
    $passholderCardSystemData->currentCard->kansenpas = false;
    $passholderCardSystemData->currentCard->status = CultureFeed_Uitpas_Passholder_Card::STATUS_ACTIVE;
    $passholderCardSystemData->currentCard->uitpasNumber = '0942000000885';

    $passholderCardSystemData->emailPreference = CultureFeed_Uitpas_Passholder_CardSystemPreferences::EMAIL_ALL_MAILS;
    $passholderCardSystemData->kansenStatuut = false;
    $passholderCardSystemData->kansenStatuutExpired = false;
    $passholderCardSystemData->kansenStatuutInGracePeriod = false;
    $passholderCardSystemData->smsPreference = CultureFeed_Uitpas_Passholder_CardSystemPreferences::SMS_NO_SMS;
    $passholderCardSystemData->status = 'ACTIVE';

    $expectedPassholder->cardSystemSpecific = array(
      $passholderCardSystemData->cardSystem->id => $passholderCardSystemData,
    );

    $this->assertEquals($expectedPassholder, $result->passholder);
  }
}
