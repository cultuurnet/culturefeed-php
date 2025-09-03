<?php
/**
 * @file
 */

class CultureFeed_Uitpas_CardSystem
{
    private int $id;

    private string $name;

  /**
   * @var CultureFeed_Uitpas_DistributionKey[]
   */
  public array $distributionKeys = array();

  public function __construct(int $id, string $name) {
    $this->id = $id;
    $this->name = $name;
  }

  public static function createFromXML(CultureFeed_SimpleXMLElement $object): CultureFeed_Uitpas_CardSystem {
    // @phpstan-ignore-next-line
    $card_system = new static(
        $object->xpath_int('id'),
        $object->xpath_str('name')
    );

    $card_system->distributionKeys = array();
    foreach ($object->xpath('distributionKeys/distributionKey') as $distributionKey) {
      $card_system->distributionKeys[] = CultureFeed_Uitpas_DistributionKey::createFromXML($distributionKey);
    }

    return $card_system;
  }

  public function getId(): int
  {
      return $this->id;
  }

  public function getName(): string
  {
      return $this->name;
  }
}
