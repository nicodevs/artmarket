<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Resumen semanal</title>



<h1>
    Hola, {{$user->username}}!
</h1>

<p>
    Esta semana tuviste

    @if (isset($notification_counters['LIKE']))
        {{ $notification_counters['LIKE'] }} like{{ $notification_counters['LIKE'] > 1 ? 's' : ''}}
    @endif

    @if (isset($notification_counters['LIKE']) && isset($notification_counters['COMMENT']))
        y
    @endif

    @if (isset($notification_counters['COMMENT']))
        {{ $notification_counters['COMMENT'] }} comentario{{ $notification_counters['COMMENT'] > 1 ? 's' : ''}}
    @endif
</p>

<p>
    &iexcl;Ingres&aacute; ahora a tu perfil para enterarte de toda la actividad de tu cuenta!
</p>

<a href="https://www.enpics.com/admin/#{{$user->username}}/notifications" style="border-color: transparent; border-style: solid; border-width: 0px; color: #FFF; display: inline-block; font-family: Helvetica,Arial,sans-serif; font-size: 18px; font-weight: normal; line-height: 1.35; margin: 15px; max-height: none; max-width: none; padding: 0.6em 1em; text-decoration: none; background-color: #60C2AC; border-radius: 10px;">
    Ingresar a mi cuenta
</a>

<p>
    <strong>El equipo</strong>
</p>

</body>
</html>