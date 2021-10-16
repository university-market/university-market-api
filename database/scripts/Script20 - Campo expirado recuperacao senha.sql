ALTER TABLE `RecuperacaoSenhaEstudante`
    ADD COLUMN `expirada` BOOLEAN NOT NULL DEFAULT 0 AFTER `completo`;