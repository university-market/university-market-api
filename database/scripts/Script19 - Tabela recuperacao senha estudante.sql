/*
 * Criação tabela de solicitações de recuperação de senha de estudantes
 */
DROP TABLE IF EXISTS `RecuperacaoSenhaEstudante`;
CREATE TABLE IF NOT EXISTS `RecuperacaoSenhaEstudante` (
    `recuperacaoSenhaEstudanteId` INT NOT NULL AUTO_INCREMENT,
    `tokenRecuperacao` VARCHAR(150) NOT NULL,
    `tempoExpiracao` INT NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `dataHoraSolicitacao` DATETIME NOT NULL,
    `completo` BOOLEAN NOT NULL DEFAULT 0,
    `estudanteId` INT NOT NULL,

    PRIMARY KEY (`recuperacaoSenhaEstudanteId`),

    CONSTRAINT `FK_Estudante_RecuperacaoSenhaEstudante`
    FOREIGN KEY (`estudanteId`) REFERENCES `Estudante`(`estudanteId`)
);