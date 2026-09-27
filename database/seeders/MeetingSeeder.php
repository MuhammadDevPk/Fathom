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
        $user = User::firstWhere('email', 'demo@fathom.test') ?? User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@fathom.test',
        ]);

        // Meeting 1: Product & Engineering Sync
        $meeting1 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Ecology & Climate Action: Dialogue with Forest Man of India & Dr. Ananth',
            'video_url' => '/videos/demo1.mp4',
            'duration_seconds' => 140,
            'transcript' => [
                [
                    'speaker' => 'Host',
                    'start' => 12.0,
                    'end' => 20.0,
                    'text' => 'I can spend one day or two days without phone or two hours a day or three hours a day without phone. I can have some discipline in using the phone.',
                ],
                [
                    'speaker' => 'Host',
                    'start' => 20.0,
                    'end' => 23.0,
                    'text' => 'Dr. Ananth I think can manage three minutes without the phone.',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 23.0,
                    'end' => 33.0,
                    'text' => 'That\'s a good start, that\'s a good start. Tell me two critically endangered plants of India, you don\'t get it—Madhuca insignis and Syzygium travancoricum.',
                ],
                [
                    'speaker' => 'Host',
                    'start' => 41.0,
                    'end' => 48.0,
                    'text' => 'Swiggy is not able to deliver to you tomatoes. Now is it a technology problem or is it a nature problem?',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 49.0,
                    'end' => 52.0,
                    'text' => 'If I answer that question, this conversation comes to an end.',
                ],
                [
                    'speaker' => 'Host',
                    'start' => 52.0,
                    'end' => 65.0,
                    'text' => 'You are underestimating us. This could be the nice ending for this beautiful conversation. How nice, appreciate it sir, well done.',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 74.0,
                    'end' => 88.0,
                    'text' => 'I have an incident in Madhya Pradesh. An Adivasi gentleman came to him and said no, no, no, he went to another tree. The banyan is a deity, the neem is a deity, your forest is full of deities.',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 89.0,
                    'end' => 99.0,
                    'text' => 'However, now we think that these things are superstition. The modern generation does not have those things.',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 100.0,
                    'end' => 110.0,
                    'text' => 'In the last few decades, nature seems to have got a beating. Many decades it went for people to accept that climate change is a reality. Am I talking too much? You stop me.',
                ],
                [
                    'speaker' => 'Host',
                    'start' => 110.0,
                    'end' => 118.0,
                    'text' => 'No, no, that\'s normal. That is the signature laughter of the Forest Man of India.',
                ],
                [
                    'speaker' => 'Jadav Payeng',
                    'start' => 122.0,
                    'end' => 125.0,
                    'text' => 'Some people say, on my birthday I will do this.',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 125.0,
                    'end' => 129.0,
                    'text' => 'No, we have to go beyond the birthdays and wedding anniversaries. We have to do more than just that.',
                ],
                [
                    'speaker' => 'Host',
                    'start' => 130.0,
                    'end' => 135.0,
                    'text' => 'Now, a question I would have for both of you: Is planting more trees the only solution?',
                ],
            ],
            'summary' => json_encode([
                'general' => "## Executive Summary\nA panel discussion featuring **Dr. Ananth** and **Jadav Payeng** (Forest Man of India) exploring climate change awareness, digital discipline, biodiversity conservation, and moving beyond symbolic plantation drives.\n\n### Key Discussion Points\n- **Endangered Biodiversity:** Highlighting critically endangered species like *Madhuca insignis* and *Syzygium travancoricum* overlooked by urban populations.\n- **Traditional Ecological Wisdom:** Indigenous Adivasi reverence for trees (treating banyan and neem as living deities) versus modern disregard.\n- **Systemic Climate Action:** The necessity of moving past token gestures (planting trees only on birthdays or anniversaries) to tackle supply chain and environmental vulnerabilities.\n\n### Notable Quotes\n- *\"We have to go beyond birthdays and wedding anniversaries. We have to do more than just that.\"*",
                'sales' => "## Strategic & Outreach Perspectives\nOpportunities to align enterprise ESG and corporate sustainability mandates with authentic grassroots reforesting and biodiversity protection frameworks.\n\n### Core Audience Insights\n- Modern consumer expectations disconnect everyday technology disruptions (e.g., agricultural supply delays) from ecological decline.\n- High resonance around engaging local communities and integrating traditional ecological practices into verified corporate green programs.\n\n### Recommended Next Steps\n- **@Host:** Package interview segments highlighting native endangered flora into short-form educational assets.\n- **@Production Team:** Distribute biodiversity preservation clips across institutional sustainability networks.",
                'engineering' => "## Technical & Systems Review\nAnalyzing supply chain fragility through the lens of environmental factors rather than purely operational and software infrastructure bugs.\n\n### Systems Analysis\n- **Consumer Platform Dependency:** Evaluating consumer platforms (e.g., rapid food delivery) when facing agricultural shocks from climatic disruptions.\n- **Data Categorization:** Archiving regional biodiversity records and indigenous forest management models as open-source environmental telemetry.\n\n### Actionable Tasks\n- **@Dr. Ananth:** Compile botanical fact sheet on *Madhuca insignis* and endangered native ecosystems.\n- **@Jadav Payeng:** Outline holistic conservation guidelines beyond standalone plantation drives.",
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Compile documentation on endangered species (Madhuca insignis, Syzygium travancoricum)',
                    'assignee' => 'Dr. Ananth',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Synthesize multi-tier ecological framework moving beyond birthday tree plantings',
                    'assignee' => 'Jadav Payeng',
                    'completed' => false,
                ],
                [
                    'id' => 3,
                    'task' => 'Publish podcast excerpt on traditional conservation and climate reality',
                    'assignee' => 'Host',
                    'completed' => true,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting1->id,
            'timestamp_seconds' => 26,
            'label' => 'Biodiversity Check',
            'note' => 'Identified critically endangered species Madhuca insignis and Syzygium travancoricum',
        ]);
        Highlight::create([
            'meeting_id' => $meeting1->id,
            'timestamp_seconds' => 74,
            'label' => 'Traditional Knowledge',
            'note' => 'Adivasi forest wisdom: treating banyan and neem trees as sacred deities',
        ]);
        Highlight::create([
            'meeting_id' => $meeting1->id,
            'timestamp_seconds' => 125,
            'label' => 'Action Call',
            'note' => 'Urging climate action beyond ceremonial birthdays and wedding anniversaries',
        ]);

        // Meeting 2: Executive Design Review
        $meeting2 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Career Advice, Reading & The Positive Sum Mindset',
            'video_url' => '/videos/demo2.mp4',
            'duration_seconds' => 480,
            'transcript' => [
                [
                    'speaker' => 'Lex Fridman',
                    'start' => 3.0,
                    'end' => 28.0,
                    'text' => 'You give a lot of people hope and millions look up to you. If we think about young people in high school or college, if they want to try to do something big in this world and have a big positive impact, what advice would you give them about their career or life in general?',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 29.0,
                    'end' => 55.0,
                    'text' => 'Try to be useful. Do things that are useful to your fellow human beings, to the world. It\'s very hard to be useful, very hard. Are you contributing more than you consume? Can you try to have a positive net contribution to society? I think that\'s the thing to aim for.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 55.0,
                    'end' => 87.0,
                    'text' => 'Not to try to be a leader for the sake of being a leader. A lot of times, the people you want as leaders are the people who don\'t want to be leaders. If you can live a useful life, that is a good life—a life worth having lived.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 88.0,
                    'end' => 100.0,
                    'text' => 'Like I said, I would encourage people to use the mental tools of physics and apply them broadly in life. They are the best tools.',
                ],
                [
                    'speaker' => 'Lex Fridman',
                    'start' => 100.0,
                    'end' => 142.0,
                    'text' => 'When you think about education and self-education, what do you recommend? There\'s university, self-study, or joining a company with people that do the thing you\'re passionate about. Which trajectory do you suggest to become useful?',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 143.0,
                    'end' => 180.0,
                    'text' => 'I\'d encourage people to read a lot of books. Basically, try to ingest as much information as you can and develop a good general knowledge so you at least have a rough lay of the knowledge landscape. Try to learn a little bit about a lot of things.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 181.0,
                    'end' => 200.0,
                    'text' => 'Because you might not know what you\'re really interested in if you aren\'t doing a peripheral exploration of the knowledge landscape. Talk to people from different walks of life, industries, and professions. Just try to learn as much as possible, and then search for meaning.',
                ],
                [
                    'speaker' => 'Lex Fridman',
                    'start' => 201.0,
                    'end' => 205.0,
                    'text' => 'Isn\'t the whole thing a search for meaning?',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 205.0,
                    'end' => 242.0,
                    'text' => 'Yeah, what\'s the meaning of life and all? But generally, I would encourage people to read broadly. And then try to find something where there\'s an overlap of your talents and what you\'re interested in.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 243.0,
                    'end' => 275.0,
                    'text' => 'People may have skill at a particular thing, but they don\'t like doing it. You want to find a good combination of the things you\'re inherently good at, but you also like doing. Reading is a super fast shortcut to figure out where you are good at it, you like doing it, and it will have positive impact.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 276.0,
                    'end' => 306.0,
                    'text' => 'As a kid, I read through the encyclopedia. Encyclopedias were digestible 40 years ago, so maybe read through the condensed version of the Encyclopedia Britannica. If you read a few paragraphs and you\'re not interested, just jump to the next subject.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 307.0,
                    'end' => 348.0,
                    'text' => 'I put a lot of stock and have a lot of respect for someone who puts in an honest day\'s work to do useful things. And just generally to have not a zero-sum mindset, but have more of a grow-the-pie mindset.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 349.0,
                    'end' => 388.0,
                    'text' => 'When we see people taking an attitude of doing things that seem morally questionable, it\'s often because they have an axiomatic zero-sum mindset. If you have a zero-sum mindset, the only way to get ahead is by taking things from others.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 389.0,
                    'end' => 430.0,
                    'text' => 'In reality, the economic pie is not fixed—it has grown dramatically over time. It\'s much better to work on adding to the economic pie, creating more than you consume.',
                ],
                [
                    'speaker' => 'Lex Fridman',
                    'start' => 430.0,
                    'end' => 474.0,
                    'text' => 'One of the reasons Rogan inspires me is he celebrates others a lot. When you celebrate and promote others and their ideas, it actually grows that pie. Resources become less scarce. If you get everybody excited about AI, physics, and mathematics, there will be more funding and everybody wins.',
                ],
                [
                    'speaker' => 'Elon Musk',
                    'start' => 474.0,
                    'end' => 478.0,
                    'text' => 'Yeah, that applies broadly. Exactly.',
                ],
            ],
            'summary' => json_encode([
                'general' => "## Executive Summary\nA conversation discussing career advice, finding purpose, and the importance of having a net positive impact on society. The dialogue emphasizes that true utility comes from creating more than you consume and broadly exploring knowledge before committing to a specific path.\n\n### Key Discussion Points\n- **Utility Over Leadership:** The primary career goal should be usefulness rather than seeking leadership positions for their own sake.\n- **Knowledge Exploration:** Broad reading, such as skimming an encyclopedia, acts as a shortcut to discovering the intersection of one's innate talents and genuine interests.\n- **First Principles Thinking:** The mental tools derived from physics are presented as the most effective framework for solving general life and career problems.\n- **The Positive-Sum Mindset:** Rejecting zero-sum thinking (where success requires taking from others) in favor of 'growing the pie' through creation and collaboration.\n\n### Notable Quotes\n- *\"Try to find something where there's an overlap of your talents and what you're interested in.\"*\n- *\"It's much better to work on adding to the economic pie, creating more than you consume.\"*",
                'sales' => "## Strategic & Outreach Perspectives\nThe dialogue provides a strong philosophical foundation for ecosystem growth over hyper-competitive extraction, which aligns perfectly with modern partnership and platform sales strategies.\n\n### Core Audience Insights\n- Prospects and partners respond highly to 'grow the pie' mentalities. Celebrating and promoting partners reduces friction and expands overall market opportunities.\n- Zero-sum negotiating tactics often lead to morally questionable decisions that damage long-term brand reputation and client trust.\n\n### Recommended Next Steps\n- **@Marketing Team:** Extract the 'grow the pie' segment for a LinkedIn thought-leadership campaign targeted at B2B partnership directors.\n- **@Sales Leadership:** Audit current incentive structures to ensure they reward overall ecosystem growth rather than purely cannibalistic market share acquisition.",
                'engineering' => "## Technical & Systems Review\nApplying first-principles physics methodologies to career development, learning, and system design.\n\n### Systems Analysis\n- **Information Ingestion:** The approach of 'skimming the encyclopedia' mirrors breadth-first search algorithms in machine learning—establishing a broad baseline of the 'knowledge landscape' before diving deep into specialized nodes.\n- **Net Positive Architecture:** Framing career and engineering impact as a metric of \"creating more than you consume.\" Systems should be designed to generate net positive utility rather than acting as rent-seeking middlemen.\n\n### Actionable Tasks\n- **@Engineering Leads:** Integrate physics-based first principles questioning into the architectural review process for Q3.\n- **@HR:** Develop a technical reading list that spans multiple disparate disciplines to encourage cross-pollination of ideas.",
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Extract the "grow the pie" segment for a LinkedIn thought-leadership campaign',
                    'assignee' => 'Marketing Team',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Integrate physics-based first principles questioning into architectural reviews',
                    'assignee' => 'Engineering Leads',
                    'completed' => true,
                ],
                [
                    'id' => 3,
                    'task' => 'Audit current incentive structures to ensure they reward overall ecosystem growth',
                    'assignee' => 'Sales Leadership',
                    'completed' => false,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 29,
            'label' => 'Core Philosophy',
            'note' => 'Strive to have a net positive contribution to society',
        ]);
        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 88,
            'label' => 'First Principles',
            'note' => 'Applying the mental tools of physics broadly in life',
        ]);
        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 243,
            'label' => 'Ikigai / Intersection',
            'note' => 'Finding the overlap between innate skill and passion',
        ]);
        Highlight::create([
            'meeting_id' => $meeting2->id,
            'timestamp_seconds' => 389,
            'label' => 'Positive-Sum Mindset',
            'note' => 'Rejecting zero-sum competition in favor of growing the pie',
        ]);

        // Meeting 3: Enterprise Q3 Sales Pipeline Call
        $meeting3 = Meeting::create([
            'user_id' => $user->id,
            'title' => 'Monthly Departmental Managers Sync: Relocation & Flexible Working',
            'video_url' => '/videos/demo3.mp4',
            'duration_seconds' => 365,
            'transcript' => [
                [
                    'speaker' => 'Narrator',
                    'start' => 12.0,
                    'end' => 25.0,
                    'text' => 'Marcus White is the Managing Director of Quartz Power Group, an energy company. Every month, the departmental managers meet to discuss high-level issues in the company. Marcus is leading this month\'s meeting.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 26.0,
                    'end' => 34.0,
                    'text' => 'Okay, well good morning everyone. Thank you very much for being here on time. We\'ve got a lot to do today, so let\'s get started.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 35.0,
                    'end' => 63.0,
                    'text' => 'Now, everyone\'s here apart from John in facilities, but we have apologies from him. Has everyone got a copy of the agenda? Good. Okay, can I draw your attention to item four, where it says presentation of online survey results? The presentation is going to be given by Miss Patricia Reyes, who is a representative of the research company we contracted.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 64.0,
                    'end' => 90.0,
                    'text' => 'Now, Miss Reyes will arrive at 11.30, so I plan to break at about 11.15 to give her time to set up. It may also mean that we need to interrupt the first few agenda items, but we\'ll come back to those.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 91.0,
                    'end' => 112.0,
                    'text' => 'Okay, so item one is relocation and plans for flexible working. Now, as you know, Paul and his team have been working on plans to extend flexible working hours across the company. So, Paul, perhaps I can begin by asking you to fill us in on your progress.',
                ],
                [
                    'speaker' => 'Paul',
                    'start' => 113.0,
                    'end' => 118.0,
                    'text' => 'Sure. Thanks, Marcus. Well, as Marcus said...',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 119.0,
                    'end' => 147.0,
                    'text' => 'Thank you very much indeed, Paul. I mean, on behalf of everyone, I\'d like to say thank you to you and to your team for all the hard work that you\'ve put in so far on this project. I really appreciate it.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 148.0,
                    'end' => 157.0,
                    'text' => 'Maya, I\'m aware that flexible working hours has a particular impact on some of your team. Do you have any thoughts on that?',
                ],
                [
                    'speaker' => 'Maya',
                    'start' => 158.0,
                    'end' => 173.0,
                    'text' => 'Well, from my point of view, what Paul is proposing sounds fine. I am a bit concerned about working with a system of core hours and then flexible hours, but I think we all need time to read through Paul\'s proposal in more detail.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 174.0,
                    'end' => 197.0,
                    'text' => 'Okay, well, that sounds reasonable. And what do other people think? Do you agree with Maya that we need to look through the proposal in more detail first? Yes? Okay, right, well, I\'m sure we can return to it at next month\'s meeting.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 198.0,
                    'end' => 216.0,
                    'text' => 'Right, so the up-and-coming board meeting. Now, we don\'t need to spend too much time on this, but there are one or two issues which we need to be aware of. Lydia, can you warn me if we go beyond 10 minutes on this one?',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 217.0,
                    'end' => 251.0,
                    'text' => 'So, those two people will be replacing the two members of the board who are leaving us. And that\'s everything, I think, on Item 2. So, right, moving on to Item 3 and the cost breakdown for the relocation. Now, Maya, thank you very much for emailing the current figures to all of us.',
                ],
                [
                    'speaker' => 'Maya',
                    'start' => 252.0,
                    'end' => 256.0,
                    'text' => 'Not really. Nothing\'s changed since it was sent out, as far as I know.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 257.0,
                    'end' => 263.0,
                    'text' => 'Are there any questions arising from Maya\'s report? Matt, you look as if you might have a question.',
                ],
                [
                    'speaker' => 'Matt',
                    'start' => 264.0,
                    'end' => 286.0,
                    'text' => 'Yes, I suppose I do. Well, it\'s more of a comment, really. The fact is, IT will have the highest costs attached to this relocation in terms of moving equipment, and I\'ll also need to bring quite a few contract people to help. But the figures in here don\'t seem to be the same as the estimates I gave you.',
                ],
                [
                    'speaker' => 'Maya',
                    'start' => 287.0,
                    'end' => 298.0,
                    'text' => 'Well, I have allocated some of the costs to the budgets of other departments. That seemed the fairest way to do it. Otherwise, as you say, IT is responsible for a large part of the relocation costs.',
                ],
                [
                    'speaker' => 'Matt',
                    'start' => 299.0,
                    'end' => 304.0,
                    'text' => 'So do you mean any IT costs related to, say, marketing, will be covered by the marketing budget?',
                ],
                [
                    'speaker' => 'Maya',
                    'start' => 305.0,
                    'end' => 313.0,
                    'text' => 'Yes, in a way. Look, this might help clarify it. You see, I\'ve broken the IT department up into segments.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 314.0,
                    'end' => 324.0,
                    'text' => 'Oh, I\'m sorry. Can we continue this discussion after lunch? The lady from the research company is waiting to join us and to present her results.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 325.0,
                    'end' => 345.0,
                    'text' => 'But before we take a break, I just want to summarize where we are so far. Now, my understanding is that Maya has tried to allocate the relocation costs across departments as far as is possible, but that Matt feels that IT is still taking too many costs. Is that right?',
                ],
                [
                    'speaker' => 'Matt',
                    'start' => 346.0,
                    'end' => 347.0,
                    'text' => 'That\'s about right.',
                ],
                [
                    'speaker' => 'Marcus',
                    'start' => 348.0,
                    'end' => 360.0,
                    'text' => 'Good. OK, let\'s take a break now. And can we all be back here promptly at 11.30 to continue where we left off? Lydia, could you bring Miss Reyes up, please?',
                ],
                [
                    'speaker' => 'Lydia',
                    'start' => 361.0,
                    'end' => 364.0,
                    'text' => 'I can do that.',
                ],
            ],
            'summary' => json_encode([
                'general' => "## Executive Summary\nQuartz Power Group held its monthly departmental managers sync led by Managing Director Marcus White. The agenda covered flexible working plans, board member replacements, and relocation cost breakdowns.\n\n### Key Discussion Points\n- **Flexible Working:** Paul presented initial plans for flexible working. Maya raised concerns regarding the overlap of core and flexible hours, resulting in a decision to defer the vote until next month.\n- **Relocation Costs:** A dispute arose between Matt (IT) and Maya over relocation cost allocations. Maya distributed IT costs across specific department budgets (e.g., Marketing), which Matt disputed against his original estimates.\n- **Guest Presentation:** The meeting paused to allow Miss Patricia Reyes from a contracted research company to present online survey results.\n\n### Action Items\n- **All Managers:** Review Paul's flexible working proposal in detail prior to next month's meeting.\n- **Maya & Matt:** Revisit and clarify IT cost segmentations and cross-department allocations after the survey presentation.\n- **Lydia:** Escort Miss Reyes to the meeting room.",
                'sales' => "## Deal Overview & Prospect Sentiment\nWhile primarily an internal operations meeting, the dispute over cost allocation highlights the necessity of strictly attributing operational and technological costs directly to the departments (such as Sales or Marketing) utilizing them.\n\n### Commercial Impact\n- Properly attributing IT hardware and relocation costs to the Marketing budget prevents the IT department from artificially inflating overhead, ensuring clearer ROI metrics on marketing spend.\n\n### Next Steps & Commitments\n- **@Marketing Leads:** Review Maya's updated segment allocations to ensure the marketing budget can absorb the designated IT relocation costs without impacting quarterly ad spend.",
                'engineering' => "## Technical Architecture & Systems Impact\nThe relocation of the IT department presents significant logistical challenges, primarily concerning equipment transfer and the necessity of external contractor support.\n\n### Implementation Blockers & Risks\n- **Resource Allocation:** Matt indicated that the physical relocation will require substantial overtime from existing IT staff as well as hiring temporary contractors.\n- **Budget Discrepancies:** Current financial estimates provided by Maya do not align with IT's internal projections, potentially threatening the contractor hiring process if the IT budget is artificially constrained.\n\n### Engineering Tasks\n- **@Matt:** Re-tabulate contractor hours and equipment moving estimates to present a counter-argument to Maya's segmentation model.",
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Review Paul\'s flexible working proposal in detail for next month\'s vote',
                    'assignee' => 'All Managers',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Clarify IT cost segmentations and cross-department allocations',
                    'assignee' => 'Maya',
                    'completed' => false,
                ],
                [
                    'id' => 3,
                    'task' => 'Escort Miss Reyes to the meeting room for the survey presentation',
                    'assignee' => 'Lydia',
                    'completed' => true,
                ],
            ],
        ]);

        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 91,
            'label' => 'Agenda Item',
            'note' => 'Initiated discussion on relocation and plans for flexible working hours',
        ]);
        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 158,
            'label' => 'Process Concern',
            'note' => 'Maya raised concerns about balancing core hours with flexible hours',
        ]);
        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 264,
            'label' => 'Budget Dispute',
            'note' => 'Matt challenged Maya\'s relocation cost allocation regarding IT contractor expenses',
        ]);
        Highlight::create([
            'meeting_id' => $meeting3->id,
            'timestamp_seconds' => 325,
            'label' => 'Status Summary',
            'note' => 'Marcus summarized the budget dispute before pausing for the guest presentation',
        ]);
    }
}
