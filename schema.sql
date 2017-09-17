CREATE DATABASE doingsdone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;;

USE doingsdone;

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  created_on    DATETIME,
  email         CHAR(128) NOT NULL,
  name          CHAR(128) NOT NULL,
  password      CHAR(60) NOT NULL,
  contacts      CHAR(255),
  is_deleted    TINYINT(1) DEFAULT 0,

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
  is_deleted      TINYINT(1) DEFAULT 0,

  UNIQUE INDEX task (name)
);

CREATE TABLE projects (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          CHAR(128) NOT NULL,
  created_by    INT,
  is_deleted    TINYINT(1) DEFAULT 0,

  UNIQUE INDEX project (name)
);
