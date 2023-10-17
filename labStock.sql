CREATE DATABASE storagedb;

USE storagedb;

CREATE TABLE sessions (
  session_id int(11) NOT NULL AUTO_INCREMENT,
  name_session varchar(255) NOT NULL,
  PRIMARY KEY (session_id)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE resp (
  resp_id int(11) NOT NULL AUTO_INCREMENT,
  name_resp varchar(255) NOT NULL,
  profile_resp varchar(255) NOT NULL,
  active_resp tinyint(1) DEFAULT 0,
  PRIMARY KEY (resp_id)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE labels (
  label_id VARCHAR(10) PRIMARY KEY,
  name_label VARCHAR(255) NOT NULL
);

CREATE TABLE products (
  product_id int(11) NOT NULL AUTO_INCREMENT,
  name_product varchar(255) NOT NULL,
  ci_product varchar(45) DEFAULT NULL,
  amount_product varchar(4) NOT NULL,
  validity_product date DEFAULT NULL,
  image_product longblob NOT NULL,
  session_id int(11) DEFAULT NULL,
  resp_id int(11) DEFAULT NULL,
  label_id varchar(10) DEFAULT NULL,
  PRIMARY KEY (product_id),
  KEY session_id (session_id),
  KEY resp_id (resp_id),
  KEY label_id (label_id),
  CONSTRAINT products_ibfk_1 FOREIGN KEY (session_id) REFERENCES sessions (session_id),
  CONSTRAINT products_ibfk_2 FOREIGN KEY (resp_id) REFERENCES resp (resp_id),
  CONSTRAINT products_ibfk_3 FOREIGN KEY (label_id) REFERENCES labels (label_id)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO sessions (`name_session`) VALUES
  ('Químicos'),
  ('Vidrarias'),
  ('Equipamentos');

INSERT INTO resp (`name_resp`, `profile_resp`, `active_resp`) VALUES
  ('User_1', 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg', 0),
  ('User_2', 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg', 1);

INSERT INTO labels (`label_id`, `name_label`) VALUES
  ('0', '0'),
  ('1', 'Explosivos'),
  ('2.1', 'Gases inflamáveis'),
  ('2.2', 'Gases não-inflamáveis'),
  ('2.3', 'Gases tóxicos'),
  ('3', 'Líquidos inflamáveis'),
  ('4.1', 'Sólidos inflamáveis'),
  ('4.2', 'Substâncias passíveis de combustão espontânea'),
  ('4.3', 'Substâncias que, em contato com a água, emitem gases inflamáveis'),
  ('5.1', 'Substâncias Oxidantes'),
  ('5.2', 'Peróxidos Orgânicos'),
  ('6.1', 'Substâncias Nocivas'),
  ('6.2', 'Substâncias Tóxicas'),
  ('6.3', 'Substâncias Infectantes'),
  ('7', 'Materiais Radioativos'),
  ('8', 'Substâncias Corrosivas'),
  ('9', 'Substâncias e Artigos Perigosos Diversos');