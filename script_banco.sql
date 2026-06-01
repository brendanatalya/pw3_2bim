CREATE DATABASE wda_crud;
USE wda_crud;
CREATE TABLE cliente (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome varchar(255) NOT NULL,
  cpf_cnpj varchar(14) NOT NULL,
  nasc datetime NOT NULL,
  endereco varchar(255) NOT NULL,
  bairro varchar(100) NOT NULL,
  cep varchar(8) NOT NULL,
  cidade varchar(100) NOT NULL,
  estado varchar(100) NOT NULL,
  telefone varchar(11) NOT NULL,
  celular varchar(11) NOT NULL,
  ie varchar(14) NOT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE medico (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome varchar(50) NOT NULL,
  endereco varchar(50) NOT NULL,
  bairro varchar(100) NOT NULL,
  cep varchar(8) NOT NULL,
  cidade varchar(100) NOT NULL,
  estado varchar(100) NOT NULL,
  crm varchar(6) NOT NULL,  
  nasc datetime NOT NULL,
  telefone varchar(11) NOT NULL,
  celular varchar(11) NOT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL,
  foto varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE revista (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  editora varchar(50) NOT NULL,
  endereco varchar(50) NOT NULL,
  bairro varchar(100) NOT NULL,
  cep varchar(8) NOT NULL,
  cidade varchar(100) NOT NULL,
  estado varchar(100) NOT NULL,
  edicao varchar(6) NOT NULL,  
  lanc datetime NOT NULL,
  telefone varchar(11) NOT NULL,
  celular varchar(11) NOT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL,
  foto varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE usuarios(
    id int AUTO_INCREMENT not null PRIMARY KEY,
    nome varchar(50) not null,
    user varchar(50) not null,
    password varchar(100) not null,
    foto varchar(50)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO cliente (nome, cpf_cnpj, nasc, endereco, bairro, cep, cidade, estado, telefone, celular, ie, created, modified) VALUES
('Ana Paula Lima', '123.456.789-01', '1990-05-10', 'Rua das Rosas, 456', 'Centro', '18000000', 'Sorocaba', 'SP', '15 32101234', '15981234567', '11122233344', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Carlos Eduardo Silva', '987.654.321-00', '1985-11-23', 'Av. São Paulo, 789', 'Jardim São Paulo', '18013000', 'Sorocaba', 'SP', '15 33445566', '15993456789', '22233344455', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Juliana Martins', '321.654.987-00', '1992-07-15', 'Rua das Laranjeiras, 123', 'Vila Hortência', '18020000', 'Sorocaba', 'SP', '15 30223344', '15987654321', '33344455566', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Marcos Vinícius Oliveira', '456.789.123-00', '1988-03-30', 'Rua dos Ipês, 321', 'Jardim América', '18110000', 'Votorantim', 'SP', '15 32445566', '15991112222', '44455566677', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Fernanda Rocha', '789.123.456-00', '1995-09-12', 'Av. Independência, 555', 'Centro', '18150000', 'Salto de Pirapora', 'SP', '15 33556677', '15992223333', '55566677788', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Ricardo Almeida', '159.753.486-00', '1979-12-01', 'Rua das Acácias, 88', 'Jardim Simus', '18055000', 'Sorocaba', 'SP', '15 33667788', '15993334444', '66677788899', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Bianca Ferreira', '753.951.258-00', '1998-02-20', 'Rua João Pessoa, 210', 'Vila Carvalho', '18085000', 'Sorocaba', 'SP', '15 33778899', '15994445555', '77788899900', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Eduardo Nascimento', '852.456.123-00', '1983-06-18', 'Rua XV de Novembro, 112', 'Centro', '13309000', 'Itu', 'SP', '15 33889900', '15995556666', '88899900011', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Patrícia Souza', '951.357.258-00', '1991-08-09', 'Av. Dom Aguirre, 901', 'Jardim Vergueiro', '18035340', 'Sorocaba', 'SP', '15 33990011', '15996667777', '99900011122', '2025-08-27 12:10:00', '2025-08-27 12:10:00'),
('Lucas Andrade', '456.321.789-00', '1986-04-05', 'Rua da Paz, 17', 'Centro', '18190000', 'Araçoiaba da Serra', 'SP', '15 34001122', '15997778888', '00011122233', '2025-08-27 12:10:00', '2025-08-27 12:10:00');


INSERT INTO medico (nome, endereco, bairro, cep, cidade, estado, crm, nasc, telefone, celular, created, modified, foto) VALUES
('Mariana Gonçalves', 'Rua Ramirez, 340','Jardim Leocadia', '74829765', 'Sorocaba','SP', '445321', '1992-05-14','15 55566678', '15988847653', '2025-08-27 12:10:00', '2025-08-27 12:10:00', 'mariana.jpg'),
('Cristina Yang', 'Rua Braz Cubas, 160', 'Parque Campolim', '98765278', 'Sorocaba','SP', '980941', '1990-06-18','15 43212356', '15975452784', '2025-08-27 12:12:00', '2025-08-27 12:12:00' , 'cristina.jpg'),
('Derick Albuquerque', 'Rua Natal, 02', 'Jardim Europa', '12378656', 'Sorocaba','SP', '564789', '1987-01-10','15 12343324', '15931123423', '2025-08-27 12:20:00', '2025-08-27 12:20:00', 'derick.jpg'),
('Alexa Gomes Lima', 'Av. Washington Luiz, 100', 'Jardim Emilia', '12368865', 'Sorocaba','SP', '867004', '1995-05-02','15 99876557', '15998765793', '2025-08-28 10:10:00', '2025-08-28 10:10:00', 'alexa.jpg'),
('Cassandra Torres', 'Rua Vital de Mello, 405', 'Jardim Villa Amato', '12765489', 'Sorocaba','SP', '112345', '1996-02-19','15 33456654', '15998761233', '2025-08-28 10:15:00', '2025-08-28 10:15:00', 'cassandra.jpg'),
('Andressa dos Santos', 'Rua Alcides Soares, 151', 'Jardim Pacaembu', '18097658', 'Sorocaba','SP', '998556', '1989-10-20','15 99876557', '15945678793', '2025-08-28 12:06:00', '2025-08-28 12:06:00', 'andressa.jpg'),
('Miranda de Queirós', 'Rua Barbosa, 300', 'Boa Vista', '12654322', 'Sorocaba','SP', '776390', '1984-12-30','15 99822557', '15993343673', '2025-08-28 12:10:00', '2025-08-28 12:10:00', 'miranda.jpg'),
('Otávio Rodrigues', 'Rua Dorival Cruz, 10', 'Jardim Europa', '12654349', 'Sorocaba','SP', '109412', '1968-09-15','15 99876557', '15998765793', '2025-08-28 12:45:00', '2025-08-28 12:45:00', 'otavio.jpg'),
('Joaquim Vaz', 'Rua Osasco, 01', 'Jardim Leocadia', '55564347', 'Sorocaba','SP', '556213', '1971-04-07','15 33445677', '15900923793', '2025-08-28 13:01:00', '2025-08-28 13:01:00', 'joaquim.jpg'),
('Marcos de Olivera', 'Rua Vidal de Araújo, 125', 'Jardim Colinas', '11225688', 'Sorocaba','SP', '884611', '1993-11-01','15 99833557', '15998712344', '2025-09-08 09:10:00', '2025-09-08 09:10:00', 'marcos.jpg');

INSERT INTO revista (editora, endereco, bairro, cep, cidade, estado, edicao, lanc, telefone, celular, created, modified, foto) VALUES
('Allure', 'One World Trade Center, 10007','NY', '74829765', 'New York City','NY', '445321', '1992-05-14','15 55566678', '15988847653', '2025-08-27 12:10:00', '2025-08-27 12:10:00', 'allure.jpg'),
('Bazaar', 'Rua Braz Cubas, 160', 'Parque Campolim', '98765278', 'Sorocaba','SP', '980941', '1990-06-18','15 43212356', '15975452784', '2025-08-27 12:12:00', '2025-08-27 12:12:00' , 'bazaar.jpg'),
('Capricho', 'Rua Natal, 02', 'Jardim Europa', '12378656', 'Sorocaba','SP', '564789', '1987-01-10','15 12343324', '15931123423', '2025-08-27 12:20:00', '2025-08-27 12:20:00', 'capricho.jpg'),
('Caras', 'Av. Washington Luiz, 100', 'Jardim Emilia', '12368865', 'Sorocaba','SP', '867004', '1995-05-02','15 99876557', '15998765793', '2025-08-28 10:10:00', '2025-08-28 10:10:00', 'caras.jpg'),
('Elle', 'Rua Vital de Mello, 405', 'Jardim Villa Amato', '12765489', 'Sorocaba','SP', '112345', '1996-02-19','15 33456654', '15998761233', '2025-08-28 10:15:00', '2025-08-28 10:15:00', 'elle.jpg'),
('Forbes', 'Rua Alcides Soares, 151', 'Jardim Pacaembu', '18097658', 'Sorocaba','SP', '998556', '1989-10-20','15 99876557', '15945678793', '2025-08-28 12:06:00', '2025-08-28 12:06:00', 'forbes.png'),
('National Geographic', 'Rua Barbosa, 300', 'Boa Vista', '12654322', 'Sorocaba','SP', '776390', '1984-12-30','15 99822557', '15993343673', '2025-08-28 12:10:00', '2025-08-28 12:10:00', 'national.png'),
('Tapas', 'Rua Dorival Cruz, 10', 'Jardim Europa', '12654349', 'Sorocaba','SP', '109412', '1968-09-15','15 99876557', '15998765793', '2025-08-28 12:45:00', '2025-08-28 12:45:00', 'tapas.jpg'),
('Variety', 'Rua Osasco, 01', 'Jardim Leocadia', '55564347', 'Sorocaba','SP', '556213', '1971-04-07','15 33445677', '15900923793', '2025-08-28 13:01:00', '2025-08-28 13:01:00', 'variety.jpg'),
('Vogue', 'Rua Vidal de Araújo, 125', 'Jardim Colinas', '11225688', 'Sorocaba','SP', '884611', '1993-11-01','15 99833557', '15998712344', '2025-09-08 09:10:00', '2025-09-08 09:10:00', 'vogue.jpg');


INSERT INTO `usuarios`(`nome`, `user`, `password`, `foto`) 
VALUES
('Administrador do site', 'admin', '$2y$10$jJMToMspJdUPNBZn/HAg6uIDpZjEzy2sJ8CRXPpWAj9Wpdy/exmAO', 'admin.png'), --teste
('Ana Souza', 'ana', '$2y$10$H1fyJ3YfrJzF3cL5Ckj.3ORZmICC8GiuR0c48b98PTAqHaXzhheaW', null), --minhasenha
('Carlos Santos', 'carlos', '$2y$10$YBO/S9PXdIIq.GRIqTDS..nKqQj4BrNCreNxHm798/Qb6p7vKe.3y', null), --carlossantos
('Maria Oliveira', 'maria', '$2y$10$HUFiATCyLu3lRkNntd.NVeEk5KLmeEvl4Wl8gopKwnPMBP0CFMxlO', null); --senha123