-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2026 a las 17:29:23
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
-- Base de datos: `sistema_hibrido`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambientes`
--

CREATE TABLE `ambientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambientes`
--

INSERT INTO `ambientes` (`id`, `nombre`, `ubicacion`, `creado_en`) VALUES
(1, 'Laboratorio de Computación A', 'Planta Baja', '2026-11-14 23:05:01'),
(2, 'Telecentro B', 'Primer Piso', '2026-11-14 23:05:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `ambiente_id` int(11) NOT NULL,
  `codigo_qr` varchar(100) NOT NULL,
  `generacion` varchar(50) NOT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `memoria_ram` varchar(50) DEFAULT NULL,
  `almacenamiento` varchar(50) DEFAULT NULL,
  `estado_operativo` enum('Operativo','Con Fallas','Inoperativo','De Baja') DEFAULT 'Operativo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `ambiente_id`, `codigo_qr`, `generacion`, `procesador`, `memoria_ram`, `almacenamiento`, `estado_operativo`, `fecha_registro`) VALUES
(1, 1, 'TEL-DE4B0F', '5ta Generación', 'Intel Core i3', '4GB', '256GB SSD', 'Operativo', '2026-11-14 23:07:55'),
(2, 2, 'TEL-FAEFA9', '11va Generación', 'Intel Core i5', '16GB', '500GB SSD', 'Con Fallas', '2026-11-14 23:45:49'),
(3, 1, 'TEL-357B1F', '11va Generación', 'Intel Core i7', '16GB', '1TB SSD', 'Operativo', '2026-11-14 23:52:12'),
(4, 2, 'TEL-2F1987', '4ta Generación', 'Ryzen 3500 G', '8GB', '500GB HDD', 'Operativo', '2026-11-14 23:52:59'),
(5, 1, 'TEL-74727E', '5ta Generación', 'Intel Core i7', '12GB', '256GB SSD', 'Operativo', '2026-11-15 00:31:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` varchar(36) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado_incidencia` enum('Reportada','En Revisión','Solucionada') DEFAULT 'Reportada',
  `fecha_reporte` datetime NOT NULL,
  `sincronizado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `equipo_id`, `usuario_id`, `descripcion`, `estado_incidencia`, `fecha_reporte`, `sincronizado_en`) VALUES
('00a0a56e-c7c1-4f52-9a10-01fe0f43e7e3', 3, 2, 'FALTA DE  MATENIMIENTE PREVENTIVO ', 'Solucionada', '2026-11-15 01:34:58', '2026-11-15 00:34:58'),
('31338321-00e1-400f-a2e3-9426796c8261', 4, 2, 'sads', 'Solucionada', '2026-11-20 17:09:37', '2026-11-20 16:09:37'),
('7bcda813-d804-406c-ab55-c599f8c71251', 5, 2, 'safsfs', 'Solucionada', '2026-11-20 16:46:47', '2026-11-20 15:46:47'),
('8829cdf9-ec7b-46cd-bde5-c9dbe8da7e48', 2, 2, 'Falla en la actualizaciones de seguridad de Windows 11.', 'Solucionada', '2026-11-15 00:46:18', '2026-11-14 23:46:18'),
('9c196bbf-ca22-498b-afb2-27486f3f10bc', 1, 2, 'El monitor parpadea constantemente y no da imagen clara. (Reportado desde App Móvil Offline)', 'Solucionada', '2026-11-15 00:16:45', '2026-11-14 23:16:45'),
('c8770d6a-b746-46ea-a995-06f3172fc978', 1, 2, 'El monitor parpadea constantemente y no da imagen clara. (Reportado desde App Móvil Offline)', 'Reportada', '2026-11-15 00:54:14', '2026-11-14 23:54:14'),
('ccfae8fa-c58d-45ae-80d9-a6dfbc0c586f', 1, 2, 'Falla en el SSD capacidad sobrepasada e inicio del equipo lento.', 'Reportada', '2026-11-15 00:14:01', '2026-11-14 23:14:01'),
('ed15b3ec-d938-4650-8d69-73a116bd8da7', 1, 2, 'El monitor parpadea constantemente y no da imagen clara. (Reportado desde App Móvil Offline)', 'Reportada', '2026-11-15 00:16:35', '2026-11-14 23:16:35'),
('edbc36e4-f16b-436c-9c8a-2798b897ef4d', 4, 2, 'El equipo presenta ruido a encender. Posible falta de limpieza.', 'Solucionada', '2026-11-15 00:53:58', '2026-11-14 23:53:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `tecnico_id` int(11) NOT NULL,
  `tipo_mantenimiento` enum('Preventivo','Correctivo') NOT NULL,
  `detalles` text DEFAULT NULL,
  `fecha_programada` date DEFAULT NULL,
  `fecha_realizada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(3, 'Encargado de Aula'),
(2, 'Técnico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `nombre`, `correo`, `password`, `estado`) VALUES
(1, 2, 'Jhon Flores', 'tecnico@telecentro.edu', 'TecPassword2026', 1),
(2, 1, 'Super Admin', 'admin@telecentro.edu', 'Admin123', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ambientes`
--
ALTER TABLE `ambientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_qr` (`codigo_qr`),
  ADD KEY `ambiente_id` (`ambiente_id`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ambientes`
--
ALTER TABLE `ambientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`ambiente_id`) REFERENCES `ambientes` (`id`);

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `incidencias_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`),
  ADD CONSTRAINT `incidencias_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `mantenimientos_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`),
  ADD CONSTRAINT `mantenimientos_ibfk_2` FOREIGN KEY (`tecnico_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
