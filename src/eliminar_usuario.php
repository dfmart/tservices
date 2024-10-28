<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=archivo.xls");

include "../conexion.php";

// Obtener los registros de clientes
$query = $conexion->query("SELECT * FROM proveedores");

?>

<table>
    <tr>
        <th>Id</th>
        <th>Nombre de la empresa</th>
        <th>Dirección</th>
        <th>Número Teléfono</th>
        <th>Correo</th>
        <th>Acciones</th>
    </tr>

    <?php
    // Verificar si hay resultados y recorrerlos
    if ($query->num_rows > 0) {
        while ($persona = $query->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($persona['id']); ?></td>
                <td><?php echo htmlspecialchars($persona['nombre']); ?></td>
                <td><?php echo htmlspecialchars($persona['direccion']); ?></td>
                <td><?php echo htmlspecialchars($persona['telefono']); ?></td>
                <td><?php echo htmlspecialchars($persona['correo']); ?></td>
                <td><!-- Aquí puedes agregar botones de acciones, si es necesario --></td>
            </tr>
        <?php }
    } ?>
</table>
