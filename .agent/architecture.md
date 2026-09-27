# System Architecture & Technical Design

## 1. Monolith Architecture Flow
**Fathom** is architected as a lean, unified Laravel 13 monolith paired with Vue 3 and Inertia v3:
- **Single-Stack SPA:** Web routes (`routes/web.php`) directly render Vue page components via `Inertia::render()`.
- **No API Complexity:** Avoids a disjointed REST/GraphQL API layer or token management (JWT/Sanctum tokens).
- **Session Authentication:** Handled seamlessly via Laravel Fortify session cookies with built-in CSRF protection.
- **Wayfinder Route Bindings:** Type-safe route invocation in TypeScript via Laravel Wayfinder.

---

## 2. Core Database Schema

### `meetings` Table
Stores primary meeting metadata, media references, transcript cues, and synthesized intelligence:
- `id` (bigint, primary key)
- `user_id` (foreign key constrained to `users`, indexed)
- `title` (string)
- `video_url` (string, nullable — points to local storage or CDN video/audio assets)
- `duration_seconds` (integer, default 0)
- `transcript` (json — structured multi-speaker transcript segments with timestamps, speaker names, and text)
- `summary` (longText, nullable — synthesized markdown executive overview)
- `summary_template` (string, default 'general' — supports 'general', 'sales', 'engineering')
- `action_items` (json, nullable — array of parsed action items with title, assignee, and completion status)
- `created_at`, `updated_at` (timestamps, with index on `created_at` for high-speed dashboard listings)

### `highlights` Table
Stores keyed timestamp bookmarks and thematic takeaways for fast seeking in the media player:
- `id` (bigint, primary key)
- `meeting_id` (foreign key constrained to `meetings`, `onDelete('cascade')`, indexed)
- `timestamp_seconds` (integer — exact playback offset in seconds)
- `label` (string — e.g. "Architecture Decision", "Action Item", "Key Feedback")
- `note` (text, nullable — contextual notes or quote snippet)
- `created_at`, `updated_at` (timestamps)

---

## 3. High-Performance Data Transfer (Inertia v3)

- **Column Pruning:** Always use explicit `select([...])` in Eloquent queries to retrieve only the fields needed for the active view. Avoid dumping massive transcript JSON payloads on index or search listing views.
- **Deferred Props (`Inertia::defer()`):**
  - Massive or computationally heavy payloads (such as extensive multi-speaker transcript JSON and AI-generated summaries) must be passed as deferred props:
    ```php
    return Inertia::render('Meetings/Show', [
        'meeting' => $meeting->only(['id', 'title', 'video_url', 'duration_seconds']),
        'highlights' => $meeting->highlights,
        'transcript' => Inertia::defer(fn () => $meeting->transcript),
        'summary' => Inertia::defer(fn () => $meeting->summary),
    ]);
    ```
  - This allows the page shell, video player, and navigation to render immediately without waiting for large JSON parsing.
- **Partial Reloads:**
  - After summary regeneration, template switching, or background sync, update the frontend via targeted partial reloads:
    ```typescript
    router.reload({ only: ['summary', 'action_items'] });
    ```
  - Never trigger a full page visit or reload for scoped component updates.

---

## 4. Asynchronous Processing & Background Jobs
- **Non-Blocking LLM Pipelines:** Any synthetic AI processing (such as meeting summarization, multi-template regeneration, or action item extraction) must be dispatched as queued Laravel Jobs (`ProcessMeetingSummary`, `ExtractActionItems`).
- **Simulated & Seeded Transcripts:** To ensure snappy local testing and offline reliability, the capture layer uses pre-seeded realistic multi-speaker recordings and mock uploads rather than maintaining fragile external recording bots.

---

## 5. Security & Data Integrity
- **Mass Assignment:** All models strictly declare explicit `$fillable` attributes.
- **Form Requests:** Every state-altering action (creating/updating meetings, adding highlights, submitting AI questions) uses dedicated Laravel `FormRequest` classes with rigorous validation rules.
- **CSRF & Session Security:** Handled automatically by Laravel and Inertia middleware (`VerifyCsrfToken`).
