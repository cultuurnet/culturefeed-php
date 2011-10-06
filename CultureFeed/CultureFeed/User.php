<?php

/**
 * Class to represent a user.
 */
class CultureFeed_User {

  /**
   * Gender 'male'.
   */
  const GENDER_MALE = 'male';

  /**
   * Gender 'female'.
   */
  const GENDER_FEMALE = 'female';

  /**
   * User status 'public'.
   */
  const STATUS_PUBLIC = 'public';

  /**
   * User status 'private'.
   */
  const STATUS_PRIVATE = 'private';

  /**
   * User status 'blocked'.
   */
  const STATUS_BLOCKED = 'blocked';

  /**
   * User status 'deleted'.
   */
  const STATUS_DELETED = 'deleted';

  /**
   * ID of the user.
   *
   * @var string
   */
  public $id;

  /**
   * Nick of the user.
   *
   * @var string
   */
  public $nick;

  /**
   * Password of the user.
   *
   * @var string
   */
  public $password;

  /**
   * First name of the user.
   *
   * @var string
   */
  public $givenName;

  /**
   * Family name of the user.
   *
   * @var string
   */
  public $familyName;

  /**
   * E-mail of the user.
   *
   * @var string
   */
  public $mbox;

  /**
   * E-mail verification status.
   *
   * @var bool
   */
  public $mboxVerified;

  /**
   * Gender of the user.
   * Possible values are represented in the GENDER_* constants.
   *
   * @var string
   */
  public $gender;

  /**
   * Date of birth of the user represented as a UNIX timestamp.
   *
   * @var integer
   */
  public $dob;

  /**
   * Depiction of the user.
   *
   * @var string
   */
  public $depiction;

  /**
   * Biography of the user.
   *
   * @var string
   */
  public $bio;

  /**
   * Home address of the user.
   *
   * @var string
   */
  public $homeAddress;

  /**
   * Coordinates of the user's home address.
   *
   * @var CultureFeed_Location
   */
  public $homeLocation;

  /**
   * Coordinates of the user's current address.
   *
   * @var CultureFeed_Location
   */
  public $currentLocation;

  /**
   * Status of the user.
   * Possible values are represented in the STATUS_* constants.
   *
   * @var string
   */
  public $status;

  /**
   * OpenID handle of the user.
   *
   * @var string
   */
  public $openid;

  /**
   * Online accounts (social services) the user is connected with.
   * Represented as an array of CultureFeed_OnlineAccount objects.
   *
   * @var array
   */
  public $holdsAccount;
  
  

  /**
   * Field privacy status.
   *
   * @var CultureFeed_UserPrivacyConfig
   */
  public $privacyConfig;

  /**
   * Convert a CultureFeed_User object to an array that can be used as data in POST requests that expect user info.
   *
   * @return array
   *   Associative array representing the object. For documentation of the structure, check the Culture Feed API documentation.
   */
  public function toPostData() {
    // For most properties we can rely on get_object_vars.
    $data = array_filter(get_object_vars($this));

    // Represent mboxVerified as a string (true/false);
    if (isset($data['mboxVerified'])) {
      $data['mboxVerified'] = $data['mboxVerified'] ? 'true' : 'false';
    }

    // Represent homeLocation as a string.
    if (isset($data['homeLocation'])) {
      $data['homeLocation'] = (string)$data['homeLocation'];
    }

    // Represent currentLocation as a string.
    if (isset($data['currentLocation'])) {
      $data['currentLocation'] = (string)$data['homeLocation'];
    }

    // Represent dob as a W3C date.
    if (isset($data['dob'])) {
      $data['dob'] = date('c', $data['dob']);
    }

    return $data;
  }

}
