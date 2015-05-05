
## 1.5.0

- Introduction of new methods & classes for POST to the UiTPAS eventActions API
- PHPUnit was added as a dev dependency
- Code coverage reporting with Coveralls.io
- Unit tests for Saved Searched implementation
- New methods validateFrequency() and getValidFrequencies() on 
  CultureFeed_SavedSearches_SavedSearch
- Methods related to the Saved Searched API now actually process the HTTP
  response and return a proper value where available
- Add unit tests for CultureFeed_EntryApi::addTagToEvent()
- Updated dependency on cultuurnet/cdb to ~2.1
