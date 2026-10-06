<?php

/*
| Option points are 0–100. A dimension score is the average of its answered questions.
| Recommendations fire when the dimension score is below "below" (lowest threshold first).
*/

return [
    'growth_score' => [
        [
            'key' => 'ai_visibility', 'name' => 'AI Visibility', 'weight' => 2,
            'description' => 'How likely AI assistants are to understand, trust and mention your business.',
            'questions' => [
                ['question' => 'When buyers ask ChatGPT, Gemini or Perplexity about your category, how often does your business appear?', 'help' => 'Try asking an assistant for providers like you.',
                    'options' => [['label' => 'We have never checked', 'points' => 0], ['label' => 'Rarely or never', 'points' => 20], ['label' => 'Sometimes, inconsistently', 'points' => 60], ['label' => 'Regularly and accurately', 'points' => 100]]],
                ['question' => 'How clearly does your website explain who you serve, what you offer and why you are credible?',
                    'options' => [['label' => 'It is vague or outdated', 'points' => 10], ['label' => 'Partly clear', 'points' => 45], ['label' => 'Clear on key pages', 'points' => 75], ['label' => 'Clear everywhere, with structured data', 'points' => 100]]],
            ],
            'recommendations' => [
                ['below' => 40, 'title' => 'Establish your AI visibility baseline', 'description' => 'Define 20–30 buyer prompts and record how AI assistants describe your category and brand today. You cannot improve what you have not measured.', 'impact' => 'high'],
                ['below' => 70, 'title' => 'Clarify your entity for machines', 'description' => 'Add Organization and Service schema, consistent company facts across profiles and an llms.txt summary so AI systems can describe you accurately.', 'impact' => 'medium'],
            ],
        ],
        [
            'key' => 'search_visibility', 'name' => 'Search Visibility', 'weight' => 2,
            'description' => 'How visible you are for the searches that bring in qualified buyers.',
            'questions' => [
                ['question' => 'How much of your qualified pipeline comes from organic search today?',
                    'options' => [['label' => 'None that we know of', 'points' => 0], ['label' => 'A small share', 'points' => 35], ['label' => 'A meaningful share', 'points' => 70], ['label' => 'A major, reliable source', 'points' => 100]]],
                ['question' => 'Do you have dedicated, optimised pages for each core service and buyer problem?',
                    'options' => [['label' => 'No — mostly a single services page', 'points' => 10], ['label' => 'Some services have pages', 'points' => 40], ['label' => 'Most services do', 'points' => 75], ['label' => 'Yes, plus supporting content clusters', 'points' => 100]]],
            ],
            'recommendations' => [
                ['below' => 40, 'title' => 'Build a page for every core service', 'description' => 'Create dedicated, intent-led pages for each service and buyer problem. This is usually the fastest route to qualified organic demand.', 'impact' => 'high'],
                ['below' => 70, 'title' => 'Fix technical foundations and topic depth', 'description' => 'Resolve technical issues and add supporting content around your highest-value services to compete for commercial terms.', 'impact' => 'medium'],
            ],
        ],
        [
            'key' => 'authority', 'name' => 'Authority', 'weight' => 2,
            'description' => 'The expertise, proof and third-party signals that make buyers trust you.',
            'questions' => [
                ['question' => 'How much verifiable proof do you publish — case studies, testimonials, reviews?',
                    'options' => [['label' => 'Almost none', 'points' => 0], ['label' => 'A few, not prominent', 'points' => 35], ['label' => 'Good proof on key pages', 'points' => 75], ['label' => 'Extensive, current and specific', 'points' => 100]]],
                ['question' => 'Do your leaders publish expertise consistently (articles, LinkedIn, talks, media)?',
                    'options' => [['label' => 'No', 'points' => 0], ['label' => 'Occasionally', 'points' => 35], ['label' => 'Regularly', 'points' => 75], ['label' => 'Yes — recognised voices in our market', 'points' => 100]]],
            ],
            'recommendations' => [
                ['below' => 40, 'title' => 'Publish proof buyers can verify', 'description' => 'Document two or three client outcomes as structured case studies and gather genuine reviews on the platforms your buyers check.', 'impact' => 'high'],
                ['below' => 70, 'title' => 'Turn leaders into visible experts', 'description' => 'A monthly thought leadership rhythm from one or two leaders compounds trust with both buyers and AI systems.', 'impact' => 'medium'],
            ],
        ],
        [
            'key' => 'demand', 'name' => 'Demand', 'weight' => 2,
            'description' => 'How consistently you create and capture qualified demand.',
            'questions' => [
                ['question' => 'How predictable is your flow of qualified leads month to month?',
                    'options' => [['label' => 'Unpredictable — mostly referrals', 'points' => 10], ['label' => 'Somewhat predictable', 'points' => 45], ['label' => 'Predictable from a few channels', 'points' => 75], ['label' => 'Predictable across several channels', 'points' => 100]]],
                ['question' => 'How do you judge paid media performance?',
                    'options' => [['label' => 'We do not run paid media', 'points' => 20], ['label' => 'Clicks and cost per click', 'points' => 25], ['label' => 'Cost per lead', 'points' => 55], ['label' => 'Qualified pipeline and revenue', 'points' => 100]]],
            ],
            'recommendations' => [
                ['below' => 40, 'title' => 'Build a repeatable demand engine', 'description' => 'Add at least one intent-capture channel (search) and one demand-creation channel (LinkedIn or YouTube) with clear qualification criteria.', 'impact' => 'high'],
                ['below' => 70, 'title' => 'Optimise media for pipeline, not leads', 'description' => 'Feed qualified outcomes from your CRM back into ad platforms so bidding learns from real value.', 'impact' => 'medium'],
            ],
        ],
        [
            'key' => 'conversion', 'name' => 'Conversion', 'weight' => 2,
            'description' => 'How effectively your website and journeys turn visitors into conversations.',
            'questions' => [
                ['question' => 'How quickly does your team respond to a new enquiry?',
                    'options' => [['label' => 'More than a day', 'points' => 10], ['label' => 'Same day', 'points' => 50], ['label' => 'Within an hour', 'points' => 80], ['label' => 'Within minutes, automatically routed', 'points' => 100]]],
                ['question' => 'Does your website give each type of buyer a clear, low-risk next step?',
                    'options' => [['label' => 'Just a contact form', 'points' => 15], ['label' => 'A couple of options', 'points' => 45], ['label' => 'Clear paths for most buyers', 'points' => 75], ['label' => 'Tailored paths, tools and proof', 'points' => 100]]],
            ],
            'recommendations' => [
                ['below' => 40, 'title' => 'Respond to every lead within the hour', 'description' => 'Automate acknowledgement and routing. Speed to lead is one of the cheapest conversion improvements available.', 'impact' => 'high'],
                ['below' => 70, 'title' => 'Add next steps for every stage of readiness', 'description' => 'Offer resources, tools and consultations so visitors who are not ready to talk still move forward.', 'impact' => 'medium'],
            ],
        ],
        [
            'key' => 'intelligence', 'name' => 'Intelligence', 'weight' => 2,
            'description' => 'How well you connect marketing activity to pipeline and revenue.',
            'questions' => [
                ['question' => 'Can you see which marketing channels generate revenue — not just leads?',
                    'options' => [['label' => 'No', 'points' => 0], ['label' => 'Roughly, with manual effort', 'points' => 35], ['label' => 'Mostly, from CRM reports', 'points' => 70], ['label' => 'Yes, in a live dashboard', 'points' => 100]]],
                ['question' => 'Is source and campaign data captured on every lead in your CRM?',
                    'options' => [['label' => 'No CRM / not captured', 'points' => 0], ['label' => 'Sometimes', 'points' => 35], ['label' => 'Usually', 'points' => 70], ['label' => 'Always, automatically', 'points' => 100]]],
            ],
            'recommendations' => [
                ['below' => 40, 'title' => 'Capture source data on every lead', 'description' => 'Store UTM, referrer and landing page data automatically in your CRM. It is the foundation of every growth decision.', 'impact' => 'high'],
                ['below' => 70, 'title' => 'Build a revenue dashboard', 'description' => 'Connect analytics, ad platforms and CRM into one view from traffic to revenue, reviewed monthly by leadership.', 'impact' => 'medium'],
            ],
        ],
    ],

    'ai_visibility' => [
        ['key' => 'ai_visibility', 'name' => 'AI Visibility', 'weight' => 3, 'description' => 'Whether AI crawlers can access and read your content, and whether it is structured to be quoted.'],
        ['key' => 'search_visibility', 'name' => 'Search Visibility', 'weight' => 2, 'description' => 'Technical and on-page foundations search engines rely on.'],
        ['key' => 'authority', 'name' => 'Authority', 'weight' => 2, 'description' => 'Visible proof, expertise and third-party profiles that build trust.'],
        ['key' => 'entity_strength', 'name' => 'Entity Strength', 'weight' => 2, 'description' => 'How clearly machines can identify who you are and connect you to other sources.'],
        ['key' => 'content_coverage', 'name' => 'Content Coverage', 'weight' => 2, 'description' => 'Depth and structure of content across services, industries and questions.'],
        ['key' => 'conversion_readiness', 'name' => 'Conversion Readiness', 'weight' => 1, 'description' => 'Whether qualified visitors have a clear, trusted way to take the next step.'],
    ],
];
