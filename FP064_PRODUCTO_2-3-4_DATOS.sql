-- Insertar datos iniciales en la tabla `Tipo_acto`
INSERT INTO `Tipo_acto` (`Id_tipo_acto`, `Descripcion`) VALUES
(1, 'Conferencia'),
(2, 'Seminario'),
(3, 'Mesa Redonda');

-- Insertar datos iniciales en la tabla `Personas`
INSERT INTO `Personas` (`Id_persona`, `Nombre`, `Apellido1`, `Apellido2`) VALUES
(1, 'Juan', 'Pérez', 'González'),
(2, 'Ana', 'López', 'Martínez'),
(3, 'Carlos', 'Díaz', 'Jiménez');

-- Insertar datos iniciales en la tabla `Tipos_usuarios`
INSERT INTO `Tipos_usuarios` (`Id_tipo_usuario`, `Descripcion`) VALUES
(1, 'Administrador'),
(2, 'Ponente'),
(3, 'Usuario');

-- Insertar datos iniciales en la tabla `Usuarios`
INSERT INTO `Usuarios` (`Id_usuario`, `Username`, `Password`, `Id_Persona`, `Id_tipo_usuario`) VALUES
(1, 'admin', 'adminpass', 1, 1),
(2, 'ponente', 'ponentepass', 2, 2),
(3, 'user', 'userpass', 3, 3);

-- Insertar datos iniciales en la tabla `Actos`
INSERT INTO `Actos` (`Id_acto`, `Fecha`, `Hora`, `Titulo`, `Descripcion_corta`, `Descripcion_larga`, `Num_asistentes`, `Id_tipo_acto`) VALUES
(1, '2023-11-15', '10:00:00', 'Innovación en IA', 'Conferencia sobre avances en Inteligencia Artificial', 'Una exploración profunda de los últimos desarrollos en el campo de la IA y su impacto en la sociedad.', 150, 1),
(2, '2023-11-16', '16:00:00', 'Blockchain y tú', 'Descubre cómo la tecnología Blockchain está revolucionando el mundo', 'Una mesa redonda para entender la tecnología Blockchain y sus aplicaciones.', 50, 3);

-- Insertar datos iniciales en la tabla `Documentacion`
INSERT INTO `Documentacion` (`Id_presentacion`, `Id_acto`, `Localizacion_documentacion`, `Orden`, `Id_persona`, `Titulo_documento`) VALUES
(1, 1, '/docs/presentaciones/ia.pdf', 1, 1, 'Introducción a la IA'),
(2, 2, '/docs/presentaciones/blockchain.pdf', 1, 2, 'Blockchain 101');

-- Insertar datos iniciales en la tabla `Inscritos`
INSERT INTO `Inscritos` (`Id_inscripcion`, `Id_persona`, `id_acto`, `Fecha_inscripcion`) VALUES
(1, 2, 1, '2023-11-01 09:00:00'),
(2, 3, 2, '2023-11-02 10:00:00');

-- Insertar datos iniciales en la tabla `Lista_Ponentes`
INSERT INTO `Lista_Ponentes` (`id_ponente`, `Id_persona`, `Id_acto`, `Orden`) VALUES
(1, 2, 1, 1),
(2, 2, 2, 1);
