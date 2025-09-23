<?php

class CultureFeed_Uitpas_Event_CultureEvent extends CultureFeed_Uitpas_ValueObject {

  /**
   * @deprecated Use the CultureFeed_Uitpas_Event_CheckinConstraintReason
   *   constants instead.
   */
  const CHECKIN_CONSTRAINT_REASON_MAXIMUM_REACHED = 'MAXIMUM_REACHED';

  /**
   * @deprecated Use the CultureFeed_Uitpas_Event_CheckinConstraintReason
   *   constants instead.
   */
  const CHECKIN_CONSTRAINT_REASON_INVALID_DATE_TIME = 'INVALID_DATE_TIME';

  /**
   * @deprecated Use the CultureFeed_Uitpas_Event_BuyConstraintReason
   *   constants instead.
   */
  const BUY_CONSTRAINT_REASON_MAXIMUM_REACHED = 'MAXIMUM_REACHED';

  public string $cdbid;

  public ?string $locationId;

  public ?string $locationName;

   public ?string $organiserId;

   public string $actorId;

  /**
   * @var \CultureFeed_Uitpas_DistributionKey[]|string
   */
  public $distributionKey;

   public int $volumeConstraint;

   public string $timeConstraintFrom;

   public string $timeConstraintTo;
   public string $periodConstraintVolume;

   public string $periodConstraintType;

   public bool $degressive;

   public string $checkinPeriodConstraintType;

   public int $checkinPeriodConstraintVolume;

   public ?string $organiserName;

   public ?string $city;

  public bool $checkinAllowed = true;

  public CultureFeed_Uitpas_Event_CheckinConstraint $checkinConstraint;

  public ?string $checkinConstraintReason;

  public ?int $checkinStartDate;

  public ?int $checkinEndDate;

  public ?string $buyConstraintReason;

  public ?float $price;

  /**
   * @var string[]
   */
  public array $postPriceNames;

  /**
   * @var float[]
   */
  public array $postPriceValues;

  public ?float $tariff;

  public ?string $title;

  public CultureFeed_Uitpas_Calendar $calendar;

  public int $numberOfPoints;

  public ?int $gracePeriodMonths;

  /**
   * @var CultureFeed_Uitpas_CardSystem[]
   */
  public array $cardSystems;

  public ?string $description;

  /**
   * @var CultureFeed_Uitpas_Event_TicketSale_Opportunity[]
   */
  public array $ticketSales;

  public function __construct() {
    $this->numberOfPoints = 0;
    $this->ticketSales = array();
    $this->postPriceNames = array();
    $this->postPriceValues = array();
  }

  protected function manipulatePostData(&$data): void {
    // Set the actor ID.
      if (isset($data['organiserId'])) {
          $data['actorId'] = $data['organiserId'];
      }

    // These are allowed params for registering an event.
    $allowed = array();

    $allowed[] = "cdbid";
    $allowed[] = "locationId";
    $allowed[] = "actorId";
    $allowed[] = "distributionKey";
    $allowed[] = "volumeConstraint";
    $allowed[] = "timeConstraintFrom";
    $allowed[] = "timeConstraintTo";
    $allowed[] = "periodConstraintVolume";
    $allowed[] = "periodConstraintType";
    $allowed[] = "degressive";
    $allowed[] = "checkinPeriodConstraintType";
    $allowed[] = "checkinPeriodConstraintVolume";
    $allowed[] = "price";
    $allowed[] = "numberOfPoints";
    $allowed[] = "gracePeriodMonths";
    $allowed[] = "gracePeriod";

    foreach ($data as $key => $value) {
      if (!in_array($key, $allowed)) {
        unset($data[$key]);
      }
    }

    $priceNameIndex = 0;
    foreach ($this->postPriceNames as $priceName) {
      $priceNameIndex++;
      $data['price.name.' . $priceNameIndex] = $priceName;
    }

    $priceValueIndex = 0;
    foreach ($this->postPriceValues as $priceValue) {
      $priceValueIndex++;
      $data['price.value.' . $priceValueIndex] = $priceValue;
    }

    // If distributionKey is an array we should convert the containing keys to
    // strings as we should only POST the distribution key id.
    if (is_array($data['distributionKey'])) {
      $data['distributionKey'] = array_map(
        function (\CultureFeed_Uitpas_DistributionKey $key) {
          return (string) $key->id;
        },
        $data['distributionKey']
      );
    }
  }

  public static function createFromXML(CultureFeed_SimpleXMLElement $object): CultureFeed_Uitpas_Event_CultureEvent
  {
    $event = new CultureFeed_Uitpas_Event_CultureEvent();
    $event->cdbid = $object->xpath_str('cdbid');
    $event->locationId = $object->xpath_str('locationId');
    $event->locationName = $object->xpath_str('locationName');
    $event->organiserId = $object->xpath_str('organiserId');
    $event->organiserName = $object->xpath_str('organiserName');
    $event->city = $object->xpath_str('city');
    $event->checkinAllowed = $object->xpath_bool('checkinAllowed')!== null ? $object->xpath_bool('checkinAllowed') : true;
    $event->checkinConstraint = CultureFeed_Uitpas_Event_CheckinConstraint::createFromXML($object->xpath('checkinConstraint', false));
    $event->checkinConstraintReason = $object->xpath_str('checkinConstraintReason');
    $event->checkinStartDate = $object->xpath_time('checkinStartDate');
    $event->checkinEndDate = $object->xpath_time('checkinEndDate');
    $event->buyConstraintReason = $object->xpath_str('buyConstraintReason');
    $event->price = $object->xpath_float('price');
    $event->tariff = $object->xpath_float('tariff');
    $event->title = $object->xpath_str('title');
    $event->description = $object->xpath_str('shortDescription');

    $object->registerXPathNamespace('cdb', CultureFeed_Cdb_Default::CDB_SCHEME_URL);

    $calendar_xml = $object->xpath('cdb:calendar', false);
    if ($calendar_xml !== FALSE && !is_array($calendar_xml)) {
      $event->calendar = CultureFeed_Uitpas_Calendar::createFromXML($calendar_xml);
    }
    $event->numberOfPoints = $object->xpath_int('numberOfPoints');
    $event->gracePeriodMonths = $object->xpath_int('gracePeriodMonths');

    $event->cardSystems = array();
    foreach ($object->xpath('cardSystems/cardSystem') as $cardSystem) {
      $event->cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystem);
    }

    $event->ticketSales = array();
    foreach ($object->xpath('ticketSales/ticketSale') as $ticketSale) {
      $event->ticketSales[] = CultureFeed_Uitpas_Event_TicketSale_Opportunity::createFromXml($ticketSale);
    }

    $event->distributionKey = array();
    foreach ($object->xpath('distributionKeys/distributionKey') as $distributionKey) {
      $event->distributionKey[] = CultureFeed_Uitpas_DistributionKey::createFromXML($distributionKey);
    }

    return $event;
  }
}
