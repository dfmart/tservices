-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2024 a las 18:19:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
(238, 'Duvanc', 'gdsgadh', '7377336', 'df@gmail');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'Telo Services', '3104358510', 'dmartinez@teloservices.com', 'Telo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_permisos`
--

CREATE TABLE `detalle_permisos` (
  `id` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_permisos`
--

INSERT INTO `detalle_permisos` (`id`, `id_permiso`, `id_usuario`) VALUES
(101, 1, 1),
(102, 2, 1),
(103, 3, 1),
(104, 4, 1),
(149, 1, 10),
(150, 2, 10),
(151, 3, 10),
(152, 4, 10),
(153, 5, 10),
(154, 6, 10),
(155, 7, 10),
(156, 8, 10),
(157, 9, 10),
(158, 10, 10),
(159, 11, 10),
(160, 12, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `nombre`) VALUES
(1, 'DISPONIBLE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'configuración'),
(2, 'usuarios'),
(3, 'clientes'),
(4, 'proveedores'),
(5, 'permiso'),
(6, 'registro_partes'),
(7, 'registro_portatiles'),
(8, 'registro_escritorio'),
(9, 'registro_impresora'),
(10, 'registro_monitor'),
(11, 'registro_servidor'),
(12, 'tipo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
(119, 'Telo', 'nnkjk', '65656', 'sjs@gmail'),
(138, '', '', '', ''),
(140, '', '', '', ''),
(141, '\"; with (frames[\'frScroll\'].document) { open(\"text/html\",\"replace\"); write(szHTML); close(); } szHTM', '', '', ''),
(142, '\"+ \"A:link,A:visited,A:active {text-decoration:none;\"+\"color:\"+c_rgszClr[3]+\";}\"+ \".clTab {cursor:ha', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_escritorio`
--

CREATE TABLE `registro_escritorio` (
  `id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `procesador` varchar(50) NOT NULL,
  `tipmemoria` varchar(50) NOT NULL,
  `tammemoria` varchar(50) NOT NULL,
  `nummodulo` int(11) NOT NULL,
  `tipdisco` varchar(50) NOT NULL,
  `tamano` varchar(50) NOT NULL,
  `bateria` varchar(50) NOT NULL,
  `nota` varchar(50) DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_escritorio`
--

INSERT INTO `registro_escritorio` (`id`, `placa`, `serial`, `proveedor_id`, `marca`, `modelo`, `procesador`, `tipmemoria`, `tammemoria`, `nummodulo`, `tipdisco`, `tamano`, `bateria`, `nota`, `estado_id`, `cliente_id`, `tecnico_id`, `fecha_ingreso`, `fecha_salida`, `imagen`) VALUES
(1, 'lp001', 'gdf', 119, 'fdg', 'dfg', 'fdg', 'fdg', 'dfg', 435, 'fdg', 'dfg', 'dfg', 'fdg', 1, 238, 1, '2024-10-26', '2024-10-26', NULL),
(3, 'vsss', 'f', 119, 'f', 'f', 'f', 'DDR3', '4GB', 2, 'SSD', '512GB', 'f', 'fddswsqwdwedweferf freferfe  ferferfe', 1, 238, 1, '2024-10-26', '2024-10-26', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_impresora`
--

CREATE TABLE `registro_impresora` (
  `id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `nota` text DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_monitor`
--

CREATE TABLE `registro_monitor` (
  `id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `nota` text DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_partes`
--

CREATE TABLE `registro_partes` (
  `id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `especificacion` varchar(50) DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_partes`
--

INSERT INTO `registro_partes` (`id`, `tipo_id`, `placa`, `serial`, `proveedor_id`, `marca`, `modelo`, `especificacion`, `estado_id`, `cliente_id`, `descripcion`, `tecnico_id`, `fecha_ingreso`, `fecha_salida`, `imagen`) VALUES
(4, 1, 'v', 'v', 119, 'vv', 'v', 'v', 1, 238, 'v', 1, '2024-10-26', '2024-10-26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_portatil`
--

CREATE TABLE `registro_portatil` (
  `id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `procesador` varchar(50) NOT NULL,
  `tipmemoria` varchar(50) NOT NULL,
  `tammemoria` varchar(50) NOT NULL,
  `nummodulo` int(11) NOT NULL,
  `tipdisco` varchar(50) NOT NULL,
  `tamano` varchar(50) NOT NULL,
  `bateria` varchar(50) NOT NULL,
  `nota` varchar(50) DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_portatil`
--

INSERT INTO `registro_portatil` (`id`, `placa`, `serial`, `proveedor_id`, `marca`, `modelo`, `procesador`, `tipmemoria`, `tammemoria`, `nummodulo`, `tipdisco`, `tamano`, `bateria`, `nota`, `estado_id`, `cliente_id`, `tecnico_id`, `fecha_ingreso`, `fecha_salida`, `imagen`) VALUES
(1, 'LP001', 'dsadas', 119, 'Dell', 't', 'we', 'DDR4', '16GB', 2, 'SSD', '512GB', 'fw', 'frwe', 1, 238, 1, '2024-10-26', '2024-10-26', NULL),
(2, 'LP002', 'kjhkj', 119, 'dfg', 'fgd', 'dfg', 'dfg', 'fdg', 0, 'dfg', 'fg', 'fg', 'gdfgfdgdfg', 1, 238, 1, '2024-10-26', '2024-10-26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_servidor`
--

CREATE TABLE `registro_servidor` (
  `id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `procesador` varchar(50) NOT NULL,
  `tipmemoria` varchar(50) NOT NULL,
  `tammemoria` varchar(50) NOT NULL,
  `nummodulo` int(11) NOT NULL,
  `tipdisco` varchar(50) NOT NULL,
  `tamano` varchar(50) NOT NULL,
  `nota` text DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `imagen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_servidor`
--

INSERT INTO `registro_servidor` (`id`, `placa`, `serial`, `proveedor_id`, `marca`, `modelo`, `procesador`, `tipmemoria`, `tammemoria`, `nummodulo`, `tipdisco`, `tamano`, `nota`, `estado_id`, `cliente_id`, `tecnico_id`, `fecha_ingreso`, `fecha_salida`, `imagen`) VALUES
(2, 'dd', 'd', 119, 'd', 'd', 'd', 'DDR3', '4GB', 2, 'HDD', '512GB', 'dd', 1, 238, 1, '2024-10-08', '2024-10-26', 'data:image/jpeg;base64,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');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnico`
--

CREATE TABLE `tecnico` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnico`
--

INSERT INTO `tecnico` (`id`, `nombre`) VALUES
(1, 'DUVAN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo`
--

CREATE TABLE `tipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo`
--

INSERT INTO `tipo` (`id`, `nombre`) VALUES
(1, 'RAM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`) VALUES
(1, 'Sistemas', 'dmartinez@teloservices.com', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(10, 'Felipe Atencia', 'mm@gmail.com', 'dfmartinez', '1ef3139942f17c426ec328ac6c536752');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_permiso` (`id_permiso`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_escritorio`
--
ALTER TABLE `registro_escritorio`
  ADD PRIMARY KEY (`placa`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `registro_impresora`
--
ALTER TABLE `registro_impresora`
  ADD PRIMARY KEY (`placa`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `registro_monitor`
--
ALTER TABLE `registro_monitor`
  ADD PRIMARY KEY (`placa`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `registro_partes`
--
ALTER TABLE `registro_partes`
  ADD PRIMARY KEY (`placa`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tipo_id` (`tipo_id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `registro_portatil`
--
ALTER TABLE `registro_portatil`
  ADD PRIMARY KEY (`placa`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `registro_servidor`
--
ALTER TABLE `registro_servidor`
  ADD PRIMARY KEY (`placa`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `tecnico`
--
ALTER TABLE `tecnico`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT de la tabla `registro_escritorio`
--
ALTER TABLE `registro_escritorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `registro_impresora`
--
ALTER TABLE `registro_impresora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registro_monitor`
--
ALTER TABLE `registro_monitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `registro_partes`
--
ALTER TABLE `registro_partes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `registro_portatil`
--
ALTER TABLE `registro_portatil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `registro_servidor`
--
ALTER TABLE `registro_servidor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tecnico`
--
ALTER TABLE `tecnico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo`
--
ALTER TABLE `tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_permisos`
--
ALTER TABLE `detalle_permisos`
  ADD CONSTRAINT `detalle_permisos_ibfk_1` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`),
  ADD CONSTRAINT `detalle_permisos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `registro_escritorio`
--
ALTER TABLE `registro_escritorio`
  ADD CONSTRAINT `registro_escritorio_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `registro_escritorio_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `registro_escritorio_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `registro_escritorio_ibfk_4` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnico` (`id`);

--
-- Filtros para la tabla `registro_impresora`
--
ALTER TABLE `registro_impresora`
  ADD CONSTRAINT `registro_impresora_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `registro_impresora_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `registro_impresora_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `registro_impresora_ibfk_4` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnico` (`id`);

--
-- Filtros para la tabla `registro_monitor`
--
ALTER TABLE `registro_monitor`
  ADD CONSTRAINT `registro_monitor_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `registro_monitor_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `registro_monitor_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `registro_monitor_ibfk_4` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnico` (`id`);

--
-- Filtros para la tabla `registro_partes`
--
ALTER TABLE `registro_partes`
  ADD CONSTRAINT `registro_partes_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `tipo` (`id`),
  ADD CONSTRAINT `registro_partes_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `registro_partes_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `registro_partes_ibfk_4` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `registro_partes_ibfk_5` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnico` (`id`);

--
-- Filtros para la tabla `registro_portatil`
--
ALTER TABLE `registro_portatil`
  ADD CONSTRAINT `registro_portatil_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `registro_portatil_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `registro_portatil_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `registro_portatil_ibfk_4` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnico` (`id`);

--
-- Filtros para la tabla `registro_servidor`
--
ALTER TABLE `registro_servidor`
  ADD CONSTRAINT `registro_servidor_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `registro_servidor_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `registro_servidor_ibfk_3` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `registro_servidor_ibfk_4` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnico` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
