<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Mensaje desde el sitio web</title>

<div style="background-color: #A0DACD; padding: 20px; color: #FFF">
    <a href="http://www.enpics.com"><img src="http://www.enpics.com/website/assets/img/logo-enpics.png" alt="Enpics"></a>
</div>

<h1>
    Mensaje de {{ $data['email'] }} desde el sitio web
</h1>

<p>
    {{ $data['message'] }}
</p>

</body>
</html>