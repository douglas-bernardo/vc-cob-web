-- Criando banco de dados com codificação padrão:
CREATE DATABASE bp_cobranca DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- Selectiona o DB recem criado
USE bp_cobranca;

-- Criando tabelas
CREATE TABLE billingDocumments (
	id INT(11) NOT NULL AUTO_INCREMENT,
	coddocumento INT(11) UNSIGNED,
    razaosocial VARCHAR(100),
    nomecliente VARCHAR(100),
    doc VARCHAR(40),
    numerocontrato VARCHAR(60),
    dataprogramada DATE,
    datalancto DATE,
    historicocompl VARCHAR(60),
    valor DECIMAL,
    valoroutra DECIMAL,
    descformarecpag VARCHAR(50),
    tipo CHAR(1),
    numdocumento VARCHAR(200),
    nomeprojeto VARCHAR(100),
    numeroprojeto INT(11),
    descricao VARCHAR(60),
    codtipdoc INT(11),
    flgcancelado CHAR(1),
    celular VARCHAR(30),
    telefone VARCHAR(30),
	PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET utf8;