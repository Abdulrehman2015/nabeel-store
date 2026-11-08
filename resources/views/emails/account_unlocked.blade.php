<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Unlocked</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; margin: 0; padding: 24px; }
        .card { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0; }
        .header { background: #071125; color: #ffffff; padding: 28px 24px; text-align: center; }
        .header h1 { margin: 0 0 6px 0; font-size: 22px; font-weight: 800; letter-spacing: -0.02em; }
        .header p { margin: 0; color: #94a3b8; font-size: 13px; }
        .content { padding: 28px 24px; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 9999px; font-size: 12px; font-weight: 800; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; text-transform: uppercase; letter-spacing: 0.05em; }
        .credentials-box { background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 18px 20px; margin: 20px 0; }
        .cred-item { margin-bottom: 12px; }
        .cred-item:last-child { margin-bottom: 0; }
        .cred-label { font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.04em; display: block; margin-bottom: 4px; }
        .cred-value { font-size: 15px; font-weight: 800; color: #0f172a; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; background: #ffffff; padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; display: inline-block; }
        .btn { display: inline-block; background: #0284c7; color: #ffffff !important; padding: 12px 26px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 14px; margin-top: 18px; text-align: center; }
        .btn:hover { background: #0369a1; }
        .footer { background: #f8fafc; padding: 20px 24px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; line-height: 1.6; }
        .notice { font-size: 12px; color: #475569; background: #f0fdf4; border-left: 3px solid #10b981; padding: 10px 14px; border-radius: 0 8px 8px 0; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>{{ store_name() }}</h1>
            <p>Store & Customer Care Operations</p>
        </div>

        <div class="content">
            <div style="text-align: center; margin-bottom: 18px;">
                <span class="badge">✓ Account Unlocked</span>
            </div>

            <p style="font-size: 15px; margin-top: 0;">Hi <strong>{{ $user->name }}</strong>,</p>

            <p style="font-size: 14px; line-height: 1.6; color: #334155;">
                Your customer account has been reviewed and <strong>successfully unlocked</strong> by store administration.
                You can now log in to the store to place orders and manage your deliveries.
            </p>

            <div class="credentials-box">
                <div class="cred-item">
                    <span class="cred-label">Login Email</span>
                    <span class="cred-value" style="font-family: inherit; font-size: 14px;">{{ $user->email }}</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Temporary Password for Sign In</span>
                    <span class="cred-value" style="color: #0284c7; letter-spacing: 0.05em;">{{ $temporaryPassword }}</span>
                </div>
            </div>

            <div class="notice">
                🔒 <strong>Security Recommendation:</strong> Use the temporary password above to sign in. Once logged in, you can update your password in your Account Settings.
            </div>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">
                    Sign In to Your Account &rarr;
                </a>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px 0;">Need assistance? Contact our customer support:</p>
            <p style="margin: 0; font-weight: 600; color: #334155;">
                Email: <a href="mailto:{{ setting('admin_email', setting('store_email', 'admin@99bulksales.my')) }}" style="color: #0284c7; text-decoration: none;">{{ setting('admin_email', setting('store_email', 'admin@99bulksales.my')) }}</a>
                &bull;
                Phone: <a href="tel:{{ setting('admin_phone', setting('store_phone', '+60 12-889 9900')) }}" style="color: #0284c7; text-decoration: none;">{{ setting('admin_phone', setting('store_phone', '+60 12-889 9900')) }}</a>
            </p>
            <p style="margin: 12px 0 0 0; font-size: 11px; color: #94a3b8;">
                &copy; {{ date('Y') }} {{ store_name() }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

