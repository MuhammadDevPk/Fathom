<?php

namespace Database\Seeders;

use App\Models\Highlight;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
        ]);

        // Meeting 1: Product & Engineering Sync
        $meeting1 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Product & Engineering Sync: AI Intelligence Engine v2',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_seconds' => 125,
            'transcript' => [
                [
                    'speaker' => 'Alex Chen',
                    'start' => 0.0,
                    'end' => 7.5,
                    'text' => 'Good morning team. Today we are locking in the final architecture for our post-meeting intelligence workspace.',
                ],
                [
                    'speaker' => 'Maya Patel',
                    'start' => 8.1,
                    'end' => 17.2,
                    'text' => 'From the frontend side, we have replaced the custom tabs and dialogs with Reka UI primitives. The accessibility and keyboard navigation are buttery smooth now.',
                ],
                [
                    'speaker' => 'Marcus Brody',
                    'start' => 18.0,
                    'end' => 28.4,
                    'text' => 'On the backend, we leveraged Inertia v3 deferred props. The initial page shell now loads in under 40 milliseconds because the heavy transcript JSON streams in asynchronously.',
                ],
                [
                    'speaker' => 'Elena Rostova',
                    'start' => 29.0,
                    'end' => 38.6,
                    'text' => 'From the AI pipeline perspective, the multi-template summarization pipeline is now fully queued. General, Sales, and Engineering summaries process in parallel.',
                ],
                [
                    'speaker' => 'Alex Chen',
                    'start' => 39.2,
                    'end' => 49.0,
                    'text' => 'That is a massive improvement. What about transcript scrubbing? When a user clicks any cue, does the video jump without noticeable lag?',
                ],
                [
                    'speaker' => 'Maya Patel',
                    'start' => 49.5,
                    'end' => 61.0,
                    'text' => 'Yes, exactly. We hooked VueUse useMediaControls directly into the HTML5 video element. Cues compute active ranges on a windowed timestamp rather than looping the full array.',
                ],
                [
                    'speaker' => 'Marcus Brody',
                    'start' => 61.8,
                    'end' => 72.5,
                    'text' => 'Decision confirmed: We are officially deprecating all synchronous LLM API calls and routing all meeting processing through our asynchronous worker queues.',
                ],
                [
                    'speaker' => 'Elena Rostova',
                    'start' => 73.1,
                    'end' => 82.0,
                    'text' => 'Agreed. That guarantees zero timeouts for meetings longer than an hour and keeps server memory usage strictly bounded.',
                ],
                [
                    'speaker' => 'Alex Chen',
                    'start' => 82.7,
                    'end' => 93.4,
                    'text' => 'Great. Action item: Maya, please finalize the scrubber seek interaction with VueUse useMediaControls and push to staging by Thursday afternoon.',
                ],
                [
                    'speaker' => 'Maya Patel',
                    'start' => 94.0,
                    'end' => 102.5,
                    'text' => 'Will do, Alex. I will have the PR up for Marcus to review by Wednesday evening.',
                ],
            ],
            'summary' => "## Executive Summary\nThe team locked in the technical architecture for the Fathom intelligence engine, standardizing on **Reka UI** for headless primitives and **Inertia v3 deferred props** for sub-50ms page loads.\n\n### Key Decisions\n- **Asynchronous Architecture:** Deprecated synchronous LLM API calls in favor of asynchronous queued jobs to eliminate timeouts on lengthy meetings.\n- **Frontend Stack:** Standardized on Reka UI and VueUse `useMediaControls` for instant video-to-transcript synchronization.\n\n### Action Items\n- **@Maya Patel:** Finalize the video scrubber seek interaction with VueUse and deploy to staging by Thursday.",
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Finalize video scrubber seek interaction with VueUse useMediaControls',
                    'assignee' => 'Maya Patel',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Validate async queue worker configuration on staging',
                    'assignee' => 'Marcus Brody',
                    'completed' => true,
                ],
                [
                    'id' => 3,
                    'task' => 'Benchmark parallel LLM summary generation across all 3 templates',
                    'assignee' => 'Elena Rostova',
                    'completed' => false,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting1->id,
            'timestamp_seconds' => 18,
            'label' => 'Architecture Decision',
            'note' => 'Inertia v3 deferred props streaming for transcript payloads',
        ]);
        Highlight::create([
            'meeting_id' => $meeting1->id,
            'timestamp_seconds' => 61,
            'label' => 'Technical Decision',
            'note' => 'Deprecate synchronous LLM calls; enforce async queued workers',
        ]);
        Highlight::create([
            'meeting_id' => $meeting1->id,
            'timestamp_seconds' => 82,
            'label' => 'Action Item',
            'note' => 'Maya to finalize scrubber seek interaction by Thursday',
        ]);

        // Meeting 2: Executive Design Review
        $meeting2 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Executive Design Review: Fathom Light-Mode Modern SaaS Redesign',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'duration_seconds' => 180,
            'transcript' => [
                [
                    'speaker' => 'Sarah Jenkins',
                    'start' => 0.0,
                    'end' => 8.2,
                    'text' => 'Welcome everyone. Today we are reviewing the visual design refresh for Fathom, moving towards a clean, light-mode modern SaaS aesthetic.',
                ],
                [
                    'speaker' => 'David Kim',
                    'start' => 9.0,
                    'end' => 18.5,
                    'text' => 'I love this direction. The crisp white backdrop with subtle sky and amber mesh gradients gives it an extremely premium feel.',
                ],
                [
                    'speaker' => 'Rachel Adams',
                    'start' => 19.1,
                    'end' => 31.0,
                    'text' => 'We established a strict token hierarchy: rounded-3xl for the main app containers, rounded-2xl for cards, and rounded-full for primary gradient CTAs.',
                ],
                [
                    'speaker' => 'Alex Chen',
                    'start' => 31.8,
                    'end' => 42.4,
                    'text' => 'What about iconography and typography? We want to avoid generic heavy fonts and maintain tight letter-spacing on all headlines.',
                ],
                [
                    'speaker' => 'Rachel Adams',
                    'start' => 43.0,
                    'end' => 54.2,
                    'text' => 'We standardized on the Inter font stack with tight tracking and configured Lucide Vue for clean 1.75 stroke-weight outlined icons.',
                ],
                [
                    'speaker' => 'David Kim',
                    'start' => 55.0,
                    'end' => 67.5,
                    'text' => 'Decision: The executive team officially approves the light-mode visual design direction. We will proceed with engineering handoff immediately.',
                ],
                [
                    'speaker' => 'Sarah Jenkins',
                    'start' => 68.2,
                    'end' => 79.0,
                    'text' => 'Excellent. Action item: Rachel, please prepare the complete icon token inventory and hand off spacing guidelines to Maya and the frontend team by tomorrow.',
                ],
                [
                    'speaker' => 'Rachel Adams',
                    'start' => 79.8,
                    'end' => 88.0,
                    'text' => 'Understood Sarah. The Figma token file and Tailwind v4 mapping document will be ready by 10 AM.',
                ],
            ],
            'summary' => "## Executive Summary\nThe executive team reviewed and approved the new **Fathom** light-mode SaaS design system. The system emphasizes clean white canvases, soft ambient mesh glows, and tight-tracking typography.\n\n### Key Decisions\n- **Approved Visual Identity:** Formally ratified the modern light-mode design with sky-to-amber primary CTAs and rounded-3xl container radii.\n- **Icon Standard:** Selected `@lucide/vue` with 1.75 stroke weight across all dashboard and detail views.\n\n### Action Items\n- **@Rachel Adams:** Export Figma design tokens and hand off Tailwind v4 theme specifications to engineering tomorrow morning.",
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Prepare icon token inventory and Tailwind v4 spacing spec',
                    'assignee' => 'Rachel Adams',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Verify color contrast compliance across light-mode badges',
                    'assignee' => 'Sarah Jenkins',
                    'completed' => true,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 19,
            'label' => 'Design Token Standard',
            'note' => 'Established rounded-3xl container and rounded-full CTA standards',
        ]);
        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 55,
            'label' => 'Executive Approval',
            'note' => 'Approved light-mode design with sky/amber mesh gradients',
        ]);
        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 68,
            'label' => 'Action Item',
            'note' => 'Rachel to hand off icon tokens and spacing guidelines by tomorrow',
        ]);

        // Meeting 3: Enterprise Q3 Sales Pipeline Call
        $meeting3 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Enterprise Q3 Sales Pipeline & Customer Discovery Call: FinTech Global',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'duration_seconds' => 210,
            'transcript' => [
                [
                    'speaker' => 'Jordan Miller',
                    'start' => 0.0,
                    'end' => 9.0,
                    'text' => 'Good afternoon Samantha. Thank you for joining us to evaluate Fathom for FinTech Global compliance and executive meeting intelligence.',
                ],
                [
                    'speaker' => 'Samantha Wu',
                    'start' => 9.8,
                    'end' => 22.4,
                    'text' => 'Thanks Jordan. Our primary concern is data residency and SOC2 Type II controls. Our audit teams review over 200 client advisory meetings weekly.',
                ],
                [
                    'speaker' => 'Devante Washington',
                    'start' => 23.0,
                    'end' => 36.5,
                    'text' => 'Fathom supports customer-managed encryption keys and dedicated enterprise tenants. Data in transit and at rest is AES-256 encrypted.',
                ],
                [
                    'speaker' => 'Priya Sharma',
                    'start' => 37.2,
                    'end' => 49.0,
                    'text' => 'We also offer automated PII redaction on transcript cues, which strips sensitive account numbers and personal data before summary synthesis.',
                ],
                [
                    'speaker' => 'Samantha Wu',
                    'start' => 49.8,
                    'end' => 61.2,
                    'text' => 'That PII redaction feature is exactly what our compliance team has been requesting. That makes this a viable platform for our wealth management division.',
                ],
                [
                    'speaker' => 'Jordan Miller',
                    'start' => 62.0,
                    'end' => 73.0,
                    'text' => 'Decision: We have reached mutual agreement to launch a 60-day enterprise proof-of-concept for 500 wealth management advisors starting next month.',
                ],
                [
                    'speaker' => 'Samantha Wu',
                    'start' => 73.8,
                    'end' => 84.5,
                    'text' => 'Wonderful. We need SSO configured and the security questionnaire signed off before our internal review committee meets on Friday.',
                ],
                [
                    'speaker' => 'Devante Washington',
                    'start' => 85.2,
                    'end' => 96.0,
                    'text' => 'Action item: Devante to configure the SAML SSO test tenant and deliver the completed SOC2 compliance package by Friday 3 PM.',
                ],
                [
                    'speaker' => 'Priya Sharma',
                    'start' => 96.8,
                    'end' => 105.0,
                    'text' => 'I will coordinate onboarding and schedule our initial admin training session for the following Monday.',
                ],
            ],
            'summary' => "## Executive Summary\nFathom met with **FinTech Global** to review enterprise compliance requirements. The prospect validated our automated PII redaction and agreed to kick off a 500-seat pilot.\n\n### Key Decisions\n- **Enterprise Pilot Approved:** Agreed on a 60-day proof-of-concept covering 500 wealth management advisors.\n- **Compliance Validation:** FinTech Global approved our SOC2 Type II security posture and AES-256 encryption architecture.\n\n### Action Items\n- **@Devante Washington:** Provision SAML SSO test tenant and deliver SOC2 compliance packet by Friday 3 PM.\n- **@Priya Sharma:** Schedule advisor administrator training kickoff for Monday.",
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Configure SAML SSO sandbox tenant and provide test credentials',
                    'assignee' => 'Devante Washington',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Deliver completed SOC2 Type II security audit documentation',
                    'assignee' => 'Devante Washington',
                    'completed' => false,
                ],
                [
                    'id' => 3,
                    'task' => 'Schedule FinTech Global admin onboarding training session',
                    'assignee' => 'Priya Sharma',
                    'completed' => true,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 23,
            'label' => 'Enterprise Security',
            'note' => 'Confirmed AES-256 encryption and dedicated tenant isolation',
        ]);
        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 62,
            'label' => 'Deal Milestone',
            'note' => 'Agreed on 60-day enterprise POC for 500 advisor seats',
        ]);
        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 85,
            'label' => 'Action Item',
            'note' => 'Devante to configure SAML SSO tenant by Friday 3 PM',
        ]);

        // Meeting 4: Infrastructure & Security Post-Mortem
        $meeting4 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Infrastructure & Security Post-Mortem: Webhook Latency Optimization',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'duration_seconds' => 150,
            'transcript' => [
                [
                    'speaker' => 'Liam O\'Connor',
                    'start' => 0.0,
                    'end' => 8.5,
                    'text' => 'Good morning. We are reviewing the root cause analysis for the transcript ingestion lag experienced during yesterday peak volume.',
                ],
                [
                    'speaker' => 'Carlos Gomez',
                    'start' => 9.2,
                    'end' => 20.0,
                    'text' => 'The bottleneck occurred because the webhook controller was parsing transcript JSON and executing summary jobs synchronously on the HTTP thread.',
                ],
                [
                    'speaker' => 'Elena Rostova',
                    'start' => 20.8,
                    'end' => 32.5,
                    'text' => 'When five concurrent 45-minute meetings finished simultaneously, PHP-FPM worker saturation caused queue backpressure and 504 gateway timeouts.',
                ],
                [
                    'speaker' => 'Alex Chen',
                    'start' => 33.2,
                    'end' => 43.0,
                    'text' => 'What is the immediate mitigation? We need to ensure webhooks return a 202 Accepted status in under 15 milliseconds regardless of payload size.',
                ],
                [
                    'speaker' => 'Carlos Gomez',
                    'start' => 43.8,
                    'end' => 56.0,
                    'text' => 'We refactored the ingestion pipeline to persist the raw JSON payload directly to storage and immediately dispatch a dedicated queued job.',
                ],
                [
                    'speaker' => 'Liam O\'Connor',
                    'start' => 56.8,
                    'end' => 69.0,
                    'text' => 'Decision: We are establishing a hard architectural rule that no webhook route is permitted to perform synchronous processing or external API calls.',
                ],
                [
                    'speaker' => 'Elena Rostova',
                    'start' => 69.8,
                    'end' => 79.5,
                    'text' => 'I verified that Redis queue workers handle the asynchronous processing with zero backlog, maintaining average turnaround times under 12 seconds.',
                ],
                [
                    'speaker' => 'Alex Chen',
                    'start' => 80.2,
                    'end' => 91.0,
                    'text' => 'Action item: Carlos, benchmark the Redis queue worker concurrency under 50 simulated simultaneous meetings and set up Pail alerting by Friday.',
                ],
                [
                    'speaker' => 'Carlos Gomez',
                    'start' => 91.8,
                    'end' => 99.5,
                    'text' => 'On it Alex. I will run the load test scripts in our staging environment and share the latency telemetry graphs.',
                ],
            ],
            'summary' => "## Executive Summary\nThe infrastructure team conducted a post-mortem on transcript ingestion latency, identifying synchronous HTTP webhook processing as the primary root cause.\n\n### Key Decisions\n- **Mandatory Async Webhooks:** Enforced a strict architectural constraint that all incoming media webhooks return a `202 Accepted` response within 15ms and dispatch async queue jobs.\n- **Queue Scaling:** Scaled Redis background workers to ensure parallel ingestion of concurrent meetings without thread starvation.\n\n### Action Items\n- **@Carlos Gomez:** Execute 50-concurrency load testing in staging and configure automated Pail alert thresholds by Friday.",
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Execute 50-concurrency load benchmark on Redis ingestion queues',
                    'assignee' => 'Carlos Gomez',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Configure real-time Pail exception monitoring alerts for workers',
                    'assignee' => 'Carlos Gomez',
                    'completed' => true,
                ],
                [
                    'id' => 3,
                    'task' => 'Document webhook asynchronous processing standard in system rules',
                    'assignee' => 'Liam O\'Connor',
                    'completed' => true,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting4->id,
            'timestamp_seconds' => 20,
            'label' => 'Root Cause Analysis',
            'note' => 'Identified synchronous HTTP thread saturation during webhook processing',
        ]);
        Highlight::create([
            'meeting_id' => $meeting4->id,
            'timestamp_seconds' => 56,
            'label' => 'Architecture Rule',
            'note' => 'Enforced mandatory asynchronous queue dispatch on all ingestion endpoints',
        ]);
        Highlight::create([
            'meeting_id' => $meeting4->id,
            'timestamp_seconds' => 80,
            'label' => 'Action Item',
            'note' => 'Carlos to benchmark queue concurrency and configure Pail alerting by Friday',
        ]);

        // Meeting 5: Customer Success & User Research Call
        $meeting5 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Customer Success & User Research: Fathom Beta Feedback with Acme Corp',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'duration_seconds' => 195,
            'transcript' => [
                [
                    'speaker' => 'Priya Sharma',
                    'start' => 0.0,
                    'end' => 8.0,
                    'text' => 'Hello Thomas, thanks for sitting down with us today to provide Acme Corp\'s feedback on your first month using the Fathom beta.',
                ],
                [
                    'speaker' => 'Thomas Wright',
                    'start' => 8.8,
                    'end' => 21.0,
                    'text' => 'Thanks Priya. Our product managers and sprint leads have been using Fathom daily. The transcript accuracy and instant search are phenomenal.',
                ],
                [
                    'speaker' => 'Maya Patel',
                    'start' => 21.8,
                    'end' => 33.5,
                    'text' => 'That is wonderful to hear Thomas. How is your team finding the executive summary tab and the action item extraction?',
                ],
                [
                    'speaker' => 'Thomas Wright',
                    'start' => 34.2,
                    'end' => 48.0,
                    'text' => 'The summaries are great for executives, but our engineering leads asked if we could switch perspectives—for instance, focusing purely on technical decisions and PRs.',
                ],
                [
                    'speaker' => 'Jordan Miller',
                    'start' => 48.8,
                    'end' => 59.5,
                    'text' => 'We are actually testing dynamic template switching: General, Sales, and Engineering templates that re-synthesize takeaways based on role context.',
                ],
                [
                    'speaker' => 'Thomas Wright',
                    'start' => 60.2,
                    'end' => 71.0,
                    'text' => 'If you provide that template toggle, Acme would gladly upgrade our entire 300-person organization to the Enterprise tier upon commercial launch.',
                ],
                [
                    'speaker' => 'Priya Sharma',
                    'start' => 71.8,
                    'end' => 83.0,
                    'text' => 'Decision: We will prioritize template switching (General, Sales, Engineering) as a marquee capability in our MVP release.',
                ],
                [
                    'speaker' => 'Maya Patel',
                    'start' => 83.8,
                    'end' => 95.5,
                    'text' => 'Action item: Priya to compile Acme Corp specific sales and engineering prompt criteria and sync with Elena on the ML prompts by Tuesday.',
                ],
                [
                    'speaker' => 'Thomas Wright',
                    'start' => 96.2,
                    'end' => 104.0,
                    'text' => 'Awesome. We look forward to testing the updated templates in next week sprint review.',
                ],
            ],
            'summary' => "## Executive Summary\nAcme Corp provided enthusiastic beta feedback on **Fathom**, highlighting search speed and speaker accuracy while requesting specialized summary perspectives for engineering and sales teams.\n\n### Key Decisions\n- **Feature Priority:** Ratified dynamic template switching (General, Sales, Engineering) as a core capability for the upcoming commercial release.\n- **Expansion Opportunity:** Acme Corp confirmed intent to expand from 40 beta seats to 300 enterprise seats upon template switcher availability.\n\n### Action Items\n- **@Priya Sharma:** Synthesize Acme prompt criteria for engineering and sales templates and share with ML team by Tuesday.",
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Synthesize Acme Corp template criteria and share with ML engineering',
                    'assignee' => 'Priya Sharma',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Design template switcher pill UI in meeting detail summary tab',
                    'assignee' => 'Maya Patel',
                    'completed' => true,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting5->id,
            'timestamp_seconds' => 34,
            'label' => 'Customer Feedback',
            'note' => 'Acme leads requested role-specific summary perspectives for engineering',
        ]);
        Highlight::create([
            'meeting_id' => $meeting5->id,
            'timestamp_seconds' => 71,
            'label' => 'Product Roadmap Decision',
            'note' => 'Prioritized dynamic template switching as core MVP feature',
        ]);
        Highlight::create([
            'meeting_id' => $meeting5->id,
            'timestamp_seconds' => 83,
            'label' => 'Action Item',
            'note' => 'Priya to compile template criteria and sync with ML team by Tuesday',
        ]);
    }
}
