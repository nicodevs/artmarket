<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Imagen reportada</title>



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