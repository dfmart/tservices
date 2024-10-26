<?php
session_start();
include "../conexion.php";

// Use prepared statements to prevent SQL injection
$id_user = $_SESSION['idUser'];
$permiso = "permiso";
$sql = mysqli_prepare($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?");
mysqli_stmt_bind_param($sql, "is", $id_user, $permiso);
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);
$existe = mysqli_fetch_all($result, MYSQLI_ASSOC);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
    exit();
}

$alert = "";

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    if (empty($nombre)) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Todos los campos son obligatorios
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        // Check if the permission already exists
        $query = mysqli_prepare($conexion, "SELECT * FROM permisos WHERE nombre = ?");
        mysqli_stmt_bind_param($query, "s", $nombre);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);

        if (mysqli_num_rows($result) > 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El permiso ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            // Insert the new permission
            $query_insert = mysqli_prepare($conexion, "INSERT INTO permisos(nombre) VALUES (?)");
            mysqli_stmt_bind_param($query_insert, "s", $nombre);
            if (mysqli_stmt_execute($query_insert)) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Permiso registrado
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

// Close the connection at the end
mysqli_close($conexion);

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
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-5 mt-3">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <?php
                            // Display the update message if it exists
                            if (isset($_SESSION['message'])) {
                                echo '<div class="alert alert-info alert-dismissible fade show" role="alert">' . $_SESSION['message'] . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>';
                                unset($_SESSION['message']); // Clear the session message
                            }
                            ?>
                            <tr>
                                <th>Id</th>
                                <th>Nombre permiso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";
                            $query = mysqli_query($conexion, "SELECT * FROM permisos");
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['id']; ?></td>
                                    <td><?php echo $data['nombre']; ?></td>
                                    <td>
                                        <a href="editar_permiso.php?id=<?php echo $data['id']; ?>" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                        <form action="eliminar_permiso.php?id=<?php echo $data['id']; ?>" method="post" class="d-inline confirmar">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

<script>
    function limpiar() {
        document.getElementById('formulario').reset();
    }
</script>
