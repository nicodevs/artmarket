<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Resumen semanal</title>

<div style="background-color: #A0DACD; padding: 20px; color: #FFF">
    <a href="http://www.enpics.com"><img src="http://www.enpics.com/website/assets/img/logo-enpics.png" alt="Enpics"></a>
</div>

<h1>
    Hola, <?=$user['username']?>!
</h1>

<p>
    Esta semana tuviste

    <?php if ($user['notification_counters']['LIKE']): ?>
        <?=$user['notification_counters']['LIKE']?> like<?= ($user['notification_counters']['LIKE'] > 1)? 's' : '' ?>
    <?php endif; ?>

    <?php if ($user['notification_counters']['LIKE'] && $user['notification_counters']['COMMENT']): ?>
        y
    <?php endif;?>

    <?php if ($user['notification_counters']['COMMENT']): ?>
        <?=$user['notification_counters']['COMMENT']?> comentario<?= ($user['notification_counters']['COMMENT'] > 1)? 's' : '' ?>
    <?php endif; ?>

    en tus im&aacute;genes.
</p>

<p>
    &iexcl;Ingres&aacute; ahora a tu perfil en <a href="http://www.enpics.com" style="color: #56A794">Enpics.com</a> para enterarte de toda la actividad de tu cuenta!
</p>

<a href="https://www.enpics.com/admin/#<?=$user['username']?>/notifications" style="border-color: transparent; border-style: solid; border-width: 0px; color: #FFF; display: inline-block; font-family: Helvetica,Arial,sans-serif; font-size: 18px; font-weight: normal; line-height: 1.35; margin: 15px; max-height: none; max-width: none; padding: 0.6em 1em; text-decoration: none; background-color: #60C2AC; border-radius: 10px;">
    Ingresar a mi cuenta
</a>

<p>
    <strong>El equipo de Enpics</strong>
</p>

</body>
</html>