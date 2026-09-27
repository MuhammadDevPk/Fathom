# Visual Design Reference & Style Specifications

> **Notice:** This document is the persistent visual memory of **Fathom**. It has been populated from the design reference screenshots and must be referenced on every turn before crafting or updating any Vue component. All UI must faithfully adhere to this aesthetic, while strictly branding all copy, labels, and products as **Fathom** (never SenseLab).

---

## 1. Overall Visual Theme

- **Mode:** Light mode by default. High contrast, clean, airy SaaS aesthetic.
- **Canvas / Background:** Pure white base (`#ffffff`) augmented with subtle ambient mesh gradients in sky blue (`rgba(56, 189, 248, 0.07)`) and soft amber/rose (`rgba(251, 146, 60, 0.06)`).
- **Surfaces & Cards:** Crisp white cards (`bg-white`) bordered with delicate neutral outlines (`border-zinc-200/80` or `border-slate-200/70`). Secondary inset backgrounds use `#f8fafc` or `#fafafa`.
- **Corner Radii Hierarchy:**
  - Hero application window & large containers: `rounded-3xl` (24px).
  - Standard cards, bento blocks, video players: `rounded-2xl` to `rounded-3xl` (16px–24px).
  - Floating badges, section tags, pills, and CTAs: `rounded-full` (9999px).
  - Form inputs, tabs, and action buttons: `rounded-xl` (12px).
- **Shadows & Elevation:**
  - Base cards: `shadow-sm` or soft atmospheric shadow `shadow-lg shadow-zinc-200/40`.
  - App window preview: `shadow-2xl shadow-slate-300/40`.
  - Interactive hover state: `hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 ease-out`.

---

## 2. Color Palette & Token Definitions

| Token | Tailwind Class / Value | Observed Usage |
|---|---|---|
| **Base Canvas** | `bg-white` (`#ffffff`) | Global page background |
| **Subtle Backdrop** | `bg-zinc-50` / `bg-slate-50` (`#f8fafc`) | Card inset areas, table headers, timeline tracks |
| **Primary Headings** | `text-zinc-900` / `text-slate-900` (`#0f172a`) | Main headlines, modal titles, bold numbers |
| **Body & Subtitles** | `text-zinc-600` / `text-slate-600` (`#475569`) | Paragraph text, card descriptions, speaker notes |
| **Muted Meta** | `text-zinc-400` / `text-slate-400` (`#94a3b8`) | Timestamps, durations, captions, hotkey labels |
| **Primary CTA Gradient** | `bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500` | "Start Free", "Record Meeting", main primary actions |
| **Secondary CTA** | `bg-white border border-zinc-200 text-zinc-800 hover:bg-zinc-50` | "How it works", "Video & Transcript", cancel actions |
| **Dark Contrast Pill** | `bg-zinc-950 text-white hover:bg-zinc-900` | "Login", "Share", high-emphasis nav actions |
| **Brand Ribbon Accent** | Multi-stop: Cyan `#06b6d4`, Blue `#2563eb`, Magenta `#d946ef` | Fathom ribbon logo, category highlights, active markers |
| **Category: Sky/Blue** | `bg-sky-50 text-sky-700 border-sky-200/60` | Architecture decisions, engineering template tags |
| **Category: Amber/Orange**| `bg-amber-50 text-amber-700 border-amber-200/60` | Action items, sales template tags, alert badges |
| **Category: Emerald/Green**| `bg-emerald-50 text-emerald-700 border-emerald-200/60` | Completed status, live indicators, outcome validated |
| **Dark Video Canvas** | `bg-zinc-950` (`#09090b`) | Video demo container, media player viewport |

---

## 3. Typography & Text Hierarchy

- **Font Family:** Clean sans-serif stack (`Inter`, system UI sans-serif).
- **Hero Title (`H1`):** `text-5xl md:text-6xl font-bold tracking-tight text-zinc-900 leading-[1.1]`.
- **Section Title (`H2`):** `text-3xl md:text-4xl font-bold tracking-tight text-zinc-900 leading-[1.2]`.
- **Card Title (`H3`):** `text-xl font-semibold text-zinc-900 tracking-tight`.
- **Hero Subtitle:** `text-lg md:text-xl text-zinc-600 leading-relaxed max-w-2xl mx-auto`.
- **Text Highlights:** Key words inside headings emphasized with color:
  - Cyan/Sky text: `text-sky-600 font-semibold`.
  - Amber text: `text-amber-600 font-semibold`.

---

## 4. Layout Primitives & Component Inventory (From Screenshots)

### 1. Navigation Header
- Sticky, floating translucent nav bar (`backdrop-blur-md bg-white/80 border-b border-zinc-200/60`).
- Left: Fathom ribbon logo + bold wordmark **Fathom**.
- Center: Horizontal navigation links ("Home", "About", "Features", "Solutions", "Resources") with chevron dropdown indicators; active link highlighted in sky blue.
- Right: Rounded-full dark contrast button ("Login" / "Get Started") with icon.

### 2. Hero Section
- Centered layout with generous top padding (`py-16 md:py-24`).
- Top pill badge: Centered `rounded-full` pill ("Backed by alóz / speedrun" or "Intelligent Meeting Copilot") with subtle border and muted typography.
- Headline: 2-line tight-tracking bold title ("Build AI That Improves Itself" → for Fathom: "Turn Meetings Into Actionable Intelligence").
- Centered subtext explaining the core value proposition.
- Dual button group: Primary gradient pill ("Start Free" with rocket icon) alongside secondary ghost button ("How it works" with sparkles icon).

### 3. Application Mockup & Floating Satellite Cards
- Main app window framed in `rounded-3xl bg-white border border-slate-200/80 shadow-2xl`.
- Inside header: Breadcrumb navigation, live indicator dot ("Live"), search bar with `Cmd+K` badge, user avatar.
- Metric counter cards: 3-column stats with pastel indicator chips ("Entries Written", "Entities Touched", "Total Reads").
- Floating overlapping cards:
  - Left card ("Rooms" / "Meetings"): Icon badge, clean title, subtext, and bottom tag card with 4 pastel indicator dots.
  - Right card ("Framework Agnostic" / "Integrations"): Central logo hub connected to external platform badges via dashed lines.

### 4. Bento Feature Grid
- 3-column responsive card grid (`gap-6`).
- Asymmetric card layout:
  - Compact icon cards with ribbon logo and bold summary.
  - Wide outcome cards featuring 4 pastel micro-dots (`bg-sky-400`, `bg-indigo-400`, `bg-pink-400`, `bg-amber-400`) and two-tone emphasis text.
  - Preview cards containing embedded dark timeline graphs or interactive dialogue traces.
  - Pill tag cloud card: Flex-wrap container with `rounded-full` pastel pill tags.

### 5. Video Showcase Container
- Expansive container with `rounded-3xl bg-zinc-950 p-8 md:p-12 shadow-2xl relative overflow-hidden`.
- Ambient neon paths: Subtle glowing cyan/blue vector lines in the background.
- Foreground overlay: Crisp white headline ("Your meetings should improve with every run.") and subhead.
- Glassmorphic Play Button: Centered translucent circle (`bg-white/10 backdrop-blur-md border border-white/20 hover:scale-110 transition-transform`) housing a white triangle play icon.

### 6. Hub-and-Spoke Topology Grid
- Central circular dark badge with Fathom logo.
- Radiating dotted connector lines (`border-dashed border-sky-300`) leading to 6 surrounding white cards.
- Cards feature micro-tables, confidence scores, and mini status badges.

### 7. 5-Minute Onboarding Flow (Step Cards)
- 3 horizontal step cards (`Step 01`, `Step 02`, `Step 03` in light blue pill badges).
- Step 01 includes a realistic interactive preview (Auth modal with "Continue with Google", "Continue with GitHub", and email form).
- Step 02 & 03 detail setup instructions with clean typography and icon accents.

---

## 5. Spacing, Rhythm & Micro-interactions

- **Max Content Width:** `max-w-6xl` or `max-w-7xl` with `mx-auto px-4 sm:px-6 lg:px-8`.
- **Section Spacing:** Generous breathing room (`py-16 md:py-24`).
- **Card Padding:** `p-6` to `p-8` for standard cards; `p-4` for compact widgets.
- **Button Micro-interactions:** Smooth scale and shadow transitions:
  - `active:scale-95 transition-all duration-150`.
- **Icons:** Standardized using `@lucide/vue`, default size `w-5 h-5` or `w-4 h-4` in badges, stroke width `1.75` to `2.0`.

---

## 6. Rules for Component Implementation

1. **Strict Reuse:** Always match the colors, radii, shadows, and spacing definitions documented above.
2. **Never Default to Dark Mode:** Even though video containers are dark, the application layout and pages are strictly light mode.
3. **No SenseLab Copy:** All references, mock names, titles, and branding must strictly use **Fathom**.
