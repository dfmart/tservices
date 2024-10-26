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
        <th>Acciones</th>
    </tr>

    <?php

include("../conexion.php");
$registros = $base->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_OBJ); 
foreach ($registros as $persona) : ?>
    <tr>
        <td><?php echo htmlspecialchars($persona->id); ?></td>
        <td><?php echo htmlspecialchars($persona->nombre); ?></td>
        <td><?php echo htmlspecialchars($persona->direccion); ?></td>
        <td><?php echo htmlspecialchars($persona->telefono); ?></td>
        <td><?php echo htmlspecialchars($persona->correo); ?></td>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

