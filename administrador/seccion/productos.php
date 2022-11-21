<?php include('../template/cabecera.php'); ?>

<?php

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

//comprobar que los datos llegan
/*echo $txtID . "<br />";
echo $txtNombre . "<br />";
echo $txtImagen . "<br />";
echo $accion . "<br />";
*/

include('../config/bd.php');

switch ($accion) {
    case "Agregar":
        //INSERT INTO `libros` (`id`, `nombre`, `imagen`) VALUES (NULL, 'Libro.php', 'imagen.jpg'); 
        $sentenciaSQL = $conexion->prepare("INSERT INTO `libros` (nombre,imagen) VALUES (:nombre, :imagen);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);

        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : "imagen.jpg";

        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpImagen != "") {
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $txtImagen);
        $sentenciaSQL->execute();

        header("location:productos.php");
        break;

    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        if ($txtImagen != "") {

            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : "imagen.jpg";
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
            

            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {

                if (file_exists("../../img/" . $libro["imagen"])) {
                    unlink("../../img/" . $libro["imagen"]);
                }
            }

            $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen', $txtImagen);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        }

        break;

    case "Cancelar":
        header("location:productos.php");
        break;

    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];

        break;

    case "Borrar":
        //borrar imagen
        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {

            if (file_exists("../../img/" . $libro["imagen"])) { 
                unlink("../../img/" . $libro["imagen"]);
            }
        }

        //borrar registro
        $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("location:productos.php");
        break;
}

/*Mostrar todos los datos en la tabla */
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
/*fin de mostrar */
?>

<div class="col-md-5">

    <div class="card">

        <div class="card-header">
            Datos del libro
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="txtID">ID:</label>
                    <input type="text" required readonly value="<?php echo $txtID; ?>" class="form-control" name="txtID" id="txtID" placeholder="ID">
                </div>

                <div class="form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" required value="<?php echo $txtNombre; ?>" class="form-control" name="txtNombre" id="txtNombre" placeholder="Nombre del libro">
                </div>

                <div class="form-group">
                    <label for="txtImagen">Imagen</label>
                    <br>
                    <?php echo $txtImagen; ?>

                    <?php if ($txtImagen != "") { ?>
                        <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen'] ?>" width="50" alt="" srcset="">
                    <?php } ?>
                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="">
                </div>

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : "" ?> value="Agregar" class="btn btn-success" style="margin-right: 12px;">Agregar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : "" ?> value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : "" ?> value="Cancelar" class="btn btn-info" style="margin-left: 12px;">Cancelar</button>
                </div>
            </form>
        </div>

    </div>
</div>
<div class="col-md-7">
<a href="reportes.php">PDF</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaLibros as $libro) { ?>
                <tr>
                    <td><?php echo $libro['id']; ?></td>
                    <td><?php echo $libro['nombre']; ?></td>
                    <td><?php echo $libro['imagen']; ?></td>
                    <td>
                        
                        <form method="post">
                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>" />
                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../template/pie.php'); ?>