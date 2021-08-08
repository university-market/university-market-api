USE `university_market_db`;
CREATE TABLE `EtapaCadastro` (
    `etapaCadastroId` INT NOT NULL,
    `descricao` VARCHAR(500) NOT NULL,
    PRIMARY KEY (`etapaCadastroId`)
);
INSERT INTO `EtapaCadastro`(`etapaCadastroId`) 
VALUES 
  (1, 'Socilitação de cadastro enviada'),
  (2, 'Validação de dados legais da empresa'),
  (3, 'Aguardando aprovação do cadastro - ajustes finais'),
  (4, 'Cadastro aprovado');