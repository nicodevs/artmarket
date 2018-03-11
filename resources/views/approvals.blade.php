<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Im&aacute;genes aprobadas</title>



<h1>
    Hola, {{$user->username}}!
</h1>

<p>
    Te contamos que hemos aprobado
    @if ($notification_counters['APPROVAL'] > 1)
        {{$notification_counters['APPROVAL']}} de tus im&aacute;genes subidas recientemente.
    @else
        una de tus im&aacute;genes subidas recientemente.
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