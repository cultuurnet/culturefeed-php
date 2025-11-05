<?php

final class CultureFeed_Uitpas_Association {

  /**
   * ID of the association.
   *
   * @var int
   */
  public $id;

  /**
   * Name of the association.
   *
   * @var string
   */
  public $name;

  /**
   * Card systems the association belongs to.
   *
   * @var CultureFeed_Uitpas_CardSystem[]
   */
  public $cardSystems;

  /**
   * If you have read permission on the association or not.
   *
   * @var boolean
   */
  public $permissionRead;

  /**
   * If you have register permission on the association or not.
   *
   * @var boolean
   */
  public $permissionRegister;

  /**
   * How the end date should be calculated.
   *
   * @var string
   */
  public $enddateCalculation;

  /**
   * @var int
   */
  public $enddateCalculationValidityTime;

  /**
   * Unix timestamp.
   *
   * @var int
   */
  public $enddateCalculationFreeDate;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   *
   * @return static
   */
  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $instance = new static();
    $instance->id = $object->xpath_int('id');
    $instance->name = $object->xpath_str('name');

    foreach ($object->xpath('cardSystems/cardSystem') as $cardSystemNode) {
      $instance->cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemNode);
    }

    $instance->permissionRead = $object->xpath_bool('permissionRead');
    $instance->permissionRegister = $object->xpath_bool('permissionRegister');
    $instance->enddateCalculation = $object->xpath_str('enddateCalculation');

    switch ($instance->enddateCalculation) {
      case CultureFeed_Uitpas_EndDateCalculation::FREE:
        $instance->enddateCalculationFreeDate = $object->xpath_time('enddateCalculationFreeDate');
        break;

      case CultureFeed_Uitpas_EndDateCalculation::BASED_ON_REGISTRATION_DATE:
      case CultureFeed_Uitpas_EndDateCalculation::BASED_ON_DATE_OF_BIRTH:
        $instance->enddateCalculationValidityTime = $object->xpath_int('enddateCalculationValidityTime');
        break;

      default:

    }

    return $instance;
  }

  /**
   * @param string $cardSystemId
   *
   * @return bool
   */
  public function inCardSystem($cardSystemId) {
    foreach ($this->cardSystems as $cardSystem) {
      if ($cardSystem->id == $cardSystemId) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
