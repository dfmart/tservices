<?php
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=registro_impresora.xls");

// Añadir BOM para UTF-8
echo "\xEF\xBB\xBF";

include "../conexion.php";

// Obtener los registros de registro_impresora
$query = $conexion->query("
    SELECT r.*, 
           p.nombre AS proveedor_nombre, 
           e.nombre AS estado_nombre, 
           c.nombre AS cliente_nombre, 
           te.nombre AS tecnico_nombre 
    FROM registro_impresora r
    LEFT JOIN proveedores p ON r.proveedor_id = p.id
    LEFT JOIN estado e ON r.estado_id = e.id
    LEFT JOIN clientes c ON r.cliente_id = c.id
    LEFT JOIN tecnico te ON r.tecnico_id = te.id
");

?>

<table>
    <tr>
        <th>Id</th>
        <th>Placa</th>
        <th>Serial</th>
        <th>Proveedor</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Nota</th>
        <th>Estado</th>
        <th>Cliente</th>
        <th>Técnico</th>
        <th>Fecha Ingreso</th>
        <th>Fecha Salida</th>
    </tr>

    <?php
    // Verificar si hay resultados y recorrerlos
    if ($query->num_rows > 0) {
        while ($data = $query->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['placa'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['serial'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['proveedor_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['marca'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['modelo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['nota'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['estado_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['cliente_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['tecnico_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['fecha_ingreso'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['fecha_salida'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php }
    } ?>
</table>
