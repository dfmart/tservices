<?php
header("Content-Type:application/xls");
header("Content-Disposition: attchment; filename=archivo.xls")


?>

<table>
    <tr>
        <th>Id</th>
        <th>Nombre de la empresa</th>
        <th>Direccion</th>
        <th>Nmero Telefono</th>
        <th>Correo</th>
       
    </tr>

    <?php

session_start();
require("../conexion.php");

$query = mysqli_query($conexion, "SELECT * FROM clientes");
$result = mysqli_num_rows($query);
if ($result > 0) {
    while ($data = mysqli_fetch_assoc($query)) { ?>
        <tr>
       
            <td><?php echo $data['id']; ?></td>
            <td><?php echo $data['nombre']; ?></td>
            <td><?php echo $data['direccion']; ?></td>
            <td><?php echo $data['telefono']; ?></td>
            <td><?php echo $data['correo']; ?></td>
           
                                        </td>
                                    </tr>
    </table>
                            <?php }
                            } ?>