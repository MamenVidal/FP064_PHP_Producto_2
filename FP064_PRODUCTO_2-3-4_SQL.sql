-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-01-2022 a las 14:18:35
-- Versión del servidor: 10.5.8-MariaDB-1:10.5.8+maria~buster
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Actos`
--

CREATE TABLE `Actos` (
  `Id_acto` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Titulo` varchar(100) NOT NULL,
  `Descripcion_corta` varchar(2000) NOT NULL,
  `Descripcion_larga` text NOT NULL,
  `Num_asistentes` int(11) NOT NULL,
  `Id_tipo_acto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Documentacion`
--

CREATE TABLE `Documentacion` (
  `Id_presentacion` int(11) NOT NULL,
  `Id_acto` int(11) NOT NULL,
  `Localizacion_documentacion` varchar(100) NOT NULL,
  `Orden` int(11) NOT NULL,
  `Id_persona` int(11) NOT NULL,
  `Titulo_documento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Inscritos`
--

CREATE TABLE `Inscritos` (
  `Id_inscripcion` int(11) NOT NULL,
  `Id_persona` int(11) NOT NULL,
  `id_acto` int(11) NOT NULL,
  `Fecha_inscripcion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Lista_Ponentes`
--

CREATE TABLE `Lista_Ponentes` (
  `id_ponente` int(11) NOT NULL,
  `Id_persona` int(11) NOT NULL,
  `Id_acto` int(11) NOT NULL,
  `Orden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Personas`
--

CREATE TABLE `Personas` (
  `Id_persona` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido1` varchar(50) NOT NULL,
  `Apellido2` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tipos_usuarios`
--

CREATE TABLE `Tipos_usuarios` (
  `Id_tipo_usuario` int(11) NOT NULL,
  `Descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tipo_acto`
--

CREATE TABLE `Tipo_acto` (
  `Id_tipo_acto` int(11) NOT NULL,
  `Descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `Id_usuario` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Id_Persona` int(11) NOT NULL,
  `Id_tipo_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Actos`
--
ALTER TABLE `Actos`
  ADD PRIMARY KEY (`Id_acto`),
  ADD KEY `FK_Actos_Id_Tipo_Acto` (`Id_tipo_acto`);

--
-- Indices de la tabla `Documentacion`
--
ALTER TABLE `Documentacion`
  ADD PRIMARY KEY (`Id_presentacion`),
  ADD KEY `FK_Documentacion_Id_Acto` (`Id_acto`),
  ADD KEY `FK_Documentacion_Id_Persona` (`Id_persona`);

--
-- Indices de la tabla `Inscritos`
--
ALTER TABLE `Inscritos`
  ADD PRIMARY KEY (`Id_inscripcion`),
  ADD KEY `FK_Inscritos_Id_Persona` (`Id_persona`),
  ADD KEY `FK_Inscritos_Id_Acto` (`id_acto`);

--
-- Indices de la tabla `Lista_Ponentes`
--
ALTER TABLE `Lista_Ponentes`
  ADD PRIMARY KEY (`id_ponente`),
  ADD KEY `FK_Lista_Ponentes_Id_Persona` (`Id_persona`),
  ADD KEY `FK_Lista_Ponentes_Id_Acto` (`Id_acto`);

--
-- Indices de la tabla `Personas`
--
ALTER TABLE `Personas`
  ADD PRIMARY KEY (`Id_persona`);

--
-- Indices de la tabla `Tipos_usuarios`
--
ALTER TABLE `Tipos_usuarios`
  ADD PRIMARY KEY (`Id_tipo_usuario`);

--
-- Indices de la tabla `Tipo_acto`
--
ALTER TABLE `Tipo_acto`
  ADD PRIMARY KEY (`Id_tipo_acto`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`Id_usuario`),
  ADD KEY `FK_Usuarios_Id_Persona` (`Id_Persona`),
  ADD KEY `FK_Usuarios_Id_Tipo_Usuarios` (`Id_tipo_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Actos`
--
ALTER TABLE `Actos`
  MODIFY `Id_acto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Documentacion`
--
ALTER TABLE `Documentacion`
  MODIFY `Id_presentacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Inscritos`
--
ALTER TABLE `Inscritos`
  MODIFY `Id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Lista_Ponentes`
--
ALTER TABLE `Lista_Ponentes`
  MODIFY `id_ponente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Personas`
--
ALTER TABLE `Personas`
  MODIFY `Id_persona` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Tipos_usuarios`
--
ALTER TABLE `Tipos_usuarios`
  MODIFY `Id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Tipo_acto`
--
ALTER TABLE `Tipo_acto`
  MODIFY `Id_tipo_acto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Actos`
--
ALTER TABLE `Actos`
  ADD CONSTRAINT `FK_Actos_Id_Tipo_Acto` FOREIGN KEY (`Id_tipo_acto`) REFERENCES `Tipo_acto` (`Id_tipo_acto`);

--
-- Filtros para la tabla `Documentacion`
--
ALTER TABLE `Documentacion`
  ADD CONSTRAINT `FK_Documentacion_Id_Acto` FOREIGN KEY (`Id_acto`) REFERENCES `Actos` (`Id_acto`),
  ADD CONSTRAINT `FK_Documentacion_Id_Persona` FOREIGN KEY (`Id_persona`) REFERENCES `Personas` (`Id_persona`);

--
-- Filtros para la tabla `Inscritos`
--
ALTER TABLE `Inscritos`
  ADD CONSTRAINT `FK_Inscritos_Id_Acto` FOREIGN KEY (`id_acto`) REFERENCES `Actos` (`Id_acto`),
  ADD CONSTRAINT `FK_Inscritos_Id_Persona` FOREIGN KEY (`Id_persona`) REFERENCES `Personas` (`Id_persona`);

--
-- Filtros para la tabla `Lista_Ponentes`
--
ALTER TABLE `Lista_Ponentes`
  ADD CONSTRAINT `FK_Lista_Ponentes_Id_Acto` FOREIGN KEY (`Id_acto`) REFERENCES `Actos` (`Id_acto`),
  ADD CONSTRAINT `FK_Lista_Ponentes_Id_Persona` FOREIGN KEY (`Id_persona`) REFERENCES `Personas` (`Id_persona`);

--
-- Filtros para la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD CONSTRAINT `FK_Usuarios_Id_Persona` FOREIGN KEY (`Id_Persona`) REFERENCES `Personas` (`Id_persona`),
  ADD CONSTRAINT `FK_Usuarios_Id_Tipo_Usuarios` FOREIGN KEY (`Id_tipo_usuario`) REFERENCES `Tipos_usuarios` (`Id_tipo_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
