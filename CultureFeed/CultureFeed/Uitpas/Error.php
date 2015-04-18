<?php
/**
 * @file
 */

/**
 * Container for error codes.
 */
class CultureFeed_Uitpas_Error
{
  const ACCESS_DENIED = 'ACCESS_DENIED';

  const ACTION_FAILED = 'ACTION_FAILED';

  const MISSING_REQUIRED_FIELDS = 'MISSING_REQUIRED_FIELDS';

  const INSZ_ALREADY_USED = 'INSZ_ALREADY_USED';

  const EMAIL_ALREADY_USED = 'EMAIL_ALREADY_USED';

  const UNKNOWN_VOUCHER = 'UNKNOWN_VOUCHER';

  const UNKNOWN_CARD = 'UNKNOWN_CARD';

  const CARD_NOT_ASSIGNED_TO_BALIE = 'CARD_NOT_ASSIGNED_TO_BALIE';

  const INVALID_CARD = 'INVALID_CARD';

  const INVALID_CARD_STATUS = 'INVALID_CARD_STATUS';
  
  const INVALID_CARD_STATUS_BLOCKED = 'INVALID_CARD_STATUS_BLOCKED';

  const INVALID_CARD_STATUS_LOCAL_STOCK = 'INVALID_CARD_STATUS_LOCAL_STOCK';

  const INVALID_CARD_STATUS_DELETED = 'INVALID_CARD_STATUS_DELETED';

  const INVALID_CARD_STATUS_STOCK = 'INVALID_CARD_STATUS_STOCK';

  const INVALID_CARD_STATUS_PROVISIONED = 'INVALID_CARD_STATUS_PROVISIONED';

  const INVALID_CARD_STATUS_SENT_TO_BALIE = 'INVALID_CARD_STATUS_SENT_TO_BALIE';

  const INVALID_VOUCHER_STATUS = 'INVALID_VOUCHER_STATUS';

  const UNKNOWN_SCHOOL = 'UNKNOWN_SCHOOL';

  const PARSE_INVALID_CITY_NAME = 'PARSE_INVALID_CITY_NAME';

  const PARSE_INVALID_INSZ = 'PARSE_INVALID_INSZ';

  const PARSE_INVALID_UITPASNUMBER = 'PARSE_INVALID_UITPASNUMBER';

  const PARSE_INVALID_VOUCHERNUMBER = 'PARSE_INVALID_VOUCHERNUMBER';

  const PARSE_INVALID_GENDER = 'PARSE_INVALID_GENDER';

  const PARSE_INVALID_DATE = 'PARSE_INVALID_DATE';

  const PARSE_INVALID_DATE_OF_BIRTH = 'PARSE_INVALID_DATE_OF_BIRTH';

  const BALIE_NOT_AUTHORIZED = 'BALIE_NOT_AUTHORIZED';

  const PARSE_INVALID_BIGDECIMAL = 'PARSE_INVALID_BIGDECIMAL';

  // Undocumented. Not sure this still applies to any method.
  const INVALID_NUMBER = 'INVALID_NUMBER';

  // Undocumented. Not sure this still applies to any method.
  const INVALID_CITY_NAME = 'INVALID_CITY_NAME';

  const ACTION_NOT_ALLOWED = 'ACTION_NOT_ALLOWED';

  const UNKNOWN_UITPASNUMBER = 'UNKNOWN_UITPASNUMBER';

  const UNKNOWN_BALIE_CONSUMERKEY = 'UNKNOWN_BALIE_CONSUMERKEY';

  const INVALID_PARAMETERS = 'INVALID_PARAMETERS';

  const UNKNOWN_CHIPNUMBER = 'UNKNOWN_CHIPNUMBER';

  const INVALID_DATE_CONSTRAINTS = 'INVALID_DATE_CONSTRAINTS';

  const UNKNOWN_PASSHOLDER_UID = 'UNKNOWN_PASSHOLDER_UID';

  const UNKNOWN_ASSOCIATION_ID = 'UNKNOWN_ASSOCIATION_ID';

  const MEMBERSHIP_NOT_POSSIBLE_AGE_CONSTRAINT = 'MEMBERSHIP_NOT_POSSIBLE_AGE_CONSTRAINT';

  const TICKETSALE_NOT_ALLOWED_FREE_EVENT = 'TICKETSALE_NOT_ALLOWED_FREE_EVENT';

  const PASSHOLDER_NO_ACTIVE_CARDSYSTEMS = 'PASSHOLDER_NO_ACTIVE_CARDSYSTEMS';

  const MAXIMUM_REACHED = 'MAXIMUM_REACHED';

  const UNKNOWN_WELCOME_ADVANTAGE_ID = 'UNKNOWN_WELCOME_ADVANTAGE_ID';

  const WELCOMEADVANTAGE_ALREADY_CASHED_IN = 'WELCOMEADVANTAGE_ALREADY_CASHED_IN';

  const CHECKIN_CURRENTLY_NOT_ALLOWED = 'CHECKIN_CURRENTLY_NOT_ALLOWED';

  const UNKNOWN_POINTS_PROMOTION_ID = 'UNKNOWN_POINTS_PROMOTION_ID';

  const UNKNOWN_BALIE_ID = 'UNKNOWN_BALIE_ID';

  const UNKNOWN_EVENT_CDBID = 'UNKNOWN_EVENT_CDBID';

  public static function allRelevantFor($path, $method = 'POST') {
    $errors = array();

    switch ($path) {

      case 'passholder/register':
        $errors[] = self::ACTION_FAILED;
        $errors[] = self::MISSING_REQUIRED_FIELDS;
        $errors[] = self::INSZ_ALREADY_USED;
        $errors[] = self::EMAIL_ALREADY_USED;
        $errors[] = self::UNKNOWN_VOUCHER;
        $errors[] = self::UNKNOWN_CARD;
        $errors[] = self::CARD_NOT_ASSIGNED_TO_BALIE;
        $errors[] = self::INVALID_CARD;
        $errors[] = self::ACCESS_DENIED;
        $errors[] = self::INVALID_CARD_STATUS;
        $errors[] = self::INVALID_CARD_STATUS_BLOCKED;
        $errors[] = self::INVALID_CARD_STATUS_DELETED;
        $errors[] = self::INVALID_CARD_STATUS_LOCAL_STOCK;
        $errors[] = self::INVALID_CARD_STATUS_PROVISIONED;
        $errors[] = self::INVALID_CARD_STATUS_SENT_TO_BALIE;
        $errors[] = self::INVALID_CARD_STATUS_STOCK;
        $errors[] = self::INVALID_VOUCHER_STATUS;
        $errors[] = self::UNKNOWN_SCHOOL;
        $errors[] = self::PARSE_INVALID_CITY_NAME;
        $errors[] = self::PARSE_INVALID_INSZ;
        $errors[] = self::PARSE_INVALID_UITPASNUMBER;
        $errors[] = self::PARSE_INVALID_VOUCHERNUMBER;
        $errors[] = self::PARSE_INVALID_GENDER;
        $errors[] = self::PARSE_INVALID_DATE;
        $errors[] = self::PARSE_INVALID_DATE_OF_BIRTH;
        $errors[] = self::BALIE_NOT_AUTHORIZED;
        // Undocumented. Not sure this still applies to passholder/register.
        $errors[] = self::PARSE_INVALID_BIGDECIMAL;
        // Undocumented. Not sure this still applies to passholder/register.
        $errors[] = self::INVALID_NUMBER;
        // Undocumented. Not sure this still applies to passholder/register.
        $errors[] = self::INVALID_CITY_NAME;
        // Undocumented.
        $errors[] = self::INVALID_DATE_CONSTRAINTS;

        break;

      case 'passholder/{uitpasNumber}':
        $errors[] = self::ACTION_NOT_ALLOWED;
        $errors[] = self::MISSING_REQUIRED_FIELDS;
        $errors[] = self::UNKNOWN_UITPASNUMBER;
        $errors[] = self::PARSE_INVALID_INSZ;
        $errors[] = self::PARSE_INVALID_UITPASNUMBER;
        $errors[] = self::PARSE_INVALID_BIGDECIMAL;
        $errors[] = self::PARSE_INVALID_GENDER;
        $errors[] = self::PARSE_INVALID_DATE;
        $errors[] = self::PARSE_INVALID_DATE_OF_BIRTH;
        break;

      case 'uitpas/passholder/eventActions':
        if ($method == 'GET') {
          $errors[] = self::UNKNOWN_BALIE_CONSUMERKEY;
          $errors[] = self::PARSE_INVALID_UITPASNUMBER;
          $errors[] = self::INVALID_PARAMETERS;
          $errors[] = self::MISSING_REQUIRED_FIELDS;
          $errors[] = self::UNKNOWN_UITPASNUMBER;
          $errors[] = self::UNKNOWN_CHIPNUMBER;
          $errors[] = self::INVALID_CARD_STATUS;
          $errors[] = self::INVALID_CARD_STATUS_BLOCKED;
          $errors[] = self::INVALID_CARD_STATUS_DELETED;
          $errors[] = self::INVALID_CARD_STATUS_LOCAL_STOCK;
          $errors[] = self::INVALID_CARD_STATUS_PROVISIONED;
          $errors[] = self::INVALID_CARD_STATUS_SENT_TO_BALIE;
          $errors[] = self::INVALID_CARD_STATUS_STOCK;
        }
        else {
          $errors[] = self::UNKNOWN_BALIE_CONSUMERKEY;
          $errors[] = self::PARSE_INVALID_UITPASNUMBER;
          $errors[] = self::UNKNOWN_EVENT_CDBID;
          $errors[] = self::UNKNOWN_UITPASNUMBER;
        }
        break;

      case 'uitpas/card':
        if ($method == 'GET') {
          $errors[] = self::MISSING_REQUIRED_FIELDS;
          $errors[] = self::UNKNOWN_CHIPNUMBER;
        }
        break;

      case 'uitpas/report/financialoverview/organiser':
        if ($method == 'POST') {
          $errors[] = self::UNKNOWN_BALIE_CONSUMERKEY;
          $errors[] = self::ACCESS_DENIED;
          $errors[] = self::MISSING_REQUIRED_FIELDS;
          $errors[] = self::PARSE_INVALID_DATE;
        }
        break;

      case 'uitpas/passholder/membership':
        if ($method == 'POST') {
          $errors[] = self::ACCESS_DENIED;
          $errors[] = self::ACTION_FAILED;
          $errors[] = self::UNKNOWN_BALIE_CONSUMERKEY;
          $errors[] = self::UNKNOWN_PASSHOLDER_UID;
          $errors[] = self::UNKNOWN_ASSOCIATION_ID;
          $errors[] = self::MISSING_REQUIRED_FIELDS;
          $errors[] = self::INVALID_PARAMETERS;
          $errors[] = self::PARSE_INVALID_DATE;
          $errors[] = self::MEMBERSHIP_NOT_POSSIBLE_AGE_CONSTRAINT;
        }
        break;

      case 'uitpas/cultureevent/{eventCdbid}/buy/{uitpasNumber}':
        if ($method == 'POST') {
          $errors[] = self::TICKETSALE_NOT_ALLOWED_FREE_EVENT;
          $errors[] = self::INVALID_CARD_STATUS;
          $errors[] = self::INVALID_CARD;
          $errors[] = self::PASSHOLDER_NO_ACTIVE_CARDSYSTEMS;
          $errors[] = self::MAXIMUM_REACHED;
          $errors[] = self::INVALID_DATE_CONSTRAINTS;
        }
        break;

      case 'uitpas/passholder/{uitpasNumber}/cashInWelcomeAdvantage':
        if ($method == 'POST') {
          $errors[] = self::UNKNOWN_CARD;
          $errors[] = self::INVALID_CARD;
          $errors[] = self::UNKNOWN_WELCOME_ADVANTAGE_ID;
          $errors[] = self::WELCOMEADVANTAGE_ALREADY_CASHED_IN;
        }
        break;

      case 'uitpas/passholder/{uitpasNumber}/cashInPointsPromotion':
        if ($method == 'POST') {
          $errors[] = self::ACTION_NOT_ALLOWED;
          $errors[] = self::UNKNOWN_CARD;
          $errors[] = self::UNKNOWN_POINTS_PROMOTION_ID;
          $errors[] = self::UNKNOWN_BALIE_ID;
        }
        break;

      case 'uitpas/passholder/checkin':
        if ($method == 'POST') {
          $errors[] = self::CHECKIN_CURRENTLY_NOT_ALLOWED;
        }
        break;
    }

    return $errors;
  }

}
