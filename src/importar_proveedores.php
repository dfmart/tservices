<?php
require '../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta
use PhpOffice\PhpSpreadsheet\IOFactory;

include "../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si se ha subido un archivo
    if (isset($_FILES['file'])) {
        $file = $_FILES['file']['tmp_name'];
        $fileType = $_FILES['file']['type'];
        
        $imported = 0;
        $errors = 0;

        // Procesar archivo CSV
        if ($fileType == 'text/csv' || pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) == 'csv') {
            if (($handle = fopen($file, 'r')) !== FALSE) {
                // Saltar la primera línea (encabezados)
                fgetcsv($handle);
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $nombre = mysqli_real_escape_string($conexion, $data[0]);
                    $direccion = mysqli_real_escape_string($conexion, $data[1]);
                    $telefono = mysqli_real_escape_string($conexion, $data[2]);
                    $correo = mysqli_real_escape_string($conexion, $data[3]);

                    $query = "INSERT INTO proveedores (nombre, direccion, telefono, correo) VALUES ('$nombre', '$direccion', '$telefono', '$correo')";
                    if (mysqli_query($conexion, $query)) {
                        $imported++;
                    } else {
                        $errors++;
                    }
                }
                fclose($handle);
            }
        }
        // Procesar archivos XLS y XLSX
        else if ($fileType == 'application/vnd.ms-excel' || $fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            
            // Saltar la primera línea (encabezados)
            array_shift($sheetData);

            foreach ($sheetData as $row) {
                $nombre = mysqli_real_escape_string($conexion, $row['A']);
                $direccion = mysqli_real_escape_string($conexion, $row['B']);
                $telefono = mysqli_real_escape_string($conexion, $row['C']);
                $correo = mysqli_real_escape_string($conexion, $row['D']);

                $query = "INSERT INTO proveedores (nombre, direccion, telefono, correo) VALUES ('$nombre', '$direccion', '$telefono', '$correo')";
                if (mysqli_query($conexion, $query)) {
                    $imported++;
                } else {
                    $errors++;
                }
            }
        } else {
            echo "Formato de archivo no soportado.";
            exit();
        }

        // Mensaje de éxito o error
        if ($errors > 0) {
            echo "Se importaron $imported proveedores. Se produjeron $errors errores.";
        } else {
            echo "Importación exitosa. Se importaron $imported proveedores.";
            // Redirigir a la plantilla de proveedores después de 2 segundos
            header('Location: proveedores.php');
            exit();
        }
    } else {
        echo "No se ha subido ningún archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Proveedores</title>
</head>
<body>
    <h1>Importar Proveedores</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona el archivo:</label>
        <input type="file" name="file" id="file" accept=".csv, .xls, .xlsx" required>
        <input type="submit" value="Importar">
    </form>
</body>
</html>
