<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=archivo.xls");

include "../conexion.php";

// Obtener los registros con las uniones necesarias
$query = mysqli_query($conexion, "
    SELECT r.*, 
           t.nombre AS tipo_nombre, 
           p.nombre AS proveedor_nombre, 
           e.nombre AS estado_nombre, 
           c.nombre AS cliente_nombre, 
           te.nombre AS tecnico_nombre 
    FROM registro_partes r
    LEFT JOIN tipo t ON r.tipo_id = t.id
    LEFT JOIN proveedores p ON r.proveedor_id = p.id
    LEFT JOIN estado e ON r.estado_id = e.id
    LEFT JOIN clientes c ON r.cliente_id = c.id
    LEFT JOIN tecnico te ON r.tecnico_id = te.id
");

?>

<table>
    <tr>
        <th>Id</th>
        <th>Tipo</th>
        <th>Placa</th>
        <th>Serial</th>
        <th>Proveedor</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Especificacion</th>
        <th>Estado</th>
        <th>Cliente</th>
        <th>Descripcion</th>
        <th>Tecnico</th>
        <th>Fecha Ingreso</th>
        <th>Fecha Salida</th>
    </tr>

    <?php
    // Verificar si hay resultados y recorrerlos
    if (mysqli_num_rows($query) > 0) {
        while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($data['id']); ?></td>
                <td><?php echo htmlspecialchars($data['tipo_nombre']); ?></td>
                <td><?php echo htmlspecialchars($data['placa']); ?></td>
                <td><?php echo htmlspecialchars($data['serial']); ?></td>
                <td><?php echo htmlspecialchars($data['proveedor_nombre']); ?></td>
                <td><?php echo htmlspecialchars($data['marca']); ?></td>
                <td><?php echo htmlspecialchars($data['modelo']); ?></td>
                <td><?php echo htmlspecialchars($data['especificacion']); ?></td>
                <td><?php echo htmlspecialchars($data['estado_nombre']); ?></td>
                <td><?php echo htmlspecialchars($data['cliente_nombre']); ?></td>
                <td><?php echo htmlspecialchars($data['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($data['tecnico_nombre']); ?></td>
                <td><?php echo htmlspecialchars($data['fecha_ingreso']); ?></td>
                <td><?php echo htmlspecialchars($data['fecha_salida']); ?></td>
            </tr>
        <?php }
    } ?>
</table>
