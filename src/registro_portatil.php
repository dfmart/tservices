<?php
session_start();
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "registro_portatiles";
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
    $procesador = mysqli_real_escape_string($conexion, $_POST['procesador']);
    $tipmemoria = mysqli_real_escape_string($conexion, $_POST['tipmemoria']);
    $tammemoria = mysqli_real_escape_string($conexion, $_POST['tammemoria']);
    $nummodulo = mysqli_real_escape_string($conexion, $_POST['nummodulo']);
    $tipdisco = mysqli_real_escape_string($conexion, $_POST['tipdisco']);
    $tamano = mysqli_real_escape_string($conexion, $_POST['tamano']);
    $bateria = mysqli_real_escape_string($conexion, $_POST['bateria']);
    $nota = mysqli_real_escape_string($conexion, $_POST['nota']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    $cliente = mysqli_real_escape_string($conexion, $_POST['cliente']);
    $tecnico = mysqli_real_escape_string($conexion, $_POST['tecnico']);
    $fecha_ingreso = mysqli_real_escape_string($conexion, $_POST['fecha_ingreso']);
    $fecha_salida = mysqli_real_escape_string($conexion, $_POST['fecha_salida']);

    $imagePaths = []; // Inicializa la variable para almacenar las rutas de las imágenes
    $imageErrors = []; // Inicializa la variable para almacenar los errores de imagen
    
    foreach ($_FILES['imagen']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['imagen']['error'][$key] === UPLOAD_ERR_OK) {
            $imagen_nombre = $_FILES['imagen']['name'][$key];
            $imagen_tmp = $_FILES['imagen']['tmp_name'][$key];
            $ruta_imagen = __DIR__ . '/../uploads/' . basename($imagen_nombre); // Asegúrate que la ruta sea correcta
    
            // Validar que el archivo es una imagen
            if (getimagesize($imagen_tmp) !== false) {
                if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
                    $imagePaths[] = $ruta_imagen; // Guardar la ruta de la imagen
                } else {
                    $imageErrors[] = "Error al subir la imagen: $imagen_nombre.";
                }
            } else {
                $imageErrors[] = "$imagen_nombre no es una imagen válida.";
            }
        } else {
            $imageErrors[] = "Error en el archivo: " . $_FILES['imagen']['error'][$key];
        }
    }
    
    // Mostrar errores, si los hay
    if (!empty($imageErrors)) {
        $alert .= '<div class="alert alert-danger">' . implode('<br>', $imageErrors) . '</div>';
    }
    
    // Procesar $imagePaths si necesitas hacer algo con las rutas de las imágenes
    if (!empty($imagePaths)) {
        // Aquí puedes realizar operaciones con las rutas de las imágenes
    }
    





    // Verificar si la placa ya existe
    $query = mysqli_query($conexion, "SELECT * FROM registro_portatil WHERE placa = '$placa'");
    if (mysqli_num_rows($query) > 0) {
        $alert .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">
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
            $alert .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Uno o más IDs no existen en la base de datos.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        } else {
            // Insertar en la base de datos
            $query_insert = mysqli_query($conexion, "INSERT INTO registro_portatil (placa, serial, proveedor_id, marca, modelo, procesador, tipmemoria, tammemoria, nummodulo, tipdisco, tamano, bateria, nota, estado_id, cliente_id, tecnico_id, fecha_ingreso, fecha_salida) 
                VALUES ('$placa', '$serial', '$proveedor', '$marca', '$modelo', '$procesador', '$tipmemoria', '$tammemoria', '$nummodulo', '$tipdisco', '$tamano', '$bateria', '$nota', '$estado', '$cliente', '$tecnico', '$fecha_ingreso', '$fecha_salida')");

            if ($query_insert) {
                // Guardar las rutas de las imágenes si es necesario
                foreach ($imagePaths as $path) {
                    mysqli_query($conexion, "INSERT INTO imagen_portatil (registro_id, ruta) VALUES (LAST_INSERT_ID(), '$path')");
                }
                
                $alert .= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Registrado con éxito
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            } else {
                $alert .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                <h5 class="modal-title" id="exampleModalLabel">Agregar Registro Portátil</h5>
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
                                <input type="text" placeholder="Ingrese placa" name="placa" id="placa" class="form-control" >
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
                                <select name="proveedor" id="proveedor" class="form-control" >
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
                                <input type="text" placeholder="Ingrese marca" name="marca" id="marca" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modelo" class="text-dark font-weight-bold">Modelo</label>
                                <input type="text" placeholder="Ingrese modelo" name="modelo" id="modelo" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="procesador" class="text-dark font-weight-bold">Procesador</label>
                                <input type="text" placeholder="Ingrese procesador" name="procesador" id="procesador" class="form-control" >
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipmemoria" class="text-dark font-weight-bold">Tipo Memoria</label>
                                <select name="tipmemoria" id="tipmemoria" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <option value="DDR3">DDR3</option>
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tammemoria" class="text-dark font-weight-bold">Tamaño Memoria</label>
                                <select name="tammemoria" id="tammemoria" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <option value="4GB">4GB</option>
                                    <option value="8GB">8GB</option>
                                    <option value="16GB">16GB</option>
                                    <option value="32GB">32GB</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nummodulo" class="text-dark font-weight-bold">Número Módulo</label>
                                <select name="nummodulo" id="nummodulo" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipdisco" class="text-dark font-weight-bold">Tipo Disco</label>
                                <select name="tipdisco" id="tipdisco" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <option value="HDD">HDD</option>
                                    <option value="SSD">SSD</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tamano" class="text-dark font-weight-bold">Tamaño</label>
                                <select name="tamano" id="tamano" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <option value="256GB">256GB</option>
                                    <option value="512GB">512GB</option>
                                    <option value="1TB">1TB</option>
                                    <option value="2TB">2TB</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bateria" class="text-dark font-weight-bold">Batería</label>
                                <input type="text" placeholder="Ingrese batería" name="bateria" id="bateria" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado" class="text-dark font-weight-bold">Estado</label>
                                <select name="estado" id="estado" class="form-control" >
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
                                <select name="cliente" id="cliente" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <?php while ($cliente = mysqli_fetch_assoc($clientes)) { ?>
                                        <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tecnico" class="text-dark font-weight-bold">Técnico</label>
                                <select name="tecnico" id="tecnico" class="form-control" >
                                    <option value="">Seleccione</option>
                                    <?php while ($tecnico = mysqli_fetch_assoc($tecnicos)) { ?>
                                        <option value="<?php echo $tecnico['id']; ?>"><?php echo $tecnico['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_ingreso" class="text-dark font-weight-bold">Fecha Ingreso</label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_salida" class="text-dark font-weight-bold">Fecha Salida</label>
                                <input type="date" name="fecha_salida" id="fecha_salida" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nota" class="text-dark font-weight-bold">Nota</label>
                                <textarea name="nota" id="nota" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="imagen" class="text-dark font-weight-bold">Imágenes</label>
                                <div class="custom-file">
                                    <input type="file" name="imagen[]" id="imagen" class="custom-file-input" accept="imagen/*" multiple >
                                    <label class="custom-file-label" for="imagen">Seleccionar imágenes</label>
                                </div>
                            </div>
                            <?php if (isset($alert) && $alert != "") { echo $alert; } ?>



<script>
    document.getElementById('imagen').addEventListener('change', function() {
        const fileNames = Array.from(this.files).map(file => file.name).join(', ');
        const nextSibling = this.nextElementSibling;
        nextSibling.innerText = fileNames;
    });
</script>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="btnGuardar" class="text-dark font-weight-bold" style="visibility: hidden;">Guardar</label>
                                <button type="submit" id="btnGuardar" class="btn btn-primary btn-block">Guardar</button>
                            </div>
                        </div>  
                        <div class="modal-footer">            
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      </div>
                      </div>
                        
                    </div>
                </form>
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
                    <a class="btn btn-primary" href="importar_portatil.php"><i class="fas fa-file-upload"></i></a>
                    <a class="btn btn-success" href="exportar_portatil.php"><i class="fas fa-file-download"></i></a>
                </div>

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
                                    <th>Procesador</th>
                                    <th>Tipo Memoria</th>
                                    <th>Tamaño Memoria</th>
                                    <th>Número Módulo</th>
                                    <th>Tipo Disco</th>
                                    <th>Tamaño</th>
                                    <th>Batería</th>
                                    <th>Nota</th>
                                    <th>Estado</th>
                                    <th>Cliente</th>
                                    <th>Técnico</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Fecha Salida</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../conexion.php";
                                $query = mysqli_query($conexion, "SELECT r.*, p.nombre AS proveedor_nombre, e.nombre AS estado_nombre, c.nombre AS cliente_nombre, te.nombre AS tecnico_nombre FROM registro_portatil r
                                LEFT JOIN proveedores p ON r.proveedor_id = p.id
                                LEFT JOIN estado e ON r.estado_id = e.id
                                LEFT JOIN clientes c ON r.cliente_id = c.id
                                LEFT JOIN tecnico te ON r.tecnico_id = te.id");
                                $result = mysqli_num_rows($query);
                                
                                if ($result > 0) {
                                    while ($data = mysqli_fetch_assoc($query)) { ?>
                                        <tr>
                                      
                                            <td><?php echo $data['id']; ?></td>
                                            <td><?php echo $data['placa']; ?></td>
                                            <td><?php echo $data['serial']; ?></td>
                                            <td><?php echo $data['proveedor_nombre']; ?></td>
                                            <td><?php echo $data['marca']; ?></td>
                                            <td><?php echo $data['modelo']; ?></td>
                                            <td><?php echo $data['procesador']; ?></td>
                                            <td><?php echo $data['tipmemoria']; ?></td>
                                            <td><?php echo $data['tammemoria']; ?></td>
                                            <td><?php echo $data['nummodulo']; ?></td>
                                            <td><?php echo $data['tipdisco']; ?></td>
                                            <td><?php echo $data['tamano']; ?></td>
                                            <td><?php echo $data['bateria']; ?></td>
                                            <td><?php echo $data['nota']; ?></td>
                                            <td><?php echo $data['estado_nombre']; ?></td>
                                            <td><?php echo $data['cliente_nombre']; ?></td>
                                            <td><?php echo $data['tecnico_nombre']; ?></td>
                                            <td><?php echo $data['fecha_ingreso']; ?></td>
                                            <td><?php echo $data['fecha_salida']; ?></td>
                                            <td>
                                                <a href="editar_portatil.php?id=<?php echo $data['id']; ?>" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                                <form action="eliminar_portatil.php?id=<?php echo $data['id']; ?>" method="post" class="d-inline confirmar">
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

<?php include_once "includes/footer.php"; ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Mostrar alertas -->
