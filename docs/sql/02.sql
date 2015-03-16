/*
* Agregar campos a clientes
 */
ALTER TABLE `clientes`
ADD `direccion` varchar(30) COLLATE 'utf8_unicode_ci' NULL,
ADD `telefono` varchar(13) COLLATE 'utf8_unicode_ci' NULL AFTER `direccion`,
COMMENT='';

ALTER TABLE `clientes`
ADD `deporte_desde` char(4) COLLATE 'utf8_unicode_ci' NULL,
ADD `deporte` tinytext COLLATE 'utf8_unicode_ci' NULL AFTER `deporte_desde`,
COMMENT='';