# Fathom — AI Meeting Intelligence

> A Fathom.video clone built in 6 hours for the 8x technical assessment.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![Inertia](https://img.shields.io/badge/Inertia-v3-9553E9?style=flat&logo=inertia&logoColor=white)
![Vue 3](https://img.shields.io/badge/Vue-3.5-4FC08D?style=flat&logo=vuedotjs&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-5.2-3178C6?style=flat&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=flat&logo=tailwindcss&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-v5-00D1B2?style=flat&logo=pest&logoColor=white)

[Live Demo](YOUR_LIVE_URL_HERE)

---

## What It Does

Fathom is an AI-powered meeting intelligence workspace that turns recorded conversations into actionable executive knowledge. Users can search and filter meetings across titles and dialogue cues, watch recordings alongside an interactive, click-to-seek transcript with active cue highlighting, and toggle between General, Sales, and Engineering summary perspectives powered by asynchronous LLM pipelines. The platform surfaces structured action items with speaker attribution, timestamped bookmarks via a highlight dialog, and an interactive Ask AI copilot that cites exact dialogue timestamps for instant media seeking.

---

## Screenshots

<!-- Add landing page, dashboard, and meeting detail screenshots here -->

---

## Tech Stack

| Layer | Technology | Version | Purpose |
|---|---|---|---|
| **Backend Framework** | PHP | `^8.3` (PHP 8.4 runtime) | Core server runtime environment |
| **Backend Framework** | Laravel Framework (`laravel/framework`) | `^13.17` | Full-stack web framework & backend monolith |
| **Authentication** | Laravel Fortify (`laravel/fortify`) | `^1.37.2` | Headless session-based authentication & password management |
| **Inertia Adapter (PHP)** | Inertia Laravel (`inertiajs/inertia-laravel`) | `^3.0` | Server-side adapter supporting deferred props & partial reloads |
| **Route Generation** | Laravel Wayfinder (`laravel/wayfinder`) | `^0.1.14` | Type-safe TypeScript route bindings and action callers |
| **Frontend Framework** | Vue (`vue`) | `^3.5.13` | Reactive Single Page Application view layer (`<script setup lang="ts">`) |
| **Inertia Client (JS)** | Inertia Vue (`@inertiajs/vue3`) | `^3.0.0` | Client-side routing, deferred prop hydration, and visit lifecycle |
| **Type System** | TypeScript (`typescript`) | `^5.2.2` | Static type checking across components, composables, and routes |
| **Styling** | Tailwind CSS (`tailwindcss`) | `^4.1.1` | Modern utility-first CSS engine with CSS-native configuration |
| **Headless UI** | Reka UI (`reka-ui`) | `^2.9.8` | Accessible headless primitives (Dialog, Tabs, Tooltip, Dropdown) |
| **Iconography** | Lucide Vue (`@lucide/vue`) | `^1.17.0` | Modern, consistent SVG icon system |
| **Reactive Helpers** | VueUse (`@vueuse/core`) | `^12.8.2` | Media playback hooks, keyboard shortcut bindings, DOM helpers |
| **Toast Notifications** | vue-sonner (`vue-sonner`) | `^2.0.0` | Lightweight, accessible notification toasts |
| **AI Integration** | Groq API (`openai/gpt-oss-120b`) | `App\Services\GroqClient` | High-speed LLM summarization and timestamped Q&A |
| **Testing Suite** | Pest (`pestphp/pest`) | `^5.2` | Behavior-driven feature and unit testing suite |
| **Static Analysis** | Larastan / PHPStan (`larastan/larastan`) | `^3.9` | Level 5 static analysis for Laravel models and types |
| **Code Formatter** | Laravel Pint (`laravel/pint`) | `^1.27` | Automated PSR-12 and Laravel code style enforcement |

---

## Features

- **Dashboard:**
  - Responsive meetings grid displaying duration, dates, and participant chips.
  - Live debounced search across meeting titles and transcript dialogue text.
  - Distinct empty states for 0-meeting accounts and 0-result search queries.
- **Meeting Detail:**
  - Responsive layout with zero vertical page scrolling and internal panel scroll containers.
  - Native HTML5 video player with keyboard shortcut (`Space` to toggle playback).
  - Bidirectional click-to-seek transcript synchronization with smooth auto-scrolling.
  - Active transcript cue highlighting using lightweight timestamp boundaries.
- **AI Summary:**
  - Dynamic template switcher supporting **General**, **Sales**, and **Engineering** perspectives.
  - Inertia v3 deferred prop delivery (`Inertia::defer()`) with pulsing loading skeletons.
  - Targeted partial page reloads (`router.reload({ only: ['summary'] })`) without full page re-renders.
  - Asynchronous background job generation (`GenerateMeetingSummary`) dispatched to the queue.
- **Action Items:**
  - Dedicated action items tab rendering structured items extracted from dialogue.
  - Clear owner and speaker attribution for each takeaway.
  - Interactive completion checkboxes stored in client session state.
- **Highlights:**
  - Bookmark creation directly from any transcript cue via an accessible Reka UI modal dialog.
  - Validation rules guaranteeing timestamps do not exceed recording duration.
  - Dedicated highlights tab allowing instant one-click seeking to bookmarked timestamps.
- **Ask AI:**
  - Conversational Q&A copilot grounded in transcript cues and synthesized meeting summaries.
  - Session-persisted dialogue history preserving back-and-forth context.
  - Clickable `[MM:SS]` timestamp citations embedded in AI answers that seek the video on click.
  - Quick-prompt starter pills for common analytical queries.
- **Authentication:**
  - Laravel Fortify session auth with CSRF protection and rate limiting.
  - Full support for registration, login, logout, password reset, and password recovery.
  - Clean light-mode card layouts with ambient gradient glows.

---

## Architecture

Fathom is built as a single-stack Laravel monolith. Web routes directly render Vue 3 components through Inertia v3 protocols, eliminating the need for a decoupled REST or GraphQL API, separate client-side state stores, or JWT token refresh cycles. Authentication is maintained via standard, secure HTTP-only cookies.

### Repository File Structure

```text
.
├── .agent/                       # Agent governance rules & architectural memory
│   ├── architecture.md           # Monolith flow, DB schema, deferred props, async jobs
│   ├── memory.md                 # State tracking, roadmap, & task history
│   ├── patterns.md               # Vue/TS standards, design tokens, anti-patterns
│   ├── qa_report.md              # Quality audit & edge-case hardening report
│   ├── system_rules.md           # Core engineering boundaries & verification protocol
│   └── ui_reference.md           # Visual design specifications & tokens
├── .agent-logs/                  # Automatic session prompt & response transcripts
├── app/
│   ├── Actions/Fortify/          # User creation & password reset actions
│   ├── Http/
│   │   ├── Controllers/          # MeetingController, HighlightController
│   │   ├── Middleware/           # HandleInertiaRequests, HandleAppearance
│   │   └── Requests/             # StoreHighlightRequest, AskMeetingQuestionRequest
│   ├── Jobs/                     # AnswerMeetingQuestion, GenerateMeetingSummary
│   ├── Models/                   # Meeting, Highlight, User
│   ├── Providers/                # AppServiceProvider, FortifyServiceProvider
│   └── Services/                 # GroqClient (LLM completions & Q&A)
├── database/
│   ├── factories/                # MeetingFactory, UserFactory, HighlightFactory
│   ├── migrations/               # Schema migrations (users, meetings, highlights, jobs)
│   └── seeders/                  # DatabaseSeeder (5 realistic multi-speaker meetings)
├── resources/
│   ├── css/                      # app.css (Tailwind v4 theme & ambient gradients)
│   └── js/
│       ├── components/           # UI primitives & domain widgets
│       │   ├── ActionItemsList.vue
│       │   ├── AskAiPanel.vue
│       │   ├── HighlightsList.vue
│       │   ├── MeetingCard.vue
│       │   ├── SummaryPanel.vue
│       │   ├── TranscriptList.vue
│       │   ├── VideoPlayer.vue
│       │   └── ui/               # Reka UI headless accessible primitives
│       ├── composables/          # useTranscriptSync.ts, useAppearance.ts
│       ├── layouts/              # AppLayout, AuthSimpleLayout
│       ├── pages/                # Inertia SPA views
│       │   ├── auth/             # Login, Register, ForgotPassword, ResetPassword
│       │   ├── Meetings/         # Index.vue (dashboard), Show.vue (detail)
│       │   ├── Error.vue         # Custom HTTP error page
│       │   └── Welcome.vue       # Public marketing landing page
│       └── types/                # meeting.ts, index.d.ts, ui.ts
└── tests/
    ├── Feature/                  # MeetingControllerTest, MeetingSummaryTest, HighlightTest, etc.
    └── Unit/                     # Unit test suites
```

### Data Model

#### `meetings` Table
| Column | Type | Attributes | Description |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Unique meeting identifier |
| `user_id` | `bigint unsigned` | Nullable, Foreign Key (`users.id`), Index | Meeting owner |
| `title` | `varchar(255)` | Not Null | Meeting title |
| `video_url` | `varchar(2048)` | Nullable | Local asset path or remote media URL |
| `duration_seconds` | `int unsigned` | Default `0` | Total video duration in seconds |
| `transcript` | `json` | Not Null | Structured array of `{speaker, start, end, text}` cues |
| `summary` | `longtext` | Nullable | Synthesized markdown meeting summary |
| `summary_template` | `varchar(255)` | Default `'general'` | Active template (`general`, `sales`, `engineering`) |
| `action_items` | `json` | Nullable | Structured array of `{text, speaker, completed}` items |
| `created_at` | `timestamp` | Nullable, Index | Creation timestamp |
| `updated_at` | `timestamp` | Nullable | Update timestamp |

#### `highlights` Table
| Column | Type | Attributes | Description |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Unique highlight identifier |
| `meeting_id` | `bigint unsigned` | Foreign Key (`meetings.id`), Cascade Delete | Parent meeting |
| `timestamp_seconds` | `int unsigned` | Not Null | Playback offset in seconds |
| `label` | `varchar(255)` | Not Null | Highlight category or title |
| `note` | `text` | Nullable | User note or contextual takeaway |
| `created_at` | `timestamp` | Nullable | Creation timestamp |
| `updated_at` | `timestamp` | Nullable | Update timestamp |

### Request Flow: Ask AI

1. **User Submission:** The user submits a question in `AskAiPanel.vue`.
2. **Route Dispatch:** The Inertia form posts to `POST /meetings/{meeting}/ask`.
3. **Validation:** `AskMeetingQuestionRequest` verifies that the `question` string is required and within acceptable length limits.
4. **Execution / Queue:** `MeetingController::ask` instantiates the `AnswerMeetingQuestion` job.
5. **AI Synthesis:** `GroqClient::answerQuestion` prompts `openai/gpt-oss-120b` with the meeting transcript and summary, instructing it to cite `MM:SS` timestamps.
6. **Session Storage:** The synthesized answer and citations are appended to the user's session history under `meeting_{id}_qa`.
7. **Partial Reload:** The controller issues a redirect back, and Inertia completes a partial reload (`only: ['qa_history']`) to render the response bubble and interactive timestamp pills without reloading video or transcript state.

---

## Local Setup

Follow these steps to run Fathom on your local machine:

```bash
git clone <repo-url>
cd Fathom
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### Environment Configuration

Configure the following variables in your `.env` file:

| Variable | Purpose | Example |
|---|---|---|
| `APP_NAME` | Application name used across titles and email headers | `Fathom` |
| `APP_ENV` | Environment identifier | `local` |
| `APP_KEY` | 32-character application encryption key | Generated via `artisan key:generate` |
| `APP_DEBUG` | Enables detailed stack traces and debugging logs | `true` |
| `APP_URL` | Root URL for local development | `http://localhost:8000` |
| `DB_CONNECTION` | Database driver | `mysql` |
| `DB_HOST` | Database host address | `127.0.0.1` |
| `DB_PORT` | Database connection port | `3306` |
| `DB_DATABASE` | Database name | `fathom` |
| `DB_USERNAME` | Database username | `root` |
| `DB_PASSWORD` | Database password | `secret` |
| `SESSION_DRIVER` | Session storage engine | `database` |
| `QUEUE_CONNECTION` | Queue driver for asynchronous AI jobs | `database` |
| `CACHE_STORE` | Cache backend | `database` |
| `GROQ_API_KEY` | Groq Cloud API Key for LLM summarization and Ask AI | `gsk_...` |
| `GROQ_MODEL` | Default model identifier for Groq API completions | `openai/gpt-oss-120b` |
| `GROQ_BASE_URL` | Base API endpoint for Groq completions | `https://api.groq.com/openai/v1` |

### Database & Development Server

Run the database migrations and seeders, link public storage, and start the development servers:

```bash
php artisan migrate --seed
php artisan storage:link
npm run dev
php artisan queue:work  # in a second terminal
```

---

## Deployment Guide

This guide details deploying Fathom on a standard production host (such as Ubuntu 24.04 LTS via Laravel Forge, Ploi, or a manual VPS).

### Server Requirements
- **PHP:** 8.3 or 8.4 (with extensions: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo_mysql`, `session`, `tokenizer`, `xml`)
- **Database:** MySQL 8.0+ or MariaDB 10.11+
- **Node.js:** Node 20+ LTS and npm
- **Package Manager:** Composer 2.x
- **Web Server:** Nginx

### Production Environment Variables

Ensure your production `.env` contains the following settings:

```ini
APP_NAME=Fathom
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fathom_production
DB_USERNAME=fathom_user
DB_PASSWORD=your_secure_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local

GROQ_API_KEY=gsk_your_groq_api_key_here
GROQ_MODEL=openai/gpt-oss-120b
GROQ_BASE_URL=https://api.groq.com/openai/v1
```

### Build Commands in Order

Execute these commands during deployment:

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Queue Worker Configuration (Supervisor)

Create `/etc/supervisor/conf.d/fathom-worker.conf`:

```ini
[program:fathom-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work database --sleep=3 --tries=3 --timeout=120
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
stopwaitsecs=3600
```

Update and restart Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start fathom-worker:*
```

### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com;
    root /path/to/public;

    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Post-Deployment Verification Checklist

- [ ] Navigating to the live domain loads the public landing page with zero asset errors.
- [ ] Account registration and login succeed via Fortify session authentication.
- [ ] Seed meetings display on the dashboard with titles, speaker tags, and durations.
- [ ] Meeting detail view renders the video player, transcript, and deferred executive summary.
- [ ] Supervisor confirms `fathom-worker` is in the `RUNNING` state (`supervisorctl status`).

---

## Testing & Quality

Fathom enforces a strict testing and static analysis gate:

```bash
composer test         # Runs Pint style check, PHPStan static analysis, and Pest feature tests
npm run types:check   # Runs vue-tsc across all Vue components and TypeScript files
npm run build         # Validates production Vite asset compilation
```

### Latest Test Results

```text
Pint Formatter:       Passed (PSR-12 / Laravel standards)
PHPStan / Larastan:   Passed (0 static analysis errors)
Pest Feature Suite:   47 total tests (44 passed, 3 skipped, 0 failures, 300 assertions)
TypeScript Checker:   0 type errors across Vue and TypeScript files
Production Build:     Clean compilation in 3.73s
```

---

## AI Agent Governance

This repository incorporates a custom AI agent governance system located in [`.agent/`](.agent/). The governance files act as persistent guardrails that keep autonomous coding agents aligned with design specifications, performance rules, and architectural standards:

- [`.agent/system_rules.md`](.agent/system_rules.md): Defines engineering boundaries, the package-first policy, and the mandatory 4-step QA verification protocol.
- [`.agent/architecture.md`](.agent/architecture.md): Mandates the single-stack Laravel + Inertia monolith pattern, database schema boundaries, deferred props, and asynchronous job processing.
- [`.agent/patterns.md`](.agent/patterns.md): Establishes Vue 3 and TypeScript standards, lightweight timestamp comparisons for video-to-transcript synchronization, and strict anti-patterns.
- [`.agent/memory.md`](.agent/memory.md): Tracks real-time phase completion, architectural decisions, and next steps across conversational checkpoints.
- [`.agent/ui_reference.md`](.agent/ui_reference.md): Captures the light-mode SaaS aesthetic, color tokens, typography scales, and component spacing specifications.
- [`.agent/qa_report.md`](.agent/qa_report.md): Documents the comprehensive quality audit, failure resilience tests, and edge-case remediations.

All agent interactions, prompts, and tool executions are automatically logged to the [`.agent-logs/`](.agent-logs/) directory per the assessment capture requirements.

---

## Scope Decision

<!-- TODO: Author — write 3–5 sentences in your own voice explaining:
what you chose to build first, what you deliberately cut, and why.
This is the human product judgment section. Do not have AI write this. -->

---

## Roadmap / What I Would Build Next

- **Real Recording Bot:** WebRTC/SIP recording bot integration (Zoom, Google Meet, Microsoft Teams) for live automated call joining.
- **Calendar Integration:** Google Calendar and Microsoft Outlook synchronization to automatically queue and schedule upcoming calls.
- **Clip Sharing:** Video snippet generation and shareable public links for specific dialogue cues and highlights.
- **Multi-User Collaboration:** Workspace team management, shared meeting workspaces, and live timestamped comments.
- **Full-Text Search Index:** PostgreSQL full-text search or Laravel Scout with Meilisearch for high-scale transcript indexing across thousands of hours of audio.
- **Streaming Ask AI Responses:** Server-Sent Events (SSE) or WebSockets integration for real-time token streaming during Ask AI completions.

---

## Credits

Built for the **8x technical assessment**.

- **Fathom.video:** Core product concept, transcript synchronization flow, and meeting intelligence UX.
- **SenseLab:** Design language, layout aesthetics, ambient gradient mesh styling, and card elevation patterns.
