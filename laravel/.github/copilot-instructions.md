# GitHub Copilot / AI Assistant Instructions

Short, actionable notes to help an AI agent be immediately productive with this codebase.

## Big picture 🔧
- This is a Laravel (v12/PHP 8.2) monolith: classic MVC (routes → controllers → models → views). See `routes/web.php` and `app/Http/Controllers/`.
- Real-time messaging uses Laravel events/broadcasting: the `App\Events\MessageSent` event broadcasts to a public channel named `chat.{min}.{max}` (see `app/Events/MessageSent.php`).
- Authentication is *session-based* (not Laravel Auth scaffolding): controllers use `session('user_id')`. See `app/Http/Middleware/EnsureSessionAuthenticated.php`.
- Roles and verification (psikolog vs anonim) are important to business logic — check `ROLE_SYSTEM.md` and `app/Http/Controllers/RegistrationController.php`.

## Key developer workflows ✅
- Project setup (recommended):
  - Run: `composer run setup` (runs composer install, copies .env, generates key, migrates, installs npm deps and builds assets)
  - Dev loop: `composer run dev` (starts `php artisan serve`, queue listener, logs pail, and `npm run dev` concurrently)
  - Tests: `composer run test` or `php artisan test` (uses RefreshDatabase in tests)
- Must run: `php artisan storage:link` to make uploaded files accessible via `asset('storage/...')` (see `SETUP.md`, `TESTING_GUIDE.md`, and `USER_PROFILE_FEATURE.md`).

## Project-specific conventions & pitfalls ⚠️
- Session-based auth: always check for `session('user_id')` / preserve `withSession()` testing approach. Do NOT assume `auth()->user()` is available.
- Messages & file uploads:
  - Controller does low-level `$_FILES` checks for upload errors (see `MessageController::send`). Keep or replicate this behavior for accurate error messages.
  - Attachment validation: `'nullable|image|max:4096'` and storage on the `public` disk (path stored in DB). Use `Storage::fake('public')` in tests.
  - Frontend displays attachments via `asset('storage/' . $message->attachment)` (see `app/Events/MessageSent::broadcastWith`).
- Broadcasting pattern:
  - Channel name is `chat.{min}.{max}` where min/max are numeric user IDs (consistent pair allows discovery by both participants). Example: `chat.3.9` for users 3 and 9.
  - Event implements `ShouldBroadcastNow` and returns a controlled payload in `broadcastWith()` (do not rely on default public property serialization).
- Tests use `RefreshDatabase`, `withSession([...])`, and assert storage via `Storage::disk('public')->assertExists(...)` (see `tests/Feature/MessageUploadTest.php`).
- Language: many UI/error strings are in Indonesian — keep the existing language for consistency.

## Integration & env notes 💡
- Check `.env` for `BROADCAST_DRIVER` (Pusher or other) if you work on real-time features. Broadcasting may fail silently — calls are wrapped in try/catch in places (e.g., `AdminController`).
- Composer scripts and `npm` are used for setup and dev (see `composer.json` and `package.json`). `php artisan pail` is used to tail logs in dev.

## Files to review first 📚
- App entry points: `routes/web.php`, `app/Http/Controllers/*`
- Auth & session: `app/Http/Middleware/EnsureSessionAuthenticated.php`
- Messages: `app/Http/Controllers/MessageController.php`, `app/Events/MessageSent.php`, `app/Models/Message.php`
- Role & verification: `ROLE_SYSTEM.md`, `app/Http/Controllers/RegistrationController.php`
- Tests: `tests/Feature/*` (use these as examples of accepted behavior)
- Setup docs: `SETUP.md`, `TESTING_GUIDE.md`, and `setup-project.sh`

## How to propose changes (short rules) ✍️
- Preserve established patterns (session auth, Indonesian strings, broadcasting channel format, upload checks).
- When modifying validation or upload code, add/update tests that use `withSession()` and `Storage::fake('public')`.
- For real-time changes: add a test that verifies the event payload or channel name (unit test event payloads or functional tests where reasonable).

---
If anything in these instructions is unclear or you'd like more detail on a specific area (broadcast config, auth flow, or test examples), tell me which part to expand. 🙋‍♂️
