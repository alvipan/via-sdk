## [1.0.2] - 2026-07-06

### Added
- Added `User` resource.
- Added `Ashvia::user()` accessor.
- Added `User::current()` to retrieve the authenticated user.

### Changed
- Improved resource separation between OAuth (`Auth`) and User APIs (`User`).