<?php
session_start();
include "../conexion.php";

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Verificación de permisos
$id_user = $_SESSION['idUser'];
$permiso = "registro_partes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql, MYSQLI_ASSOC);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
    exit();
}

$alert = "";
if (isset($_POST['import_excel'])) {
    $file = $_FILES['file']['tmp_name'];

    if (file_exists($file)) {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        foreach ($rows as $index => $row) {
            // Verifica que el número de columnas sea el esperado
            if (count($row) < 13) {
                $alert .= 'Fila ' . ($index + 1) . ' incompleta: ' . implode(", ", $row) . '<br>';
                continue; // Salta a la siguiente fila
            }

            // Sanitiza los datos
            $placa = mysqli_real_escape_string($conexion, $row[0]);
            $tipo = mysqli_real_escape_string($conexion, $row[1]);
            $serial = mysqli_real_escape_string($conexion, $row[2]);
            $proveedor = mysqli_real_escape_string($conexion, $row[3]);
            $marca = mysqli_real_escape_string($conexion, $row[4]);
            $modelo = mysqli_real_escape_string($conexion, $row[5]);
            $especificacion = mysqli_real_escape_string($conexion, $row[6]);
            $estado = mysqli_real_escape_string($conexion, $row[7]);
            $cliente = mysqli_real_escape_string($conexion, $row[8]);
            $descripcion = mysqli_real_escape_string($conexion, $row[9]);
            $tecnico = mysqli_real_escape_string($conexion, $row[10]);
            $fecha_ingreso = mysqli_real_escape_string($conexion, $row[11]);
            $fecha_salida = mysqli_real_escape_string($conexion, $row[12]);

            // Obtén los IDs correspondientes
            $tipo_id = getIdFromTable($conexion, 'tipo', $tipo);
            $proveedor_id = getIdFromTable($conexion, 'proveedores', $proveedor);
            $estado_id = getIdFromTable($conexion, 'estado', $estado);
            $cliente_id = getIdFromTable($conexion, 'clientes', $cliente);
            $tecnico_id = getIdFromTable($conexion, 'tecnico', $tecnico);

            // Inserta los datos en la base de datos
            $query_insert = mysqli_query($conexion, "INSERT INTO registro_partes (tipo_id, placa, serial, proveedor_id, marca, modelo, especificacion, estado_id, cliente_id, descripcion, tecnico_id, fecha_ingreso, fecha_salida) 
                VALUES ('$tipo_id', '$placa', '$serial', '$proveedor_id', '$marca', '$modelo', '$especificacion', '$estado_id', '$cliente_id', '$descripcion', '$tecnico_id', '$fecha_ingreso', '$fecha_salida')");

            if (!$query_insert) {
                $alert .= 'Error al registrar la placa: ' . $placa . ' - ' . mysqli_error($conexion) . '<br>';
            }
        }

        // Mensaje de éxito o error
        if (empty($alert)) {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Todos los registros se han importado correctamente.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $alert . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
            </div>';
        }
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Archivo no encontrado.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>';
    }
}

// Función para obtener ID de una tabla basada en el nombre
function getIdFromTable($conexion, $table, $name) {
    $name = mysqli_real_escape_string($conexion, $name);
    $query = mysqli_query($conexion, "SELECT id FROM $table WHERE nombre = '$name'");
    $result = mysqli_fetch_assoc($query);
    return $result ? $result['id'] : null; // Retorna null si no se encuentra
}

// Incluye el encabezado de la página
include_once "includes/header.php"; 
?>

<div class="container">
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <input type="submit" name="import_excel" value="Importar Excel" class="btn btn-primary">
    </form>

    <?php if (isset($alert)) echo $alert; ?>
</div>

<?php include_once "includes/footer.php"; ?>
