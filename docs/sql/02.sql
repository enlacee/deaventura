/*
* Agregar campos a clientes
 */
ALTER TABLE `clientes`
ADD `direccion` varchar(30) COLLATE 'utf8_unicode_ci' NULL,
ADD `telefono` varchar(13) COLLATE 'utf8_unicode_ci' NULL AFTER `direccion`,
COMMENT='';