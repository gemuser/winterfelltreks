@extends('emails.layout')

@section('content')
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 48px; height: 48px; line-height: 48px; background-color: #fff7ed; border: 1px solid #fed7aa; border-radius: 50%; color: #ea580c; font-size: 24px; margin-bottom: 12px;">
            🔐
        </div>
        <h2 style="margin: 0 0 6px; font-size: 22px; color: #0f172a; font-weight: 800;">Two-Step Verification</h2>
        <p style="margin: 0; color: #64748b; font-size: 14px;">Sign-in request for <strong>{{ $user->name }}</strong></p>
    </div>

    <p style="text-align: center; color: #475569; font-size: 14px; margin-bottom: 20px;">
        Please enter the following 6-digit one-time passcode to complete your sign-in to the <strong>Winterfell Staff & Guide Portal</strong>:
    </p>

    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 12px; padding: 28px 20px; text-align: center; margin: 24px 0; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
        <p style="margin: 0 0 8px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.18em; color: #ea580c; font-weight: 800;">Verification Code</p>
        <div style="font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 900; letter-spacing: 0.35em; color: #ffffff; text-shadow: 0 2px 8px rgba(0,0,0,0.4); padding-left: 0.35em;">
            {{ $otpCode }}
        </div>
        <p style="margin: 10px 0 0; font-size: 12px; color: #94a3b8;">
            ⏱ This code expires in <strong>10 minutes</strong>
        </p>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin: 20px 0; font-size: 12px; color: #64748b; line-height: 1.6;">
        <strong style="color: #334155; display: block; margin-bottom: 4px;">Sign-in Attempt Details:</strong>
        • Requested At: <strong>{{ now()->format('M d, Y H:i:s') }}</strong><br>
        @if($ipAddress)
        • Originating IP: <strong>{{ $ipAddress }}</strong><br>
        @endif
        • Account: <strong>{{ $user->email }}</strong>
    </div>

    <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 4px; padding: 12px 16px; margin: 24px 0; font-size: 12px; color: #991b1b; line-height: 1.5;">
        <strong>Didn't request this code?</strong><br>
        If you did not attempt to sign in, someone may know your password. Please reset your password immediately or contact your portal administrator.
    </div>
@endsection

