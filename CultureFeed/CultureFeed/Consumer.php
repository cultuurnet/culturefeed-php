<?php

class CultureFeed_Consumer {

  public string $consumerKey;

  public string $consumerSecret;

  public array $group;

  public int $creationDate;

  public int $id;

  public string $name;

  public string $description;

  public string $logo;

  public string $status;

  public string $domain;

  public string $callback;

  public string $searchPrefixFilterQuery;

  public string $destinationAfterEmailVerification;

  /**
   * @var string[]
   */
  public array $admins;

  public ?string $searchPrefix;

  public ?string $apiKeySapi3;

  public ?string $searchPrefixSapi3;

  public function toPostData(): array {
    // For most properties we can rely on get_object_vars.
    $data = get_object_vars($this);

    // Represent creationDate as a W3C date.
    if (isset($data['creationDate'])) {
      $data['creationDate'] = date('c', $data['creationDate']);
    }

    $data = array_filter($data);

    return $data;
  }
}
