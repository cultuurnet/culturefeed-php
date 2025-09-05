<?php

final class CultureFeed_Uitpas_Default implements CultureFeed_Uitpas {

  use Culturefeed_ValidationTrait;

  private ICultureFeed $culturefeed;
  private CultureFeed_OAuthClient $oauth_client;

  public function __construct(ICultureFeed $culturefeed) {
    $this->culturefeed = $culturefeed;
    $this->oauth_client = $culturefeed->getClient();
  }

  public function getCouponsForPassholder(
    string $uitpas_number,
    ?string $consumer_key_counter = null,
    ?int $max = null,
    ?int $start = null
  ): CultureFeed_ResultSet {
    $data = array();
    $path = 'uitpas/passholder/' . $uitpas_number . '/coupons';

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    if ($max) {
      $data['max'] = $max;
    }

    if ($start) {
      $data['start'] = $start;
    }

    $result = $this->oauth_client->authenticatedGetAsXML($path, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $coupons = array();
    $objects = $xml->xpath('/ticketSaleCoupons/ticketSaleCoupon');
    $total = count($objects);

    foreach ($objects as $object) {
      $coupons[] = CultureFeed_Uitpas_Event_TicketSale_Coupon::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $coupons);
  }

  public function getAssociations(
    ?string $consumer_key_counter = null,
    ?bool $readPermission = null,
    ?bool $registerPermission = null
  ): CultureFeed_ResultSet {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    // The parameters reflect the existing UiTPAS API.
    // You have to leave out permissions completely if you don't want to
    // filter at all.
    // Filter values should be strings, because booleans would be casted to 0
    // or 1 and the API would not be able to parse those apparently.
    if (!is_null($readPermission)) {
      $data['readPermission'] = $readPermission ? 'true' : 'false';
    }
    if (!is_null($registerPermission)) {
      $data['registerPermission'] = $registerPermission ? 'true' : 'false';
    }

    $result = $this->oauth_client->authenticatedGetAsXML('uitpas/association/list', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $associations = array();
    $objects = $xml->xpath('/response/associations/association');
    $total = count($objects);

    foreach ($objects as $object) {
      $associations[] = CultureFeed_Uitpas_Association::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $associations);
  }

  public function getDistributionKeysForOrganizer(string $cdbid): CultureFeed_ResultSet
  {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/distributionkey/organiser/' . $cdbid, array());
    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $distribution_keys = array();

    foreach ($xml->xpath('/response/cardSystems/cardSystem') as $cardSystemXml) {
      $cardSystem = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);

      $objects = $cardSystemXml->xpath('distributionKeys/distributionKey');

      foreach ($objects as $object) {
        $distributionKey = CultureFeed_Uitpas_DistributionKey::createFromXML($object);
        $distributionKey->cardSystem = $cardSystem;
        $distribution_keys[] = $distributionKey;
      }
    }

    $total = count($distribution_keys);
    return new CultureFeed_ResultSet($total, $distribution_keys);
  }

  public function getCardSystemsForOrganizer(string $cdbid): CultureFeed_ResultSet
  {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/distributionkey/organiser/' . $cdbid, []);
    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    } catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $cardSystems = [];
    foreach ($xml->xpath('/response/cardSystems/cardSystem') as $cardSystemXml) {
      $cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    $total = count($cardSystems);
    return new CultureFeed_ResultSet($total, $cardSystems);
  }

  public function registerDistributionKeysForOrganizer(string $cdbid, array $distribution_keys): void {
    $this->oauth_client->consumerPostAsXml('uitpas/distributionkey/organiser/' . $cdbid, $distribution_keys);
  }

  public function getPrice(?string $consumer_key_counter = null): CultureFeed_ResultSet
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/uitpasPrice', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $prices = array();

    foreach ($xml->xpath('uitpasPrices/uitpasPrice') as $price_xml) {
      $prices[] = CultureFeed_Uitpas_Passholder_UitpasPrice::createFromXML($price_xml);
    }

    $total = count($prices);

    return new CultureFeed_ResultSet($total, $prices);
  }

  /**
   * @throws CultureFeed_ParseException
   * @throws LogicException
   */
  public function getPriceByUitpas(
    string $uitpas_number,
    string $reason,
    ?int $date_of_birth = null,
    ?string $postal_code = null,
    ?string $voucher_number = null,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_UitpasPrice {
    $data = array(
      'reason' => $reason,
      'uitpasNumber' => $uitpas_number,
    );

    return $this->requestPrice($data, $date_of_birth, $postal_code, $voucher_number, $consumer_key_counter);
  }

  /**
   * @throws CultureFeed_ParseException
   * @throws LogicException
   */
  public function getPriceForUpgrade(
    string $card_system_id,
    int $date_of_birth,
    ?string $postal_code = null,
    ?string $voucher_number = null,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_UitpasPrice {
    $reason = CultureFeed_Uitpas_Passholder_UitpasPrice::REASON_CARD_UPGRADE;

    $data = array(
      'reason' => $reason,
      'cardSystemId' => $card_system_id
    );

    return $this->requestPrice($data, $date_of_birth, $postal_code, $voucher_number, $consumer_key_counter);
  }

  /**
   * @param $data
   */
  private function requestPrice($data, $date_of_birth = null, $postal_code = null, $voucher_number = null, $consumer_key_counter = null) {
    if (!is_null($date_of_birth)) {
      $data['dateOfBirth'] = date('Y-m-d', $date_of_birth);
    }
    if (!is_null($postal_code)) {
      $data['postalCode'] = $postal_code;
    }
    if (!is_null($voucher_number)) {
      $data['voucherNumber'] = $voucher_number;
    }
    if (!is_null($consumer_key_counter)) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/price', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $price_xml = $xml->xpath('uitpasPrice', FALSE);
    if (!($price_xml instanceof CultureFeed_SimpleXMLElement)) {
      throw new LogicException('Could not find expected uitpasPrice tag in response XML.');
    }

    return CultureFeed_Uitpas_Passholder_UitpasPrice::createFromXML($price_xml);
  }

  /**
   * @throws CultureFeed_ParseException
   * @throws CultureFeed_Uitpas_PassholderException
   */
  public function createPassholder(
    CultureFeed_Uitpas_Passholder $passholder,
    ?string $consumer_key_counter = null
  ): string {
    $data = $passholder->toPostData();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/register', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $code = $xml->xpath_str('/response/code');
    if ($code == 'INSZ_ALREADY_USED') {

      $exception = CultureFeed_Uitpas_PassholderException::createFromXML($code, $xml);
      throw $exception;

    }

    return $xml->xpath_str('/response/message');
  }

  public function createMembershipForPassholder(
    CultureFeed_Uitpas_Passholder_Membership $membership
  ): CultureFeed_Uitpas_Response {
    $data = $membership->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/membership', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  public function registerEvent(CultureFeed_Uitpas_Event_CultureEvent $event): CultureFeed_Uitpas_Response
  {
    return $this->consumerPostWithSimpleResponse(
      'uitpas/cultureevent/register',
      $event
    );
  }

  public function getEvent(string $id): CultureFeed_Uitpas_Event_CultureEvent
  {
    $result = $this->oauth_client->consumerGetAsXml('uitpas/cultureevent/' . $id);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Event_CultureEvent::createFromXML($xml);
  }

  public function getCardSystemsForEvent(string $cdbid): CultureFeed_ResultSet
  {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/cultureevent/' . $cdbid . '/cardsystems', []);
    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    } catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $cardSystems = [];
    foreach ($xml->xpath('/response/cardSystems/cardSystem') as $cardSystemXml) {
      $cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    $total = count($cardSystems);
    return new CultureFeed_ResultSet($total, $cardSystems);
  }

  public function eventHasTicketSales(string $cdbid): bool
  {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/cultureevent/' . $cdbid . '/hasticketsales');

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    } catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $responseTag = $xml->xpath('/response', false);
    $code = $responseTag->xpath_str('code');
    $hasTicketSales = $responseTag->xpath_bool('hasTicketSales');

    if ($code === 'ACTION_SUCCEEDED') {
      return (bool) $hasTicketSales;
    } elseif ($code === 'UNKNOWN_EVENT_CDBID') {
      throw new CultureFeed_HttpException($result, 404);
    } else {
      throw new CultureFeed_Cdb_ParseException('Got unknown response code ' . $code);
    }
  }

  public function setCardSystemsForEvent(string $cdbid, array $cardSystemIds): CultureFeed_Uitpas_Response
  {
    return $this->consumerPostWithSimpleResponse(
      'uitpas/cultureevent/' . $cdbid . '/preset_cardsystems',
      [
        'cardSystemId' => $cardSystemIds,
      ]
    );
  }

  public function addCardSystemToEvent(string $cdbid, int $cardSystemId, ?int $distributionKey = null): CultureFeed_Uitpas_Response
  {
    $postData = array_filter(
      [
        'cardSystemId' => $cardSystemId,
        'distributionKey' => $distributionKey,
      ]
    );

    return $this->consumerPostWithSimpleResponse('uitpas/cultureevent/' . $cdbid . '/cardsystems', $postData);
  }

  public function deleteCardSystemFromEvent(string $cdbid, int $cardSystemId): CultureFeed_Uitpas_Response
  {
    $result = $this->oauth_client->request(
      'uitpas/cultureevent/' . $cdbid . '/cardsystems/' . $cardSystemId,
      [],
      'DELETE',
      FALSE
    );

    try {
      $xml = new CultureFeed_SimpleXMLElement($result->response);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result->response);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));

    return $response;
  }

  /**
   * @param CultureFeed_Uitpas_ValueObject|array $data
   * @throws CultureFeed_ParseException
   */
  private function consumerPostWithSimpleResponse(string $path, $data): CultureFeed_Uitpas_Response
  {
    if ($data instanceof CultureFeed_Uitpas_ValueObject) {
      $data = $data->toPostData();
    }

    $result = $this->oauth_client->consumerPostAsXml($path, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));

    return $response;
  }

  public function updateEvent(CultureFeed_Uitpas_Event_CultureEvent $event): CultureFeed_Uitpas_Response
  {
    return $this->consumerPostWithSimpleResponse(
      'uitpas/cultureevent/update',
      $event
    );
  }

  public function resendActivationEmail(string $uitpas_number, ?string $consumer_key_counter = NULL): CultureFeed_Uitpas_Response {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $uitpas_number . '/resend_activation_mail', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  public function getPassholderByUitpasNumber(string $uitpas_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Passholder
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/' . $uitpas_number, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $object = $xml->xpath('/passHolder', false);

    return CultureFeed_Uitpas_Passholder::createFromXml($object);
  }

  public function identify(string $identification_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Identity
  {
    $data = array(
      'identification' => $identification_number,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/retrieve', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $object = $xml->xpath('/response', false);

    return CultureFeed_Uitpas_Identity::createFromXml($object);
  }

  public function getPassholderByUser(string $user_id, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Passholder
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/uid/' . $user_id, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Passholder::createFromXml($xml);
  }

  public function searchPassholders(
    CultureFeed_Uitpas_Passholder_Query_SearchPassholdersOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_Uitpas_Passholder_ResultSet {
    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml('uitpas/passholder/search', $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/search', $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $passholders = array();
    $objects = $xml->xpath('/response/passholders/passholder');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $passholders[] = CultureFeed_Uitpas_Passholder::createFromXML($object);
    }

    $invalidUitpasNumbers = $xml->xpath_str('/response/invalidUitpasNumbers/invalidUitpasNumber', TRUE);

    return new CultureFeed_Uitpas_Passholder_ResultSet($total, $passholders, $invalidUitpasNumbers);
  }

  public function getWelcomeAdvantagesForPassholder(
    CultureFeed_Uitpas_Passholder_Query_WelcomeAdvantagesOptions $query
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet {
    $data = $query->toPostData();
    unset($data['uitpas_number']);
    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/' . $query->uitpas_number . '/welcomeadvantages', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    // Can not use CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet::createfromXML() here
    // because the response format is not consistent.
    // It lacks a 'total' element for example.
    $promotion_elements = $xml->xpath('promotion');
    $promotions = array();
    foreach ($promotion_elements as $promotion_element) {
      $promotions[] = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML($promotion_element);
    }
    $total = count($promotions);

    $advantages = new CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet($total, $promotions);
    return $advantages;
  }

  public function checkinPassholder(CultureFeed_Uitpas_Passholder_Query_CheckInPassholderOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/checkin', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $points = $xml->xpath_int('/response/points');
    return $points;
  }

  public function cashInWelcomeAdvantage(
    string $uitpas_number,
    int $welcome_advantage_id,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantage {
     $data = array(
       'welcomeAdvantageId' => $welcome_advantage_id,
     );

     if ($consumer_key_counter) {
       $data['balieConsumerKey'] = $consumer_key_counter;
     }

     $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $uitpas_number . '/cashInWelcomeAdvantage', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotion = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML($xml->xpath('/promotionTO', false));
    return $promotion;
  }

  public function getPromotionPoints(
    CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions $query
  ): CultureFeed_ResultSet {
    $data = $query->toPostData();
    $result = $this->oauth_client->consumerGetAsXml('uitpas/passholder/pointsPromotions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotions = CultureFeed_Uitpas_Passholder_PointsPromotionResultSet::createFromXML($xml->xpath('/response', false));

    return $promotions;
  }

  public function getCashedInPromotionPoints(
    CultureFeed_Uitpas_Passholder_Query_SearchCashedInPromotionPointsOptions $query
  ): CultureFeed_ResultSet {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/' . $query->uitpasNumber . '/cashedPointsPromotions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotions = array();
    $objects = $xml->xpath('/response/cashedPromotions/cashedPromotion');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $promotions[] = CultureFeed_Uitpas_Passholder_CashedInPointsPromotion::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $promotions);
  }

  public function cashInPromotionPoints(
    string $uitpas_number,
    int $points_promotion_id,
    string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_PointsPromotion {
    $data = array(
      'pointsPromotionId' => $points_promotion_id,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $uitpas_number . '/cashInPointsPromotion', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotion = CultureFeed_Uitpas_Passholder_PointsPromotion::createFromXML($xml->xpath('/promotionTO', false));
    return $promotion;
  }

  public function getPassholderEventActions(CultureFeed_Uitpas_Passholder_Query_EventActions $query) {
    $data = $query->toPostData();

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/eventActions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $eventActions = CultureFeed_Uitpas_Passholder_EventActions::createFromXML($xml);
    return $eventActions;
  }

  public function postPassholderEventActions(
    CultureFeed_Uitpas_Passholder_Query_ExecuteEventActions $eventActions
  ): CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult {
    $data = $eventActions->toPostData();

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/eventActions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $eventActions = CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult::createFromXML($xml);
    return $eventActions;
  }

  public function uploadPicture(string $id, string $file_data, ?string $consumer_key_counter = null): void {
    $data = array(
      'picture' => $file_data,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $id . '/uploadPicture', $data, TRUE, TRUE);
  }

  /**
   * @throws CultureFeed_ParseException
   */
  public function updatePassholder(
    CultureFeed_Uitpas_Passholder $passholder,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Response {
    $data = $passholder->toPostData();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $passholder->uitpasNumber, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  public function updatePassholderCardSystemPreferences(
    CultureFeed_Uitpas_Passholder_CardSystemPreferences $preferences
  ): CultureFeed_Uitpas_Response {
    $data = $preferences->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $preferences->id . '/' . $preferences->cardSystemId, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  public function updatePassholderOptInPreferences(
    string $id,
    CultureFeed_Uitpas_Passholder_OptInPreferences $preferences,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_OptInPreferences {
    $data = $preferences->toPostData();

    if ($consumer_key_counter) {
        $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $id . '/optinpreferences', $data);

    try {
        $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
        throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Passholder_OptInPreferences::createFromXML($xml->xpath('optInPreferences', false));

    return $response;
  }

  public function blockUitpas(string $uitpas_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Response
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/block/' . $uitpas_number, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  public function searchWelcomeAdvantages(
    CultureFeed_Uitpas_Promotion_Query_WelcomeAdvantagesOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet {
    $path = 'uitpas/promotion/welcomeAdvantages';

    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml($path, $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml($path, $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotions = CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet::createFromXML($xml->xpath('/response', false));
    return $promotions;
  }

  public function getCard(CultureFeed_Uitpas_CardInfoQuery $card_query): CultureFeed_Uitpas_CardInfo
  {
    $data = $card_query->toPostData();

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/card', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $card = CultureFeed_Uitpas_CardInfo::createFromXML($xml->xpath('/response', FALSE));
    return $card;
  }

  public function getPassholderActivationLink(
    CultureFeed_Uitpas_Passholder_Query_ActivationData $activation_data,
    ?callable $destination_callback = null
  ): string {
    $path = "uitpas/passholder/{$activation_data->uitpasNumber}/activation";

    $params = array(
      'dob' => $activation_data->dob->format('Y-m-d'),
    );

    $result = $this->oauth_client->consumerGetAsXml($path, $params);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $link = $xml->xpath_str('/response/activationLink');

    $query = array();

    if ($destination_callback) {
      $query['destination'] = call_user_func($destination_callback);
    }

    if (!empty($query)) {
      $link .= '?' . http_build_query($query);
    }

    return $link;
  }

  public function constructPassHolderActivationLink(
    string $uid,
    string $activation_code,
    ?string $destination = null
  ): string {
    $path = "uitpas/activate/{$uid}/{$activation_code}";

    $query = array();

    if ($destination) {
      $query['destination'] = $destination;
    }

    $link = $this->oauth_client->getUrl($path, $query);

    return $link;
  }

  public function getPassholderActivationLinkChainedWithAuthorization(
    string $uitpas_number,
    DateTime $date_of_birth,
    string $callback_url
  ): string {
    $c = $this->culturefeed;
    $activation_data = new CultureFeed_Uitpas_Passholder_Query_ActivationData();
    $activation_data->uitpasNumber = $uitpas_number;
    $activation_data->dob = $date_of_birth;

    $link = $this->getPassholderActivationLink($activation_data, function () use ($c, $callback_url) {
      $token = $c->getRequestToken($callback_url);

      $auth_url = $c->getUrlAuthorize($token, $callback_url, CultureFeed::AUTHORIZE_TYPE_REGULAR, TRUE);

      return $auth_url;
    });

    return $link;
  }

  public function registerUitpas(CultureFeed_Uitpas_Passholder_Query_RegisterUitpasOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/newCard', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return $xml->xpath_str('/response/message');
  }

  public function registerPassholderInCardSystem(
    $passholderId,
    CultureFeed_Uitpas_Passholder_Query_RegisterInCardSystemOptions $query
  ): CultureFeed_Uitpas_Passholder {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml("uitpas/passholder/{$passholderId}/register", $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $object = $xml->xpath('/passHolder', false);

    return CultureFeed_Uitpas_Passholder::createFromXml($object);
  }

  /**
   * @throws CultureFeed_ParseException
   * @throws CultureFeed_Exception
   */
  public function registerTicketSale(
    string $uitpas_number,
    string $cdbid,
    ?string $consumer_key_counter = null,
    ?string $price_class = null,
    ?string $ticket_sale_coupon_id = null,
    ?int $amount_of_tickets = null
  ): CultureFeed_Uitpas_Event_TicketSale {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }
    if ($ticket_sale_coupon_id) {
      $data['ticketSaleCouponId'] = $ticket_sale_coupon_id;
    }
    if ($price_class) {
      $data['priceClass'] = $price_class;
    }
    if ($amount_of_tickets) {
      $data['amountOfTickets'] = (int) $amount_of_tickets;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/cultureevent/' . $cdbid . '/buy/' . $uitpas_number, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = $xml->xpath('/response', false);
    if ($response instanceof CultureFeed_SimpleXMLElement) {
      $response = CultureFeed_Response::createFromResponseBody($response);
      throw new CultureFeed_Exception($response->getMessage(), $response->getCode());
    }

    $ticket_sale = CultureFeed_Uitpas_Event_TicketSale::createFromXML($xml->xpath('/ticketSale', false));
    return $ticket_sale;
  }

  public function cancelTicketSale(string $uitpas_number, string $cdbid, ?string $consumer_key_counter = null): bool
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    try {
      $this->oauth_client->authenticatedPostAsXml('uitpas/cultureevent/' . $cdbid . '/cancel/' . $uitpas_number, $data);
      return true;
    }
    catch (Exception $e) {
      return false;
    }
  }

  public function cancelTicketSaleById(int $ticketId, ?string $consumer_key_counter = null): void
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPostAsXml('uitpas/cultureevent/cancel/' . $ticketId, $data);
  }

  public function getPassholderForTicketSale( CultureFeed_Uitpas_Event_TicketSale $ts, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Passholder
  {
    $user_id = $ts->userId;
    return $this->getPassholderByUser($user_id, $consumer_key_counter);
  }

  public function searchCheckins(
    CultureFeed_Uitpas_Event_Query_SearchCheckinsOptions $query,
    ?string $consumer_key_counter = null,
    string $method = CultureFeed_Uitpas::USER_ACCESS_TOKEN
  ): CultureFeed_ResultSet {
    $data = $query->toPostData();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $path = 'uitpas/cultureevent/searchCheckins';

    if ($method == CultureFeed_Uitpas::USER_ACCESS_TOKEN) {
      $result = $this->oauth_client->authenticatedGetAsXml($path, $data);
    }
    else {
      $result = $this->oauth_client->consumerGetAsXml($path, $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $checkins = array();
    $objects = $xml->xpath('/response/checkinActivities/checkinActivity');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $checkins[] = CultureFeed_Uitpas_Event_CheckinActivity::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $checkins);
  }

  public function searchPassholderCheckins(CultureFeed_Uitpas_Passholder_Query_SearchCheckinsOptions $query): CultureFeed_ResultSet
  {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cultureevent/searchCheckins', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $checkins = array();
    $objects = $xml->xpath('/response/checkinActivities/checkinActivity');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $checkins[] = CultureFeed_Uitpas_Event_CheckinActivity::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $checkins);
  }

  public function searchEvents(CultureFeed_Uitpas_Event_Query_SearchEventsOptions $query): CultureFeed_ResultSet
  {
    $data = $query->toPostData();

    $result = $this->oauth_client->consumerGetAsXml('uitpas/cultureevent/search', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $events = array();
    $objects = $xml->xpath('/cultureEvents/event');
    $total = $xml->xpath_int('/cultureEvents/total');

    foreach ($objects as $object) {
      $events[] = CultureFeed_Uitpas_Event_CultureEvent::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $events);
  }

  public function searchCounters(
    CultureFeed_Uitpas_Counter_Query_SearchCounterOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_ResultSet {
    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml('uitpas/balie/search', $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/search', $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $counters = array();
    $objects = $xml->xpath('/response/balies/balie');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $counters[] = CultureFeed_Uitpas_Counter::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $counters);
  }

  public function searchPointOfSales(
    CultureFeed_Uitpas_Counter_Query_SearchPointsOfSaleOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_ResultSet {
    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml('uitpas/balie/pos', $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/pos', $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $pos = array();
    $objects = $xml->xpath('/response/balies/balie');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $pos[] = CultureFeed_Uitpas_Counter::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $pos);
  }

  public function searchTicketSales(CultureFeed_Uitpas_Event_Query_SearchTicketSalesOptions $query): CultureFeed_ResultSet
  {
    $data = $query->toPostData();

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cultureevent/searchTicketsales', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $total = $xml->xpath_int('/response/total');
    $objects = $xml->xpath('/response/ticketSales/ticketSale');
    $ticket_sales = array();

    foreach ($objects as $object) {
      $ticket_sales[] = CultureFeed_Uitpas_Event_TicketSale::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $ticket_sales);
  }

  public function addMemberToCounter(string $uid, ?string $consumer_key_counter = NULL): void {
    $data = array(
      'uid' => $uid,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPost('uitpas/balie/member', $data);
  }

  public function removeMemberFromCounter($uid, $consumer_key_counter = NULL): void {
    $data = array(
      'uid' => $uid,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPost('uitpas/balie/removeMember', $data);
  }

  public function getCardCounters(?string $consumer_key_counter = NULL): array
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/countCards', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $counters = array();
    $objects = $xml->xpath('/response/counters/counter');

    foreach ($objects as $object) {
      $counters[] = CultureFeed_Uitpas_Counter_CardCounter::createFromXML($object);
    }

    return $counters;

  }

  public function getMembersForCounter(?string $consumer_key_counter = null): array
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/listEmployees', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $data = array();

    foreach ($xml->xpath('/response/admins/admin') as $object) {
      $data['admins'][] = CultureFeed_Uitpas_Counter_Member::createFromXML($object);
    }

    foreach ($xml->xpath('/response/members/member') as $object) {
      $data['members'][] = CultureFeed_Uitpas_Counter_Member::createFromXML($object);
    }

    return $data;
  }

  public function searchCountersForMember(string $uid): CultureFeed_ResultSet
  {
    $data = array(
      'uid' => $uid,
    );

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/list', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $counters = array();
    $objects = $xml->xpath('balies/balie');
    $total = count($objects);

    foreach ($objects as $object) {
      $counters[] = CultureFeed_Uitpas_Counter_Employee::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $counters);
  }

  public function getDevices(?string $consumer_key_counter = null, bool $show_event = false): array
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    if ($show_event) {
      $data['showEvent'] = 'true';
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cid/list', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $devices = array();
    $objects = $xml->xpath('/response/cids/cid');

    foreach ($objects as $object) {
      $devices[] = CultureFeed_Uitpas_Counter_Device::createFromXML($object);
    }

    return $devices;
  }

  public function getEventsForDevice(string $consumer_key_device, ?string $consumer_key_counter = NULL): CultureFeed_Uitpas_Counter_Device
  {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cid/' . $consumer_key_device, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Counter_Device::createFromXml($xml->xpath('/response', FALSE));
  }

  public function connectDeviceWithEvent(string $consumer_key_device, string $cdbid, ?string $consumer_key_counter = NULL): CultureFeed_Uitpas_Counter_Device {
    $data = array(
      'cdbid' => $cdbid,
      'cidConsumerKey' => $consumer_key_device,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/cid/connect', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Counter_Device::createFromXml($xml->xpath('/response', FALSE));
  }

  public function getWelcomeAdvantage(
    int $id,
    ?CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = null
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantage {
    $path = 'uitpas/promotion/welcomeAdvantage/' . $id;

    $params = array();

    if ($passholder) {
      $params += $passholder->params();
    }

    $result = $this->oauth_client->consumerGetAsXml($path, $params);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $advantage = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML($xml);

    return $advantage;
  }

  public function getPointsPromotion(
    int $id,
    CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = null
  ): CultureFeed_Uitpas_Passholder_PointsPromotion {
    $path = 'uitpas/promotion/pointsPromotion/' . $id;

    $params = array();

    if ($passholder) {
      $params += $passholder->params();
    }

    $result = $this->oauth_client->consumerGetAsXml($path, $params);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotion = CultureFeed_Uitpas_Passholder_PointsPromotion::createFromXML($xml);

    return $promotion;
  }

  public function getCardSystems(?string $permanent = null): array
  {
    if ($permanent == 'permanent') {
			$result = $this->oauth_client->consumerGetAsXml('uitpas/cardsystem?permanent=true');
		}
		else {
			$result = $this->oauth_client->consumerGetAsXml('uitpas/cardsystem');
		}

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $cardsystems = array();

    foreach ($xml->cardSystems->cardSystem as $cardSystemXml) {
      $cardsystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    return $cardsystems;
  }

  public function generateFinancialOverviewReport(
    DateTime $start_date,
    DateTime $end_date,
    ?string $consumer_key_counter = null
  ): string {
    $data = array(
      'startDate' => $start_date->format(DateTime::W3C),
      'endDate' => $end_date->format(DateTime::W3C),
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPost(
      'uitpas/report/financialoverview/organiser',
      $data
    );

    $response = CultureFeed_Response::createFromResponseBody($result);

    if ($response->getCode() !== 'ACTION_SUCCEEDED') {
      throw new RuntimeException('Expected response code ACTION_SUCCEEDED, got ' . $response->getCode());
    }

    // Extract the reportId from the relative URL we get back.
    // Example:
    // /uitpas/report/financialoverview/organiser/19/status?balieConsumerKey=31413BDF-DFC7-7A9F-10403618C2816E44
    if (1 === preg_match('@organiser/([^/]+)/status@', $response->getResource(), $matches)) {
      $reportId = $matches[1];
    }
    else {
      throw new RuntimeException('Unable to extract report ID from response');
    }

    return $reportId;
  }

  public function financialOverviewReportStatus(
    string $report_id,
    ?string $consumer_key_counter = NULL
  ): CultureFeed_ReportStatus {
    $params = array();

    if ($consumer_key_counter) {
      $params['balieConsumerKey'] = $consumer_key_counter;
    }

    $response_xml = $this->oauth_client->authenticatedGetAsXml(
      "uitpas/report/financialoverview/organiser/{$report_id}/status",
      $params
    );

    $response = CultureFeed_Response::createFromResponseBody($response_xml);

    return CultureFeed_ReportStatus::createFromResponse($response);
  }

  public function downloadFinancialOverviewReport(
    string $report_id,
    ?string $consumer_key_counter = NULL
  ) {
    $params = array();

    if ($consumer_key_counter) {
      $params['balieConsumerKey'] = $consumer_key_counter;
    }

    $response = $this->oauth_client->authenticatedGet(
      "uitpas/report/financialoverview/organiser/{$report_id}/download",
      $params
    );

    return $response;
  }

  /**
   * @return array<CultureFeed_Uitpas_Calendar_Period>
   * @throws CultureFeed_ParseException
   */
  public function getFinancialOverviewReportPeriods(string $consumer_key_counter): array
  {
    $params = array(
      'balieConsumerKey' => $consumer_key_counter,
    );

    $response = $this->oauth_client->authenticatedGetAsXml(
      'uitpas/report/financialoverview/organiser/periods',
      $params
    );

    try {
      $xml = new CultureFeed_SimpleXMLElement($response);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($response);
    }

    $periods = array();

    foreach ($xml->periods->period as $periodXml) {
      $periods[] = CultureFeed_Uitpas_Calendar_Period::createFromXML($periodXml);
    }

    return $periods;
  }

  public function deleteMembership(
    string $uid,
    string $assocationId,
    ?string $consumer_key_counter = NULL
  ): CultureFeed_Uitpas_Response {
    $data = array(
      'uid' => $uid,
      'associationId' => $assocationId
    );
    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/membership/delete', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  public function getGroupPass(string $id): CultureFeed_Uitpas_GroupPass
  {

    $result = $this->oauth_client->consumerGetAsXml('uitpas/grouppass/' . $id);
    $xml = $this->validateResult($result, '');

    return CultureFeed_Uitpas_GroupPass::createFromXML($xml);
  }

}
