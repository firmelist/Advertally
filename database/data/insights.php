<?php

/*
| Launch content. Educational only — no invented statistics, clients or results.
| Authored by the Advertally team entities (replace with named experts in the admin when ready).
*/

return [
    'categories' => [
        ['slug' => 'ai-search', 'name' => 'AI Search', 'description' => 'How AI assistants and generative search are changing how buyers discover businesses.'],
        ['slug' => 'seo', 'name' => 'SEO', 'description' => 'Technical, content and authority SEO for commercial outcomes.'],
        ['slug' => 'b2b-growth', 'name' => 'B2B Growth', 'description' => 'Strategy for leaders responsible for revenue growth.'],
        ['slug' => 'demand', 'name' => 'Demand', 'description' => 'Paid media, ABM and lead generation measured on pipeline.'],
        ['slug' => 'authority', 'name' => 'Authority', 'description' => 'Content, thought leadership and reputation.'],
        ['slug' => 'conversion', 'name' => 'Conversion', 'description' => 'Websites, CRO and journeys that turn attention into conversations.'],
        ['slug' => 'automation', 'name' => 'Automation', 'description' => 'AI agents, CRM and marketing automation.'],
        ['slug' => 'analytics', 'name' => 'Analytics', 'description' => 'Measurement, attribution and revenue intelligence.'],
    ],

    'authors' => [
        ['slug' => 'advertally-growth-team', 'type' => 'team', 'name' => 'Advertally Growth Team', 'job_title' => 'Strategy & delivery',
            'bio' => 'The strategists, specialists and engineers who design and run Advertally growth systems across AI search, demand, authority, conversion, automation and intelligence.',
            'expertise' => ['B2B growth strategy', 'AI search', 'Demand generation', 'Conversion optimisation', 'Marketing automation', 'Analytics']],
        ['slug' => 'advertally-research', 'type' => 'team', 'name' => 'Advertally AI Search Lab', 'job_title' => 'Research',
            'bio' => 'The Advertally AI Search Lab studies how AI assistants and search engines discover, evaluate and recommend businesses, and publishes its methods and sources.',
            'expertise' => ['Generative engine optimisation', 'AI visibility measurement', 'Entity SEO', 'Search behaviour']],
    ],

    'posts' => [
        [
            'slug' => 'ai-search-b2b-buyers', 'category' => 'ai-search', 'author' => 'advertally-growth-team', 'type' => 'article', 'featured' => true, 'days_ago' => 6,
            'title' => 'AI search is changing how B2B buyers build shortlists',
            'excerpt' => 'Buyers increasingly ask AI assistants to explain problems and suggest providers before visiting a single website. Here is what that means for how you get found — and chosen.',
            'tags' => ['AI search', 'GEO', 'B2B buying'],
            'services' => ['geo', 'ai-visibility', 'seo'], 'industries' => ['technology', 'saas', 'consulting'],
            'content' => <<<'HTML'
<p>For most of the last two decades, B2B discovery followed a familiar path: a search, a list of links, a few website visits and eventually a contact form. That path still exists, but it is no longer the only one — and for many buyers it is no longer the first.</p>
<h2>From links to answers</h2>
<p>AI assistants such as ChatGPT, Gemini, Perplexity, Claude and Copilot, along with Google's AI Overviews and AI Mode, now answer many research questions directly. A buyer can ask "what should I look for in a cloud migration partner?" or "which firms specialise in fintech compliance in India?" and receive a synthesised answer, often naming specific companies.</p>
<p>That changes two things. First, some research that used to generate website visits now happens without a click. Second, the shortlist itself can be shaped before your website is ever considered.</p>
<h2>What AI systems appear to reward</h2>
<p>No one outside the companies building these systems knows exactly how they choose what to cite, and anyone who claims to guarantee inclusion is overselling. But the publicly documented guidance and observable behaviour point to consistent themes:</p>
<ul>
<li><strong>Accessibility.</strong> Content must be crawlable and readable without complex JavaScript.</li>
<li><strong>Clarity.</strong> Pages that state plainly who a business serves, what it offers and where it operates are easier to summarise accurately.</li>
<li><strong>Corroboration.</strong> Consistent facts across your website, profiles, directories and third-party mentions make you a safer recommendation.</li>
<li><strong>Expertise.</strong> Specific, experience-based answers to real questions are more useful to cite than generic marketing copy.</li>
</ul>
<h2>What to do about it</h2>
<p>The good news is that the work that improves AI visibility overlaps heavily with work that already drives growth: clear positioning, strong service pages, genuine proof and expert content. The difference is discipline. Treat SEO, generative engine optimisation and answer engine optimisation as one programme, and start measuring how AI assistants describe your category today.</p>
<p>A practical first step is to write down the twenty or thirty questions your best customers asked before they bought — then see how AI assistants answer them, and whether your business appears. That baseline tells you where to focus.</p>
<blockquote>Search is changing. The businesses that stay visible will be the ones machines can understand and buyers can trust.</blockquote>
HTML,
        ],
        [
            'slug' => 'seo-geo-aeo-one-discipline', 'category' => 'ai-search', 'author' => 'advertally-research', 'type' => 'framework', 'featured' => false, 'days_ago' => 13,
            'title' => 'SEO, GEO and AEO: one discipline, not three retainers',
            'excerpt' => 'New acronyms invite new budgets. In practice, search engine, generative engine and answer engine optimisation share the same foundations — and work best as one programme.',
            'tags' => ['SEO', 'GEO', 'AEO', 'Framework'],
            'services' => ['seo', 'geo', 'aeo', 'entity-seo'], 'industries' => [],
            'content' => <<<'HTML'
<p>Every shift in search produces new terminology. Generative engine optimisation (GEO) and answer engine optimisation (AEO) describe real changes in how people find information. The risk is treating them as separate disciplines with separate owners, tools and budgets.</p>
<h2>Shared foundations</h2>
<p>All three depend on the same core capabilities:</p>
<ol>
<li><strong>Technical access</strong> — pages that can be crawled, rendered and indexed.</li>
<li><strong>Entity clarity</strong> — consistent, structured information about your organisation, people and services.</li>
<li><strong>Topical depth</strong> — content that covers the problems you solve thoroughly, from basic questions to specialist detail.</li>
<li><strong>Authority</strong> — third-party signals that your business is credible: mentions, links, reviews and expert recognition.</li>
</ol>
<h2>Where the emphasis differs</h2>
<p><strong>SEO</strong> focuses on ranking pages for queries and earning clicks. <strong>AEO</strong> emphasises structuring content as direct, quotable answers to specific questions. <strong>GEO</strong> emphasises being included and accurately represented in synthesised AI answers, which often draw on several sources at once.</p>
<p>These are differences of emphasis, not of foundation. A page that ranks well, answers a question directly and is corroborated elsewhere is well placed for all three.</p>
<h2>The Advertally approach</h2>
<p>We run AI Search as a single programme with one roadmap: fix access, clarify entities, build answer-ready topical depth, earn authority and monitor visibility in both traditional search and AI assistants. One team, one set of priorities and one measurement framework — tied to qualified pipeline rather than rankings alone.</p>
HTML,
        ],
        [
            'slug' => 'cost-per-lead-wrong-kpi', 'category' => 'demand', 'author' => 'advertally-growth-team', 'type' => 'article', 'featured' => false, 'days_ago' => 20,
            'title' => 'Why cost per lead is the wrong headline KPI',
            'excerpt' => 'Optimising for cheap leads quietly trains your campaigns to find people who will never buy. Here is a better way to judge demand generation.',
            'tags' => ['Demand generation', 'Paid media', 'Pipeline'],
            'services' => ['google-ads', 'linkedin-ads', 'lead-generation', 'attribution'], 'industries' => ['saas', 'financial-services'],
            'content' => <<<'HTML'
<p>Cost per lead is easy to measure, easy to report and easy to improve. That is exactly the problem. When campaigns are optimised to reduce it, platforms learn to find the people most likely to fill in a form — not the people most likely to become customers.</p>
<h2>How cheap leads become expensive</h2>
<p>Lower cost per lead often comes from broader targeting, softer offers and easier forms. Each of those can increase volume while reducing quality. Sales teams spend more time on unqualified conversations, follow-up slows down and genuinely qualified buyers wait longer for a response.</p>
<h2>Measure further down the funnel</h2>
<p>A more useful hierarchy of metrics for demand generation looks like this:</p>
<ul>
<li><strong>Cost per qualified lead</strong> — using criteria agreed with sales.</li>
<li><strong>Cost per opportunity</strong> — leads that become real sales conversations.</li>
<li><strong>Pipeline created</strong> — the value of those opportunities.</li>
<li><strong>Customer acquisition cost and payback</strong> — what it actually costs to win revenue.</li>
</ul>
<h2>Close the loop</h2>
<p>The practical step is to send qualification and opportunity data from your CRM back to ad platforms as offline conversions. Bidding algorithms then learn from outcomes that matter. It requires clean source tracking on every lead and a shared definition of "qualified" — both of which pay for themselves quickly.</p>
<blockquote>Don't buy clicks. Build demand — and measure it the way your finance team would.</blockquote>
HTML,
        ],
        [
            'slug' => 'speed-to-lead', 'category' => 'conversion', 'author' => 'advertally-growth-team', 'type' => 'guide', 'featured' => false, 'days_ago' => 27,
            'title' => 'Speed to lead: the cheapest conversion improvement most firms ignore',
            'excerpt' => 'You have already paid to earn the enquiry. How quickly and consistently you respond determines how much of that investment turns into pipeline.',
            'tags' => ['Conversion', 'Automation', 'Sales process'],
            'services' => ['lead-automation', 'crm-automation', 'whatsapp-automation'], 'industries' => ['professional-services', 'recruitment'],
            'content' => <<<'HTML'
<p>Most conversion work focuses on the website: headlines, forms, proof and page speed. Those matter. But one of the largest leaks in many B2B funnels happens after the form is submitted — in the time it takes someone to respond.</p>
<h2>Why response time matters</h2>
<p>A buyer who has just submitted an enquiry is at peak attention. They are often comparing several providers at the same time. The first credible, helpful response sets the frame for the conversation; a slow one suggests how the relationship might feel later.</p>
<h2>Common causes of slow response</h2>
<ul>
<li>Enquiries landing in a shared inbox no one owns.</li>
<li>Manual copying of details into a CRM before anyone acts.</li>
<li>No routing rules for region, service or deal size.</li>
<li>No alerts outside office hours, even for high-value enquiries.</li>
</ul>
<h2>A simple automation pattern</h2>
<ol>
<li><strong>Capture</strong> every form, chat and call into the CRM automatically, with source data attached.</li>
<li><strong>Acknowledge</strong> instantly by email or WhatsApp with a useful next step.</li>
<li><strong>Route</strong> to the right owner using clear rules, with a backup owner.</li>
<li><strong>Alert</strong> the owner in the channel they actually watch.</li>
<li><strong>Escalate</strong> if the lead is not contacted within an agreed window.</li>
</ol>
<p>None of this replaces human judgement. It simply ensures that every enquiry reaches a person quickly, with context — so your team spends its time on the conversation rather than the logistics.</p>
HTML,
        ],
        [
            'slug' => 'minimum-viable-measurement-stack', 'category' => 'analytics', 'author' => 'advertally-growth-team', 'type' => 'guide', 'featured' => false, 'days_ago' => 34,
            'title' => 'The minimum viable measurement stack for B2B growth',
            'excerpt' => 'You do not need an enterprise data warehouse to connect marketing to revenue. You need five things done properly.',
            'tags' => ['Analytics', 'Attribution', 'GA4'],
            'services' => ['marketing-analytics', 'attribution', 'dashboards'], 'industries' => ['b2b-services'],
            'content' => <<<'HTML'
<p>Leadership teams want a simple answer: which marketing investments create revenue? Many businesses cannot answer it — not because they lack tools, but because the basics are incomplete.</p>
<h2>1. Clean analytics</h2>
<p>GA4, ideally via Google Tag Manager, with consent respected and key actions tracked as conversions. Not every page view — the actions that indicate genuine intent.</p>
<h2>2. Consistent campaign tagging</h2>
<p>A documented UTM convention used for every campaign, email and partner link. Without it, attribution degrades into "direct" and guesswork.</p>
<h2>3. Source data on every lead</h2>
<p>First-touch and last-touch source, landing page and campaign stored on the lead record in your CRM automatically — never typed in by hand.</p>
<h2>4. A shared pipeline definition</h2>
<p>Agreed stages from lead to customer, with clear criteria, so marketing and sales report on the same numbers.</p>
<h2>5. One dashboard</h2>
<p>A single view from traffic to revenue, refreshed automatically and reviewed monthly by leadership: traffic, leads, qualified leads, opportunities, customers, revenue, acquisition cost and return.</p>
<p>With these five in place, more sophisticated attribution becomes possible. Without them, it is not worth attempting.</p>
HTML,
        ],
    ],

    'research' => [
        [
            'slug' => 'ai-visibility-framework', 'category' => 'frameworks', 'author' => 'advertally-research', 'featured' => true, 'days_ago' => 4,
            'title' => 'The Advertally AI Visibility Framework',
            'summary' => 'How we assess whether a business can be found, understood, trusted and recommended by AI assistants — the six dimensions behind the Advertally AI Visibility Score, and the on-site signals we measure for each.',
            'key_findings' => [
                'AI visibility depends first on access: content that AI crawlers cannot fetch or render cannot be used.',
                'Entity clarity — consistent, structured facts about an organisation — reduces the risk of AI systems describing a business inaccurately.',
                'Authority signals that are verifiable by third parties matter more than claims a business makes about itself.',
                'On-site readiness is necessary but not sufficient; live AI answer monitoring is required to understand actual visibility.',
            ],
            'services' => ['geo', 'ai-visibility', 'entity-seo'],
            'body' => <<<'HTML'
<h2>Why a framework</h2>
<p>"AI visibility" is easy to talk about and hard to measure. We needed a consistent way to assess businesses before and during engagements, and to explain priorities in plain language to leadership teams. This framework is the result. It is deliberately conservative: it measures conditions we can observe, and it is explicit about what it cannot tell you.</p>
<h2>The six dimensions</h2>
<h3>1. AI Visibility (access and answer-readiness)</h3>
<p>Can AI crawlers access the site? Is meaningful content present in the HTML rather than only rendered by JavaScript? Is content structured around questions with clear, quotable answers? Is there an llms.txt file summarising the site?</p>
<h3>2. Search Visibility</h3>
<p>The technical and on-page foundations search engines rely on: HTTPS, titles, descriptions, headings, canonical tags, sitemaps, mobile readiness and response time. Many AI answers draw on content that already performs well in search.</p>
<h3>3. Authority</h3>
<p>Visible proof and expertise: about pages, case studies, testimonials and reviews, content hubs and links to official profiles.</p>
<h3>4. Entity Strength</h3>
<p>How clearly machines can identify the organisation: Organization schema, sameAs connections to external profiles, consistent naming and Open Graph identity.</p>
<h3>5. Content Coverage</h3>
<p>Depth and structure across services, industries and buyer questions, including FAQ content and internal linking.</p>
<h3>6. Conversion Readiness</h3>
<p>Visibility only matters if qualified visitors can act. We check for clear calls to action, forms, direct contact routes, analytics and trust signals at decision points.</p>
<h2>What the framework does not measure</h2>
<p>The automated assessment does not query AI assistants and does not claim to measure live rankings or citations. Those depend on systems that change frequently and on off-site factors. In engagements, we pair this framework with prompt-based monitoring across assistants, reviewed over time.</p>
HTML,
            'methodology' => '<p>Each dimension is scored 0–100 from weighted checks marked pass (full weight), warning (half weight) or fail (zero). The overall score is a weighted average of the six dimensions, with AI access weighted most heavily. Checks are run against the public homepage, robots.txt, llms.txt and sitemap.xml at the time of the audit.</p>',
            'sources' => [
                ['title' => 'Google Search Central — AI features and your website', 'url' => 'https://developers.google.com/search/docs/appearance/ai-features'],
                ['title' => 'The llms.txt proposal', 'url' => 'https://llmstxt.org/'],
                ['title' => 'Schema.org — Organization', 'url' => 'https://schema.org/Organization'],
                ['title' => 'OpenAI — Overview of OpenAI crawlers', 'url' => 'https://platform.openai.com/docs/bots'],
            ],
        ],
        [
            'slug' => 'ai-crawler-access-checklist', 'category' => 'ai-search-research', 'author' => 'advertally-research', 'featured' => false, 'days_ago' => 16,
            'title' => 'AI crawler access: a robots.txt checklist for B2B websites',
            'summary' => 'Many websites block AI crawlers unintentionally through inherited robots.txt rules or security settings. This checklist explains the main AI user agents, what they are used for and the decisions to make deliberately.',
            'key_findings' => [
                'Different AI user agents serve different purposes — training, search indexing and user-initiated browsing — and can be controlled separately.',
                'Blocking a training crawler does not necessarily block the same company\'s search or user-browsing agents, and vice versa.',
                'Security and bot-protection settings can block AI agents even when robots.txt allows them.',
                'Access decisions should be made deliberately by the business, balancing visibility against content-use preferences.',
            ],
            'services' => ['geo', 'seo', 'ai-visibility'],
            'data' => [
                'columns' => ['User agent', 'Operator', 'Primary purpose (per public documentation)'],
                'rows' => [
                    ['GPTBot', 'OpenAI', 'Crawling content that may be used to train generative AI models'],
                    ['OAI-SearchBot', 'OpenAI', 'Surfacing websites in ChatGPT search features'],
                    ['ChatGPT-User', 'OpenAI', 'Visiting pages when a ChatGPT user asks it to'],
                    ['ClaudeBot', 'Anthropic', 'Crawling public web content for model development'],
                    ['PerplexityBot', 'Perplexity', 'Indexing websites to surface them in Perplexity answers'],
                    ['Google-Extended', 'Google', 'Control token for use of content in Gemini models; does not affect Google Search inclusion'],
                ],
            ],
            'body' => <<<'HTML'
<h2>Why this matters</h2>
<p>If an AI system cannot access your content, it cannot use it to describe or recommend you. We regularly see robots.txt files and firewall rules that block AI agents without anyone in the business having made that decision.</p>
<h2>The checklist</h2>
<ol>
<li><strong>Review robots.txt.</strong> Look for rules naming AI user agents, and for broad <code>Disallow: /</code> rules under <code>User-agent: *</code>.</li>
<li><strong>Decide per purpose.</strong> You may choose to allow search and user-initiated agents while restricting training crawlers — or allow all. Make it a deliberate decision.</li>
<li><strong>Check your firewall and bot protection.</strong> CDN and security tools can block or challenge AI agents regardless of robots.txt.</li>
<li><strong>Ensure content renders without JavaScript.</strong> Key messaging should be present in the HTML response.</li>
<li><strong>Publish an llms.txt.</strong> A short, plain-language guide to who you are and your most important pages.</li>
<li><strong>Re-check after changes.</strong> Platform migrations and security updates frequently reset these settings.</li>
</ol>
<p>Operators update their crawlers and documentation over time. Always confirm current user-agent names and behaviour in each operator's official documentation before changing rules.</p>
HTML,
            'methodology' => '<p>User-agent purposes summarised from operators\' public documentation at the time of writing. This is guidance, not legal advice; content-use decisions should reflect your organisation\'s policies.</p>',
            'sources' => [
                ['title' => 'OpenAI — Overview of OpenAI crawlers', 'url' => 'https://platform.openai.com/docs/bots'],
                ['title' => 'Google — Overview of Google crawlers and fetchers', 'url' => 'https://developers.google.com/search/docs/crawling-indexing/overview-google-crawlers'],
                ['title' => 'Google Search Central — Introduction to robots.txt', 'url' => 'https://developers.google.com/search/docs/crawling-indexing/robots/intro'],
            ],
        ],
    ],
];
