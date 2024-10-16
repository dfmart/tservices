<?php
session_start();
include_once "includes/header.php";
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "proveedores";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if(isset($_SESSION['message']))
    {
        echo"<h4>".$_SESSION['message']."</h4>";
        unset($_SESSION['message']);
    }
    ?>
<form action="importar_proveedor.php" method="post" enctype="multipart/form-data">
    <label for="file">Selecciona un archivo Excel:</label>
    <input type="file" name="import_file" class="form-control">
    <button type="submit"  name="save_excel_data" value="Importar">Importar</button>
</form>
    
</body>
</html>