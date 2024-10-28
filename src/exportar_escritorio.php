<?php
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=registro_escritorio.xls");

// Añadir BOM para UTF-8
echo "\xEF\xBB\xBF";

include "../conexion.php";

// Obtener los registros del registro_escritorio
$query = mysqli_query($conexion, "
    SELECT r.*, 
           p.nombre AS proveedor_nombre, 
           e.nombre AS estado_nombre, 
           c.nombre AS cliente_nombre, 
           te.nombre AS tecnico_nombre 
    FROM registro_escritorio r
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
        <th>Procesador</th>
        <th>Tipo de Memoria</th>
        <th>Tamaño de Memoria</th>
        <th>Número de Módulo</th>
        <th>Tipo de Disco</th>
        <th>Tamaño</th>
        <th>Batería</th>
        <th>Nota</th>
        <th>Estado</th>
        <th>Cliente</th>
        <th>Técnico</th>
        <th>Fecha Ingreso</th>
        <th>Fecha Salida</th>
    </tr>

    <?php
    // Verificar si hay resultados y recorrerlos
    if (mysqli_num_rows($query) > 0) {
        while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['placa'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['serial'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['proveedor_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['marca'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['modelo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['procesador'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['tipmemoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['tammemoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['nummodulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['tipdisco'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['tamano'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($data['bateria'], ENT_QUOTES, 'UTF-8'); ?></td>
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
