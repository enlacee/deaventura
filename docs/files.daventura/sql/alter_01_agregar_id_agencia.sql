ALTER TABLE `aventuras` 
ADD COLUMN `id_agencia` INT(11) NULL DEFAULT NULL AFTER `estado_aventura`,
ADD INDEX `fk_aventuras_agencias` (`id_agencia` ASC);
ALTER TABLE `aventuras` 
ADD CONSTRAINT `fk_aventuras_agencias1`
  FOREIGN KEY (`id_agencia`)
  REFERENCES `agencias` (`id_agencia`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
