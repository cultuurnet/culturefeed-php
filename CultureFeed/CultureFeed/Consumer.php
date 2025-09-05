<?php

final class CultureFeed_Consumer {
  public string $consumerKey;
  public string $consumerSecret;
  public ?array $group = null;
  public int $creationDate;
  public int $id;
  public string $name;
  public ?string $description = null;
  public ?string $logo = null;
  public string $status;
  public string $domain;
  public string $callback;
  public ?string $searchPrefixFilterQuery = null;
  public string $destinationAfterEmailVerification;
  public ?array $admins = null;
  public ?string $searchPrefix = null;
  public ?string $apiKeySapi3 = null;
  public ?string $searchPrefixSapi3 = null;

  public function toPostData(): array
  {
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
