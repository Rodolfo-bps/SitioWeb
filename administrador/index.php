
<?php

session_start();

if ($_POST) {
    if (($_POST['usuario'] == "bps") && ($_POST['contraseña'] == "1234")) {
        $_SESSION['usuario'] = "ok";
        $_SESSION['nombreUsuario'] = "bps";
        echo "<script>alert('Inicio de sesion exitoso');</script>";
        header('location:inicio.php');
    }else {
        $mensaje = "Error: El usuario o contraseña son incorrectos";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<style>
    body {
        background: #eee;
    }

    .container {
        border: none;
        
    }
</style>

<body >
    <br>
    <br>
    <br>
    <br>



    <div class="container">
        <div class="row" >

            <div class="col-md-4">

            </div>

            <div class="col-md-4">
                <div class="card" style="box-shadow: 0px 14px 22px -16px rgba(0,0,0,0.4);">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">

                        <?php if(isset($mensaje)) {?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                        <?php }?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Usuario</label>
                                <input type="text" class="form-control" name="usuario" placeholder="Escribe tu usuario">
                                <small id="emailHelp" class="form-text text-muted">Nunca compartas tu usuario con nadie.</small>
                            </div>

                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" class="form-control" name="contraseña" placeholder="Escribe tu contraseña">
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label">Recordarme</label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Entrar al Administrador</button>
                        </form>


                    </div>

                </div>
            </div>

        </div>
    </div>
</body>

</html>