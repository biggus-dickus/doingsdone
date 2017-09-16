CREATE DATABASE doingsdone;

USE doingsdone;

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  created_on    DATETIME,
  email         CHAR(128) NOT NULL,
  name          CHAR(128) NOT NULL,
  password      CHAR(60) NOT NULL,
  contacts      CHAR(255),
  is_deleted    TINYINT(1),

  INDEX username (name),
  UNIQUE INDEX usermail (email)
);

CREATE TABLE tasks (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  project_id      INT,
  name            CHAR(128) NOT NULL,
  created_by      INT,
  created_on      DATETIME NOT NULL,
  completed_on    DATETIME,
  deadline        DATETIME,
  attachment_URL  CHAR(255),
  is_deleted      TINYINT(1),

  UNIQUE INDEX task (name)
);

CREATE TABLE projects (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          CHAR(128) NOT NULL,
  is_deleted    TINYINT(1),

  UNIQUE INDEX project (name)
);
