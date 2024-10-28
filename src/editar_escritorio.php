<?php
session_start();
include "../conexion.php";

// Check user permissions
$id_user = $_SESSION['idUser'];
$permiso = "registro_escritorio";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p 
    INNER JOIN detalle_permisos d ON p.id = d.id_permiso 
    WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql, MYSQLI_ASSOC);

if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
    exit();
}

// Initialize alert variable
$alert = "";

// Check if ID is set for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conexion, "SELECT * FROM registro_escritorio WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        header('Location: registro_escritorio.php');
        exit();
    }
}

// Process the edit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['placa', 'proveedor', 'estado', 'cliente', 'tecnico'];
    $empty_fields = array_filter($required_fields, function($field) {
        return empty($_POST[$field]);
    });

    if (!empty($empty_fields)) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Todos los campos son obligatorios
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        // Prepare data for update
        $id = $_POST['id'];
        $placa = mysqli_real_escape_string($conexion, $_POST['placa']);
        $serial = mysqli_real_escape_string($conexion, $_POST['serial']);
        $proveedor = mysqli_real_escape_string($conexion, $_POST['proveedor']);
        $marca = mysqli_real_escape_string($conexion, $_POST['marca']);
        $modelo = mysqli_real_escape_string($conexion, $_POST['modelo']);
        $procesador = mysqli_real_escape_string($conexion, $_POST['procesador']);
        $tipmemoria = mysqli_real_escape_string($conexion, $_POST['tipmemoria']);
        $tammemoria = mysqli_real_escape_string($conexion, $_POST['tammemoria']);
        $nummodulo = intval($_POST['nummodulo']);
        $tipdisco = mysqli_real_escape_string($conexion, $_POST['tipdisco']);
        $tamano = mysqli_real_escape_string($conexion, $_POST['tamano']);
        $bateria = mysqli_real_escape_string($conexion, $_POST['bateria']);
        $nota = mysqli_real_escape_string($conexion, $_POST['nota']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        $cliente = mysqli_real_escape_string($conexion, $_POST['cliente']);
        $tecnico = mysqli_real_escape_string($conexion, $_POST['tecnico']);
        $fecha_ingreso = mysqli_real_escape_string($conexion, $_POST['fecha_ingreso']);
        $fecha_salida = mysqli_real_escape_string($conexion, $_POST['fecha_salida']);
        $imagen = mysqli_real_escape_string($conexion, $_POST['imagen']); 

        // Update the record
        $sql_update = mysqli_query($conexion, "UPDATE registro_escritorio SET 
            placa = '$placa', 
            serial = '$serial', 
            proveedor_id = '$proveedor', 
            marca = '$marca', 
            modelo = '$modelo', 
            procesador = '$procesador', 
            tipmemoria = '$tipmemoria', 
            tammemoria = '$tammemoria', 
            nummodulo = $nummodulo, 
            tipdisco = '$tipdisco', 
            tamano = '$tamano', 
            bateria = '$bateria', 
            nota = '$nota', 
            estado_id = '$estado', 
            cliente_id = '$cliente', 
            tecnico_id = '$tecnico', 
            fecha_ingreso = '$fecha_ingreso', 
            fecha_salida = '$fecha_salida', 
            imagen = '$imagen' 
            WHERE id = $id");
        
        if ($sql_update) {
            $_SESSION['message'] = 'Escritorio modificado con éxito';
            header('Location: registro_escritorio.php');
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al modificar el escritorio: ' . mysqli_error($conexion) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
    }
}

// Get data for dropdowns
$proveedores = mysqli_query($conexion, "SELECT id, nombre FROM proveedores");
$estados = mysqli_query($conexion, "SELECT id, nombre FROM estado");
$clientes = mysqli_query($conexion, "SELECT id, nombre FROM clientes");
$tecnicos = mysqli_query($conexion, "SELECT id, nombre FROM tecnico");

// Close connection
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
                                <label for="placa" class="text-dark font-weight-bold">Placa</label>
                                <input type="text" placeholder="Ingrese placa" name="placa" id="placa" class="form-control" value="<?php echo $data['placa']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="serial" class="text-dark font-weight-bold">Serial</label>
                                <input type="text" placeholder="Ingrese serial" name="serial" id="serial" class="form-control" value="<?php echo $data['serial']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="marca" class="text-dark font-weight-bold">Marca</label>
                                <input type="text" placeholder="Ingrese marca" name="marca" id="marca" class="form-control" value="<?php echo $data['marca']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modelo" class="text-dark font-weight-bold">Modelo</label>
                                <input type="text" placeholder="Ingrese modelo" name="modelo" id="modelo" class="form-control" value="<?php echo $data['modelo']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="procesador" class="text-dark font-weight-bold">Procesador</label>
                                <input type="text" placeholder="Ingrese procesador" name="procesador" id="procesador" class="form-control" value="<?php echo $data['procesador']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipmemoria" class="text-dark font-weight-bold">Tipo de Memoria</label>
                                <input type="text" placeholder="Ingrese tipo de memoria" name="tipmemoria" id="tipmemoria" class="form-control" value="<?php echo $data['tipmemoria']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tammemoria" class="text-dark font-weight-bold">Tamaño de Memoria</label>
                                <input type="text" placeholder="Ingrese tamaño de memoria" name="tammemoria" id="tammemoria" class="form-control" value="<?php echo $data['tammemoria']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nummodulo" class="text-dark font-weight-bold">Número de Módulo</label>
                                <input type="number" placeholder="Ingrese número de módulo" name="nummodulo" id="nummodulo" class="form-control" value="<?php echo $data['nummodulo']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipdisco" class="text-dark font-weight-bold">Tipo de Disco</label>
                                <input type="text" placeholder="Ingrese tipo de disco" name="tipdisco" id="tipdisco" class="form-control" value="<?php echo $data['tipdisco']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tamano" class="text-dark font-weight-bold">Tamaño</label>
                                <input type="text" placeholder="Ingrese tamaño" name="tamano" id="tamano" class="form-control" value="<?php echo $data['tamano']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bateria" class="text-dark font-weight-bold">Batería</label>
                                <input type="text" placeholder="Ingrese batería" name="bateria" id="bateria" class="form-control" value="<?php echo $data['bateria']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                                <label for="tecnico" class="text-dark font-weight-bold">Técnico</label>
                                <select name="tecnico" id="tecnico" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <?php while ($tecnico = mysqli_fetch_assoc($tecnicos)) { ?>
                                        <option value="<?php echo $tecnico['id']; ?>" <?php echo ($tecnico['id'] == $data['tecnico_id']) ? 'selected' : ''; ?>><?php echo $tecnico['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_ingreso" class="text-dark font-weight-bold">Fecha Ingreso</label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="<?php echo $data['fecha_ingreso']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_salida" class="text-dark font-weight-bold">Fecha Salida</label>
                                <input type="date" name="fecha_salida" id="fecha_salida" class="form-control" value="<?php echo $data['fecha_salida']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nota" class="text-dark font-weight-bold">Nota</label>
                                <input type="text" placeholder="Ingrese nota" name="nota" id="nota" class="form-control" value="<?php echo $data['nota']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="imagen" class="text-dark font-weight-bold">Imagen</label>
                                <input type="text" placeholder="Ingrese URL de la imagen" name="imagen" id="imagen" class="form-control" value="<?php echo $data['imagen']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="registro_escritorio.php" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
