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
- [x] Phase 7.5: UI Polish + Public Landing Page (Public landing page at `/` matching SenseLab visual references, Dashboard & Detail rounded-3xl and ambient wash polish, Sidebar soft highlight pill active states, consistent primary gradient tokens)
- [ ] Phase 8: Final review, polish & verification

---

## Phase 7.5: UI Polish & Public Landing Page
- **Public Landing Page (`resources/js/pages/Welcome.vue`):** Built a high-converting public landing page for `/` with sticky top nav, hero section with 2-line tight headline and dual CTAs, interactive product preview mockup with floating satellite cards, 3-column features grid, 3-step onboarding flow, and tech stack badges row.
- **Dashboard Polish (`resources/js/pages/Meetings/Index.vue` & `MeetingCard.vue`):** Added subtle ambient wash with blurred light-mode glow orbs behind the banner, increased card padding to `p-7`/`p-8`, enlarged title contrast, and refined card hover state with subtle lift and soft atmospheric shadow.
- **Meeting Detail Polish (`resources/js/pages/Meetings/Show.vue` & `VideoPlayer.vue`):** Upgraded video container and panels to `rounded-3xl`, added top ambient gradient wash, refined tabs bar with soft shadow on active tab triggers, and added micro-transitions.
- **Sidebar Polish (`resources/js/components/NavMain.vue`):** Replaced default active state with a soft highlight pill (`bg-sky-50 text-sky-700 border border-sky-200/70 shadow-2xs`).
- **Consistency Pass:** Unified Fathom primary gradient (`bg-gradient-fathom`, `text-gradient-fathom`) and standardized all transitions to `duration-200`.

---

## 4. Current Next Step
Phase 7.5 complete. Awaiting user approval before proceeding to Phase 8.
