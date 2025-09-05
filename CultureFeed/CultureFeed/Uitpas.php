<?php

interface CultureFeed_Uitpas {
  const CONSUMER_REQUEST = 'ConsumerRequest';
  const USER_ACCESS_TOKEN = 'UserAccessToken';

  public function getCouponsForPassholder(
      string $uitpas_number,
      ?string $consumer_key_counter = null,
      ?int $max = null,
      ?int $start = null
  ): CultureFeed_ResultSet;

  public function getAssociations(
      ?string $consumer_key_counter = null,
      ?bool $readPermission = null,
      ?bool $registerPermission = null
  ): CultureFeed_ResultSet;

  public function registerDistributionKeysForOrganizer(string $cdbid, array $distribution_keys): void;

  public function getDistributionKeysForOrganizer(string $cdbid): CultureFeed_ResultSet;

  public function getCardSystemsForOrganizer(string $cdbid): CultureFeed_ResultSet;

  public function getPrice(?string $consumer_key_counter = null): CultureFeed_ResultSet;

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
  ): CultureFeed_Uitpas_Passholder_UitpasPrice;

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
  ): CultureFeed_Uitpas_Passholder_UitpasPrice;

 /**
  * @throws CultureFeed_ParseException
  * @throws CultureFeed_Uitpas_PassholderException
  */
  public function createPassholder(
      CultureFeed_Uitpas_Passholder $passholder,
      ?string $consumer_key_counter = null
  ): string;

  public function createMembershipForPassholder(
   CultureFeed_Uitpas_Passholder_Membership $membership
  ): CultureFeed_Uitpas_Response;

  public function resendActivationEmail(string $uitpas_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Response;

  public function getPassholderByUitpasNumber(string $uitpas_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Passholder;

  public function identify(string $identification_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Identity;

  public function getPassholderByUser(string $user_id, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Passholder;

  public function searchPassholders(
    CultureFeed_Uitpas_Passholder_Query_SearchPassholdersOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_Uitpas_Passholder_ResultSet;

  public function getWelcomeAdvantagesForPassholder(
    CultureFeed_Uitpas_Passholder_Query_WelcomeAdvantagesOptions $query
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet;

  public function checkinPassholder(CultureFeed_Uitpas_Passholder_Query_CheckInPassholderOptions $query);

  public function cashInWelcomeAdvantage(
    string $uitpas_number,
    int $welcome_advantage_id,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantage;


  public function getPromotionPoints(
    CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions $query
  ): CultureFeed_ResultSet;

  public function getCashedInPromotionPoints(
    CultureFeed_Uitpas_Passholder_Query_SearchCashedInPromotionPointsOptions $query
  ): CultureFeed_ResultSet;

  public function cashInPromotionPoints(
    string $uitpas_number,
    int $points_promotion_id,
    string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_PointsPromotion;

  public function uploadPicture(string $id, string $file_data, ?string $consumer_key_counter = null): void;

  /**
   * @throws CultureFeed_ParseException
   */
  public function updatePassholder(
    CultureFeed_Uitpas_Passholder $passholder,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Response;

  public function updatePassholderCardSystemPreferences(
    CultureFeed_Uitpas_Passholder_CardSystemPreferences $preferences
  ): CultureFeed_Uitpas_Response;

  public function updatePassholderOptInPreferences(
    string $id,
    CultureFeed_Uitpas_Passholder_OptInPreferences $preferences,
    ?string $consumer_key_counter = null
  ): CultureFeed_Uitpas_Passholder_OptInPreferences;

  public function blockUitpas(string $uitpas_number, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Response;

  public function searchWelcomeAdvantages(
    CultureFeed_Uitpas_Promotion_Query_WelcomeAdvantagesOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet;

  public function getCard(CultureFeed_Uitpas_CardInfoQuery $card_query): CultureFeed_Uitpas_CardInfo;

  public function getPassholderActivationLink(
    CultureFeed_Uitpas_Passholder_Query_ActivationData $activation_data,
    ?callable $destination_callback = null
  ): string;

  public function constructPassHolderActivationLink(
    string $uid,
    string $activation_code,
    ?string $destination = null
  ): string;

  public function getPassholderActivationLinkChainedWithAuthorization(
    string $uitpas_number,
    DateTime $date_of_birth,
    string $callback_url
  ): string;

  public function registerUitpas(CultureFeed_Uitpas_Passholder_Query_RegisterUitpasOptions $query);

  public function registerPassholderInCardSystem(
    $passholderId,
    CultureFeed_Uitpas_Passholder_Query_RegisterInCardSystemOptions $query
  ): CultureFeed_Uitpas_Passholder;

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
  ): CultureFeed_Uitpas_Event_TicketSale;

  public function cancelTicketSale(string $uitpas_number, string $cdbid, ?string $consumer_key_counter = null): bool;

  public function searchCheckins(
    CultureFeed_Uitpas_Event_Query_SearchCheckinsOptions $query,
    ?string $consumer_key_counter = null,
    string $method = CultureFeed_Uitpas::USER_ACCESS_TOKEN
  ): CultureFeed_ResultSet;

  public function cancelTicketSaleById(int $ticketId, ?string $consumer_key_counter = null): void;

  public function searchEvents(CultureFeed_Uitpas_Event_Query_SearchEventsOptions $query): CultureFeed_ResultSet;

  public function searchCounters(
    CultureFeed_Uitpas_Counter_Query_SearchCounterOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_ResultSet;

  public function searchPointOfSales(
    CultureFeed_Uitpas_Counter_Query_SearchPointsOfSaleOptions $query,
    string $method = CultureFeed_Uitpas::CONSUMER_REQUEST
  ): CultureFeed_ResultSet;

  public function searchTicketSales(CultureFeed_Uitpas_Event_Query_SearchTicketSalesOptions $query): CultureFeed_ResultSet;

  public function addMemberToCounter(string $uid, ?string $consumer_key_counter = null): void;

  public function removeMemberFromCounter(string $uid, ?string $consumer_key_counter = null): void;

  public function getMembersForCounter(?string $consumer_key_counter = null): array;

  /**
   * @return array<CultureFeed_Uitpas_Counter_CardCounter>
   */
  public function getCardCounters(?string $consumer_key_counter = NULL): array;

  public function searchCountersForMember(string $uid): CultureFeed_ResultSet;

  /**
   * @return array<CultureFeed_Uitpas_Counter_Device>
   */
  public function getDevices(?string $consumer_key_counter = null, bool $show_event = false): array;

  public function getEventsForDevice(string $consumer_key_device, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Counter_Device;

  public function connectDeviceWithEvent(string $device_id, string $cdbid, ?string $consumer_key_counter = null): CultureFeed_Uitpas_Counter_Device;

  public function getWelcomeAdvantage(int $id, ?CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = null): CultureFeed_Uitpas_Passholder_WelcomeAdvantage;

  public function getPointsPromotion(
    int $id,
    CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = null
  ): CultureFeed_Uitpas_Passholder_PointsPromotion;

  public function registerEvent(CultureFeed_Uitpas_Event_CultureEvent $event): CultureFeed_Uitpas_Response;

  public function updateEvent(CultureFeed_Uitpas_Event_CultureEvent $event): CultureFeed_Uitpas_Response;

  public function getEvent(string $id): CultureFeed_Uitpas_Event_CultureEvent;

  public function getCardSystemsForEvent(string $cdbid): CultureFeed_ResultSet;

  public function eventHasTicketSales(string $cdbid): bool;

  public function setCardSystemsForEvent(string $cdbid, array $cardSystemIds): CultureFeed_Uitpas_Response;

  public function addCardSystemToEvent(string $cdbid, int $cardSystemId, ?int $distributionKey = null): CultureFeed_Uitpas_Response;

  public function deleteCardSystemFromEvent(string $cdbid, int $cardSystemId): CultureFeed_Uitpas_Response;

  public function getCardSystems(?string $permanent = null): array;

  public function generateFinancialOverviewReport(
    DateTime $start_date,
    DateTime $end_date,
    ?string $consumer_key_counter = null
  ): string;

  public function financialOverviewReportStatus(
    string $report_id,
    ?string $consumer_key_counter = NULL
  ): CultureFeed_ReportStatus;

  public function downloadFinancialOverviewReport(
    string $report_id,
    ?string $consumer_key_counter = NULL
  );

  /**
   * @return array<CultureFeed_Uitpas_Calendar_Period>
   * @throws CultureFeed_ParseException
   */
  public function getFinancialOverviewReportPeriods(string $consumer_key_counter): array;

  public function deleteMembership(
    string $uid,
    string $assocationId,
    ?string $consumer_key_counter = NULL
  ): CultureFeed_Uitpas_Response;

  public function getPassholderEventActions(CultureFeed_Uitpas_Passholder_Query_EventActions $query);

  public function postPassholderEventActions(
    CultureFeed_Uitpas_Passholder_Query_ExecuteEventActions $eventActions
  ): CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult;

  public function getGroupPass(string $id): CultureFeed_Uitpas_GroupPass;
}
