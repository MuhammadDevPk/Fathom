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
- [ ] Phase 8: Final review, polish & verification

---

## Phase 7.5c: Targeted UI Polish
- **Sidebar Header & Nav Rows ([`AppLogo.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/AppLogo.vue) & [`NavMain.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/NavMain.vue)):**
  - Tightened logo icon to "Fathom" wordmark spacing (`ml-1.5`).
  - Styled "Platform" label with lighter weight (`font-normal`), increased tracking (`tracking-[0.14em] uppercase`), and increased gap (`mb-2 px-3`).
  - Transformed nav rows to soft tinted pill background without hard borders (`bg-sky-100/60 font-medium text-sky-800`), matching hover tint (`hover:bg-zinc-100/70`), and aligned icon and label baselines.
- **Search Bar ([`Meetings/Index.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Meetings/Index.vue)):**
  - Enhanced to `rounded-2xl` with `shadow-sm`, subtle focus border with sky focus ring (`focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:shadow-md`).
  - Increased vertical padding to `py-4`, enlarged icon to `size-4.5`, muted placeholder (`text-zinc-400/80`), and widened gap between icon and input text (`pl-13`).
- **Meeting Cards ([`MeetingCard.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/MeetingCard.vue)):**
  - Integrated SenseLab pricing card inspiration: added subtle low-opacity blue→amber gradient border treatment on hover (`from-sky-400/40 via-indigo-300/25 to-amber-400/40`).
  - Replaced harsh borders with soft glow shadow (`shadow-sm shadow-slate-200/50 hover:shadow-xl hover:shadow-sky-100/50`).
  - Converted speaker chips to filled pastel pill backgrounds (`bg-zinc-100/90`, `bg-sky-50/90 text-sky-700`, `bg-purple-50/90 text-purple-700`, `bg-amber-50/90 text-amber-700`).
  - Enhanced duration badge with soft blue fill, bold weight (`bg-sky-50/90 font-bold px-3.5 py-1.5 text-sky-700`), and no harsh border.
  - Increased card padding to `p-8 md:p-9`, widened gap between title and chips to `mt-6`, and adjusted hover lift to subtle `-translate-y-0.5` without border color flicker.
- **Meeting Detail Page ([`Meetings/Show.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/pages/Meetings/Show.vue), [`TranscriptList.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/TranscriptList.vue), [`Breadcrumbs.vue`](file:///Users/muhammad/Personal/Projects/Personal%20Projects/8x/Fathom/resources/js/components/Breadcrumbs.vue)):**
  - Header: Breadcrumbs and back navigation made smaller and muted (`text-[11px] text-zinc-400`) with generous spacing; title enlarged to `text-3xl md:text-4xl font-extrabold tracking-tight`; right meta badges updated to soft filled pills (duration, date, synced media) with zero border-only styling.
  - Tabs bar: Converted to soft pill container (`rounded-full bg-zinc-100/80 p-1`) with soft pill active triggers (`rounded-full px-3.5 py-1.5 bg-white shadow-xs` with category accent text).
  - Transcript component: Speaker badges converted to soft filled pastel pills without outlines; timestamp pills given soft blue tint with tightened tracking (`tracking-tight`); increased cue vertical rhythm from `space-y-2` to `space-y-6`; added subtle row hover state with light sky background tint and `border-l-sky-300` accent while maintaining sky-blue active cue styling.
  - **100% Viewport Height & No-Scroll Architecture:** Sized detail layout to `h-[calc(100vh-4rem)] max-h-[calc(100vh-4rem)] overflow-hidden` with `flex-1 min-h-0` columns; constrained video player (`max-h-[220px] xl:max-h-[250px]`) so both video and executive summary tabs are 100% visible on screen without page scroll; panel contents scroll internally (`overflow-y-auto`).
  - **Relocated AI Chat to Transcript Panel:** Transferred Ask AI into the right panel tabs alongside Transcript and Highlights (`Transcript`, `Ask AI`, `Highlights`); `AskAiPanel` now occupies 100% of panel height with auto-scrolling conversation flow and sticky footer input.

---

## 4. Current Next Step
Viewport fitting and AI chat relocation complete. Awaiting user approval.
