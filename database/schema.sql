CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE operations_marketing (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE financial_data (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE marketing_data (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_operations_marketing (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  operation_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (operation_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (operation_id) REFERENCES operations_marketing(id)
);

CREATE TABLE user_financial_data (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  financial_data_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (financial_data_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (financial_data_id) REFERENCES financial_data(id)
);

CREATE TABLE user_marketing_data (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  marketing_data_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (marketing_data_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (marketing_data_id) REFERENCES marketing_data(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO operations_marketing (name, description)
VALUES ('Operation 1', 'Description 1'),
       ('Operation 2', 'Description 2'),
       ('Operation 3', 'Description 3');

INSERT INTO financial_data (name, description)
VALUES ('Financial Data 1', 'Description 1'),
       ('Financial Data 2', 'Description 2'),
       ('Financial Data 3', 'Description 3');

INSERT INTO marketing_data (name, description)
VALUES ('Marketing Data 1', 'Description 1'),
       ('Marketing Data 2', 'Description 2'),
       ('Marketing Data 3', 'Description 3');