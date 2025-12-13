<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; background-color:#f8f8fb; font-family:Arial, Helvetica, sans-serif;">

    <!-- Outer Background Table -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8f8fb; padding:24px 0;">
        <tr>
            <td align="center">

                <!-- Main Email Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0"
                    style="width:100%; max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden;">

                    <!-- HEADER -->
                    <tr>
                        <td align="center"
                            style="
                                padding: 28px 0; 
                                background:#ffffff; 
                                border-bottom:6px solid #f8e8e3;
                            ">
                            <table role="presentation" cellspacing="0" cellpadding="0"
                                style="width:140px; height:46px;">
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <img src="{{ asset('assets/images/411_458.svg') }}" width="32"
                                            alt="Logo Icon" style="display:block;">
                                    </td>

                                    <td style="vertical-align:middle; padding-left:10px;">
                                        <img src="{{ asset('assets/images/411_459.svg') }}" width="90"
                                            alt="Esimira" style="display:block;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>



                    <!-- HERO IMAGE -->
                    <tr>
                        <td>
                            <img src="{{ asset('assets/images/welcome-main.svg') }}" width="100%" alt="Welcome"
                                style="display:block; width:100%; max-width:600px; border:0;">
                        </td>
                    </tr>

                    <!-- CONTENT SECTION -->
                    <tr>
                        <td style="padding:32px;">

                            <h2 style="margin:0 0 16px; font-size:22px; color:#111827; text-align:center;">
                                Confirm Verification Code
                            </h2>

                            <p style="margin:0 0 20px; font-size:14px; color:#4b5563; line-height:1.6;">
                                Hi {{ $name ?? 'User' }}, <br><br>
                                Your verification code is:
                            </p>

                            <!-- OTP BOXES -->
                            <!-- SINGLE OTP BOX -->
                            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                            <tr>
                                <td align="center"
                                    
                                    style="
                                        margin: 0;
                                        margin-top: 60px;
                                        font-size: 40px;
                                        font-weight: 600;
                                        letter-spacing: 25px;
                                        color: #ef7f50;
                                    "
                                    >
                                    {{ $otp ?? '------' }}
                                </td>
                            </tr>
                            </table>



                            <p style="margin:24px 0; font-size:14px; color:#4b5563; line-height:1.6;">
                                Whether you're here for your brand, a cause, or simply for fun — welcome!
                                Your code is valid for the next <strong>5 minutes</strong>.
                            </p>

                            <!-- BUTTON -->
                            {{-- <div style="text-align:center; margin:30px 0 10px;">
                                <a href="{{ $verifyUrl ?? '#' }}"
                                    style="
                    background:#ef7f50;
                    color:#ffffff;
                    padding:14px 28px;
                    text-decoration:none;
                    border-radius:8px;
                    font-size:15px;
                    font-weight:bold;
                    display:inline-block;
                  ">
                                    Verify Email
                                </a>
                            </div> --}}

                            <p style="margin:24px 0 0; font-size:14px; color:#4b5563; line-height:1.6;">
                                Thanks,<br>
                                <strong>Esimira Team</strong>
                            </p>

                        </td>
                    </tr>

                    <br>

                    <!-- FOOTER SECTION -->
                    <tr>
                        <td style="background:#fdfdfd; padding:28px;">

                            <!-- App Promotion -->
                            <p style="margin:0 0 12px; font-size:13px; color:#6b7280;">
                                Experience it instantly at the tap of a button! Download our app for Google and Mac.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <a href="#"><img src="{{ asset('assets/images/gplay.png') }}"
                                                width="130" alt="Google Play" style="display:block;"></a>
                                    </td>

                                    <td style="padding-left:10px;">
                                        <a href="#"><img src="{{ asset('assets/images/app-store.svg') }}"
                                                width="130" alt="App Store" style="display:block;"></a>
                                    </td>
                                </tr>
                            </table>

                            <!-- SOCIAL ICONS -->
                            <table role="presentation" width="100%" style="margin-top:20px;">
                                <tr>
                                    <td align="right">
                                        <a href="#"><img src="{{ asset('assets/images/facebook.svg') }}"
                                                width="28" alt="Facebook" style="margin-left:8px;"></a>
                                        <a href="#"><img src="{{ asset('assets/images/linkedin-welcome.svg') }}"
                                                width="28" alt="LinkedIn" style="margin-left:8px;"></a>
                                        <a href="#"><img src="{{ asset('assets/images/instagram-welcome.svg') }}"
                                                width="28" alt="Instagram" style="margin-left:8px;"></a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Legal -->
                            <p style="margin-top:20px; font-size:12px; color:#9ca3af; line-height:1.6;">
                                Have questions? Reach us at
                                <a href="mailto:info@esimira.com" style="color:#ef7f50; text-decoration:none;">
                                    info@esimira.com
                                </a>.
                                <br><br>
                                © {{ date('Y') }} Esimira. All rights reserved.
                            </p>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
