<?php

class CultureFeed_Uitpas_Passholder_OptInPreferences extends CultureFeed_Uitpas_ValueObject {

  /**
   * True if the passholder has opted in to receive service mails (only used for registration).
   *
   * @var bool
   */
  public $optInServiceMails;

  /**
   * True if the passholder has opted in to receive milestone mails (only used for registration).
   *
   * @var bool
   */
  public $optInMilestoneMails;

  /**
   * True if the passholder has opted in to receive info mails (only used for registration).
   *
   * @var bool
   */
  public $optInInfoMails;

  /**
   * True if the passholder has opted in to receive SMS messages (only used for registration).
   *
   * @var bool
   */
  public $optInSms;

  /**
   * True if the passholder has opted in to receive info via post (only used for registration).
   *
   * @var bool
   */
  public $optInPost;


  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $preferences = new CultureFeed_Uitpas_Passholder_OptInPreferences();

    $preferences->optInServiceMails = $object->xpath_str('optInServiceMails');
    $preferences->optInMilestoneMails = $object->xpath_str('optInMilestoneMails');
    $preferences->optInInfoMails = $object->xpath_str('optInInfoMails');
    $preferences->optInSms = $object->xpath_str('optInSms');
    $preferences->optInPost = $object->xpath_str('optInPost');

    return $preferences;
  }

}
