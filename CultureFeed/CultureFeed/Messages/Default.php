<?php

/**
 * @class
 * Contains all methods for sending / receiving messages.
 */
class CultureFeed_Messages_Default implements CultureFeed_Messages {

  use \Culturefeed_ValidationTrait;

  /**
   * Status code when the call was succesfull
   * @var string
   */
  const CODE_SUCCESS = 'SUCCESS';

  /**
   * Type key for the count of messages that the user has read.
   * @var string
   */
  const MESSAGE_COUNT_READ = 'READ';

  /**
   * Type key for they count of unread messages.
   * @var string
   */
  const MESSAGE_COUNT_UNREAD = 'UNREAD';

  /**
   * CultureFeed object to make CultureFeed core requests.
   * @var ICultureFeed
   */
  protected $culturefeed;

  /**
   * OAuth request object to do the request.
   *
   * @var CultureFeed_OAuthClient
   */
  protected $oauth_client;

  public function __construct(ICultureFeed $culturefeed) {
    $this->culturefeed = $culturefeed;
    $this->oauth_client = $culturefeed->getClient();
  }

  /**
   * @see CultureFee_Messages::getMessageCount().
   */
  public function getMessageCount() {

    $result = $this->oauth_client->authenticatedGetAsXml('message/totals');

    try {
      $xmlElement = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $message_count = array();
    $total = $xmlElement->xpath('/response/total');
    if (!$total) {
      return array();
    }

    foreach ($total as $count) {
      $attributes = $count->attributes();
      $message_count[(string)$attributes['type']] = (string) $count;
    }

    return $message_count;

  }

  /**
   * @see CultureFeed_Messages::getMessages()
   */
  public function getMessages($recipientPage = NULL, $type = NULL) {

    $params = array();
    if (!empty($recipientPage)) {
      $params['recipientPage'] = $recipientPage;
    }

    if (!empty($type)) {
      $params['type'] = $type;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('message/list', $params);
    $xmlElement = $this->validateResult($result, CultureFeed_Messages_Default::CODE_SUCCESS);

    $messages = array();
    $messageElements = $xmlElement->xpath('/response/messages/message');

    foreach ($messageElements as $element) {

      // Service doesn't support filtering on status yet. We only return not deleted messages for now.
      if ($element->xpath_str('status') == CultureFeed_Messages_Message::STATUS_DELETED) {
        continue;
      }

      $messages[] = CultureFeed_Messages_Message::parseFromXml($element);
    }

    return new CultureFeed_ResultSet(count($messages), $messages);

  }

  /**
   * @see CultureFeed_Messages::getMessage()
   */
  public function getMessage($id) {

    $result = $this->oauth_client->authenticatedGetAsXml('message/' . $id);
    $xmlElement = $this->validateResult($result, CultureFeed_Messages_Default::CODE_SUCCESS);

    $threadElement = $xmlElement->xpath('/response/thread');

    return CultureFeed_Messages_Message::parseFromXml($threadElement[0]);

  }

  /**
   * @see CultureFeed_Messages::sendMessages()
   */
  public function sendMessage($params) {

    $result = $this->oauth_client->authenticatedPostAsXml('message', $params);
    $xmlElement = $this->validateResult($result, CultureFeed_Messages_Default::CODE_SUCCESS);

    return $xmlElement->xpath_str('id');

  }

  /**
   * @see CultureFeed_Messages::deleteMessage()
   */
  public function deleteMessage($id) {

    $result = $this->oauth_client->authenticatedPostAsXml('message/' . $id . '/delete');
    $xmlElement = $this->validateResult($result, CultureFeed_Messages_Default::CODE_SUCCESS);

  }

}
