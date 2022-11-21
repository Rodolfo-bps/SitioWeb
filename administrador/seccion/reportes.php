<?php
session_start();

if ($_POST) {
    if (($_POST['usuario'] == "bps") && ($_POST['contraseña'] == "1234")) {
        $_SESSION['usuario'] = "ok";
        $_SESSION['nombreUsuario'] = "bps";
        echo "<script>alert('Inicio de sesion exitoso');</script>";
        header('location:inicio.php');
    } else {
        $mensaje = "Error: El usuario o contraseña son incorrectos";
    }
}


ob_start();

include('../config/bd.php');

$sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<style>
    /*tablas*/
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #98BAE7;
        color: black;
    }

    /*fin tabla*/
</style>

<body>
    <h2 style="text-align: center;">Reportes Soriana</h2>
    <h5 style="text-align: center;">Una dos tres</h5>
    <br>
    <br>
    <label for="">Nombre</label>
    <input type="text">
    <br><br>
    <table id="customers">

        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>

        </tr>


        <?php foreach ($listaLibros as $libro) { ?>
            <tr>
                <td><?php echo $libro['id']; ?></td>
                <td><?php echo $libro['nombre']; ?></td>
                <td>
                    <img class="" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/sitioweb/img/<?php echo $libro['imagen']; ?>" width="100" alt="" srcset="">

                </td>
            </tr>
        <?php } ?>

    </table>
</body>

</html>
<?php
$html = ob_get_clean();
//echo $html; 

require_once '../libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$dompdf->stream("archivo_.pdf", array("Attachment" => false));

?>