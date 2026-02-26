<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Slow Jobs — Fabninjas JobLens</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238b5cf6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='11' cy='11' r='8'/><line x1='21' y1='21' x2='16.65' y2='16.65'/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #09090b;
            color: #fafafa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            max-width: 960px;
            margin: 0 auto;
            padding: 48px 24px 48px;
            flex: 1;
        }

        /* ── Header ──────────────────────────────── */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 32px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 14px;
            font-weight: 600;
            color: #a1a1aa;
            letter-spacing: -0.01em;
        }

        .brand-text strong {
            color: #fafafa;
        }

        .header p {
            font-size: 15px;
            color: #71717a;
            line-height: 1.5;
        }

        .live-dot {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            background: rgba(34, 197, 94, 0.08);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.18);
            white-space: nowrap;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .live-dot span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4ade80;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* ── Info box ────────────────────────────── */
        .info-box {
            background: #111113;
            border: 1px solid #1c1c22;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 15px;
            color: #a1a1aa;
            line-height: 1.6;
        }

        .info-box svg {
            width: 20px;
            height: 20px;
            color: #6366f1;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-box code {
            background: #1c1c22;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'SF Mono', 'Fira Code', ui-monospace, monospace;
            font-size: 14px;
            color: #c4b5fd;
        }

        /* ── Table ───────────────────────────────── */
        .table-card {
            background: #111113;
            border: 1px solid #1c1c22;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            border-bottom: 1px solid #1c1c22;
        }

        .table-bar-title {
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 20px;
            padding: 0 7px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            background: #27272a;
            color: #a1a1aa;
        }

        .table-scroll {
            overflow-x: auto;
        }

        .table-scroll::-webkit-scrollbar { height: 6px; }
        .table-scroll::-webkit-scrollbar-track { background: transparent; }
        .table-scroll::-webkit-scrollbar-thumb { background: #27272a; border-radius: 999px; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 560px;
        }

        thead th {
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 500;
            color: #52525b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            background: #0c0c0e;
            border-bottom: 1px solid #1c1c22;
            white-space: nowrap;
        }

        thead th:first-child { padding-left: 20px; }
        thead th:last-child  { padding-right: 20px; }

        tbody tr {
            transition: background 100ms ease;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        tbody td {
            padding: 11px 16px;
            font-size: 13px;
            color: #fafafa;
            border-bottom: 1px solid #18181b;
            white-space: nowrap;
        }

        tbody td:first-child { padding-left: 20px; }
        tbody td:last-child  { padding-right: 20px; }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .c-id {
            color: #fafafa;
            font-variant-numeric: tabular-nums;
            font-size: 12px;
        }

        .c-job {
            color: #fafafa;
            font-weight: 500;
        }

        .c-ms {
            font-variant-numeric: tabular-nums;
        }

        .c-ms .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .c-ms .pill i {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #ef4444;
            display: block;
        }

        .c-time {
            color: #fafafa;
            font-size: 12px;
        }

        /* ── Empty ───────────────────────────────── */
        .empty {
            padding: 56px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto 14px;
            background: #18181b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            width: 22px;
            height: 22px;
            color: #4ade80;
        }

        .empty h3 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .empty p {
            font-size: 15px;
            color: #52525b;
            max-width: 340px;
            margin: 0 auto;
            line-height: 1.5;
        }

        @media (max-width: 640px) {
            .page { padding: 28px 14px 48px; }
            .header { flex-direction: column; }
            h1 { font-size: 20px; }
            table { min-width: 480px; }
        }
    </style>
</head>
<body>
@php
    $thresholdMs = config('joblens.slow_job_threshold_ms', 1000);
    $jobCount = isset($jobs) ? $jobs->count() : 0;
@endphp

<div class="page">

    <div class="header">
        <div>
            <div class="brand">
                <div class="brand-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg></div>
                <div class="brand-text"><h1><strong>Fabninjas / JobLens</strong></h1></div>
            </div>
        </div>
        <div class="live-dot"><span></span> Monitoring</div>
    </div>

    <div class="info-box">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
        <div>
            JobLens consider the job slow if the time taken by the job for execution is greater then the 80% of it's declared timeout.
            <br>
            If no timeout set for the job then it will take the slow threshold set into <code>config/joblens.php</code> config file.
        </div>
    </div>

    <div class="table-card">
        <div class="table-bar">
            <div class="table-bar-title">
                Logged slow jobs
                <span class="count">{{ $jobCount }}</span>
            </div>
        </div>

        <div class="table-scroll">
            @if($jobCount > 0)
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Job</th>
                        <th>Duration</th>
                        <th>Recorded at</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td class="c-id">{{ $job->id }}</td>
                            <td class="c-job">{{ $job->job_name ?? '—' }}</td>
                            <td class="c-ms">
                                <span class="pill"><i></i> {{ number_format($job->execution_time, 2) }} ms</span>
                            </td>
                            <td class="c-time">{{ $job->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <h3>All clear</h3>
                    <p>No slow jobs detected yet. When a job exceeds {{ $thresholdMs }}ms it will appear here.</p>
                </div>
            @endif
        </div>
    </div>

</div>

<div style="padding: 12px 0 20px; text-align: center; font-size: 13px; color: #52525b;">
    © 2026 Fabninjas. All rights reserved.
</div>

</body>
</html>
