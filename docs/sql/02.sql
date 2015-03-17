/*
* Agregar campos a clientes
* Nuevos campos en Cuenta perfil del aventurero
 */
ALTER TABLE `clientes`
ADD `direccion` varchar(30) COLLATE 'utf8_unicode_ci' NULL,
ADD `telefono` varchar(13) COLLATE 'utf8_unicode_ci' NULL AFTER `direccion`,
COMMENT='';

/* 02 */
ALTER TABLE `clientes`
ADD `deporte_desde` char(4) COLLATE 'utf8_unicode_ci' NULL,
ADD `deporte` tinytext COLLATE 'utf8_unicode_ci' NULL AFTER `deporte_desde`,
COMMENT='';

/* 03 */
ALTER TABLE `clientes`
CHANGE `deporte` `deporte_favorito` tinytext COLLATE 'utf8_unicode_ci' NULL AFTER `deporte_desde`,
ADD `deporte_equipo_que_utilizo` varchar(255) COLLATE 'utf8_unicode_ci' NULL,
ADD `describete` tinytext COLLATE 'utf8_unicode_ci' NULL AFTER `deporte_equipo_que_utilizo`,
ADD `nivel` char(1) COLLATE 'utf8_unicode_ci' NULL AFTER `describete`;

/**/
ALTER TABLE `clientes`
CHANGE `direccion` `vivo_en` varchar(30) COLLATE 'utf8_unicode_ci' NULL AFTER `fecha_nacimiento_cliente`,
COMMENT='';