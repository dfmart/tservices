<?php
session_start();
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

// Inicializamos la variable de alerta
$alert = "";

// Verificamos si se ha enviado el ID del cliente
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Aseguramos que sea un entero
    // Consultamos los datos del cliente
    $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    // Si el cliente no existe, redirigimos o mostramos un error
    if (!$data) {
        header('Location: clientes.php'); // Redirigir si el cliente no se encuentra
        exit();
    }
}

// Procesamos el formulario de edición
if (!empty($_POST)) {
    if (empty($_POST['nombre']) || empty($_POST['direccion']) || empty($_POST['telefono']) || empty($_POST['correo'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Todos los campos son obligatorios
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];

        // Actualizamos los datos del cliente
        $sql_update = mysqli_query($conexion, "UPDATE clientes SET nombre = '$nombre', direccion = '$direccion', telefono = '$telefono', correo = '$correo' WHERE id = $id");
        
        // Verificamos si la actualización fue exitosa
        if ($sql_update) {
            $_SESSION['message'] = 'Cliente modificado con éxito';
        } else {
            $_SESSION['message'] = 'Error al modificar el cliente';
        }
        
        // Redirigimos a la plantilla de clientes
        header('Location: clientes.php');
        exit();
    }
}

include_once "includes/header.php";
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Nombre</label>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" value="<?php echo isset($data['nombre']) ? $data['nombre'] : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                                <input type="text" placeholder="Ingrese Dirección" name="direccion" id="direccion" class="form-control" value="<?php echo isset($data['direccion']) ? $data['direccion'] : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                                <input type="number" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control" value="<?php echo isset($data['telefono']) ? $data['telefono'] : ''; ?>" required>
                                <input type="hidden" name="id" id="id" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="correo" class="text-dark font-weight-bold">Correo</label>
                                <input type="email" placeholder="Ingrese Correo" name="correo" id="correo" class="form-control" value="<?php echo isset($data['correo']) ? $data['correo'] : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-5 mt-3">
                            <input type="submit" value="Actualizar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                            <a href="clientes.php" class="btn btn-danger">Atrás</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>

