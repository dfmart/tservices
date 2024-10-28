<?php
session_start();
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "registro_monitor";
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
    
    // Verificar si la placa ya existe
    $query = mysqli_query($conexion, "SELECT * FROM registro_monitor WHERE placa = '$placa'");
    if (mysqli_num_rows($query) > 0) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            La placa ya existe
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
    } else {
        // Verificar si los IDs existen en sus respectivas tablas
        $proveedor_exists = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = '$proveedor'");
        $estado_exists = mysqli_query($conexion, "SELECT * FROM estado WHERE id = '$estado'");
        $cliente_exists = mysqli_query($conexion, "SELECT * FROM clientes WHERE id = '$cliente'");
        $tecnico_exists = mysqli_query($conexion, "SELECT * FROM tecnico WHERE id = '$tecnico'");

        if (mysqli_num_rows($proveedor_exists) == 0 || mysqli_num_rows($estado_exists) == 0 || mysqli_num_rows($cliente_exists) == 0 || mysqli_num_rows($tecnico_exists) == 0) {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Uno o más IDs no existen en la base de datos.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        } else {
            // Insertar en la base de datos
            $query_insert = mysqli_query($conexion, "INSERT INTO registro_monitor (placa, serial, proveedor_id, marca, modelo, nota, estado_id, cliente_id, tecnico_id, fecha_ingreso, fecha_salida) 
                VALUES ('$placa', '$serial', '$proveedor', '$marca', '$modelo', '$nota', '$estado', '$cliente', '$tecnico', '$fecha_ingreso', '$fecha_salida')");

            if ($query_insert) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Registrado con éxito
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error al registrar: ' . mysqli_error($conexion) . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }
}

// Obtener datos para los campos de selección
$proveedores = mysqli_query($conexion, "SELECT id, nombre FROM proveedores");
$estados = mysqli_query($conexion, "SELECT id, nombre FROM estado");
$clientes = mysqli_query($conexion, "SELECT id, nombre FROM clientes");
$tecnicos = mysqli_query($conexion, "SELECT id, nombre FROM tecnico");

// Cerrar la conexión al finalizar
mysqli_close($conexion);

include_once "includes/header.php";
?>

<!-- Botón para abrir el modal -->
<div>
    <button class="btn btn-success" id="btnAgregar" data-toggle="modal" data-target="#exampleModal">
        Agregar <i class="fas fa-plus"></i>
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Registro</h5>
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
                                <input type="text" placeholder="Ingrese serial" name="serial" id="serial" class="form-control" required>
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
                                <input type="text" placeholder="Ingrese marca" name="marca" id="marca" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modelo" class="text-dark font-weight-bold">Modelo</label>
                                <input type="text" placeholder="Ingrese modelo" name="modelo" id="modelo" class="form-control">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
                <?php echo (isset($alert)) ? $alert : ''; ?>
            </div>
        </div>
    </div>
</div>


<!-- Exportar, importar-->
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <div class="text-center mt-3">
                    <a class="btn btn-primary" href="importar_monitor.php"><i class="fas fa-file-upload"></i></a>
                    <a class="btn btn-success" href="exportar_monitor.php"><i class="fas fa-file-download"></i></a>
                </div>

<!-- Tabla para mostrar registros -->
<div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="tbl">
                            <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Placa</th>
                <th>Serial</th>
                <th>Proveedor</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Nota</th>
                <th>Estado</th>
                <th>Cliente</th>
                <th>Técnico</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Salida</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";
            $query = mysqli_query($conexion, "SELECT r.*, p.nombre as proveedor_nombre, e.nombre as estado_nombre, c.nombre as cliente_nombre, t.nombre as tecnico_nombre FROM registro_monitor r
            LEFT JOIN proveedores p ON r.proveedor_id = p.id
            LEFT JOIN estado e ON r.estado_id = e.id
            LEFT JOIN clientes c ON r.cliente_id = c.id
            LEFT JOIN tecnico t ON r.tecnico_id = t.id");
            while ($data = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo $data['placa']; ?></td>
                    <td><?php echo $data['serial']; ?></td>
                    <td><?php echo $data['proveedor_nombre']; ?></td>
                    <td><?php echo $data['marca']; ?></td>
                    <td><?php echo $data['modelo']; ?></td>
                    <td><?php echo $data['nota']; ?></td>
                    <td><?php echo $data['estado_nombre']; ?></td>
                    <td><?php echo $data['cliente_nombre']; ?></td>
                    <td><?php echo $data['tecnico_nombre']; ?></td>
                    <td><?php echo $data['fecha_ingreso']; ?></td>
                    <td><?php echo $data['fecha_salida']; ?></td>
                    <td>
                    <a href="editar_monitor.php?id=<?php echo $data['id']; ?>" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                <form action="eliminar_monitor.php?id=<?php echo $data['id']; ?>" method="post" class="d-inline confirmar">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i></button>
                                </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include_once "includes/footer.php"; ?>

<script>
function editarRegistro(element) {
    // Funcionalidad para editar registro
}

function eliminarRegistro(element) {
    const id = element.getAttribute('data-id');
    if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        // Función AJAX para eliminar
        // ...
    }
}
</script>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
