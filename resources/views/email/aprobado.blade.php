<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://fonts.cdnfonts.com/css/brain-wants" rel="stylesheet">



</head>

<body style="margin: 0; background-color: #ffffff;">
    <center style="width: 100%; table-layout: fixed; background-color: #ffffff; padding-bottom: 60px;">
        <table
            style="background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0;  color:#171a1b; font-family: sans-serif;"
            width="100%">
            <!-- Borde Multicolor -->
            <tr>
                <td height="8"
                    style="background: linear-gradient(to right, #f3ff3f, #ec4777, #db00c9, #b015f8, #009bdb, #00d3db);">
                </td>
            </tr>

            <!-- Seccion Logo -->
            <tr>
                <td style="padding: 14px 0 4px;">
                    <table width="100%" style="border-spacing: 0;">
                        <tr>
                            <td
                                style="display: flex; flex-flow: row wrap; justify-content: space-between; font-size: 0;padding: 10px;">
                                <table style="width:80%;">
                                    <tr>
                                        <td style="padding: 0 0px 10px;">
                                            <a href="http://adsupp.com" target="_blank">
                                                <img src="https://adsupp-reproductor-pantallas.s3.amazonaws.com/img-emails/pago-exitoso/img1.jpeg"
                                                    width="100" alt="AdsUpp Logo" title="AdsUpp">
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <table style="width:19%;" class="column">
                                    <tr>
                                        <td>
                                            <a href="https://www.instagram.com/ads.upp" target="_blank">
                                                <img src="https://adsupp-reproductor-pantallas.s3.amazonaws.com/img-emails/pago-exitoso/img2.jpeg"
                                                    width="40" alt="instagram">
                                            </a>
                                            <a href="https://www.tiktok.com/@ads.upp" style="padding-left:10px;"
                                                target="_blank">
                                                <img src="https://adsupp-reproductor-pantallas.s3.amazonaws.com/img-emails/pago-exitoso/img3.jpeg"
                                                    width="40" style="border-radius: 7px;" alt="TikTok">
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%; padding: 10px;">
                        <tr>
                            <td>
                                <h1
                                    style=" 
                                    font-family: 'Brain Wants', sans-serif;">
                                    Recibimos tu pago correctamente</h1>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3 style="font-weight: normal;">Tu publicación está lista para publicarse en <span
                                        id="nombre-pantalla">{{ $screen_name }}</span>.</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="margin-bottom: 0;">Detalles de Tu Publicación:</h4>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 20px;">
                                <p>
                                    • Ubicación: Pantalla AdsUpp - <span
                                        id="nombre-pantalla">{{ $screen_name }}</span>,
                                    <span id="direccion">{{ $screen_location }}</span><br>
                                    • Duración de la publicación: <span id="cantidad-tiempo">{{ $media_duration }}
                                        segundos</span><br>
                                    • Horario de emisión: <span
                                        id="fecha">{{ date('d/m/Y', strtotime($media_date)) }} -
                                        {{ date('H:i', strtotime($media_time)) }}</span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Gracias por confiar en <span
                                        style="font-family: 'Brain Wants', sans-serif;">AdsUpp</span> para compartir tu
                                    publicación.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Saludos<br> El Equippo de <b
                                        style="font-family: 'Brain Wants', sans-serif;
                                    ">AdsUpp</b>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
