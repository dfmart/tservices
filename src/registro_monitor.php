<?php
session_start();
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "registro_monitore";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql, MYSQLI_ASSOC);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alert = "";
    $placa = mysqli_real_escape_string($conexion, $_POST['placa']);
    $serial = mysqli_real_escape_string($conexion, $_POST['serial']);
    $proveedor = mysqli_real_escape_string($conexion, $_POST['proveedor']);
    $marca = mysqli_real_escape_string($conexion, $_POST['marca']);
    $modelo = mysqli_real_escape_string($conexion, $_POST['modelo']);
    $nota = mysqli_real_escape_string($conexion, $_POST['nota']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    $cliente = mysqli_real_escape_string($conexion, $_POST['cliente']);
    $tecnico = mysqli_real_escape_string($conexion, $_POST['tecnico']);
    $fecha_ingreso = mysqli_real_escape_string($conexion, $_POST['fecha_ingreso']);
    $fecha_salida = mysqli_real_escape_string($conexion, $_POST['fecha_salida']);
    
    $imagePaths = [];
    $imageErrors = [];

    // Procesar imagen
    foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
            $imagen_nombre = $_FILES['imagenes']['name'][$key];
            $imagen_tmp = $_FILES['imagenes']['tmp_name'][$key];
            $ruta_imagen = __DIR__ . '/../uploads/' . basename($imagen_nombre);

            if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
                $imagePaths[] = $ruta_imagen;
            } else {
                $imageErrors[] = "Error al subir la imagen: $imagen_nombre.";
            }
        } else {
            $imageErrors[] = "Error en el archivo: " . $_FILES['imagenes']['error'][$key];
        }
    }

    if (!empty($imageErrors)) {
        $alert .= '<div class="alert alert-danger">' . implode('<br>', $imageErrors) . '</div>';
    }

    // Verificar si la placa ya existe
    $query = mysqli_query($conexion, "SELECT * FROM registro_monitor WHERE placa = '$placa'");
    if (mysqli_num_rows($query) > 0) {
        $alert = '<div class="alert alert-warning">La placa ya existe</div>';
    } else {
        // Verificar si los IDs existen en sus respectivas tablas
        $proveedor_exists = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = '$proveedor'");
        $estado_exists = mysqli_query($conexion, "SELECT * FROM estado WHERE id = '$estado'");
        $cliente_exists = mysqli_query($conexion, "SELECT * FROM clientes WHERE id = '$cliente'");
        $tecnico_exists = mysqli_query($conexion, "SELECT * FROM tecnico WHERE id = '$tecnico'");

        if (mysqli_num_rows($proveedor_exists) == 0 || mysqli_num_rows($estado_exists) == 0 || mysqli_num_rows($cliente_exists) == 0 || mysqli_num_rows($tecnico_exists) == 0) {
            $alert = '<div class="alert alert-danger">Uno o más IDs no existen en la base de datos.</div>';
        } else {
            // Insertar en la base de datos
            $query_insert = mysqli_query($conexion, "INSERT INTO registro_monitor (placa, serial, proveedor_id, marca, modelo, nota, estado_id, cliente_id, tecnico_id, fecha_ingreso, fecha_salida, imagen) 
                VALUES ('$placa', '$serial', '$proveedor', '$marca', '$modelo', '$nota', '$estado', '$cliente', '$tecnico', '$fecha_ingreso', '$fecha_salida', '" . implode(',', $imagePaths) . "')");

            if ($query_insert) {
                $alert = '<div class="alert alert-success">Registrado con éxito</div>';
            } else {
                $alert = '<div class="alert alert-danger">Error al registrar: ' . mysqli_error($conexion) . '</div>';
            }
        }
    }
}

// Obtener datos para los campos de selección
$proveedores = mysqli_query($conexion, "SELECT id, nombre FROM proveedores");
$estados = mysqli_query($conexion, "SELECT id, nombre FROM estado");
$clientes = mysqli_query($conexion, "SELECT id, nombre FROM clientes");
$tecnicos = mysqli_query($conexion, "SELECT id, nombre FROM tecnico");

// Obtener los registros de monitores
$monitores = mysqli_query($conexion, "SELECT rm.*, p.nombre as proveedor_nombre, e.nombre as estado_nombre FROM registro_monitor rm 
    INNER JOIN proveedores p ON rm.proveedor_id = p.id 
    INNER JOIN estado e ON rm.estado_id = e.id 
    ORDER BY rm.id DESC");

// Cerrar la conexión al finalizar
mysqli_close($conexion);

include_once "includes/header.php";
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Registro de Monitores</h2>
            <button class="btn btn-success" id="btnAgregar" data-toggle="modal" data-target="#exampleModal">
                Agregar <i class="fas fa-plus"></i>
            </button>

            <!-- Mensajes de alerta -->
            <?php if (!empty($alert)) echo $alert; ?>

            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Serial</th>
                        <th>Proveedor</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Estado</th>
                        <th>Fecha Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($monitor = mysqli_fetch_assoc($monitores)) { ?>
                        <tr>
                            <td><?php echo $monitor['id']; ?></td>
                            <td><?php echo $monitor['placa']; ?></td>
                            <td><?php echo $monitor['serial']; ?></td>
                            <td><?php echo $monitor['proveedor_nombre']; ?></td>
                            <td><?php echo $monitor['marca']; ?></td>
                            <td><?php echo $monitor['modelo']; ?></td>
                            <td><?php echo $monitor['estado_nombre']; ?></td>
                            <td><?php echo $monitor['fecha_ingreso']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm">Editar</button>
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1000px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Registro Monitor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" id="formulario">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="placa" class="text-dark font-weight-bold">Placa</label>
                                    <input type="text" placeholder="Ingrese placa" name="placa" id="placa" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="serial" class="text-dark font-weight-bold">Serial</label>
                                    <input type="text" placeholder="Ingrese serial" name="serial" id="serial" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="proveedor" class="text-dark font-weight-bold">Proveedor</label>
                                    <select name="proveedor" id="proveedor" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <?php while ($proveedor = mysqli_fetch_assoc($proveedores)) { ?>
                                            <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="marca" class="text-dark font-weight-bold">Marca</label>
                                    <input type="text" placeholder="Ingrese marca" name="marca" id="marca" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="modelo" class="text-dark font-weight-bold">Modelo</label>
                                    <input type="text" placeholder="Ingrese modelo" name="modelo" id="modelo" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nota" class="text-dark font-weight-bold">Nota</label>
                                    <textarea placeholder="Ingrese nota" name="nota" id="nota" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estado" class="text-dark font-weight-bold">Estado</label>
                                    <select name="estado" id="estado" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <?php while ($estado = mysqli_fetch_assoc($estados)) { ?>
                                            <option value="<?php echo $estado['id']; ?>"><?php echo $estado['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cliente" class="text-dark font-weight-bold">Cliente</label>
                                    <select name="cliente" id="cliente" class="form-control">
                                        <option value="">Seleccione</option>
                                        <?php while ($cliente = mysqli_fetch_assoc($clientes)) { ?>
                                            <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tecnico" class="text-dark font-weight-bold">Técnico</label>
                                    <select name="tecnico" id="tecnico" class="form-control">
                                        <option value="">Seleccione</option>
                                        <?php while ($tecnico = mysqli_fetch_assoc($tecnicos)) { ?>
                                            <option value="<?php echo $tecnico['id']; ?>"><?php echo $tecnico['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_ingreso" class="text-dark font-weight-bold">Fecha de Ingreso</label>
                                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_salida" class="text-dark font-weight-bold">Fecha de Salida</label>
                                    <input type="date" name="fecha_salida" id="fecha_salida" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="imagenes" class="text-dark font-weight-bold">Imágenes</label>
                                    <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>
