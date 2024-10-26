<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "proveedores";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}


$alert = "";

// Procesamos el formulario de entrada
if (!empty($_POST)) {
    if (empty($_POST['nombre']) || empty($_POST['direccion']) || empty($_POST['telefono']) || empty($_POST['correo'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Todos los campos son obligatorios
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];

        // Verificamos si el cliente ya existe
        $query = mysqli_query($conexion, "SELECT * FROM proveedores WHERE nombre = '$nombre'");
        $result = mysqli_fetch_array($query);
        
        if ($result > 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El proveedor ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            // Insertamos el nuevo cliente
            $query_insert = mysqli_query($conexion, "INSERT INTO proveedores (nombre, direccion, telefono, correo) VALUES ('$nombre', '$direccion', '$telefono', '$correo')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Proveedor registrado
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error al registrar
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            }
        }
    }
}

// Cerrar la conexión al finalizar
mysqli_close($conexion);
include_once "includes/header.php";
?>
<!-- Botón para abrir el modal -->
<div >
    <button class="btn btn-success" id="btnAgregar" data-toggle="modal" data-target="#exampleModal">
        Agregar <i class="fas fa-plus"></i>
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="form-group">
                        <label for="nombre" class="text-dark font-weight-bold">Nombre</label>
                        <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="text-dark font-weight-bold">Dirección</label>
                        <input type="text" placeholder="Ingrese Dirección" name="direccion" id="direccion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono" class="text-dark font-weight-bold">Teléfono</label>
                        <input type="number" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo" class="text-dark font-weight-bold">Correo</label>
                        <input type="email" placeholder="Ingrese correo" name="correo" id="correo" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarDatos()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <div class="text-center mt-3">
                    <a class="btn btn-primary" href="importar_clientes.php"><i class="fas fa-file-upload"></i></a>
                    <a class="btn btn-success" href="exportar_clientes.php"><i class="fas fa-file-download"></i></a>
                </div>

                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="tbl">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre de la empresa</th>
                                    <th>Dirección</th>
                                    <th>Número Teléfono</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../conexion.php";
                                $query = mysqli_query($conexion, "SELECT * FROM proveedores");
                                $result = mysqli_num_rows($query);
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_assoc($query)) { ?>
                                        <tr>
                                            <td><?php echo $data['id']; ?></td>
                                            <td><?php echo $data['nombre']; ?></td>
                                            <td><?php echo $data['direccion']; ?></td>
                                            <td><?php echo $data['telefono']; ?></td>
                                            <td><?php echo $data['correo']; ?></td>
                                            <td>
                                                <a href="editar_proveedores.php?id=<?php echo $data['id']; ?>" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                                <form action="eliminar_proveedor.php?id=<?php echo $data['id']; ?>" method="post" class="d-inline confirmar">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i></button>
                                            </form>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>

<script>
function guardarDatos() {
    var form = document.getElementById('formulario');
    if (form.checkValidity()) {
        form.submit(); // Enviar el formulario
    } else {
        alert("Por favor completa todos los campos.");
    }
}
</script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">