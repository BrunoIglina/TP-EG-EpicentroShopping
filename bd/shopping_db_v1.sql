-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3309
-- Tiempo de generación: 20-02-2025 a las 16:03:07
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
-- Base de datos: `shopping_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locales`
--

CREATE TABLE `locales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ubicacion` varchar(50) NOT NULL,
  `rubro` varchar(20) NOT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `locales`
--

INSERT INTO `locales` (`id`, `nombre`, `ubicacion`, `rubro`, `idUsuario`) VALUES
(1, 'Burger King', 'Local 22', 'Alimentos', 6),
(2, 'Mostaza', 'Local 21', 'Alimentos', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedades`
--

CREATE TABLE `novedades` (
  `id` int(11) NOT NULL,
  `tituloNovedad` varchar(30) NOT NULL,
  `textoNovedad` varchar(200) NOT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date NOT NULL,
  `categoria` enum('Inicial','Medium','Premium') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `novedades`
--

INSERT INTO `novedades` (`id`, `tituloNovedad`, `textoNovedad`, `fecha_desde`, `fecha_hasta`, `categoria`) VALUES
(1, 'Navidad en Epicentro', 'Descuento en juguetes', '2025-02-12', '2025-12-25', 'Inicial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `local_id` int(11) DEFAULT NULL,
  `textoPromo` varchar(200) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `diasSemana` varchar(70) NOT NULL,
  `categoriaCliente` enum('Inicial','Medium','Premium') NOT NULL,
  `estadoPromo` set('Pendiente','Aprobada','Denegada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id`, `local_id`, `textoPromo`, `fecha_inicio`, `fecha_fin`, `diasSemana`, `categoriaCliente`, `estadoPromo`) VALUES
(1, 1, '2x1', '2025-02-12', '2025-02-26', 'Lunes,Martes,Miércoles', 'Inicial', 'Aprobada'),
(2, 2, '2x1', '2025-02-12', '2025-02-26', 'Lunes,Martes,Miércoles', 'Inicial', 'Aprobada'),
(3, 2, '4x2', '2025-02-12', '2025-02-26', 'Lunes,Martes,Miércoles', 'Inicial', 'Aprobada'),
(4, 1, '25% OFF', '2025-02-13', '2025-02-19', 'Lunes,Martes,Domingo', 'Inicial', 'Pendiente'),
(5, 2, '10% OFF', '2025-02-14', '2025-02-26', 'Miércoles,Jueves,Viernes', 'Inicial', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones_cliente`
--

CREATE TABLE `promociones_cliente` (
  `idCliente` int(11) NOT NULL,
  `idPromocion` int(11) NOT NULL,
  `fechaUsoPromo` date NOT NULL,
  `estado` enum('enviada','aceptada','rechazada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones_cliente`
--

INSERT INTO `promociones_cliente` (`idCliente`, `idPromocion`, `fechaUsoPromo`, `estado`) VALUES
(8, 1, '2025-02-12', 'enviada'),
(8, 2, '2025-02-12', 'enviada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` enum('Administrador','Dueño','Cliente') NOT NULL,
  `categoria` enum('Inicial','Medium','Premium') DEFAULT NULL,
  `validado` tinyint(1) DEFAULT 0,
  `token_validacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `tipo`, `categoria`, `validado`, `token_validacion`) VALUES
(6, 'brunoniglina@gmail.com', '$2y$10$riQzskTvG5NpOzV1u4MZyuGzL8iSOcykqHUbhkJeo6GrZ9Ugrq9wW', 'Dueño', NULL, 1, NULL),
(8, 'brunitoiglina@gmail.com', '$2y$10$7GdSSRn8y8fezyK7knbWJOz6iFaYa0YTVFUZdQOURKhZHrujTBjya', 'Cliente', 'Inicial', 1, 'fea8d43c168eeaa35c16e29a7f7fabac'),
(11, 'biglina@gmail.com', '$2y$10$XXcWtYmMTIarCElwVLLHTed.xFrYA0A0FGOlnmwbW.i4z8AsRq3m2', 'Administrador', NULL, 1, NULL),
(12, 'brunoiglinadev@gmail.com', '$2y$10$IPohjLmYtZvaobVF0CD1POlWBHYBGQHJQpOOgG5MCvm8mxPEm9o3e', 'Cliente', 'Inicial', 0, '2367d3d031c984e72f9cdb310acff7c4');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `locales`
--
ALTER TABLE `locales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `novedades`
--
ALTER TABLE `novedades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `local_id` (`local_id`);

--
-- Indices de la tabla `promociones_cliente`
--
ALTER TABLE `promociones_cliente`
  ADD PRIMARY KEY (`idCliente`,`idPromocion`),
  ADD KEY `fk_promocion` (`idPromocion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `locales`
--
ALTER TABLE `locales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `novedades`
--
ALTER TABLE `novedades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `locales`
--
ALTER TABLE `locales`
  ADD CONSTRAINT `locales_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD CONSTRAINT `promociones_ibfk_1` FOREIGN KEY (`local_id`) REFERENCES `locales` (`id`);

--
-- Filtros para la tabla `promociones_cliente`
--
ALTER TABLE `promociones_cliente`
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`idCliente`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_promocion` FOREIGN KEY (`idPromocion`) REFERENCES `promociones` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
