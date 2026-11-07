<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Winterfell Treks & Expedition' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1f2937;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f3f4f6;
            padding: 40px 16px;
        }
        .main-card {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #0f172a;
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-family: 'Cinzel', Georgia, serif;
            font-size: 24px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0;
            color: #ea580c;
            font-size: 12px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-weight: 600;
        }
        .content {
            padding: 32px 28px;
        }
        .content h2 {
            margin: 0 0 16px;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .content p {
            margin: 0 0 16px;
            font-size: 15px;
            line-height: 1.6;
            color: #4b5563;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-new { background-color: #fee2e2; color: #b91c1c; }
        .badge-under_review { background-color: #fef3c7; color: #b45309; }
        .badge-contacted { background-color: #dbeafe; color: #1d4ed8; }
        .badge-quotation_sent { background-color: #f3e8ff; color: #7e22ce; }
        .badge-follow_up_required { background-color: #ffedd5; color: #c2410c; }
        .badge-closed { background-color: #dcfce7; color: #15803d; }
        .info-box {
            background-color: #f8fafc;
            border-left: 4px solid #ea580c;
            border-radius: 6px;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .detail-table {
            width: 100%;
            margin: 20px 0;
            border-top: 1px solid #f1f5f9;
        }
        .detail-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .detail-table td.label {
            color: #64748b;
            width: 38%;
            font-weight: 500;
        }
        .detail-table td.value {
            color: #0f172a;
            font-weight: 600;
        }
        .btn-action {
            display: inline-block;
            background-color: #ea580c;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            margin: 20px 0 10px;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        .footer a {
            color: #ea580c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-card">
            <div class="header">
                <h1>{{ config('app.name', 'Winterfell Treks') }}</h1>
                <p>Himalayan Expeditions & Adventures</p>
            </div>
            
            <div class="content">
                @yield('content')
            </div>
            
            <div class="footer">
                @php
                    $companyEmail = \App\Models\SiteSetting::getValue('email_primary') ?: 'winterfelltreks@gmail.com';
                    $companyPhone = \App\Models\SiteSetting::getValue('phone_primary') ?: '+977-1-4000000';
                    $companyAddress = \App\Models\SiteSetting::getValue('office_address') ?: 'Thamel, Kathmandu, Nepal';
                @endphp
                <p style="margin: 0 0 6px 0; font-weight: 600; color: #334155;">Winterfell Treks & Expedition Pvt. Ltd.</p>
                <p style="margin: 0 0 6px 0;">{{ $companyAddress }}</p>
                <p style="margin: 0 0 10px 0;">
                    Email: <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a> | 
                    Phone: <a href="tel:{{ $companyPhone }}">{{ $companyPhone }}</a>
                </p>
                <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                    © {{ date('Y') }} Winterfell Treks & Expedition. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

