<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
  <form action="<?PHP echo base_url('admin/process_login') ?>" method="POST">
    <input type="text" name="username" placeholder="Nombre de Usuario">
    <input type="password" name="password" placeholder="********">
    <input type="submit" value="Iniciar Sesión">
  </form>
</body>
</html>