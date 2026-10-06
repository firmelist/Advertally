@extends('layouts.app')

@section('content')
    @include('partials.report', [
        'audit' => $audit,
        'eyebrow' => 'Advertally Growth Score™',
        'title' => 'Growth Score for '.$audit->company,
        'scoreLabel' => 'Overall Growth Score',
        'note' => 'Based on your self-assessment. Scores reflect the answers provided and are a starting point for a diagnosis, not an audit of live data.',
        'nextTitle' => 'Turn this score into a growth plan.',
        'nextText' => 'A strategist will review your answers, validate them against your live presence and outline the three moves with the highest revenue impact.',
        'secondaryLabel' => 'Run the AI Visibility Audit',
        'secondaryUrl' => route('ai-audit'),
    ])
@endsection
