@php
    $host = parse_url($report->url, PHP_URL_HOST);
    $color = $report->score >= 85 ? '#0F766E' : ($report->score >= 60 ? '#D9540B' : '#DC2626');
    $labels = ['security' => 'Security', 'speed' => 'Speed', 'mobile' => 'Mobile', 'seo' => 'SEO', 'conversion' => 'Lead capture'];
    $groups = collect($report->checks)->groupBy('category');
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 32px 40px; }
    body { font-family: DejaVu Sans, sans-serif; color: #0B1B4D; font-size: 11px; line-height: 1.5; }
    .brand { color: #2952CC; font-weight: bold; font-size: 18px; }
    .dot { color: #F26B1D; }
    .header { border-bottom: 3px solid #2952CC; padding-bottom: 10px; margin-bottom: 20px; }
    .score { font-size: 48px; font-weight: bold; color: {{ $color }}; }
    h2 { font-size: 14px; margin: 22px 0 8px; color: #0B1B4D; border-bottom: 1px solid #E3E8F2; padding-bottom: 4px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 7px 6px; border-bottom: 1px solid #EEF1F7; vertical-align: top; }
    .st { width: 60px; font-weight: bold; font-size: 10px; }
    .pass { color: #0F766E; } .warn { color: #D9540B; } .fail { color: #DC2626; }
    .muted { color: #52607A; }
    .cta { margin-top: 26px; background: #0B1B4D; color: #fff; padding: 16px 18px; border-radius: 8px; }
    .cta b { color: #F98545; }
</style>
</head>
<body>
    <div class="header">
        <span class="brand">advertally<span class="dot">.</span></span>
        <span class="muted" style="float:right">Website Audit · {{ $report->created_at?->format('d M Y') }}</span>
    </div>

    <table><tr>
        <td style="border:none; width:65%">
            <div class="muted">Website</div>
            <div style="font-size:18px; font-weight:bold">{{ $host }}</div>
            <p class="muted">This report checks security, speed, mobile readiness, SEO essentials and lead-capture elements on your homepage.</p>
        </td>
        <td style="border:none; text-align:right">
            <div class="score">{{ $report->score }}<span style="font-size:16px" class="muted">/100</span></div>
            <div style="color: {{ $color }}; font-weight:bold">{{ $report->grade }}</div>
        </td>
    </tr></table>

    @foreach ($labels as $key => $label)
        @continue(! $groups->has($key))
        <h2>{{ $label }}</h2>
        <table>
            @foreach ($groups[$key] as $c)
                <tr>
                    <td class="st {{ $c['status'] }}">{{ ['pass' => 'PASS', 'warn' => 'IMPROVE', 'fail' => 'FIX'][$c['status']] }}</td>
                    <td><b>{{ $c['label'] }}</b><br><span class="muted">{{ $c['detail'] }}</span></td>
                </tr>
            @endforeach
        </table>
    @endforeach

    <div class="cta">
        <b>Want these fixed?</b> Our team can fix most issues within a week.<br>
        WhatsApp {{ setting('phone') }} · {{ setting('email') }} · {{ url('/') }}
    </div>
</body>
</html>
