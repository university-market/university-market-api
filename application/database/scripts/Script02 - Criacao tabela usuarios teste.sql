USE university_market_db;
CREATE TABLE Users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    email VARCHAR(50) NOT NULL,
    date date NOT null,
    bloqued bit,
    curso VARCHAR(50) NOT null,
    token VARCHAR(50),
    PRIMARY KEY (id)
);