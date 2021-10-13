/*
 * Criação tabela de tags
 */
DROP TABLE IF EXISTS `Tag`;
CREATE TABLE IF NOT EXISTS `Tag` (
    `tagId` INT NOT NULL AUTO_INCREMENT,
    `conteudo` VARCHAR(50) NOT NULL,

    PRIMARY KEY (`tagId`) 
);
