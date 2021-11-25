-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2021 a las 06:44:37
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_carvy_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `progreso` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `faltante` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `orden_id`, `detalle`, `progreso`, `total`, `faltante`, `fecha`, `created_at`, `updated_at`) VALUES
(1, 3, 'Orden en Proceso', 10, 10, 90, '2021-11-09', '2021-11-10 04:08:44', '2021-11-10 04:08:44'),
(2, 2, 'Orden en Proceso', 10, 10, 90, '2021-11-17', '2021-11-17 23:24:45', '2021-11-17 23:24:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `averias_fallas`
--

CREATE TABLE `averias_fallas` (
  `id` int(11) NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `averias_fallas`
--

INSERT INTO `averias_fallas` (`id`, `descripcion`, `precio`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Cambio de Aceite', 15, 'A', '2021-06-03 21:37:22', '2021-06-03 21:37:22'),
(2, 'Cambio de Mortiguador', 16.5, 'A', '2021-06-03 22:04:18', '2021-06-03 22:04:18'),
(3, 'Cambio de Llanta', 25, 'A', '2021-06-03 22:04:18', '2021-06-03 22:04:18'),
(4, 'Reparación de Motor', 80, 'A', '2021-07-20 19:18:43', '2021-07-20 19:18:43'),
(5, 'Cambio Pastilla De Freno', 25, 'A', '2021-07-22 01:49:04', '2021-07-22 01:49:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `fecha`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Accesorios', '2021-05-03', 'A', '2021-05-04 04:15:17', '2021-05-04 05:26:21'),
(2, 'Llantas', '2021-05-03', 'A', '2021-05-04 04:17:54', '2021-05-04 05:30:47'),
(3, 'Motores', '2021-05-03', 'A', '2021-05-04 04:24:07', '2021-05-04 04:24:07'),
(4, 'Aceites', '2021-05-03', 'A', '2021-05-04 04:54:23', '2021-05-04 04:54:23'),
(5, 'Piezas', '2021-05-03', 'A', '2021-05-04 04:55:35', '2021-05-04 05:31:19'),
(6, 'Filtros', '2021-05-04', 'A', '2021-05-04 16:42:43', '2021-05-04 16:42:43'),
(12, 'Bombas', '2021-06-01', 'A', '2021-06-02 01:02:39', '2021-06-02 01:02:39'),
(13, 'Pruebas2', '2021-07-15', 'I', '2021-07-15 08:03:53', '2021-08-23 22:40:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `hora_ingreso` time DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `persona_id`, `fecha_ingreso`, `hora_ingreso`, `estado`, `created_at`, `updated_at`) VALUES
(1, 63, '2021-10-08', '04:10:39', 'A', '2021-10-08 21:23:39', '2021-10-08 21:23:39'),
(2, 64, '2021-10-27', '07:10:26', 'A', '2021-10-28 00:09:26', '2021-10-28 00:09:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_vehiculos`
--

CREATE TABLE `clientes_vehiculos` (
  `id` int(11) NOT NULL,
  `vehiculo_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `clientes_vehiculos`
--

INSERT INTO `clientes_vehiculos` (`id`, `vehiculo_id`, `cliente_id`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1008, 1, 'A', '2021-10-20 03:29:24', '2021-10-20 03:29:24'),
(2, 1009, 2, 'A', '2021-10-28 00:09:49', '2021-10-28 00:09:49'),
(3, 1010, 2, 'A', '2021-10-28 00:09:54', '2021-10-28 00:09:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `serie_documento` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `sub_total` float DEFAULT NULL,
  `iva` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `fecha_compra` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `usuario_id`, `serie_documento`, `descuento`, `sub_total`, `iva`, `total`, `fecha_compra`, `created_at`, `updated_at`, `estado`) VALUES
(1, 12, 1, '3456788', 0, 0, 0, 0, '2021-11-18', '2021-11-19 03:06:19', '2021-11-19 03:06:19', 'A'),
(2, 12, 1, '8999898989', 0, 0, 0, 0, '2021-11-18', '2021-11-19 03:07:21', '2021-11-19 03:07:21', 'A'),
(3, 12, 1, '657890789', 0, 3100, 372, 3472, '2021-11-23', '2021-11-24 04:57:04', '2021-11-24 04:57:04', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_compra` float DEFAULT NULL,
  `total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `compra_id`, `producto_id`, `cantidad`, `precio_compra`, `total`) VALUES
(1, 1, 5, 0, 0, 0),
(2, 2, 5, 0, 6.8, 0),
(3, 3, 5, 200, 15.5, 3100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `es_orden` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `cantidad`, `precio_venta`, `total`, `producto_id`, `es_orden`) VALUES
(1, 1, 5, 15.5, 77.5, 5, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `usuario_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_orden`
--

CREATE TABLE `estado_orden` (
  `id` int(11) NOT NULL,
  `detalle` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `abrv` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estado_orden`
--

INSERT INTO `estado_orden` (`id`, `detalle`, `abrv`, `estado`) VALUES
(1, 'Atendido', 'ATE', 'A'),
(2, 'Cancelado', 'CAN', 'A'),
(3, 'Pendiente', 'PEN', 'A'),
(4, 'Proceso', 'PRO', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `transaccion_id` int(11) DEFAULT NULL,
  `tipo` enum('E','S') COLLATE utf8_unicode_ci DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `cantidad_disponible` int(11) DEFAULT NULL,
  `precio_disponible` double DEFAULT NULL,
  `total_disponible` double DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `producto_id`, `transaccion_id`, `tipo`, `cantidad`, `precio`, `total`, `cantidad_disponible`, `precio_disponible`, `total_disponible`, `fecha`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'E', 0, 0, 0, 0, 0, 0, '2021-11-18', '2021-11-19 03:06:20', '2021-11-19 03:06:20'),
(2, 5, 2, 'E', 0, 6.8, 0, 0, 0, 0, '2021-11-18', '2021-11-19 03:07:22', '2021-11-19 03:07:22'),
(3, 5, 3, 'E', 200, 15.5, 3100, 200, 15.5, 3100, '2021-11-23', '2021-11-24 04:57:05', '2021-11-24 04:57:05'),
(4, 5, 4, 'S', -5, 15.5, 77.5, 195, 15.5, 3022.5, '2021-11-24', '2021-11-24 06:25:44', '2021-11-24 06:25:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `descripcion`, `fecha`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Ford', NULL, '2021-05-11', 'A', '2021-05-11 22:18:29', '2021-05-11 22:18:29'),
(2, 'Mazda', NULL, '2021-05-11', 'A', '2021-05-11 22:18:29', '2021-05-11 22:18:29'),
(3, 'Susuki', NULL, '2021-06-01', 'A', '2021-06-02 02:21:19', '2021-06-02 02:21:19'),
(4, 'Chevrolet', NULL, '2021-06-01', 'A', '2021-06-02 02:21:19', '2021-06-02 02:21:19'),
(5, 'Fiat', NULL, '2021-06-01', 'A', '2021-06-02 02:23:24', '2021-06-02 02:23:24'),
(6, 'Kia', NULL, '2021-06-01', 'A', '2021-06-02 02:23:24', '2021-06-02 02:23:24'),
(7, 'Hyundai', NULL, '2021-06-01', 'A', '2021-06-02 02:26:16', '2021-06-02 02:26:16'),
(8, 'Honda', NULL, '2021-06-01', 'A', '2021-06-02 02:26:16', '2021-06-02 02:26:16'),
(9, 'Nissan', NULL, '2021-06-01', 'A', '2021-06-02 02:29:11', '2021-06-02 02:29:11'),
(10, 'Peugeot', NULL, '2021-06-01', 'A', '2021-06-02 02:29:47', '2021-06-02 02:29:47'),
(999, 'Sin marca', NULL, '2021-07-06', 'A', '2021-07-06 20:08:54', '2021-07-06 20:08:54'),
(1003, 'Prueba ', NULL, '2021-07-28', 'I', '2021-07-28 18:17:17', '2021-07-28 20:02:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `comprado` char(1) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecanicos`
--

CREATE TABLE `mecanicos` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `mecanicos`
--

INSERT INTO `mecanicos` (`id`, `persona_id`, `fecha_registro`, `estado`, `status`, `created_at`, `updated_at`) VALUES
(1, 34, '2021-06-03', 'A', 'D', '2021-06-03 19:21:43', '2021-08-23 23:15:45'),
(2, 40, '2021-06-03', 'A', 'D', '2021-06-03 19:21:43', '2021-08-24 19:25:27'),
(3, 41, '2021-06-03', 'A', 'O', '2021-06-03 19:39:51', '2021-11-10 04:08:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `menu` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icono` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pos` int(2) DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `id_seccion`, `menu`, `icono`, `url`, `pos`, `estado`) VALUES
(1, 0, 'Inicio', 'fa fa-laptop', 'inicio', 0, 'A'),
(2, 0, 'Gestión de Usuarios', 'fas fa-users', 'gestion-usuarios', 1, 'A'),
(3, 0, 'Clientes', 'fas fa-address-book', 'cliente', 2, 'A'),
(4, 0, 'Ordenes de Trabajo', 'fas fa-book', 'orden_trabajo', 3, 'A'),
(5, 1, 'Dashboard Admin', '#', 'inicio/administrador', 0, 'A'),
(6, 1, 'Dashboard Recepcionista', '#', 'inicio/recepcionista', 1, 'A'),
(7, 1, 'Dashboard Mecanico', '#', 'inicio/mecanico', 2, 'A'),
(8, 1, 'Dashboard Cliente', '#', 'inicio/cliente', 3, 'A'),
(9, 2, 'Nuevo Usuario', '#', 'gestion/nuevo', 0, 'A'),
(10, 2, 'Listar Usuarios', '#', 'gestion/listar', 1, 'A'),
(11, 2, 'Gestionar Rol', '#', 'gestion/rol', 2, 'I'),
(12, 3, 'Nuevo Cliente', '#', 'cliente/nuevo', 0, 'A'),
(13, 3, 'Listar Cliente', '#', 'cliente/listar', 1, 'A'),
(14, 3, 'Gestionar Vehículos', '#', 'cliente/vehiculo', 3, 'A'),
(15, 3, 'Listar Vehículos', '#', 'cliente/listar_vehiculo', 4, 'A'),
(16, 4, 'Nueva Orden', '#', 'orden_trabajo/nueva', 0, 'A'),
(17, 4, 'Gestionar Ordenes', '#', 'orden_trabajo/gestionar', 1, 'A'),
(18, 1, 'Dashboard J.Bodega', '#', 'inicio/bodega', 4, 'A'),
(19, 0, 'Gestión de Productos', 'fas fa-box-open', '#', 4, 'A'),
(20, 19, 'Categorías', '#', 'producto/categoria', 1, 'A'),
(21, 19, 'Nuevo Producto', '#', 'producto/nuevo', 2, 'A'),
(22, 19, 'Listar Productos', '#', 'producto/listar', 3, 'A'),
(23, 0, 'Proveedores', 'fas fa-truck', '#', 5, 'A'),
(24, 23, 'Nuevo Proveedor', '#', 'proveedor/nuevo', 1, 'A'),
(25, 23, 'Listar Proveedor', '#', 'proveedor/listar', 2, 'A'),
(27, 0, 'Compras', 'fas fa-shopping-cart', 'compra', 6, 'A'),
(28, 27, 'Nueva Compra', NULL, 'compra/nueva', 1, 'A'),
(29, 27, 'Listar compras', NULL, 'compra/listar', 2, 'A'),
(30, 4, 'Pendiente', NULL, 'orden_trabajo/pendiente', 0, 'A'),
(31, 4, 'Procesos', NULL, 'orden_trabajo/procesos', 1, 'A'),
(32, 4, 'Cancelado', NULL, 'orden_trabajo/cancelado', 2, 'A'),
(33, 4, 'Atendido', NULL, 'orden_trabajo/atentido', 3, 'A'),
(34, 0, 'Ventas', 'fas fa-money-bill-alt', 'venta', 7, 'A'),
(35, 34, 'Nueva Venta', NULL, 'venta/nueva', 1, 'A'),
(36, 34, 'Listar Ventas', NULL, 'venta/listar', 2, 'A'),
(37, 0, 'Kardex', 'fas fa-digital-tachograph', 'kardex', 8, 'A'),
(38, 37, 'Ver Kardex', '#', 'kardex/ver', 0, 'A'),
(39, 3, 'Gestionar Marcas', '#', 'cliente/marca', 2, 'A'),
(40, 2, 'Permisos', '#', 'gestion/permisos', 3, 'A'),
(41, 0, 'Reportes', 'fas fa-chart-pie', 'reportes', 9, 'A'),
(42, 41, 'Repuestos más Vendidos', '#', 'reportes/respuestos', 1, 'A'),
(43, 41, 'Ordenes De Trabajos', '#', 'reportes/ordenes', 2, 'A'),
(44, 41, 'Ventas mensuales', NULL, 'reportes/ventas_mensuales', 3, 'A'),
(45, 41, 'Repuestos por agotarse', '#', 'reportes/agotarse', 4, 'A'),
(46, 41, 'Ventas por categoría', '#', 'reportes/categoria', 5, 'A'),
(47, 41, 'Vehiculos reparados', '#', 'reportes/vehiculo', 6, 'A'),
(48, 0, 'Proyecciones', 'fas fa-chart-line', 'proyeccion', 10, 'A'),
(49, 48, 'Ventas', '#', 'proyeccion/ventas', 1, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `leido` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordentrabajo_averiasfallas`
--

CREATE TABLE `ordentrabajo_averiasfallas` (
  `id` int(11) NOT NULL,
  `orden_de_trabajo_id` int(11) DEFAULT NULL,
  `averias_fallas_id` int(11) DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ordentrabajo_averiasfallas`
--

INSERT INTO `ordentrabajo_averiasfallas` (`id`, `orden_de_trabajo_id`, `averias_fallas_id`, `estado`) VALUES
(1, 1, 3, 'A'),
(2, 2, 5, 'A'),
(3, 3, 1, 'A'),
(4, 3, 3, 'A'),
(5, 3, 2, 'A'),
(6, 3, 5, 'A'),
(7, 3, 4, 'A'),
(8, 4, 5, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_de_trabajos`
--

CREATE TABLE `orden_de_trabajos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vehiculo_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mecanico_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `suma` float DEFAULT NULL,
  `fecha_trabajo` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `fecha_trabajo_salida` date DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado_orden_id` int(11) DEFAULT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observacion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `facturado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `orden_de_trabajos`
--

INSERT INTO `orden_de_trabajos` (`id`, `cliente_id`, `vehiculo_id`, `usuario_id`, `mecanico_id`, `fecha`, `hora`, `descripcion`, `suma`, `fecha_trabajo`, `hora_inicio`, `fecha_trabajo_salida`, `hora_salida`, `estado`, `estado_orden_id`, `codigo`, `observacion`, `facturado`, `created_at`, `updated_at`) VALUES
(1, 2, 1009, 1, 3, '2021-11-08', '18:11:23', '', 25, '2021-11-08', '18:30:00', '2021-11-08', '19:00:00', 'A', 3, 'f5cd19e70', '', 'N', '2021-11-08 23:29:23', '2021-11-08 23:29:23'),
(2, 1, 1008, 1, 3, '2021-11-09', '20:11:30', '', 25, '2021-11-09', '20:33:00', '2021-11-10', '10:00:00', 'A', 4, 'dda368b39', '', 'N', '2021-11-10 01:23:30', '2021-11-17 23:24:44'),
(3, 2, 1010, 1, 3, '2021-11-09', '20:11:19', '', 161.5, '2021-11-09', '20:48:00', '2021-11-10', '12:00:00', 'A', 4, '096d427ad', '', 'N', '2021-11-10 01:49:19', '2021-11-10 04:08:44'),
(4, 1, 1008, 1, 2, '2021-11-17', '18:11:53', '', 25, '2021-11-17', '18:21:00', '2021-11-17', '18:27:00', 'A', 3, 'ed2f2e58e', '', 'N', '2021-11-17 23:21:53', '2021-11-17 23:21:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `acceso` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `rol_id`, `menu_id`, `acceso`, `estado`) VALUES
(1, 1, 1, 'S', 'A'),
(2, 1, 5, 'S', 'A'),
(3, 1, 2, 'S', 'A'),
(4, 1, 10, 'S', 'A'),
(6, 1, 3, 'S', 'A'),
(7, 1, 12, 'S', 'A'),
(8, 1, 13, 'S', 'A'),
(9, 1, 14, 'S', 'A'),
(10, 1, 15, 'S', 'A'),
(11, 1, 4, 'S', 'A'),
(12, 1, 16, 'S', 'A'),
(13, 1, 17, 'S', 'A'),
(14, 2, 1, 'S', 'A'),
(15, 2, 6, 'S', 'A'),
(16, 2, 3, 'S', 'A'),
(17, 2, 12, 'S', 'A'),
(18, 2, 13, 'S', 'A'),
(19, 2, 14, 'S', 'A'),
(20, 2, 15, 'S', 'A'),
(21, 2, 4, 'S', 'A'),
(22, 2, 16, 'S', 'A'),
(23, 2, 17, 'S', 'A'),
(24, 5, 1, 'S', 'A'),
(25, 5, 18, 'S', 'A'),
(26, 5, 19, 'S', 'A'),
(27, 5, 20, 'S', 'A'),
(28, 5, 21, 'S', 'A'),
(30, 1, 19, 'S', 'A'),
(31, 1, 20, 'S', 'A'),
(32, 1, 21, 'S', 'A'),
(33, 1, 22, 'S', 'A'),
(34, 1, 23, 'S', 'A'),
(35, 1, 24, 'S', 'A'),
(36, 1, 25, 'S', 'A'),
(37, 5, 23, 'S', 'A'),
(38, 5, 24, 'S', 'A'),
(39, 5, 25, 'S', 'A'),
(42, 1, 9, 'S', 'A'),
(43, 1, 27, 'S', 'A'),
(44, 1, 28, 'S', 'A'),
(45, 5, 27, 'S', 'A'),
(46, 5, 28, 'S', 'A'),
(47, 1, 29, 'S', 'A'),
(48, 5, 29, 'S', 'A'),
(49, 3, 1, 'S', 'A'),
(50, 3, 7, 'S', 'A'),
(51, 3, 4, 'S', 'A'),
(53, 3, 31, 'S', 'A'),
(54, 3, 32, 'S', 'A'),
(55, 3, 33, 'S', 'A'),
(56, 1, 34, 'S', 'A'),
(57, 1, 35, 'S', 'A'),
(58, 1, 36, 'S', 'A'),
(61, 5, 37, 'S', 'A'),
(62, 5, 38, 'S', 'A'),
(63, 1, 39, 'S', 'A'),
(64, 1, 40, 'S', 'A'),
(66, 5, 22, 'S', 'A'),
(67, 1, 41, 'S', 'N'),
(68, 1, 42, 'S', 'A'),
(69, 1, 43, 'S', 'A'),
(72, 1, 44, 'S', 'A'),
(73, 1, 45, 'S', 'A'),
(74, 1, 46, 'S', 'A'),
(75, 1, 47, 'S', 'A'),
(76, 1, 48, 'S', 'A'),
(77, 1, 49, 'S', 'A'),
(78, 3, 30, 'S', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombres` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellidos` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `cedula`, `nombres`, `apellidos`, `telefono`, `correo`, `direccion`, `estado`, `created_at`, `updated_at`) VALUES
(1, '0930287768', 'Dalton', 'Santistevan', '0999314187', 'dalton@gmail.com', 'La Libertad', 'A', '2021-04-26 19:53:45', '2021-04-26 19:53:45'),
(2, '2400454721', 'Juan', 'Galarza', '0968772111', 'juan@gmail.com', 'La Libertad', 'A', '2021-04-26 19:54:25', '2021-04-26 19:54:25'),
(3, '2454544455', 'Karen', 'Villón', '0983456784', 'karen@gmail.com', 'Muey', 'A', '2021-04-29 17:59:59', '2021-04-29 17:59:59'),
(18, '2345344340', 'Fernando', 'Hidalgo', '0959519754', 'fernano@gmail.com', 'Posorja', 'A', '2021-05-03 18:21:57', '2021-05-03 18:21:57'),
(33, '1010101010', 'Christian', 'Vergara', '0983894110', 'chiliwili@gmail.com', 'La Libertad', 'A', '2021-05-20 20:54:35', '2021-05-20 20:54:35'),
(34, '0911855344', 'Alfonzo', 'Romero', '0918243957', 'alfredo@gmail.com', 'Guayaquil', 'A', '2021-05-25 19:14:20', '2021-05-25 19:14:20'),
(40, '2451399808', 'Jorge', 'Velez', '0996173833', 'jorge@hotmail.com', 'Guayaquil', 'A', '2021-05-25 19:55:57', '2021-05-25 19:55:57'),
(41, '1704997012', 'Julio', 'Carbo', '0989656543', 'julio@gmail.com', 'Salinas', 'A', '2021-06-03 19:39:51', '2021-06-03 19:39:51'),
(52, '2400287716', 'Josue', 'Tomala', '0987654322', 'josue@gmail.com', 'Tambo', 'A', '2021-08-09 23:52:07', '2021-08-09 23:52:07'),
(53, '2450162348', 'Carla', 'Sanchez', '0987667677', 'carla@hotmail.com', 'Muey', 'A', '2021-08-09 23:53:35', '2021-08-09 23:53:35'),
(54, '2450042805', 'Alejandro', 'Suarez', '0985421236', 'alejandro@gmail.com', 'Salinas', 'A', '2021-08-09 23:56:51', '2021-08-09 23:56:51'),
(55, '1711402980', 'Maria', 'Lopez', '0986653745', 'maria@outlook.com', 'Quito', 'A', '2021-08-09 23:59:44', '2021-08-09 23:59:44'),
(56, '2400512516', 'Freddy', 'Veliz', '0988115522', 'freddy@hotmail.com', 'Guayaquil', 'A', '2021-08-10 00:09:57', '2021-08-10 00:09:57'),
(58, '0912091209', 'Pablo', 'Pincay', '0987654567', 'pablo@gmail.com', 'Manta', 'A', '2021-08-12 15:38:23', '2021-08-12 15:38:23'),
(59, '2466017767', 'Fabian', 'Rosales', '5678967897', 'ivan@gmail.com', 'Cuenca', 'A', '2021-08-24 16:11:47', '2021-08-24 16:11:47'),
(60, '0922515663', 'alicia', 'andradre', '0678567896', 'ali@gmail.com', 'guayas', 'A', '2021-08-24 20:15:43', '2021-08-24 20:15:43'),
(61, '3456783456', 'alicia', 'andradre', '4567845678', 'ali@gmail.com', 'guayas', 'A', '2021-08-24 20:17:39', '2021-08-24 20:17:39'),
(62, '2345672345', 'kevin', 'romero', '4567856785', 'juan@gmail.com', 'sdfcgvhbj', 'A', '2021-08-24 21:14:42', '2021-08-24 21:14:42'),
(63, '0930287760', 'dalton Zack', 'santistevan', '5678967897', 'dalton@outlook.com', 'burro', 'A', '2021-10-08 21:23:39', '2021-10-08 21:24:20'),
(64, '2400454720', 'juan carlos', 'galarza', '5467857849', 'juan@outlook.com', 'la libertad', 'A', '2021-10-28 00:09:26', '2021-10-28 00:09:26'),
(65, '2450098708', 'Marcelo', 'feocococ', '5678867897', 'm@gmail.com', 'csdcsdcmsdmc sdmnc sd', 'A', '2021-11-18 22:17:23', '2021-11-18 22:17:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `codigo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock` int(5) DEFAULT NULL,
  `stock_minimo` int(4) DEFAULT NULL,
  `stock_maximo` int(4) DEFAULT NULL,
  `precio_compra` double DEFAULT NULL,
  `precio_venta` double DEFAULT NULL,
  `margen` double DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `codigo`, `nombre`, `img`, `descripcion`, `stock`, `stock_minimo`, `stock_maximo`, `precio_compra`, `precio_venta`, `margen`, `fecha`, `estado`, `created_at`, `updated_at`) VALUES
(1, 5, 'cb-001', 'Chapas de Biela', 'repuesto01.png', 'Chapas', 0, 8, 20, 0, 18, 18, '2021-05-03', 'A', '2021-05-03 22:43:29', '2021-09-03 00:53:38'),
(3, 2, 'll-123', 'Llantas VanContact', 'repuesto02.png', 'Llantas', 0, 5, 10, 0, 54.66, 54.66, '2021-05-04', 'A', '2021-05-05 04:44:12', '2021-08-24 00:19:18'),
(4, 5, 'cd-124', 'Correa de Distribución', 'repuesto03.jpg', 'Correa', 0, 5, 10, 0, 35.2, 35.2, '2021-05-04', 'A', '2021-05-05 05:30:51', '2021-08-24 00:19:18'),
(5, 4, 'av-567', 'Aceite Valvoline', 'aceite.png', 'Aceite', 195, 3, 10, 15.5, 15.5, -15.5, '2021-05-06', 'A', '2021-05-07 02:58:11', '2021-11-24 06:25:44'),
(6, 5, 'aa-423', 'Amortiguador', 'repuesto04.jpg', 'Amortiguador', 0, 9, 20, 0, 27.62, 27.62, '2021-05-06', 'A', '2021-05-07 03:04:21', '2021-08-24 20:00:28'),
(7, 3, 'mm-789', 'Motor Aveo', 'repuesto05.jpg', 'Aveo', 0, 3, 5, 0, 140.8, 140.8, '2021-05-06', 'A', '2021-05-07 03:08:54', '2021-08-24 00:20:25'),
(8, 5, 'pf-987', 'Pastillas de Freno', 'repuesto06.jpg', 'Pastillas de freno', 0, 3, 10, 0, 80.25, 80.25, '2021-06-01', 'A', '2021-06-02 00:38:21', '2021-08-23 23:28:08'),
(9, 5, 'df-008', 'Disco de Freno', 'repuesto08.jpg', 'Disco', 0, 3, 20, 0, 26.27, 26.27, '2021-06-01', 'A', '2021-06-02 00:42:12', '2021-08-24 19:19:50'),
(10, 5, 'zf-009', 'Zapatillas de Freno', 'repuesto10.jpg', 'Zapatillas', 0, 3, 20, 0, 18.8, 18.8, '2021-06-01', 'A', '2021-06-02 00:42:12', '2021-08-23 23:08:07'),
(11, 6, 'fa-666', 'Filtro de Aire', 'repuesto09.jpg', 'Filtro de aire', 0, 5, 25, 0, 6.5, 6.5, '2021-06-01', 'A', '2021-06-02 01:03:18', '2021-08-23 23:23:39'),
(12, 6, 'fa-456', 'Filtro de Aceite', 'repuesto21.png', 'Filtro de Aceite', 0, 5, 10, 0, 6.6, 6.6, '2021-06-01', 'A', '2021-06-02 01:09:21', '2021-08-23 23:24:22'),
(13, 5, 'pl-222', 'Pluma Limpiadoras', 'repuesto11.jpg', 'Pluma Limpiadoras', 0, 6, 10, 0, 6.5, 6.5, '2021-06-01', 'A', '2021-06-02 01:09:21', '2021-08-23 23:27:22'),
(14, 5, 'ee-044', 'Embrague', 'repuesto12.jpg', 'Embrague', 0, 8, 10, 0, 53.11, 53.11, '2021-06-01', 'A', '2021-06-02 01:17:27', '2021-09-03 00:53:38'),
(15, 5, 'bj-099', 'Bujia', 'repuesto13.jpg', 'Bujia', 0, 5, 10, 0, 17.89, 17.89, '2021-06-01', 'A', '2021-06-02 01:17:27', '2021-08-24 06:29:52'),
(16, 5, 'pt-511', 'Pistón', 'repuesto14.jpg', 'Pistón', 0, 5, 10, 0, 55.2, 55.2, '2021-06-01', 'A', '2021-06-02 01:25:23', '2021-08-23 23:35:37'),
(17, 5, 'bn-903', 'Bobina', 'repuesto15.jpg', NULL, 0, 5, 20, 0, 35, 35, '2021-06-01', 'A', '2021-06-02 01:25:23', '2021-08-23 23:17:19'),
(18, 5, 'rl-007', 'Ruliman', 'repuesto16.jpeg', 'Ruliman', 0, 5, 20, 0, 10, 10, '2021-06-01', 'A', '2021-06-02 01:31:46', '2021-08-23 23:27:21'),
(19, 12, 'bo-010', 'Bomba de Agua', 'repuesto18.jpg', 'Bomba de Agua', 0, 5, 20, 0, 26.15, 26.15, '2021-06-01', 'A', '2021-06-02 01:31:46', '2021-08-23 22:50:33'),
(20, 12, 'bc-809', 'Bomba de Combustible', 'repuesto20.png', 'Bomba de Combustible', 0, 3, 10, 0, 12.6, 12.6, '2021-06-01', 'A', '2021-06-02 01:37:42', '2021-08-23 22:50:33'),
(21, 12, 'ba-871', 'Bomba de Aceite', 'repuesto19.jpg', 'Bomba de Aceite', 0, 3, 10, 0, 19.25, 19.25, '2021-06-01', 'A', '2021-06-02 01:37:42', '2021-08-23 23:17:19'),
(24, 1, 'asi-458', 'Asiento de Carro', 'repuesto24.jpg', 'Asiento 3D', 0, 5, 50, 0, 26.6, 26.6, '2021-07-01', 'A', '2021-07-02 00:21:44', '2021-08-24 20:00:28'),
(33, 1, 'tryu4333', 'Hgdbnjkslk', 'hacker.jpg', 'hgebjndmk', 0, 5, 10, 0, 60, 60, '2021-11-18', 'A', '2021-11-18 22:14:38', '2021-11-18 22:14:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `ruc` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razon_social` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `telefono` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `ruc`, `razon_social`, `direccion`, `correo`, `fecha`, `telefono`, `estado`, `created_at`, `updated_at`) VALUES
(12, '2147483647543', 'AEADE SA', 'Quito', 'aeade@outlook.com', '2021-07-27', '0427856789', 'A', '2021-05-07 05:24:46', '2021-07-27 20:14:00'),
(15, '2400129439001', 'CONAUTO SA', 'Loja', 'conauto@gmail.com', '2021-05-11', '0943485837', 'A', '2021-05-11 23:55:27', '2021-05-11 23:55:27'),
(16, '0847855545522', 'VALVOLINE SA', 'Quito', 'valvoline@hotmail.com', '2021-05-27', '0984525688', 'A', '2021-05-27 20:10:40', '2021-05-27 20:10:40'),
(17, '0595954885955', 'MAVESA SA', 'Guayaquil', 'mavesa@gmail.com', '2021-05-27', '0987452655', 'A', '2021-05-27 20:11:28', '2021-05-27 20:11:28'),
(18, '0528855562525', 'CHEVROLET SA', 'Los Rios', 'chevrolet@yahoo.com', '2021-05-27', '0963424243', 'A', '2021-05-27 20:12:09', '2021-05-27 20:12:09'),
(20, '2458457787542', 'PRODUCTOS SA', 'productos', 'productos@gmail.com', '2021-06-28', '0987454752', 'A', '2021-06-28 19:24:33', '2021-07-27 20:26:26'),
(21, '2525252510222', 'prueba', 'prueba', 'prueba@gmail.com', '2021-08-16', '0985255454', 'A', '2021-08-16 19:15:21', '2021-08-16 19:15:21'),
(22, '2245254022555', 'prueba', 'prueba', 'ali@gmail.com', '2021-08-24', '0987451365', 'A', '2021-08-24 19:27:37', '2021-08-24 19:27:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `cargo` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `cargo`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Administra todo el sistema', 'A', '2021-04-26 19:48:10', '2021-04-26 19:48:10'),
(2, 'Recepcionista', 'Se encarga de todos los menos ecexto el registro de modulo de usuario', 'A', '2021-04-26 19:48:48', '2021-04-26 19:48:48'),
(3, 'Mecanico', 'Se encarga de la gestion de estado de orden', 'A', '2021-04-26 19:49:07', '2021-04-26 19:49:07'),
(5, 'Jefe de Bodega', 'Se encarga de la administració y gestión de los productos de la empresa', 'A', '2021-05-03 18:20:41', '2021-05-03 18:20:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `suma` float DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id` int(11) NOT NULL,
  `tipo_movimiento` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`id`, `tipo_movimiento`, `fecha`, `descripcion`, `venta_id`, `compra_id`, `created_at`, `updated_at`) VALUES
(1, 'E', '2021-11-18', 'Compra con n° de serie 3456788', NULL, 1, '2021-11-19 03:06:19', '2021-11-19 03:06:19'),
(2, 'E', '2021-11-18', 'Compra con n° de serie 8999898989', NULL, 2, '2021-11-19 03:07:22', '2021-11-19 03:07:22'),
(3, 'E', '2021-11-23', 'Compra con n° de serie 657890789', NULL, 3, '2021-11-24 04:57:05', '2021-11-24 04:57:05'),
(4, 'S', '2021-11-24', 'Venta con n° de serie 5467', 1, NULL, '2021-11-24 06:25:44', '2021-11-24 06:25:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `usuario` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clave` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conf_clave` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `persona_id`, `rol_id`, `usuario`, `img`, `clave`, `conf_clave`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'dalton', '0930287768.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-04-26 19:58:01', '2021-04-26 19:58:01'),
(2, 2, 1, 'juan', 'FOTOCARNET.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-04-26 19:59:14', '2021-04-19 19:59:14'),
(3, 3, 2, 'karen', 'default.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-04-29 18:01:25', '2021-04-29 18:01:25'),
(4, 18, 5, 'fernando', 'default.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-05-03 18:23:35', '2021-05-03 18:23:35'),
(16, 33, 1, 'christian', 'chiliwili.jpg', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'A', '2021-05-20 20:54:35', '2021-05-20 20:54:35'),
(20, 34, 3, 'alfonzo', 'default.jpg', '300074621462bc04be6c83903577bc879e8b21c0f0bb0248c58442a2594b2b26', '300074621462bc04be6c83903577bc879e8b21c0f0bb0248c58442a2594b2b26', 'A', '2021-05-25 19:17:55', '2021-05-25 19:17:55'),
(23, 41, 3, 'julio', 'julio.jpg', '901be86d450c504e8555ffeeeab1e06b926c8785fd99ef382c1310b7c66bc167', '901be86d450c504e8555ffeeeab1e06b926c8785fd99ef382c1310b7c66bc167', 'A', '2021-06-03 19:39:51', '2021-06-03 19:39:51'),
(26, 40, 3, 'jorge', 'default.jpg', '67c888af8ad80f0232832431fb0bbb478f12740ff8b451d8d4ce0238a2d8b63a', '67c888af8ad80f0232832431fb0bbb478f12740ff8b451d8d4ce0238a2d8b63a', 'A', '2021-06-27 01:07:25', '2021-06-27 01:07:25'),
(31, 65, 5, 'marcelo', 'hacker.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-11-18 22:17:23', '2021-11-18 22:17:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `placa` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modelo` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kilometro` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `libre` char(1) COLLATE utf8_unicode_ci DEFAULT 'S',
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `marca_id`, `placa`, `modelo`, `color`, `kilometro`, `libre`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'AZY-123', 'Ranger 4x4', 'Negro', '1300km', 'N', 'A', '2021-05-17 19:13:39', '2021-08-23 22:43:52'),
(2, 2, 'ABC-123', 'Mazda2', 'Gris', '1000km', 'N', 'A', '2021-05-17 20:47:28', '2021-08-23 22:44:07'),
(7, 3, 'XYS-1456', 'Forza2', 'Rojo', '2200km', 'N', 'A', '2021-05-17 21:03:24', '2021-08-23 22:44:00'),
(11, 1, 'VBV-5585', 'F50 4x4', 'Negro', '120000km', 'N', 'A', '2021-05-20 17:59:00', '2021-08-23 22:44:12'),
(14, 3, 'BHN-6565', 'Gran Vitara', 'Negro', '500km', 'N', 'A', '2021-05-20 18:49:13', '2021-08-23 22:44:24'),
(15, 4, 'AAA-0101', 'Spark', 'Azul', '46000km', 'N', 'A', '2021-05-20 18:49:58', '2021-08-23 22:44:35'),
(16, 7, 'DDD-0202', 'Elantra', 'Cafe', '200km', 'N', 'A', '2021-05-20 18:51:02', '2021-08-24 19:22:31'),
(20, 10, 'ECR-5859', 'Dsdf', 'Rojo', '6600km', 'N', 'A', '2021-06-24 21:11:11', '2021-08-24 19:54:04'),
(999, 999, 'sin plac', NULL, NULL, NULL, 'S', 'A', '2021-07-06 20:09:51', '2021-07-06 20:09:51'),
(1007, 3, 'ZZZ-777', 'Hgfe', 'Rojo', '12000km', 'N', 'A', '2021-08-24 16:13:58', '2021-08-24 16:14:10'),
(1008, 4, 'CCC-333', 'Fxes', 'Rojo', '120000km', 'N', 'A', '2021-10-20 03:29:14', '2021-10-20 03:29:24'),
(1009, 4, 'BBB-0000', 'Jdhbcjhbdhcj', 'Rojo', '467km', 'N', 'A', '2021-10-28 00:07:08', '2021-10-28 00:09:49'),
(1010, 4, 'BBB-0001', 'Che-001', 'Azul', '1459599km', 'N', 'A', '2021-10-28 00:07:42', '2021-10-28 00:09:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `serie` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `iva` float DEFAULT NULL,
  `descuento_porcentaje` float DEFAULT NULL,
  `descuento_efectivo` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `fecha_venta` date DEFAULT NULL,
  `hora_venta` time DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `serie`, `usuario_id`, `cliente_id`, `empresa_id`, `subtotal`, `iva`, `descuento_porcentaje`, `descuento_efectivo`, `total`, `fecha_venta`, `hora_venta`, `estado`, `created_at`, `updated_at`) VALUES
(1, '5467', 1, 1, 1, 77.5, 9.3, NULL, 0, 86.8, '2021-11-24', '01:25:44', 'A', '2021-11-24 06:25:44', '2021-11-24 06:25:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actividad_orden` (`orden_id`);

--
-- Indices de la tabla `averias_fallas`
--
ALTER TABLE `averias_fallas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_persona` (`persona_id`);

--
-- Indices de la tabla `clientes_vehiculos`
--
ALTER TABLE `clientes_vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ch_cliente` (`cliente_id`),
  ADD KEY `ch_vehiculo` (`vehiculo_id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compras_proveedor` (`proveedor_id`),
  ADD KEY `fk_compras_usuarios` (`usuario_id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_compra_compra` (`compra_id`),
  ADD KEY `fk_detalle_compra_producto` (`producto_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_venta_venta` (`venta_id`),
  ADD KEY `fk_detalle_venta_producto` (`producto_id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_usuario` (`usuario_id`);

--
-- Indices de la tabla `estado_orden`
--
ALTER TABLE `estado_orden`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventario_producto` (`producto_id`),
  ADD KEY `fk_inventario_transaccion` (`transaccion_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_materiales_orden_de_trabajo` (`orden_id`),
  ADD KEY `fk_materiales_producto` (`producto_id`);

--
-- Indices de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mecanico_personas` (`persona_id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notificaciones_rol` (`rol_id`);

--
-- Indices de la tabla `ordentrabajo_averiasfallas`
--
ALTER TABLE `ordentrabajo_averiasfallas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ordentrabajo_averiasfallas_averias_fallas` (`averias_fallas_id`),
  ADD KEY `fk_ordentrabajo_averiasfallas_orden_trabajo` (`orden_de_trabajo_id`);

--
-- Indices de la tabla `orden_de_trabajos`
--
ALTER TABLE `orden_de_trabajos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orden_de_trabajos_clientes` (`cliente_id`),
  ADD KEY `fk_orden_de_trabajos_vehiculos` (`vehiculo_id`),
  ADD KEY `fk_orden_de_trabajos_usuarios` (`usuario_id`),
  ADD KEY `fk_orden_de_trabajos_mecanico` (`mecanico_id`),
  ADD KEY `fk_orden_de_trabajos_estado_orden` (`estado_orden_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permisos_rol` (`rol_id`),
  ADD KEY `permisos_menu` (`menu_id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_categoria` (`categoria_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_servicios_orden` (`orden_id`),
  ADD KEY `fk_servicios_venta` (`venta_id`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaccion_ventas` (`venta_id`),
  ADD KEY `fk_transaccion_compras` (`compra_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_persona` (`persona_id`),
  ADD KEY `usuario_rol` (`rol_id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehiculos_marca` (`marca_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ventas_usuario` (`usuario_id`),
  ADD KEY `fk_ventas_cliente` (`cliente_id`),
  ADD KEY `fk_ventas_empresa` (`empresa_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `averias_fallas`
--
ALTER TABLE `averias_fallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes_vehiculos`
--
ALTER TABLE `clientes_vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estado_orden`
--
ALTER TABLE `estado_orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ordentrabajo_averiasfallas`
--
ALTER TABLE `ordentrabajo_averiasfallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `orden_de_trabajos`
--
ALTER TABLE `orden_de_trabajos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1011;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_actividad_orden` FOREIGN KEY (`orden_id`) REFERENCES `orden_de_trabajos` (`id`);

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `cliente_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `clientes_vehiculos`
--
ALTER TABLE `clientes_vehiculos`
  ADD CONSTRAINT `ch_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ch_vehiculo` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_compras_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `fk_compras_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `fk_detalle_compra_compra` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `fk_detalle_compra_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_detalle_venta_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `fk_empresa_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_inventario_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_inventario_transaccion` FOREIGN KEY (`transaccion_id`) REFERENCES `transacciones` (`id`);

--
-- Filtros para la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `fk_materiales_orden_de_trabajo` FOREIGN KEY (`orden_id`) REFERENCES `orden_de_trabajos` (`id`),
  ADD CONSTRAINT `fk_materiales_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD CONSTRAINT `fk_mecanico_personas` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notificaciones_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `ordentrabajo_averiasfallas`
--
ALTER TABLE `ordentrabajo_averiasfallas`
  ADD CONSTRAINT `fk_ordentrabajo_averiasfallas_averias_fallas` FOREIGN KEY (`averias_fallas_id`) REFERENCES `averias_fallas` (`id`),
  ADD CONSTRAINT `fk_ordentrabajo_averiasfallas_orden_trabajo` FOREIGN KEY (`orden_de_trabajo_id`) REFERENCES `orden_de_trabajos` (`id`);

--
-- Filtros para la tabla `orden_de_trabajos`
--
ALTER TABLE `orden_de_trabajos`
  ADD CONSTRAINT `fk_orden_de_trabajos_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_orden_de_trabajos_estado_orden` FOREIGN KEY (`estado_orden_id`) REFERENCES `estado_orden` (`id`),
  ADD CONSTRAINT `fk_orden_de_trabajos_mecanico` FOREIGN KEY (`mecanico_id`) REFERENCES `mecanicos` (`id`),
  ADD CONSTRAINT `fk_orden_de_trabajos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_orden_de_trabajos_vehiculos` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  ADD CONSTRAINT `permisos_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `fk_servicios_orden` FOREIGN KEY (`orden_id`) REFERENCES `orden_de_trabajos` (`id`),
  ADD CONSTRAINT `fk_servicios_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `fk_transaccion_compras` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `fk_transaccion_ventas` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_marca` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_ventas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `fk_ventas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
