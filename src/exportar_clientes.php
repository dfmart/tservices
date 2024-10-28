<?php
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=clientes.xls");

// Añadir BOM para UTF-8
echo "\xEF\xBB\xBF";

include "../conexion.php";

// Obtener los registros de clientes
$query = $conexion->query("SELECT * FROM clientes");

?>

<table>
    <tr>
        <th>Id</th>
        <th>Nombre de la empresa</th>
        <th>Dirección</th>
        <th>Número de Teléfono</th>
        <th>Correo</th>
    </tr>

    <?php
    // Verificar si hay resultados y recorrerlos
    if ($query->num_rows > 0) {
        while ($persona = $query->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($persona['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($persona['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($persona['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($persona['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($persona['correo'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php }
    } ?>
</table>
