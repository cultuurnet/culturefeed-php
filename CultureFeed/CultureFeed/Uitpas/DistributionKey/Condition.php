<?php

class CultureFeed_Uitpas_DistributionKey_Condition extends CultureFeed_Uitpas_ValueObject {

  const DEFINITION_KANSARM = 'KANSARM';

  const DEFINITION_PRICE = 'PRICE';

  const OPERATOR_IN = 'IN';

  const OPERATOR_LESS_THAN = 'LESS_THAN';

  const VALUE_MY_CARDSYSTEM = 'MY_CARDSYSTEM';

  const VALUE_AT_LEAST_ONE_CARDSYSTEM = 'AT_LEAST_ONE_CARDSYSTEM';

  public string $definition;

  public string $operator;

  public string $value;

  public static function createFromXML(CultureFeed_SimpleXMLElement $object): CultureFeed_Uitpas_DistributionKey_Condition
  {

    $condition = new self();

    $condition->definition = $object->xpath_str('definition');
    $condition->operator = $object->xpath_str('operator');
    $condition->value = $object->xpath_str('value');

    return $condition;

  }

}
