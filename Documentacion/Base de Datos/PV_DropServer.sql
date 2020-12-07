-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2019 a las 22:59:18
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectolaravel`
--
-- CREATE DATABASE proyectolaravel;
-- USE proyectolaravel;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `condicion`, `created_at`, `updated_at`) VALUES
(1, 'harinas', 'todas las harinas', 1, NULL, NULL),
(2, 'pastas', 'todas las pastas', 1, NULL, NULL),
(3, 'detergentes', 'todos los detergentes', 1, '2019-04-25 21:14:31', '2019-04-25 23:33:01'),
(4, 'refrescos', 'todos los refrescos', 1, '2019-04-25 23:33:40', '2019-04-25 23:33:40'),
(5, 'cervezas', 'todas las cervezas', 1, '2019-04-27 02:08:12', '2019-05-04 21:25:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `created_at`, `updated_at`) VALUES
(1, 'pedro', 'CEDULA', '123456', 'av carlota', '123456', 'pedro@gmail.com', NULL, NULL),
(2, 'daniel', 'CEDULA', '123', 'av alemania', '4569', 'daniel@gmail.com', '2019-04-30 02:57:23', '2019-04-30 03:23:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(10) UNSIGNED NOT NULL,
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `tipo_identificacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_compra` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_compra` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Disparadores `compras`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockCompraAnular` AFTER UPDATE ON `compras` FOR EACH ROW BEGIN
  UPDATE productos p
    JOIN detalle_compras di
      ON di.idproducto = p.id
     AND di.idcompra = new.id
     set p.stock = p.stock - di.cantidad;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id` int(10) UNSIGNED NOT NULL,
  `idcompra` int(10) UNSIGNED NOT NULL,
  `idproducto` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `detalle_compras`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockCompra` AFTER INSERT ON `detalle_compras` FOR EACH ROW BEGIN
 UPDATE productos SET stock = stock + NEW.cantidad 
 WHERE productos.id = NEW.idproducto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(10) UNSIGNED NOT NULL,
  `idventa` int(10) UNSIGNED NOT NULL,
  `idproducto` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `descuento` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detalle_creditos` (
  `id` int(10) UNSIGNED NOT NULL,
  `idcredito` int(10) UNSIGNED NOT NULL,
  `idproducto` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `descuento` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Disparadores `detalle_ventas`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockVenta` AFTER INSERT ON `detalle_ventas` FOR EACH ROW BEGIN
 UPDATE productos SET stock = stock - NEW.cantidad 
 WHERE productos.id = NEW.idproducto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

DELIMITER $$
CREATE TRIGGER `tr_updStockCredito` AFTER INSERT ON `detalle_creditos` FOR EACH ROW BEGIN
 UPDATE productos SET stock = stock - NEW.cantidad 
 WHERE productos.id = NEW.idproducto;
END
$$
DELIMITER ;


--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_04_23_210438_create_categorias_table', 1),
(4, '2019_04_25_203705_create_productos_table', 2),
(5, '2019_04_29_144035_create_proveedores_table', 3),
(6, '2019_04_29_172617_create_clientes_table', 4),
(7, '2019_04_30_135525_create_roles_table', 5),
(8, '2019_04_30_000000_create_users_table', 6),
(9, '2019_05_03_141422_create_compras_table', 7),
(10, '2019_05_03_141516_create_detalle_compras_table', 7),
(11, '2019_05_04_193824_create_ventas_table', 8),
(12, '2019_05_04_193920_create_detalle_ventas_table', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `idcategoria` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  `imagen` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `idcategoria`, `codigo`, `nombre`, `precio_venta`, `stock`, `condicion`, `imagen`, `created_at`, `updated_at`) VALUES
(1, 1, '12345', 'harina de maiz', '100.00', 0, 1, '1556309730.jpeg', NULL, '2019-04-27 02:49:14'),
(2, 1, '5666', 'harina de trigo', '101.00', 0, 1, '1556309823.jpeg', '2019-04-26 23:09:43', '2019-04-27 02:17:03'),
(3, 5, '789', 'cerveza heineken', '100.00', 0, 1, '1556309794.jpeg', '2019-04-27 02:09:02', '2019-04-27 02:49:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `created_at`, `updated_at`) VALUES
(1, 'carlos', 'CEDULA', '12345', 'av california', '123655', 'carlos@gmail.com', NULL, NULL),
(2, 'luis', 'CEDULA', '123456789', 'av new york', '123456', 'luis@gmail.com', '2019-04-29 22:51:44', '2019-04-29 23:06:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `condicion`) VALUES
(1, 'Administrador', 'Administrador', 1),
(2, 'Vendedor', 'Vendedor', 1);
-- (3, 'Comprador', 'Comprador', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT '1',
  `idrol` int(10) UNSIGNED NOT NULL,
  `imagen` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `usuario`, `password`, `condicion`, `idrol`, `imagen`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'CEDULA', '123456', 'av carlota', '123456', 'admin@gmail.com', 'admin', '$2y$10$vtYeYCXK5GmjImguTT./kuib23ZqGcI4A8HBPAJrgBFgyYvaOVZbe', 1, 1, '1556657502.jpeg', NULL, NULL, '2019-05-01 03:21:02'),
-- Usuario: admin contraseña: adminDps
(2, 'carlos', 'DNI', '12345', 'av san jose', '789456', 'carlos@gmail.com', 'carlos', '$2y$10$./WMu2je8eyYF0U0v/ho3.4H96OnYhThuZpOVmMQoR6Ei7aypKL/q', 1, 2, '1556656697.jpeg', NULL, '2019-05-01 02:38:17', '2019-05-03 05:22:03'),
-- Usuario: carlos contraseña: crlDrps
(3, 'Alberto', 'CEDULA', '12369', 'av italia', '459666', 'alberto@gmail.com', 'alberto', '$2y$10$RzTLNay7a3YzjSeCUGDc6eon.qsY9/r6qWsojAnNNmy3JbtlXrUvG', 1, 2, '1556839376.png', NULL, '2019-05-03 05:22:56', '2019-05-03 05:22:56');
-- Usuario: alberto contraseña: aboDrps
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED  NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `tipo_identificacion` varchar(20) COLLATE utf8mb4_unicode_ci  NULL,
  `num_venta` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `creditos` (
  `id` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `tipo_identificacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_venta` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `ventas`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockVentaAnular` AFTER UPDATE ON `ventas` FOR EACH ROW BEGIN
  UPDATE productos p
    JOIN detalle_ventas dv
      ON dv.idproducto = p.id
     AND dv.idventa= new.id
     set p.stock = p.stock + dv.cantidad;
end
$$
DELIMITER ;

--

DELIMITER $$
CREATE TRIGGER `tr_updStockCreditoAnular` AFTER UPDATE ON `creditos` FOR EACH ROW BEGIN
  UPDATE productos p
    JOIN detalle_ventas dv
      ON dv.idproducto = p.id
     AND dv.idventa= new.id
     set p.stock = p.stock + dv.cantidad;
end
$$
DELIMITER ;



-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_nombre_unique` (`nombre`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_idproveedor_foreign` (`idproveedor`),
  ADD KEY `compras_idusuario_foreign` (`idusuario`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_compras_idcompra_foreign` (`idcompra`),
  ADD KEY `detalle_compras_idproducto_foreign` (`idproducto`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_idventa_foreign` (`idventa`),
  ADD KEY `detalle_ventas_idproducto_foreign` (`idproducto`);
  
  
  -- Indices de la tabla `detalle_creditos`
--
ALTER TABLE `detalle_creditos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_idcredito_foreign` (`idcredito`),
  ADD KEY `detalle_ventas_idproducto_foreign` (`idproducto`);
  
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_nombre_unique` (`nombre`),
  ADD KEY `productos_idcategoria_foreign` (`idcategoria`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proveedores_nombre_unique` (`nombre`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nombre_unique` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`),
  ADD KEY `users_idrol_foreign` (`idrol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_idcliente_foreign` (`idcliente`),
  ADD KEY `ventas_idusuario_foreign` (`idusuario`);
  
--
-- Indices de la tabla `creditos`
--
ALTER TABLE `creditos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_idcliente_foreign` (`idcliente`),
  ADD KEY `ventas_idusuario_foreign` (`idusuario`);
    

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
  
  -- AUTO_INCREMENT de la tabla `detalle_creditos`
--
ALTER TABLE `detalle_creditos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `credito`
--
ALTER TABLE `creditos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_idproveedor_foreign` FOREIGN KEY (`idproveedor`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `compras_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_idcompra_foreign` FOREIGN KEY (`idcompra`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_compras_idproducto_foreign` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_idproducto_foreign` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalle_ventas_idventa_foreign` FOREIGN KEY (`idventa`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

-- Filtros para la tabla `detalle_creditos`
--
ALTER TABLE `detalle_creditos`
  ADD CONSTRAINT `detalle_creditos_idproducto_foreign` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalle_creditos_idcredito_foreign` FOREIGN KEY (`idcredito`) REFERENCES `creditos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_idcategoria_foreign` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_idrol_foreign` FOREIGN KEY (`idrol`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_idcliente_foreign` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`);
  
-- Filtros para la tabla `creditos`
--
ALTER TABLE `creditos`
  ADD CONSTRAINT `creditos_idcliente_foreign` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `creditos_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`);
COMMIT;


-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `idcliente`, `idusuario`, `tipo_identificacion`, `num_venta`, `fecha_venta`, `impuesto`, `total`, `estado`, `created_at`, `updated_at`) VALUES
(9, NULL, 1, NULL, '1', '2020-08-10 00:00:00', '0.20', '72.72', 'Registrado', '2020-08-11 03:20:09', '2020-08-11 03:20:09'),
(10, NULL, 1, NULL, '2', '2020-09-10 00:00:00', '0.20', '72.72', 'Registrado', '2020-09-11 03:20:09', '2020-09-11 03:20:09'),
(11, 1, 1, 'FACTURA', '3', '2020-08-10 00:00:00', '0.20', '1310.40', 'Registrado', '2020-08-11 05:56:48', '2020-08-11 05:56:48'),
(12, NULL, 1, NULL, '4', '2020-08-10 00:00:00', '0.20', '1440.00', 'Registrado', '2020-08-11 06:09:02', '2020-08-11 06:09:02'),
(13, 1, 3, 'FACTURA', '233', '2020-08-17 00:00:00', '0.20', '121.20', 'Registrado', '2020-08-18 09:40:55', '2020-08-18 09:40:55'),
(14, NULL, 3, NULL, '123', '2020-08-26 00:00:00', '0.20', '480.00', 'Registrado', '2020-08-27 07:05:50', '2020-08-27 07:05:50'),
(15, 1, 3, 'FACTURA', '435', '2020-08-26 00:00:00', '0.20', '54.00', 'Registrado', '2020-08-27 07:08:41', '2020-08-27 07:08:41'),
(16, 1, 3, 'FACTURA', '9', '2020-10-29 00:00:00', '0.20', '240.00', 'Registrado', '2020-10-29 14:27:00', '2020-10-29 14:27:00');

--

--
-- Volcado de datos para la tabla `compras`
--
INSERT INTO `compras` (`id`, `idproveedor`, `idusuario`, `tipo_identificacion`, `num_compra`, `fecha_compra`, `impuesto`, `total`, `estado`, `created_at`, `updated_at`) VALUES
(8, 1, 1, 'TICKET', '1', '2020-08-10 00:00:00', '0.20', '216.00', 'Registrado', '2020-08-11 07:46:46', '2020-08-11 07:46:46'),
(9, 1, 1, 'FACTURA', '23', '2020-08-26 00:00:00', '0.20', '48000.00', 'Registrado', '2020-08-27 07:01:57', '2020-08-27 07:01:57');
--
--


--
-- Volcado de datos para la tabla `creditos`
--

INSERT INTO `creditos` (`id`, `idcliente`, `idusuario`, `tipo_identificacion`, `num_venta`, `fecha_venta`, `impuesto`, `total`, `estado`, `created_at`, `updated_at`) VALUES
(7, 1, 1, 'FACTURA', '3', '2020-08-10 00:00:00', '0.20', '1310.40', 'Anulado', '2020-08-11 05:37:43', '2020-08-11 05:57:16'),
(8, 1, 2, 'FACTURA', '233', '2020-08-17 00:00:00', '0.20', '121.20', 'Anulado', '2020-08-18 09:40:27', '2020-08-18 09:41:21'),
(9, 1, 2, 'FACTURA', '435', '2020-08-26 00:00:00', '0.20', '54.00', 'Anulado', '2020-08-27 07:07:53', '2020-08-27 07:09:08'),
(10, 1, 2, 'FACTURA', '9', '2020-10-29 00:00:00', '0.20', '240.00', 'Anulado', '2020-10-29 14:24:29', '2020-10-29 14:27:25');

--

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id`, `idcompra`, `idproducto`, `cantidad`, `precio`) VALUES
(13, 8, 1, 2, '90.00'),
(14, 9, 2, 20, '2000.00');

--

--
-- Volcado de datos para la tabla `detalle_creditos`
--

INSERT INTO `detalle_creditos` (`id`, `idcredito`, `idproducto`, `cantidad`, `precio`, `descuento`) VALUES
(18, 7, 1, 12, '100.00', '9.00'),
(19, 8, 2, 1, '101.00', '0.00'),
(20, 9, 3, 2, '25.00', '10.00'),
(21, 10, 1, 2, '100.00', '0.00');

--


--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `idventa`, `idproducto`, `cantidad`, `precio`, `descuento`) VALUES
(14, 9, 2, 2, '101.00', '70.00'),
(15, 11, 1, 12, '100.00', '9.00'),
(16, 12, 1, 12, '100.00', '0.00'),
(17, 13, 2, 1, '101.00', '0.00'),
(18, 14, 1, 4, '100.00', '0.00'),
(19, 15, 3, 2, '25.00', '10.00'),
(20, 16, 1, 2, '100.00', '0.00');

--






/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


