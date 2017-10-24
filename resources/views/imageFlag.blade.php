<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Imagen reportada</title>

<div style="background-color: #A0DACD; padding: 20px; color: #FFF">
    <a href="http://www.enpics.com"><img src="http://www.enpics.com/website/assets/img/logo-enpics.png" alt="Enpics"></a>
</div>

<h1>
    Imagen reportada
</h1>

<p>
    La imagen <a href="https://ww.enpics.com/images/{{ $image->id }}">{{ $image->name }}</a> ha sido reportada con el siguiente mensaje:
</p>

<p>
    {{ $data['message'] }}
</p>

</body>
</html>