# Coding Patterns & Anti-Patterns

## 1. UI/UX Visual Direction (Design System)
Inspired by our modern SaaS design reference screenshots, the visual target for **Fathom** is crisp, light-mode, and minimalist with polished micro-interactions:
- **Default Theme:** Light mode. Crisp white surfaces (`#ffffff`), very soft background tones (`#fafafa` / `zinc-50`), delicate border lines (`border-zinc-200/80` or `border-slate-200/70`), and soft shadows (`shadow-sm`, `shadow-lg shadow-zinc-200/40`).
- **Accents & Gradients:**
  - **Primary CTAs:** Vibrant, energetic gradient pills (cyan-blue `#0284c7` to amber-orange `#f59e0b` or vibrant indigo `#6366f1` to sky-blue `#38bdf8`) with crisp white typography and rounded-full borders.
  - **Secondary Actions:** Minimalist white/ghost buttons with thin neutral borders (`border-zinc-200 hover:bg-zinc-50 hover:border-zinc-300`).
  - **Badges & Pills:** Rounded-full pill tags with subtle neutral borders and pastel icon accents (e.g. `Step 01`, `Backed by...`, `How it works`).
- **Typography:**
  - Clean sans-serif font family (Inter, system sans).
  - Headings: Bold, tight letter-spacing (`tracking-tight`), dark slate/charcoal text (`text-zinc-900` or `text-slate-900`).
  - Body: Legible, muted text (`text-zinc-600` or `text-slate-600`) with relaxed line heights.
- **Key Layout Patterns:**
  - **Hero Pattern:** Pill badge header, bold 2-line title, centered explanation paragraph, dual CTAs (gradient primary + ghost secondary), and an interactive app preview framed by floating capability cards.
  - **Feature Bento Grid:** 3-column structured cards with crisp icons, clear titles, concise descriptions, and embedded UI mockups.
  - **Step-by-Step Flow:** Numbered horizontal cards (`Step 01`, `Step 02`, `Step 03`) with interactive preview forms.
  - **Video Showcase Container:** Large dark container (`bg-zinc-950`) with generous `rounded-3xl` corners, subtle neon ambient trace paths, and a centered glassmorphic play button.

---

## 2. Vue 3 & TypeScript Patterns

- **Component Standard:** Every component must be written as:
  ```vue
  <script setup lang="ts">
  // Typed props, emits, and composables
  </script>
  ```
- **Video & Transcript Synchronization:**
  - Leverage `@vueuse/core`'s `useMediaControls` or native HTML5 `<video>` event hooks.
  - Highlight active transcript dialogue cues using lightweight timestamp range comparisons:
    ```typescript
    const isCueActive = (start: number, end: number, currentTime: number) => {
      return currentTime >= start && currentTime <= end;
    };
    ```
  - Avoid running heavy iteration loops on every `timeupdate` tick.
  - Auto-scroll the active cue into view using smooth scrolling with debounce or threshold checking.
- **Component Hierarchy:**
  - `resources/js/Pages/`: Coordinates routing, page shells, Inertia data props, and partial reloads.
  - `resources/js/Components/`: Modular, single-responsibility components built on `reka-ui` accessible primitives and Tailwind v4.

---

## 3. Pest Testing Patterns

- Write succinct, behavior-driven feature tests in `tests/Feature/` using Pest syntax:
  ```php
  it('displays the meeting details and synced transcript', function () {
      $user = User::factory()->create();
      $meeting = Meeting::factory()->create(['user_id' => $user->id]);

      $this->actingAs($user)
          ->get(route('meetings.show', $meeting))
          ->assertOk()
          ->assertInertia(fn ($page) => $page
              ->component('Meetings/Show')
              ->has('meeting')
          );
  });
  ```
- Use model factories for test setup rather than manually writing raw database records.

---

## 4. Strict Anti-Patterns (Forbidden)

- ❌ **Do NOT use dark mode as the default.** The design target is strictly clean, high-contrast light mode.
- ❌ **Do NOT build custom modal, dropdown, tab, or tooltip logic from scratch.** Use `reka-ui` headless primitives.
- ❌ **Do NOT introduce Pinia, Vuex, or ad-hoc global state stores.** Manage state via Inertia props, `useForm`, and URL query parameters.
- ❌ **Do NOT write raw unescaped SQL queries or unindexed database lookups.**
- ❌ **Do NOT attempt to build an external meeting recording bot.** The media capture layer is stubbed using realistic multi-speaker JSON data and seeded recordings.
- ❌ **Do NOT write plain JavaScript.** Every Vue and TS file must satisfy `npm run types:check` (`vue-tsc --noEmit`).
- ❌ **Do NOT mention or reference "SenseLab" anywhere in UI, copy, code, or documentation.** All product identity is **Fathom**.
