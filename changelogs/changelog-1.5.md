
## 1.5.0

- Updated dependency on cultuurnet/cdb to ~2.1
- Introduction of new methods & classes for POST to the UiTPAS eventActions API
- PHPUnit was added as a dev dependency
- Code coverage reporting with Coveralls.io
- Unit tests for Saved Searches implementation
- New methods validateFrequency() and getValidFrequencies() on 
  CultureFeed_SavedSearches_SavedSearch
- Methods related to the Saved Searches API now actually process the HTTP
  response and return a proper value where available
- Added argument to the CultureFeed_EntryApi constructor to specify the cdbxml
  version to use (defaults to 3.2 for backwards compatibility reasons)
- CultureFeed_EntryApi::addTagToEvent() now also accepts keyword objects,
  and handles their cdbxml 3.3 visible property
- Added unit tests for CultureFeed_EntryApi::addTagToEvent()
- Handle new cdbxml 3.3 properties of file (title, copyright, subbrand and 
  description) in CultureFeed_EntryApi link methods
- Added new methods for collaboration (plaintext) links to CultureFeed_EntryApi:
    - addCollaborationLinkToEvent()
    - addCollaborationLinkToProduction()
    - addCollaborationLinkToActor()
