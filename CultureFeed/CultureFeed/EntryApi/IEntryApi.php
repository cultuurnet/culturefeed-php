<?php

interface CultureFeed_EntryApi_IEntryApi {

  public function getEvent($id);

  public function createEvent(CultureFeed_Cdb_Item_Event $event, $cdb_schema_version);

  public function updateEvent(CultureFeed_Cdb_Item_Event $event, $cdb_schema_version);

  public function deleteEvent($id);

  public function addTagToEvent(CultureFeed_Cdb_Item_Event $event, $keywords);

  public function removeTagFromEvent(CultureFeed_Cdb_Item_Event $event, $keyword);
  
  public function checkPermission($userid, $email, $ids);

}