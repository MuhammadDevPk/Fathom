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
- [ ] Phase 8: Final review, polish & verification

---

## Phase 7.5: UI Polish & Authentication Redesign
- **Public Landing Page (`resources/js/pages/Welcome.vue`):** Built a high-converting public landing page for `/` with sticky top nav, hero section with 2-line tight headline and dual CTAs, interactive product preview mockup with floating satellite cards, 3-column features grid, 3-step onboarding flow, and tech stack badges row.
- **Authentication Screens (`resources/js/layouts/auth/AuthSimpleLayout.vue`, `Login.vue`, `Register.vue`, `ForgotPassword.vue`, `ResetPassword.vue`):**
  - Completely replaced the plain unstyled starter-kit screens with an elevated SaaS layout.
  - Added ambient mesh background glow orbs (`sky-200/30` and `amber-200/25` with radial dot matrix).
  - Floating top navigation bar with a "Back to Fathom" pill button (`ArrowLeft`) and live AI status pill.
  - Centered brand lockup with Fathom gradient container (`bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500`) and Sparkles emblem.
  - Elevated card container with `rounded-3xl border border-zinc-200/80 bg-white/95 p-8 shadow-2xl shadow-slate-200/60 backdrop-blur-md`.
  - Upgraded inputs to `h-11 rounded-xl` with smooth focus rings (`focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10`).
  - Styled primary CTA with Fathom's signature gradient pill (`bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500`) with loading spinner.
  - Added helpful default demo credentials banner (`demo@example.com / password`).
- **Brand Consistency (`AppLogoIcon.vue`, `AppLogo.vue`):** Replaced the default Laravel polygon SVG with Fathom's signature Sparkles emblem so sidebar, header, and auth layouts are 100% consistently branded.

---

## 4. Current Next Step
Authentication polish complete. Awaiting user review or proceeding to Phase 8 final review.
