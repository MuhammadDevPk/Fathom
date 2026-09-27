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
- [x] Phase 8: Production README creation and verification (13-section technical assessment documentation with exact dependency versions, data model, request flow, deployment guide, and test statistics)

---

## Phase 8: Production Documentation
- **Production README.md ([`README.md`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/README.md)):**
  - Formatted across all 13 required sections in order with static badge row and live demo placeholder.
  - Dependency table matched to exact versions from `composer.json` and `package.json`.
  - Concrete data model schema and end-to-end request flow ("Ask AI") documented.
  - Complete local setup, `.env` specifications, and production deployment guide (Nginx + Supervisor worker configuration).
  - Scope decision placeholder section preserved for human author.
  - Governance overview referencing all `.agent/` files and `.agent-logs/` transcripts.

---

## 4. Current Next Step
Repository documentation complete and 100% test-verified. Ready for assessment submission.
