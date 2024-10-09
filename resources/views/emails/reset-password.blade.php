<!DOCTYPE html>
<html>

<head>
    <title>איפוס סיסמה</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            padding: 10px 15px;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            direction: rtl;
            text-align: right;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        p {
            margin: 0;
            font-size: 16px;
            /* adjust the font size for readability on mobile */
            line-height: 22px;
            color: #666666;
        }

        .button {
            display: block;
            width: 100%;
            max-width: 280px;
            padding: 15px 25px;
            background-color: #4A35EA;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 auto;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px) {
            h1 {
                font-size: 24px !important;
                line-height: 28px !important;
            }

            p {
                font-size: 16px !important;
                line-height: 22px !important;
            }

            td {
                padding: 15px !important;
            }

            .button {
                width: 100% !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">

    <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        איפוס סיסמה
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#f4f4f4" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
                            <a href="{{ $siteUrl }}" target="_blank">
                                <img alt="Logo" src="{{ $logo }}" width="169" height="40" style="display: block; width: 169px; max-width: 169px; min-width: 169px;" border="0">
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0 0; color: #111111; font-family: Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <h1 style="font-size: 28px; font-weight: 400; margin: 0; letter-spacing: 0px;">איפוס סיסמה</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="right" style="padding: 20px 30px 40px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">,שלום {{ $notifiable->name }}</p>
                            <p style="margin: 0;">.קיבלת הודעת דוא"ל זו בעקבות בקשתך לאיפוס </p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="right">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" style="border-radius: 3px;" bgcolor="#4A35EA"><a href="{{ $resetUrl }}" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #4A35EA; display: inline-block;">אפס סיסמה</a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="right" style="padding: 0px 30px 0px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="direction: rtl; text-align: right;">תוקף הקישור לאיפוס הסיסמה יפוג לאחר 60 דקות.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="right" style="padding: 20px 30px 20px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="direction: rtl; text-align: right;">אם לא ביקשת לאפס את סיסמתך, נא להתעלם מהודעה זו.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="right" style="padding: 0px 30px 20px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">,בברכה<br>צוות אופקית – תוכנת התיעוד הדיגיטאלי של ארגון אופק</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="right" style="padding: 0px 30px 40px 30px; border-radius: 0 0 4px 4px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">:איפוס הסיסמה אינו עובד? נא להעתיק את הקישור הבא לדפדפן ולהיכנס דרכו</p>
                            <p style="margin: 0;"><a href="https://ofktabam.net/password/reset" target="_blank" style="color: #4A35EA; text-decoration: none;">https://ofktabam.net/password/reset</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><br><br><br><br></tr>
    </table>
</body>

</html>
