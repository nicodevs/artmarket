<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Im&aacute;genes aprobadas</title>

<div style="background-color: #A0DACD; padding: 20px; color: #FFF">
    <a href="http://www.enpics.com"><img src="http://www.enpics.com/website/assets/img/logo-enpics.png" alt="Enpics"></a>
</div>

<h1>
    Hola, <?=$user['user']['username']?>!
</h1>

<p>
    Te contamos que hemos aprobado
    <?php if ($user['notification_counters']['APPROVAL'] > 1): ?>
        <?=$user['notification_counters']['APPROVAL']?> de tus
    <?php else: ?>
        una de tus
    <?php endif; ?>
    im&aacute;genes subida<?= ($user['notification_counters']['APPROVAL'] > 1)? 's' : '' ?> recientemente.
</p>

<p>
    &iexcl;Ingres&aacute; ahora a tu perfil en <a href="http://www.enpics.com" style="color: #56A794">Enpics.com</a> para enterarte de toda la actividad de tu cuenta!
</p>

<a href="https://www.enpics.com/admin/#<?=$user['user']['username']?>/notifications" style="border-color: transparent; border-style: solid; border-width: 0px; color: #FFF; display: inline-block; font-family: Helvetica,Arial,sans-serif; font-size: 18px; font-weight: normal; line-height: 1.35; margin: 15px; max-height: none; max-width: none; padding: 0.6em 1em; text-decoration: none; background-color: #60C2AC; border-radius: 10px;">
    Ingresar a mi cuenta
</a>

<p>
    <strong>El equipo de Enpics</strong>
</p>

</body>
</html>