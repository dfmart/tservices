<?php
session_start();
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "registro_partes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql, MYSQLI_ASSOC);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
    exit();
}

// Inicializamos la variable de alerta
$alert = "";

// Verificamos si se ha enviado el ID del parte
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Consultamos los datos del parte
    $query = mysqli_query($conexion, "SELECT * FROM registro_partes WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    // Si el parte no existe, redirigimos o mostramos un error
    if (!$data) {
        header('Location: registro_partes.php');
        exit();
    }
}

// Procesamos el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['placa']) || empty($_POST['tipo']) || empty($_POST['proveedor']) || empty($_POST['estado']) || empty($_POST['cliente']) || empty($_POST['tecnico'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Todos los campos son obligatorios
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        $id = $_POST['id'];
        $placa = mysqli_real_escape_string($conexion, $_POST['placa']);
        $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
        $serial = mysqli_real_escape_string($conexion, $_POST['serial']);
        $proveedor = mysqli_real_escape_string($conexion, $_POST['proveedor']);
        $marca = mysqli_real_escape_string($conexion, $_POST['marca']);
        $modelo = mysqli_real_escape_string($conexion, $_POST['modelo']);
        $especificacion = mysqli_real_escape_string($conexion, $_POST['especificacion']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        $cliente = mysqli_real_escape_string($conexion, $_POST['cliente']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $tecnico = mysqli_real_escape_string($conexion, $_POST['tecnico']);
        $fecha_ingreso = mysqli_real_escape_string($conexion, $_POST['fecha_ingreso']);
        $fecha_salida = mysqli_real_escape_string($conexion, $_POST['fecha_salida']);

        // Actualizamos los datos del parte
        $sql_update = mysqli_query($conexion, "UPDATE registro_partes SET tipo_id = '$tipo', placa = '$placa', serial = '$serial', proveedor_id = '$proveedor', marca = '$marca', modelo = '$modelo', especificacion = '$especificacion', estado_id = '$estado', cliente_id = '$cliente', descripcion = '$descripcion', tecnico_id = '$tecnico', fecha_ingreso = '$fecha_ingreso', fecha_salida = '$fecha_salida' WHERE id = $id");
        
        // Verificamos si la actualización fue exitosa
        if ($sql_update) {
            $_SESSION['message'] = 'Parte modificado con éxito';
            header('Location: registro_partes.php');
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al modificar el parte: ' . mysqli_error($conexion) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
    }
}

// Obtener datos para los campos de selección
$tipos = mysqli_query($conexion, "SELECT id, nombre FROM tipo");
$proveedores = mysqli_query($conexion, "SELECT id, nombre FROM proveedores");
$estados = mysqli_query($conexion, "SELECT id, nombre FROM estado");
$clientes = mysqli_query($conexion, "SELECT id, nombre FROM clientes");
$tecnicos = mysqli_query($conexion, "SELECT id, nombre FROM tecnico");

// Cerrar la conexión al finalizar
mysqli_close($conexion);

include_once "includes/header.php";
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($alert) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo" class="text-dark font-weight-bold">Tipo</label>
                                <select name="tipo" id="tipo" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php while ($tipo = mysqli_fetch_assoc($tipos)) { ?>
                                        <option value="<?php echo $tipo['id']; ?>" <?php echo ($tipo['id'] == $data['tipo_id']) ? 'selected' : ''; ?>><?php echo $tipo['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="placa" class="text-dark font-weight-bold">Placa</label>
                                <input type="text" placeholder="Ingrese placa" name="placa" id="placa" class="form-control" value="<?php echo $data['placa']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="serial" class="text-dark font-weight-bold">Serial</label>
                                <input type="text" placeholder="Ingrese serial" name="serial" id="serial" class="form-control" value="<?php echo $data['serial']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="proveedor" class="text-dark font-weight-bold">Proveedor</label>
                                <select name="proveedor" id="proveedor" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php while ($proveedor = mysqli_fetch_assoc($proveedores)) { ?>
                                        <option value="<?php echo $proveedor['id']; ?>" <?php echo ($proveedor['id'] == $data['proveedor_id']) ? 'selected' : ''; ?>><?php echo $proveedor['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="marca" class="text-dark font-weight-bold">Marca</label>
                                <input type="text" placeholder="Ingrese marca" name="marca" id="marca" class="form-control" value="<?php echo $data['marca']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modelo" class="text-dark font-weight-bold">Modelo</label>
                                <input type="text" placeholder="Ingrese modelo" name="modelo" id="modelo" class="form-control" value="<?php echo $data['modelo']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="especificacion" class="text-dark font-weight-bold">Especificación</label>
                                <input type="text" placeholder="Ingrese especificación" name="especificacion" id="especificacion" class="form-control" value="<?php echo $data['especificacion']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="estado" class="text-dark font-weight-bold">Estado</label>
                                <select name="estado" id="estado" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php while ($estado = mysqli_fetch_assoc($estados)) { ?>
                                        <option value="<?php echo $estado['id']; ?>" <?php echo ($estado['id'] == $data['estado_id']) ? 'selected' : ''; ?>><?php echo $estado['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cliente" class="text-dark font-weight-bold">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php while ($cliente = mysqli_fetch_assoc($clientes)) { ?>
                                        <option value="<?php echo $cliente['id']; ?>" <?php echo ($cliente['id'] == $data['cliente_id']) ? 'selected' : ''; ?>><?php echo $cliente['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descripcion" class="text-dark font-weight-bold">Descripción</label>
                                <input type="text" placeholder="Ingrese descripción" name="descripcion" id="descripcion" class="form-control" value="<?php echo $data['descripcion']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tecnico" class="text-dark font-weight-bold">Técnico</label>
                                <select name="tecnico" id="tecnico" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php while ($tecnico = mysqli_fetch_assoc($tecnicos)) { ?>
                                        <option value="<?php echo $tecnico['id']; ?>" <?php echo ($tecnico['id'] == $data['tecnico_id']) ? 'selected' : ''; ?>><?php echo $tecnico['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_ingreso" class="text-dark font-weight-bold">Fecha Ingreso</label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="<?php echo $data['fecha_ingreso']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_salida" class="text-dark font-weight-bold">Fecha Salida</label>
                                <input type="date" name="fecha_salida" id="fecha_salida" class="form-control" value="<?php echo $data['fecha_salida']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Actualizar Parte</button>
                        <a href="registro_partes.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>
