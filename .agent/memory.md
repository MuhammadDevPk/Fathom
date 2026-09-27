# Active Project Memory & Roadmap

## 1. Current Objective
Build and ship the **Fathom** meeting intelligence MVP featuring:
- High-performance, clean light-mode UI inspired by our SaaS reference design.
- Instant, sub-second transcript/video player seeking and dialogue synchronization.
- Executive AI summarization with dynamic template toggling (General, Sales, Engineering).
- Interactive Action Items checklist with speaker attribution.
- Full text search across meetings and transcript cues.

---

## 2. Stack & Architecture Decisions
- **Backend:** Laravel 13 (`^13.17`), Fortify session authentication, Inertia Laravel v3 (`^3.0`), Laravel Wayfinder.
- **Frontend:** Vue 3 (`^3.5.13`, `<script setup lang="ts">`), TypeScript (`^5.2.2`), `@inertiajs/vite` v3, `@vueuse/core` (`^12.8.2`), Vite-Plus (`vp`).
- **UI & Styling:** Tailwind CSS v4 (`^4.1.1`), `reka-ui` (`^2.9.8`), `@lucide/vue` (`^1.17.0`), `vue-sonner` (`^2.0.0`).
- **Testing & Quality:** Pest v5 (`^5.2`), Larastan (`^3.9`), Laravel Pint (`^1.27`).
- **Capture Strategy:** Stubbed media pipeline utilizing realistic pre-seeded multi-speaker JSON transcripts and sample video/audio.
- **Visual Reference:** See [`.agent/ui_reference.md`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/.agent/ui_reference.md) for the visual specifications captured from the design screenshots.

---

## 3. Active Roadmap
- [x] Initial repository setup (Laravel 13, Fortify, Inertia v3, Vue 3.5, TypeScript, Tailwind v4, Reka UI)
- [x] Agent capture infrastructure & verification protocol ([`CAPTURE-TEST.md`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/CAPTURE-TEST.md))
- [x] Core governance and system specification files ([`.agent/`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/.agent))
- [x] Phase 1: Data Foundation (Database migrations & Eloquent models for `meetings` and `highlights`)
- [x] Phase 1: Realistic 5-meeting multi-speaker seeders with rich transcripts, decisions, and action items
- [x] Phase 2: Backend Controller, Routes & Meeting Data Pipeline (Index & Show routes, thin controller, pagination)
- [x] Phase 3: Detail layout with 3-panel structure (HTML5 video top-left, summary bottom-left, transcript right)
- [x] Phase 4: Video-to-transcript playback synchronization (click-to-seek, active cue highlighting, smooth auto-scroll, useTranscriptSync composable, spacebar play/pause shortcut)
- [x] Phase 5: AI Summary + Template Switching (Reka UI Tabs, Inertia v3 deferred props, partial reloads via router.reload, queued GenerateMeetingSummary job with GroqClient and graceful offline fallback)
- [x] Phase 6: Action Items + Highlights (ActionItemsList with session checkboxes, Reka UI highlight dialog, StoreHighlightRequest validation, side panel & tab list, click-to-seek)
- [x] Phase 7: Ask AI + Global Search (AskAiPanel with timestamp citations and seek sync, session-based Q&A history, AnswerMeetingQuestion queued job, GroqClient.answerQuestion, debounced live search with scopeSearch)
- [x] Phase 7.5: UI Polish + Public Landing Page & Authentication Screens (Public landing page at `/`, Dashboard & Detail rounded-3xl and ambient wash polish, Sidebar soft highlight pill active states, Auth layout and Login/Register/Password screens transformed with modern light-mode SaaS cards, ambient glow washes, and Fathom branding)
- [x] Phase 7.5c: Targeted UI Polish (Sidebar header & nav rows, Index search bar, Meeting cards with SenseLab soft gradient border and filled pills, Meeting detail header, tabs, and transcript cues)
- [x] Phase 7.6: QA Audit + Edge Case Hardening (Comprehensive audit report at `.agent/qa_report.md`, custom `Error.vue` page, route protection on `/meetings`, search debounced spinner, race condition and double-submit guards, break-words overflow protection, 100% green tests)
- [x] Phase 7.7: Ask AI Intelligence + Markdown Rendering (Scoped LLM prompt to question depth, structured headline + 3–5 bullets per summary section, lightweight MarkdownRenderer with markdown-it, escaped raw HTML, and preserved interactive timestamp pills)
- [x] Phase 7.8: Live Demo Embed on Landing Page (Public `/demo/meeting` route with shortest meeting, browser mockup iframe with skeleton loader, fullscreen toggle via useFullscreen, and demo read-only guards)
- [x] Phase 7.9: Additional Fixes (Action items checkbox session persistence `meeting_{id}_action_items`, deterministic 10-entry speakerColors palette, aligned MeetingFactory with updated seeder)
- [x] Phase 7.10: Guest Demo Access + Shareable Meeting Links (One-click demo login without signup, rate-limited POST /demo/login, demo@fathom.test seeded with 5 meetings, signed public share URL per meeting with 403 signature check & session persistence, read-only Show.vue with copy share link button & vue-sonner toast, glassmorphic play button overlay, and comprehensive mutation guards)
- [x] Phase 8: Production README creation and verification (13-section technical assessment documentation with exact dependency versions, data model, request flow, deployment guide, and test statistics)

---

## Phase 7.7: Ask AI Intelligence + Markdown Rendering
- **Scoped Question Answering ([`app/Services/GroqClient.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Services/GroqClient.php)):**
  - Rewrote system prompt for `answerQuestion()` with strict rules: answers only what was asked, no volunteering unrequested speaker lists or agendas, 2–4 sentences for simple questions, bullets only on request, zero markdown tables unless requested, inline `[MM:SS]` timestamp citations, and single-sentence `"Not covered in this meeting."` response when topics are absent.
  - Updated `buildSystemPrompt()` for summaries to enforce structured headline + 3–5 bullets per section rather than free-form paragraphs.
  - Added intelligent fallback behavior for speaker inquiries and out-of-scope topics.
- **Markdown Rendering in Ask AI ([`resources/js/components/MarkdownRenderer.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/MarkdownRenderer.vue) & [`AskAiPanel.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/AskAiPanel.vue)):**
  - Installed lightweight parser `markdown-it` (`^14.1.0`) and `@types/markdown-it` (`^14.1.2`).
  - Created `MarkdownRenderer.vue` rendering markdown with `html: false` (escapes raw HTML for security) and `linkify: true`.
  - Added styled typography classes and scoped styles for paragraphs, lists, bold text, inline code, and tables.
  - Implemented non-destructive timestamp pill parsing (`[MM:SS]` / `MM:SS`) on text nodes after markdown rendering, preserving click-to-seek video playback.

---

## Phase 8: Production Documentation & Polish
- **Production README.md ([`README.md`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/README.md)):**
  - Formatted across all 13 required sections in order with static badge row and live demo placeholder.
  - Dependency table matched to exact versions from `composer.json` and `package.json`.
  - Concrete data model schema and end-to-end request flow ("Ask AI") documented.
  - Complete local setup, `.env` specifications, and production deployment guide (Nginx + Supervisor worker configuration).
  - Scope decision placeholder section preserved for human author.
  - Governance overview referencing all `.agent/` files and `.agent-logs/` transcripts.
- **Detail View & Navigation Polish:**
  - Increased video player height (`VideoPlayer.vue`) from `max-h-[220px]` to `h-[300px]` to `h-[425px]` with natural 16:9 proportions, eliminating the squashed/too-wide appearance.
  - Implemented internal smooth scrolling in `SummaryPanel.vue` via Reka UI `ScrollAreaRoot`, `ScrollAreaViewport`, `ScrollAreaScrollbar`, and `ScrollAreaThumb`, identical to `TranscriptList.vue`.
  - Removed "Repository" and "Documentation" links from `AppSidebar.vue`.

---

## Phase 7.8: Live Demo Embed on Landing Page
- **Public Demo Route ([`routes/web.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/routes/web.php) & [`app/Http/Controllers/MeetingController.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php)):**
  - Added public GET route `demo.meeting` (`/demo/meeting`) rendering the shortest seeded meeting via `Meeting::query()->orderBy('duration_seconds', 'asc')->firstOrFail()`.
  - Reused `renderMeetingDetail()` helper with `isDemo: true`.
  - Added read-only safeguards: guest visitors can play media, click transcript cues, and switch summary templates, while mutations (bookmarking highlights, regenerating summaries, querying Ask AI) are cleanly disabled.
  - Added top Demo Mode banner in [`Show.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Meetings/Show.vue) with direct link to `/register` (`target="_top"`).
  - Bypassed sidebar and header in [`AppSidebarLayout.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/layouts/app/AppSidebarLayout.vue) when `isDemo` is active.
- **Interactive Landing Page Embed ([`resources/js/pages/Welcome.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Welcome.vue)):**
  - Embedded responsive `<iframe>` inside a browser mockup frame with URL bar (`fathom.test/demo/meeting`), macOS window dots, and "Live Demo" badge.
  - Added subtle loading skeleton with spinner until `@load` triggers.
  - Implemented one-click fullscreen expansion and collapse with `@vueuse/core` `useFullscreen` and `@lucide/vue` `Maximize2` / `Minimize2` icons.

---

## Phase 7.9: Additional Fixes
- **Fix 1: Persist Action Item Checkbox State ([`MeetingController.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php), [`ActionItemsList.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/ActionItemsList.vue), [`.agent/architecture.md`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/.agent/architecture.md)):**
  - Added `POST /meetings/{meeting}/action-items/toggle` route storing checked indices in Laravel session key `meeting_{id}_action_items` as `['item_index_X' => true]`.
  - Initialized session from seeded data on first view and hydrated `actionItemState` prop.
  - Connected `ActionItemsList.vue` with optimistic local reactivity and non-reloading `router.post()` background synchronization.
  - Preserved local-only temporary behavior for demo mode guests.
- **Fix 2: Deterministic Speaker Colors ([`speakerColors.ts`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/lib/speakerColors.ts)):**
  - Removed all hardcoded name-to-color dictionaries.
  - Created reusable `resources/js/lib/speakerColors.ts` with 10 distinct, aesthetic pastel entries matching `.agent/ui_reference.md`.
  - Implemented `buildSpeakerColorMap` (order of first appearance) and `getSpeakerColor` (hash fallback) across `TranscriptList.vue`, `MeetingCard.vue`, and `ActionItemsList.vue`.
- **Fix 3: Aligned MeetingFactory ([`MeetingFactory.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/database/factories/MeetingFactory.php) & [`MeetingModelTest.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/tests/Feature/MeetingModelTest.php)):**
  - Updated `MeetingFactory` to produce structured multi-template summaries (`general`, `sales`, `engineering`), realistic speakers (`Host`, `Dr. Ananth`, `Jadav Payeng`), and action items with `id`, `task`, `assignee`, and `completed`.
  - Added Pest test `it('factory produces a meeting with a well-formed transcript')`.

---

## Phase 7.10: Guest Demo Access + Shareable Meeting Links
- **Part 1 — Guest Demo Login ([`routes/web.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/routes/web.php) & [`MeetingController.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php)):**
  - Created seeded account `demo@fathom.test` in [`database/seeders/MeetingSeeder.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/database/seeders/MeetingSeeder.php) with all 5 realistic meetings assigned, complete with rich multi-speaker transcripts, multi-template summaries, action items, and timestamped highlights.
  - Added route `POST /demo/login` named `demo.login` with `throttle:10,1` rate limiting (10 requests per minute). Automatically authenticates `demo@fathom.test`, regenerates session, and redirects to `/meetings`.
  - Added one-click **"Try Demo — no signup"** secondary CTA on landing page hero and top nav ([`resources/js/pages/Welcome.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Welcome.vue)).
  - Added ghost buttons **"Or try the demo without an account →"** below login form ([`resources/js/pages/auth/Login.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/auth/Login.vue)) and register form ([`resources/js/pages/auth/Register.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/auth/Register.vue)).
- **Part 2 — Shareable Meeting Links ([`MeetingController.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php) & [`Show.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Meetings/Show.vue)):**
  - Added public GET route `GET /share/{meeting}` named `meetings.share` outside `auth` middleware.
  - Generates indefinitely valid signed URLs via `URL::signedRoute('meetings.share', ['meeting' => $meeting->id])`. Validates signature on receipt with `$request->hasValidSignature()`, persisting validation state in session key `share_verified_{id}` so subsequent partial deferred prop requests and template switches remain authenticated. Returns 403 on invalid or tampered signatures.
  - Renders existing `Show.vue` in read-only mode (`isDemo: true`). Guests and demo users can play synchronized video, click transcript cues, switch between summary templates (`general`, `sales`, `engineering`), and switch tabs.
  - Mutation endpoints ([`MeetingController::toggleActionItem`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php), [`MeetingController::generateSummary`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php), [`MeetingController::ask`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php), [`HighlightController::store`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/HighlightController.php)) explicitly block `demo@fathom.test` with HTTP 403 Forbidden responses.
  - Added **"Copy share link"** button with `Share2` icon in meeting detail header (authenticated view only). Copies signed URL to clipboard via `navigator.clipboard` (with fallback) and triggers a `vue-sonner` toast notification (`"Link copied"`).
  - Enhanced [`VideoPlayer.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/VideoPlayer.vue) with a glassmorphic play button overlay when paused, guaranteeing video always displays a visible interactive play trigger.
  - Added comprehensive test coverage in [`tests/Feature/MeetingDemoAndShareTest.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/tests/Feature/MeetingDemoAndShareTest.php).

---

## Phase 7.11: Meeting Deletion + Idempotent Seeder
- **Meeting Deletion ([`MeetingController.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/app/Http/Controllers/MeetingController.php), [`routes/web.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/routes/web.php), [`Show.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Meetings/Show.vue)):**
  - Added route `DELETE /meetings/{meeting}` named `meetings.destroy` under `auth` middleware.
  - Method `MeetingController::destroy` verifies meeting ownership via `abort_unless($meeting->user_id === auth()->id(), 403)` and deletes the record, redirecting to `meetings.index` with success flash message.
  - Added `'user_id' => $meeting->user_id` to meeting payload in `renderMeetingDetail()`.
  - Added "Delete" action button in [`Show.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Meetings/Show.vue) header, visible strictly to the meeting owner.
  - Integrated `reka-ui` `AlertDialog` (`AlertDialogRoot`, `AlertDialogTrigger`, `AlertDialogPortal`, `AlertDialogOverlay`, `AlertDialogContent`, `AlertDialogTitle`, `AlertDialogDescription`, `AlertDialogCancel`, `AlertDialogAction`) to require explicit confirmation before deleting.
- **Idempotent Seeder ([`database/seeders/MeetingSeeder.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/database/seeders/MeetingSeeder.php)):**
  - Converted all `Meeting::create(...)` invocations to `Meeting::updateOrCreate(['title' => $title], [...])`.
  - Converted all `Highlight::create(...)` invocations to `Highlight::updateOrCreate(['meeting_id' => $meeting->id, 'timestamp_seconds' => ..., 'label' => ...], ['note' => ...])`.
  - Verified seeder idempotency: running `php artisan db:seed --class=MeetingSeeder` multiple times produces zero duplicates.
- **Verification & Tests ([`tests/Feature/MeetingDeleteTest.php`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/tests/Feature/MeetingDeleteTest.php)):**
  - Added Pest tests for meeting deletion, forbidden access for non-owners (403), and seeder idempotency.
  - Aligned expected counts in legacy tests with updated 3-meeting seeder dataset.

---

## 4. Current Next Step
Phase 7.11 complete and verified (Pest 62/62 passing, Pint clean, PHPStan 0 errors, Vue TSC clean, VP Build clean). Ready for user review.
