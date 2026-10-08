<?php

/*
 * Starter internships. No brands, projects or statistics are seeded — those must be real and are added in the admin
 * (Careers → Brands & clients / Client projects / Statistics). Stipend, openings and dates are left for the team to set.
 */

$journey = [
    ['title' => 'Apply', 'text' => 'Send your details, résumé and a short note on why this internship.'],
    ['title' => 'Conversation', 'text' => 'Shortlisted candidates have a short call with the team, and may get a small practical task.'],
    ['title' => 'Onboarding', 'text' => 'Meet your mentor, get access to tools and learn how we plan, ship and measure work.'],
    ['title' => 'Learn by doing', 'text' => 'Contribute to live work with regular reviews and feedback.'],
    ['title' => 'Showcase', 'text' => 'Finish with work you can explain in interviews and a review of what you achieved.'],
];

$benefits = [
    ['title' => 'Mentorship', 'text' => 'Regular reviews with experienced practitioners, not just task lists.'],
    ['title' => 'Real work', 'text' => 'Contribute to live projects, subject to confidentiality and project needs.'],
    ['title' => 'Certificate', 'text' => 'An internship certificate on successful completion.'],
    ['title' => 'Recommendation', 'text' => 'A letter of recommendation for strong performers.'],
    ['title' => 'Portfolio', 'text' => 'Work you can talk about — where client confidentiality allows.'],
    ['title' => 'AI-native tools', 'text' => 'Learn how a modern team uses AI to research, build and measure.'],
];

return [
    [
        'title' => 'Digital Marketing Internship',
        'slug' => 'digital-marketing-internship',
        'department' => 'Digital Marketing',
        'headline' => 'Learn digital marketing on *real growth work.*',
        'summary' => 'Join the Advertally team and learn SEO, AI search, paid media, content and analytics by contributing to live campaigns, with mentorship from people who do this every day.',
        'location' => 'Remote', 'work_mode' => 'remote', 'duration' => '3 months', 'hours' => 'Full-time, Monday–Friday', 'start_date' => 'Rolling',
        'why' => [
            ['title' => 'Work, not busywork', 'text' => 'You contribute to real campaigns and audits rather than practice exercises.'],
            ['title' => 'Modern marketing', 'text' => 'Learn how AI search, GEO and automation are changing how businesses get found.'],
            ['title' => 'Measured outcomes', 'text' => 'See how work is connected to analytics, leads and revenue, not vanity metrics.'],
        ],
        'team_intro' => 'From the first week you work alongside strategists and specialists: you join stand-ups, see how briefs become campaigns, and get feedback on everything you ship.',
        'team_points' => ['Join team stand-ups and planning', 'Get a mentor and regular reviews', 'Use the same tools as the team', 'Present your work and get feedback'],
        'work_on' => [
            ['title' => 'Search & AI visibility', 'items' => ['Keyword and topic research', 'Technical SEO checks', 'AI search visibility reviews', 'On-page optimisation']],
            ['title' => 'Content & social', 'items' => ['Content briefs and outlines', 'Social media calendars', 'Blog and landing page drafts']],
            ['title' => 'Paid media & analytics', 'items' => ['Campaign research', 'Ad copy variations', 'Reporting dashboards', 'Competitor analysis']],
        ],
        'toolkit' => ['Google Search Console', 'Google Analytics 4', 'Google Ads', 'Meta Ads Manager', 'Looker Studio', 'ChatGPT / Claude / Gemini', 'Canva', 'Google Sheets'],
        'skills' => ['SEO fundamentals', 'AI search (GEO/AEO)', 'Content strategy', 'Paid media basics', 'Analytics & reporting', 'Communication'],
        'learn' => ['How search engines and AI assistants find and cite businesses', 'How to research keywords, audiences and competitors', 'How to plan and measure a campaign', 'How to write for search and for people'],
        'who_should_apply' => ['Students or recent graduates interested in marketing', 'Career switchers building practical experience', 'Curious people who like testing ideas with data'],
        'requirements' => ['Good written English', 'Comfortable with spreadsheets', 'A laptop and reliable internet', 'Available for the full duration'],
        'exposure' => ['SEO audits', 'AI search visibility', 'Content strategy', 'Social media planning', 'Campaign research', 'Competitor analysis', 'Reporting'],
        'career_growth' => 'The internship builds a foundation for roles such as SEO executive, performance marketing executive, content strategist or marketing analyst.',
        'career_paths' => ['SEO Executive', 'Performance Marketing Executive', 'Content Strategist', 'Social Media Executive', 'Marketing Analyst'],
        'faqs' => [
            ['question' => 'Do I need previous experience?', 'answer' => 'No. We look for curiosity, clear writing and willingness to learn. Any projects or coursework you have done help.'],
            ['question' => 'Is the internship remote?', 'answer' => 'Yes, this internship is remote. You will need a laptop and a reliable internet connection.'],
            ['question' => 'Will I get a certificate?', 'answer' => 'Yes, a certificate is issued on successful completion of the internship.'],
            ['question' => 'Will I work with real clients?', 'answer' => 'You may contribute to client work depending on your role, project allocation and confidentiality requirements.'],
        ],
    ],
    [
        'title' => 'React Developer Internship',
        'slug' => 'react-developer-internship',
        'department' => 'Development',
        'headline' => 'Build real interfaces with *React.*',
        'summary' => 'Work with the Advertally development team on websites, dashboards and growth tools. Learn modern React, component design and how front-end work affects conversion and performance.',
        'location' => 'Remote', 'work_mode' => 'remote', 'duration' => '3–6 months', 'hours' => 'Full-time, Monday–Friday', 'start_date' => 'Rolling',
        'why' => [
            ['title' => 'Production code', 'text' => 'Your pull requests are reviewed and, when ready, shipped.'],
            ['title' => 'Business context', 'text' => 'Understand why speed, accessibility and conversion matter, not just how to code them.'],
            ['title' => 'Code reviews', 'text' => 'Regular, constructive reviews from experienced developers.'],
        ],
        'team_intro' => 'You join the development team from day one: you pick up scoped tickets, pair with a developer and learn our workflow from branch to deploy.',
        'team_points' => ['Set up the codebase in week one', 'Pair-program with a developer', 'Ship reviewed pull requests', 'Join sprint planning and demos'],
        'work_on' => [
            ['title' => 'Interfaces', 'items' => ['Reusable React components', 'Responsive layouts', 'Accessible forms']],
            ['title' => 'Data & dashboards', 'items' => ['API integration', 'Charts and reporting views', 'State management']],
            ['title' => 'Quality', 'items' => ['Performance improvements', 'Testing basics', 'Code reviews']],
        ],
        'toolkit' => ['React', 'TypeScript', 'Tailwind CSS', 'Vite', 'Git & GitHub', 'REST APIs', 'Figma', 'Chrome DevTools'],
        'skills' => ['React & hooks', 'TypeScript', 'Component design', 'API integration', 'Git workflow', 'Web performance'],
        'learn' => ['How to structure a React codebase', 'How to turn a design into accessible components', 'How to work with APIs and loading states', 'How code reviews and deployments work in a team'],
        'who_should_apply' => ['Students or graduates who have built something with React', 'Self-taught developers with a small portfolio', 'People who enjoy turning designs into working interfaces'],
        'requirements' => ['Working knowledge of HTML, CSS and JavaScript', 'Some React experience (projects count)', 'Basic Git', 'A laptop and reliable internet'],
        'exposure' => ['Website builds', 'Landing pages', 'Dashboards', 'Component libraries', 'Performance optimisation', 'API integrations'],
        'career_growth' => 'The internship prepares you for front-end or full-stack developer roles, with real code you can discuss in interviews.',
        'career_paths' => ['Front-end Developer', 'React Developer', 'Full-stack Developer', 'UI Engineer'],
        'faqs' => [
            ['question' => 'How much React do I need to know?', 'answer' => 'You should be able to build a small app with components, props, state and hooks. We will help you with the rest.'],
            ['question' => 'Should I include a portfolio?', 'answer' => 'Yes, please add a GitHub or portfolio link. Small, finished projects are better than large unfinished ones.'],
            ['question' => 'Is the internship remote?', 'answer' => 'Yes, this internship is remote.'],
        ],
    ],
    [
        'title' => 'AI / ML Internship',
        'slug' => 'ai-ml-internship',
        'department' => 'AI & Automation',
        'headline' => 'Apply AI to *real business problems.*',
        'summary' => 'Explore how large language models, automation and data are used to solve real marketing and operations problems — from AI assistants to analysis workflows — with guidance from the team.',
        'location' => 'Remote', 'work_mode' => 'remote', 'duration' => '3–6 months', 'hours' => 'Full-time, Monday–Friday', 'start_date' => 'Rolling',
        'why' => [
            ['title' => 'Applied AI', 'text' => 'Focus on useful applications, not only models and notebooks.'],
            ['title' => 'End-to-end', 'text' => 'Go from problem definition to prototype to evaluation.'],
            ['title' => 'Responsible use', 'text' => 'Learn to evaluate output quality, cost and privacy trade-offs.'],
        ],
        'team_intro' => 'You join the AI & automation team, scope small experiments with a mentor and present what worked — and what did not.',
        'team_points' => ['Scope an experiment in week one', 'Weekly reviews with a mentor', 'Document and present findings', 'Ship prototypes the team can use'],
        'work_on' => [
            ['title' => 'LLM applications', 'items' => ['Prompt design and evaluation', 'Retrieval-augmented generation (RAG)', 'AI assistants and agents']],
            ['title' => 'Automation', 'items' => ['Workflow automation', 'Data extraction and cleaning', 'API integrations']],
            ['title' => 'Analysis', 'items' => ['Exploratory data analysis', 'Classification and clustering', 'Evaluation reports']],
        ],
        'toolkit' => ['Python', 'Jupyter', 'pandas', 'OpenAI / Anthropic / Gemini APIs', 'LangChain or similar', 'SQL', 'Git & GitHub', 'n8n / Make'],
        'skills' => ['Python for data', 'LLM prompting & evaluation', 'RAG basics', 'Automation workflows', 'Data analysis', 'Technical writing'],
        'learn' => ['How to turn a business problem into an AI experiment', 'How to evaluate model output reliably', 'How to build simple RAG and automation pipelines', 'How to think about cost, privacy and failure modes'],
        'who_should_apply' => ['Students in computer science, data science or related fields', 'Developers curious about applied AI', 'People who like experimenting and writing up results'],
        'requirements' => ['Comfortable with Python', 'Basic statistics', 'Some exposure to ML or LLM APIs (projects count)', 'A laptop and reliable internet'],
        'exposure' => ['AI assistants', 'Automation workflows', 'Content analysis', 'Lead scoring experiments', 'Data cleaning', 'Evaluation'],
        'career_growth' => 'The internship builds practical experience for roles in applied AI, ML engineering, automation and data analysis.',
        'career_paths' => ['AI Engineer', 'ML Engineer', 'Automation Specialist', 'Data Analyst'],
        'faqs' => [
            ['question' => 'Do I need to know deep learning?', 'answer' => 'No. Python and curiosity matter more. Most of the work is applied: using models well, not training them from scratch.'],
            ['question' => 'Will I work with client data?', 'answer' => 'Only where appropriate and permitted. Many experiments use public or synthetic data.'],
            ['question' => 'Is the internship remote?', 'answer' => 'Yes, this internship is remote.'],
        ],
    ],
];
