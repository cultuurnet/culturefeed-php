<?php

class CultureFeed_PointsPromotion {

  const CASHIN_PROMOTION_NOT_ALLOWED = 'CASHIN_PROMOTION_NOT_ALLOWED';

  const NOT_POSSIBLE_POINTS_CONSTRAINT = 'NOT_POSSIBLE_POINTS_CONSTRAINT';

  const NOT_POSSIBLE_USER_VOLUME_CONSTRAINT = 'NOT_POSSIBLE_USER_VOLUME_CONSTRAINT';

  const POSSIBLE = 'POSSIBLE';

  const ABSOLUTE = "ABSOLUTE";

  public string $cashInState;

  public bool $cashedIn;

  public string $id;

  public int $cashingPeriodBegin;

  public int $cashingPeriodEnd;

  public int $creationDate;

  public int $maxAvailableUnits;

  public ?CultureFeed_PeriodConstraint $periodConstraint = NULL;

  public float $points;

  public bool $inSpotlight;

  public string $title;

  public string $description1;

  public string $description2;

  public array $pictures = array();

  public int $unitsTaken;

  public function toPostData(): array {
    // For most properties we can rely on get_object_vars.
    $data = get_object_vars($this);

    // Represent cashingPeriodBegin as a W3C date.
    if (isset($data['cashingPeriodBegin'])) {
      $data['cashingPeriodBegin'] = date('c', $data['cashingPeriodBegin']);
    }

    // Represent creationDate as a W3C date.
    if (isset($data['creationDate'])) {
      $data['creationDate'] = date('c', $data['creationDate']);
    }

    // Booleans to string.
    $data['cashedIn'] = $data['cashedIn'] ? "true" : "false";
    $data['inSpotlight'] = $data['inSpotlight'] ? "true" : "false";

    $data = array_filter($data);

    return $data;
  }

  public static function parseFromXML(CultureFeed_SimpleXMLElement $element): CultureFeed_PointsPromotion {

    if (empty($element->id)) {
      throw new CultureFeed_ParseException('id missing for PointsPromotions element');
    }

    if (empty($element->title)) {
      throw new CultureFeed_ParseException('title missing for PointsPromotions element');
    }

    if (empty($element->points)) {
      throw new CultureFeed_ParseException('points missing for PointsPromotions element');
    }

    $pointsPromotion = new CultureFeed_PointsPromotion();

    $pointsPromotion->id = $element->xpath_str('id');
    $pointsPromotion->cashInState = $element->xpath_str('cashInState');
    $pointsPromotion->cashedIn = $element->xpath_str('cashedIn') == "true" ? TRUE : FALSE;
    $pointsPromotion->inSpotlight = $element->xpath_str('inSpotlight') == "true" ? TRUE : FALSE;
    $pointsPromotion->cashingPeriodBegin = $element->xpath_time('cashingPeriodBegin');
    $pointsPromotion->cashingPeriodEnd = $element->xpath_time('cashingPeriodEnd');
    $pointsPromotion->creationDate = $element->xpath_time('creationDate');
    $pointsPromotion->maxAvailableUnits = $element->xpath_str('maxAvailableUnits');
    $pointsPromotion->points = $element->xpath_str('points');
    $pointsPromotion->title = $element->xpath_str('title');
    $pointsPromotion->unitsTaken = $element->xpath_str('unitsTaken');

    $pointsPromotion->description1 = $element->xpath_str('description1');
    $pointsPromotion->description2 = $element->xpath_str('description2');

    // Set relations.
    if (!empty($element->pictures) && !empty($element->pictures->picture)) {

      foreach ($element->pictures->picture as $picture) {
        $pointsPromotion->pictures[] = (string) $picture;
      }

    }

    $periodType = $element->xpath_str('periodConstraint/periodType');
    $periodVolume = $element->xpath_str('periodConstraint/periodVolume');
    if (!empty($periodType) && !empty($periodVolume)) {
      $pointsPromotion->periodConstraint = new CultureFeed_PeriodConstraint($periodType, $periodVolume);
    }

    return $pointsPromotion;

  }
}