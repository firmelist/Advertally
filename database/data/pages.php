<?php

$block = fn (string $type, array $data) => ['type' => $type, 'data' => $data];

return [
    /*
    | HOMEPAGE — narrative: discovery changed → search is conversational → AI is a discovery layer →
    | channels interconnect → isolated services fail → one growth system → six engines →
    | technology powers it → measure it → get your Growth Score.
    */
    [
        'slug' => 'home', 'title' => 'Home',
        'blocks' => [
            $block('hero', [
                'eyebrow_tag' => 'AI-native', 'eyebrow' => 'Growth & Revenue Partner',
                'headline' => 'Make Your Business *Impossible to Ignore.*',
                'subheadline' => 'Advertally builds AI-ready growth systems that help businesses get discovered, trusted and chosen across search, AI platforms and every digital touchpoint that matters.',
                'primary_label' => 'Get Your Growth Score', 'primary_url' => '/growth-score',
                'secondary_label' => 'Talk to an Expert', 'secondary_url' => '/contact',
                'visual' => 'growth-os',
                'trust_points' => [],
            ]),
            $block('trust', [
                'points' => [
                    ['icon' => 'sparkles', 'title' => 'AI-Powered Strategies', 'text' => 'Built for how buyers discover businesses now — across search and AI.'],
                    ['icon' => 'database', 'title' => 'Data-Backed Execution', 'text' => 'Every decision grounded in your numbers, not channel habits.'],
                    ['icon' => 'trending-up', 'title' => 'Measurable Business Outcomes', 'text' => 'Reported in qualified leads, pipeline and revenue.'],
                ],
                'show_logos' => true, 'logos_title' => 'Trusted by growth-focused teams',
            ]),
            $block('journey', [
                'eyebrow' => '01 — The journey has changed',
                'headline' => "Your Customers Don't Search Like They Used To.",
                'intro' => 'Search is becoming conversational. AI is becoming a discovery layer. And buyers move between channels long before they speak to sales.',
                'old_label' => 'The traditional journey', 'old_path' => ['Google', 'Website', 'Contact'],
                'old_text' => 'A short, linear path: rank on Google, send traffic to the website, wait for the form.',
                'new_label' => 'The modern journey', 'new_path' => ['Search', 'AI', 'Social', 'Reviews', 'YouTube', 'Communities', 'Website', 'Conversation', 'CRM', 'Purchase'],
                'new_text' => 'Buyers research across search, AI assistants, social proof and communities — and quietly skip any business that is not present, credible and consistent along the way.',
                'message' => 'Your marketing strategy needs to work across the entire journey.',
            ]),
            $block('story', [
                'eyebrow' => 'Why connected growth',
                'headline' => 'Marketing is no longer a collection of channels.',
                'intro' => 'It is a connected growth system. Here is why isolated services stop working — and what replaces them.',
                'items' => [
                    ['title' => 'The way customers discover businesses has changed.', 'text' => 'Research starts in many places, and the shortlist forms before the first conversation.'],
                    ['title' => 'Search is becoming conversational.', 'text' => 'Buyers ask full questions and expect direct answers, not ten blue links.'],
                    ['title' => 'AI is becoming a discovery layer.', 'text' => 'Assistants summarise, compare and recommend — often before anyone visits a website.'],
                    ['title' => 'Digital channels are interconnected.', 'text' => 'Search, social, reviews and content reinforce or undermine each other.'],
                    ['title' => 'Isolated services leave gaps.', 'text' => 'An SEO vendor, an ads vendor and a web vendor rarely add up to growth.'],
                    ['title' => 'Advertally builds one connected growth system.', 'text' => 'Six engines, one strategy, one team and one measure of success: revenue.'],
                ],
            ]),
            $block('growth_os', [
                'eyebrow' => '07 — Advertally Growth OS',
                'headline' => 'One Growth System. Six Engines.',
                'intro' => 'Each engine is valuable on its own. Connected, they compound — visibility feeds trust, trust lifts conversion, and data shows where to invest next.',
            ]),
            $block('signal', [
                'eyebrow' => 'The Advertally Signal™',
                'headline' => 'From Search to AI to Revenue.',
                'intro' => 'We follow customer intent through every touchpoint — and engineer each one to move the right buyer closer to a decision.',
                'steps' => ['Customer Intent', 'Search', 'AI', 'Authority', 'Demand', 'Conversion', 'Revenue'],
                'points' => [
                    ['title' => 'Be found before the click', 'text' => 'Visible in search results and AI answers where research begins.'],
                    ['title' => 'Turn attention into pipeline', 'text' => 'Demand and conversion engineered around qualified buyers.'],
                    ['title' => 'Connect marketing to revenue', 'text' => 'Every engine measured against the outcomes leadership cares about.'],
                ],
            ]),
            $block('technology', [
                'eyebrow' => '08 — Growth Technology',
                'headline' => 'Technology is the engine behind modern growth.',
                'intro' => 'We build the digital infrastructure that connects marketing, automation, customer data and revenue — high-converting websites, CRM and API integration, AI integration, custom growth tools and dashboards.',
                'message' => "We don't build technology for technology's sake. We build technology that helps businesses grow.",
                'cta_label' => 'Explore Growth Technology',
            ]),
            $block('score', [
                'eyebrow' => '09 — Measure the result',
                'headline' => 'Know your score. Fix what matters first.',
                'intro' => 'The Advertally Growth Score™ benchmarks your business from 0–100 across six dimensions and shows exactly where growth is leaking — in about four minutes.',
                'cta_label' => 'Get Your Growth Score',
            ]),
            $block('case_studies', [
                'eyebrow' => 'Growth Stories',
                'headline' => 'Diagnosis first. Then the system. Then the numbers.',
            ]),
            $block('insights', [
                'lab_eyebrow' => 'Advertally AI Search Lab',
                'lab_headline' => 'Research on how AI discovers businesses.',
                'lab_intro' => 'Frameworks, experiments and studies on AI search and B2B discovery — with methods and sources published.',
                'eyebrow' => 'Insights',
                'headline' => 'Thinking for leaders who own growth.',
            ]),
            $block('testimonials', ['eyebrow' => 'In their words', 'headline' => 'What leaders say about working with us.']),
            $block('talent', [
                'eyebrow' => 'Advertally Technology & Talent',
                'headline' => 'Need More Technical Capacity?',
                'intro' => 'Extend your team with experienced technology and digital growth specialists — individually or as a dedicated team.',
                'options' => [
                    ['label' => 'Hire Developers', 'url' => '/technology-talent/hire-developers'],
                    ['label' => 'Hire Designers', 'url' => '/technology-talent/hire-designers'],
                    ['label' => 'Hire SEO Specialists', 'url' => '/technology-talent/hire-seo-specialists'],
                    ['label' => 'Hire Digital Marketers', 'url' => '/technology-talent/hire-digital-marketers'],
                    ['label' => 'Dedicated Teams', 'url' => '/technology-talent/dedicated-teams'],
                ],
                'cta_label' => 'Explore Technology & Talent', 'cta_url' => '/technology-talent',
            ]),
            $block('cta', [
                'title' => 'Get Your Growth Score.',
                'text' => 'Four minutes. Six engines. A clear view of where your growth system is strong — and the moves that will matter most.',
            ]),
        ],
        'seo' => ['title' => 'Advertally — AI-Native Growth & Revenue Partner', 'description' => 'Advertally builds AI-ready growth systems that help ambitious businesses get discovered, trusted and chosen across search, AI platforms and every digital touchpoint that drives revenue.'],
    ],

    [
        'slug' => 'about', 'title' => 'About Advertally',
        'blocks' => [
            $block('hero', [
                'eyebrow_tag' => 'About', 'eyebrow' => 'AI-native growth engineering',
                'headline' => 'Marketing Changed. *So Did We.*',
                'subheadline' => 'Advertally began in digital marketing execution. As discovery moved to AI and channels became interconnected, we rebuilt the company around one idea: growth is a system, not a set of services.',
                'primary_label' => 'Get Your Growth Score', 'primary_url' => '/growth-score', 'secondary_label' => 'How we work', 'secondary_url' => '/approach',
                'visual' => 'evolution',
            ]),
            $block('comparison', [
                'eyebrow' => 'Our evolution', 'headline' => 'From channels to a connected growth system.',
                'left_title' => 'Traditional marketing', 'left_items' => ['SEO', 'PPC', 'Social', 'Website'],
                'left_text' => 'Separate services, separate reports, separate vendors — and nobody accountable for revenue.',
                'right_title' => 'Advertally today', 'right_items' => ['AI Search', 'Demand', 'Authority', 'Conversion', 'Automation', 'Intelligence'],
                'right_text' => 'Six engines designed to work together, powered by Growth Technology and measured on pipeline and revenue.',
                'conclusion' => 'We call it AI-Native Growth Engineering.',
            ]),
            $block('features', [
                'eyebrow' => 'What we believe', 'headline' => 'Principles that shape every engagement.',
                'items' => [
                    ['icon' => 'target', 'title' => 'Revenue is the measure', 'text' => 'We report on qualified leads, pipeline and revenue — not activity.'],
                    ['icon' => 'compass', 'title' => 'Diagnose before prescribing', 'text' => 'We understand the business before recommending a single tactic.'],
                    ['icon' => 'shield-check', 'title' => 'No fake claims', 'text' => 'No invented statistics, testimonials or guarantees. Ever.'],
                    ['icon' => 'sparkles', 'title' => 'AI with human judgement', 'text' => 'AI removes repetitive work; people make the decisions that matter.'],
                    ['icon' => 'network', 'title' => 'Systems over channels', 'text' => 'Each engine is designed to make the others more effective.'],
                    ['icon' => 'lock', 'title' => 'You own everything', 'text' => 'Your accounts, data, code and content — always transferable.'],
                ],
            ]),
            $block('equation', [
                'eyebrow' => 'The Advertally equation',
                'terms' => ['Search', 'AI', 'Demand', 'Authority', 'Conversion', 'Automation', 'Data', 'Technology'],
                'result' => 'Revenue Growth',
                'text' => 'Advertally is not simply another agency managing SEO, ads or social media. We build the connected growth system that helps businesses get found, become trusted, generate demand, convert customers and measure revenue in the AI era.',
            ]),
            $block('cta', ['title' => "Let's find out where your growth is leaking.", 'text' => 'Start with the Growth Score, or talk to a strategist about your next growth engine.']),
        ],
        'seo' => ['title' => 'About Advertally — Marketing Changed. So Did We.', 'description' => 'Advertally evolved from digital marketing execution to AI-native growth engineering: one connected system across AI search, demand, authority, conversion, automation and intelligence.'],
    ],

    [
        'slug' => 'approach', 'title' => 'Our Approach',
        'blocks' => [
            $block('hero', [
                'eyebrow_tag' => 'Approach', 'eyebrow' => 'How Advertally works',
                'headline' => 'How *Advertally* Works.',
                'subheadline' => 'A disciplined, repeatable process that connects strategy, execution and technology around the outcomes that matter: qualified leads, pipeline and revenue.',
                'primary_label' => 'Get Your Growth Score', 'primary_url' => '/growth-score', 'secondary_label' => 'Talk to an Expert', 'secondary_url' => '/contact',
                'visual' => 'cycle',
            ]),
            $block('steps', [
                'eyebrow' => 'Six steps', 'headline' => 'Diagnose. Discover. Build. Activate. Measure. Improve.',
                'steps' => [
                    ['icon' => 'compass', 'title' => 'Diagnose', 'text' => 'Understand the business: market, buyers, economics, competitors and current growth system.'],
                    ['icon' => 'search', 'title' => 'Discover', 'text' => 'Identify the visibility, demand and conversion opportunities with the highest revenue potential.'],
                    ['icon' => 'layers', 'title' => 'Build', 'text' => 'Create the growth system — positioning, content, journeys, technology and measurement.'],
                    ['icon' => 'rocket', 'title' => 'Activate', 'text' => 'Launch campaigns, content, technology and automation with one accountable team.'],
                    ['icon' => 'bar-chart', 'title' => 'Measure', 'text' => 'Track qualified leads, pipeline and revenue — reported in language leadership uses.'],
                    ['icon' => 'refresh', 'title' => 'Improve', 'text' => 'Continuously optimise every engine based on evidence, not habit.'],
                ],
            ]),
            $block('features', [
                'eyebrow' => 'Working with us', 'headline' => 'What you can expect.', 'background' => 'canvas', 'columns' => 3,
                'items' => [
                    ['icon' => 'users', 'title' => 'Senior attention', 'text' => 'Strategists who understand business stay involved beyond the pitch.'],
                    ['icon' => 'file-text', 'title' => 'Clear priorities', 'text' => 'A roadmap that says what to do first — and what not to do.'],
                    ['icon' => 'chart', 'title' => 'Honest reporting', 'text' => 'Progress reported transparently, including what did not work.'],
                ],
            ]),
            $block('faq', [
                'title' => 'Questions about working with Advertally',
                'items' => [
                    ['question' => 'Do we have to use all six engines?', 'answer' => 'No. Most clients start with the engines that address their biggest constraint. The system view simply ensures each investment supports the others.'],
                    ['question' => 'How do engagements typically start?', 'answer' => 'With a diagnosis — usually two to four weeks — that produces a prioritised growth roadmap. Many clients then move to an ongoing retainer for the engines they need.'],
                    ['question' => 'Do you work with in-house teams?', 'answer' => 'Yes. We often provide strategy, specialist depth and technology while your team owns brand, sales and day-to-day execution.'],
                ],
            ]),
            $block('cta', ['title' => 'Start with a diagnosis.', 'text' => 'The Growth Score is a quick first look. A strategist can take it further in a focused conversation.']),
        ],
        'seo' => ['title' => 'How Advertally Works — Our Growth Approach', 'description' => 'Diagnose, discover, build, activate, measure and improve: the Advertally process for connecting strategy, execution and technology to pipeline and revenue.'],
    ],

    [
        'slug' => 'careers', 'title' => 'Careers',
        'blocks' => [
            $block('hero', [
                'eyebrow_tag' => 'Careers', 'eyebrow' => 'Build the next generation of growth',
                'headline' => 'Do the best work of your career on *growth that matters.*',
                'subheadline' => 'We are building a team of strategists, specialists and engineers who care about outcomes, think in systems and use AI to do better work — not less thinking.',
                'primary_label' => 'Introduce yourself', 'primary_url' => '/contact', 'secondary_label' => null,
                'visual' => 'constellation',
            ]),
            $block('features', [
                'eyebrow' => 'How we work', 'headline' => 'What it is like at Advertally.',
                'items' => [
                    ['icon' => 'target', 'title' => 'Outcomes over output', 'text' => 'We measure success by client growth, not hours or deliverables.'],
                    ['icon' => 'sparkles', 'title' => 'AI-native by default', 'text' => 'We use AI to remove repetitive work and raise the quality bar.'],
                    ['icon' => 'book', 'title' => 'Always learning', 'text' => 'Research, experiments and knowledge-sharing are part of the job.'],
                ],
            ]),
            $block('rich_text', [
                'heading' => 'Open roles',
                'body' => '<p>We publish open roles here as they become available. If you are exceptional at SEO and AI search, paid media, content, CRO, marketing automation, analytics or Laravel development, we would still like to hear from you.</p><p>Use the contact form and choose "Something else" as your challenge, or email your portfolio and a short note on the work you are proudest of.</p>',
            ]),
        ],
        'seo' => ['title' => 'Careers at Advertally', 'description' => 'Join Advertally — strategists, specialists and engineers building AI-native growth systems for ambitious businesses.'],
    ],

    ['slug' => 'contact', 'title' => 'Contact', 'blocks' => [], 'seo' => ['title' => "Contact Advertally — Let's Build Your Next Growth Engine"]],

    [
        'slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'template' => 'legal',
        'body' => <<<'HTML'
<p><strong>This policy is a starting template and must be reviewed by your legal adviser before publication.</strong></p>
<h2>Who we are</h2><p>Advertally ("we", "us") operates this website. For any privacy question, contact us using the details on our contact page.</p>
<h2>What we collect</h2><ul><li>Information you submit in forms: name, company, business email, phone, website and details of your request.</li><li>Answers you provide to the Growth Score and details submitted for the AI Visibility Audit.</li><li>Technical and attribution data: pages visited, referring website, campaign parameters (UTM), device type and IP address.</li><li>Analytics data, only if you accept analytics cookies.</li></ul>
<h2>How we use it</h2><p>To respond to your request, prepare reports you ask for, improve our website and services, and understand which marketing channels work. We do not sell your personal data.</p>
<h2>Legal basis and retention</h2><p>We process data based on your consent and our legitimate interest in responding to business enquiries. We retain enquiry data for as long as needed for these purposes and applicable legal obligations.</p>
<h2>Sharing</h2><p>We use trusted service providers (hosting, email, CRM and analytics) who process data on our behalf under appropriate agreements. AI providers, if used to generate report summaries, receive only the minimum data necessary.</p>
<h2>Your rights</h2><p>You may request access to, correction of or deletion of your personal data, and withdraw consent at any time, by contacting us.</p>
HTML,
    ],
    [
        'slug' => 'terms', 'title' => 'Terms of Use', 'template' => 'legal',
        'body' => <<<'HTML'
<p><strong>This is a starting template and must be reviewed by your legal adviser before publication.</strong></p>
<h2>Use of this website</h2><p>Content on this website is provided for general information. It does not constitute professional advice for your specific situation.</p>
<h2>Free tools</h2><p>The Growth Score and AI Visibility Audit provide indicative assessments based on the information you provide and publicly available signals. They do not guarantee search rankings, AI citations or commercial results.</p>
<h2>Intellectual property</h2><p>All content, frameworks and materials on this site are owned by Advertally unless stated otherwise and may not be reproduced without permission.</p>
<h2>Limitation of liability</h2><p>To the extent permitted by law, Advertally is not liable for losses arising from use of this website or reliance on its content.</p>
HTML,
    ],
    [
        'slug' => 'cookie-policy', 'title' => 'Cookie Policy', 'template' => 'legal',
        'body' => <<<'HTML'
<p><strong>This is a starting template and must be reviewed by your legal adviser before publication.</strong></p>
<h2>Essential cookies</h2><p>Required for the website to work: session, security (CSRF) and your cookie preference. They also store first-touch campaign attribution so we can understand how you found us.</p>
<h2>Analytics and marketing cookies</h2><p>Loaded only if you choose "Accept analytics". These may include Google Analytics / Tag Manager, Microsoft Clarity, LinkedIn Insight Tag and Meta Pixel, depending on our configuration.</p>
<h2>Changing your choice</h2><p>Clear the <code>adv_consent</code> cookie in your browser to be asked again.</p>
HTML,
    ],
];
