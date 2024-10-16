<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
require '../vendor/autoload.php'; // Asegúrate de que la ruta a autoload.php sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\writer\Xlsx;

if (isset($_POST['save_excel_data'])) { // Cambié $_post a $_POST
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) { // Cambié IF a if


        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();



        $count="0";
        foreach ($data as $row)
         {
            if($count>0)
            {      
        
            $nombre=  $row['0']; // Cambié ['0'] a [0] para mejor legibilidad
            $direccion= $row['1']; // Cambié ['1'] a [1]
            $telefono=  $row['2']; // Cambié ['2'] a [2]
            $correo=  $row['3']; // Cambié ['3'] a [3]

            $studentQuery="INSERT INTO clientes (nombre,direccion,telefono,correo) VALUES ('$nombre','$direccion','$telefono','$correo')";
            $resultados=mysqli_query($conexion, $studentQuery);
            $msg = true;

            }
            else   
            {
            $count= "1";
            }
        }
   

            if(isset($msg))
            {
                $_SESSION['message'] = "Datos guardados";
                header('location: importar_clientes.php');
                exit(0);


            }else{
                $_SESSION['message'] = "No guardados";
                header('location: importar_clientes.php');
                exit(0);

            }
    } 
    
    else 
    
    {
        $_SESSION['message'] = "Invalid File";
        header('location: importar_clientes.php');
        exit(0);
    }
}

?>
