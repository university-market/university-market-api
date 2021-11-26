ALTER TABLE `Denuncias` DROP COLUMN `apurada`;

ALTER  TABLE `Denuncias` ADD COLUMN `resultado_denuncia_id` INT NULL;

ALTER TABLE `Denuncias`
ADD CONSTRAINT `FK_Denuncia_Resultado_Denuncia`
FOREIGN KEY (`resultado_denuncia_id`) REFERENCES `Resultados_Denuncias`(`id`);

ALTER  TABLE `Denuncias` ADD COLUMN `situacao_denuncia_id` INT NOT NULL DEFAULT 1;

ALTER TABLE `Denuncias`
ADD CONSTRAINT `FK_Denuncia_Situacao_Denuncia`
FOREIGN KEY (`situacao_denuncia_id`) REFERENCES `Situacoes_Denuncias`(`id`);