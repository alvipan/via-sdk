## [1.0.3] - 2026-07-22

### Added

- Added `Account` resource.
- Added `loginUrl()`.
- Added `registerUrl()`.
- Added `forgotPasswordUrl()`.
- Added authentication endpoints to `Config`.

### Changed

- Authentication URLs now point to `/login`.
- Shared authentication URL builder in `Resource`.

### Deprecated

- `authorizationUrl()` is deprecated in favor of `loginUrl()`.

## [1.0.2] - 2026-07-06

### Added
- Added `User` resource.
- Added `Ashvia::user()` accessor.
- Added `User::current()` to retrieve the authenticated user.

### Changed
- Improved resource separation between OAuth (`Auth`) and User APIs (`User`).