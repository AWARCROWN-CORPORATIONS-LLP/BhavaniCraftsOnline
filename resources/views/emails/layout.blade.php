<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhavani Crafts | Sacred Correspondence</title>
</head>
<body style="margin: 0; padding: 0; background-color: #fbfbfc; font-family: 'Inter', Helvetica, Arial, sans-serif;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.05); border: 1px solid #f1f1f1;">
        
        <!-- Header -->
        <div style="background-color: #111111; padding: 40px 20px; text-align: center;">
            <p style="color: #c62828; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 4px; margin-bottom: 8px;">Bhavani Crafts</p>
            <h1 style="color: #ffffff; font-size: 20px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin: 0;">@yield('email_title')</h1>
        </div>

        <!-- Content -->
        <div style="padding: 60px 40px; text-align: left;">
            @yield('email_content')
        </div>

        <!-- Footer -->
        <div style="background-color: #fafafa; padding: 30px 40px; border-top: 1px solid #f1f1f1; text-align: center;">
            <p style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; line-height: 1.6;">
                Forging Heritage into Devotion<br>
                &copy; {{ date('Y') }} Bhavani Crafts. All Rights Reserved.
            </p>
            <div style="margin-top: 20px;">
                <a href="#" style="color: #c62828; text-decoration: none; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin: 0 10px;">Privacy</a>
                <a href="#" style="color: #c62828; text-decoration: none; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin: 0 10px;">Support</a>
            </div>
        </div>
    </div>
</body>
</html>
