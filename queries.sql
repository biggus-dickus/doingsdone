# Create users
INSERT INTO
  users (created_on, email, name, password, userpic)
VALUES
  ('2017-09-01', 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', '/img/1.jpg'),
  ('2017-09-01', 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL),
  ('2017-09-01', 'warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL);

# Create projects for user1
INSERT INTO
  projects (name, created_by)
VALUES
  ('Входящие', 1),
  ('Учеба', 1),
  ('Работа', 1),
  ('Домашние дела', 1),
  ('Авто', 1);

# Create tasks for user1
INSERT INTO
  tasks (project_id, name, created_by, created_on, deadline, completed_on)
VALUES
  (3, 'Собеседование в IT-компании', 1, '2017-09-02', '2018-06-01', NULL),
  (3, 'Выполнить тестовое задание', 1, '2017-09-02', '2018-05-18', NULL),
  (2, 'Сделать задание первого раздела', 1, '2017-09-03', '2018-04-21', NULL),
  (1, 'Встреча с другом', 1, '2017-09-04', '2018-04-22', '2017-09-15'),
  (4, 'Купить корм для кота', 1, '2017-09-05', NULL, NULL),
  (4, 'Заказать пиццу', 1, '2017-09-10', NULL, NULL);

# Get all projects for specified user
SELECT id, name FROM projects WHERE created_by = 1 ORDER BY id;

# Get all tasks for specified project
SELECT * FROM tasks WHERE project_id = 3 ORDER BY deadline;

# Mark task as completed
UPDATE tasks SET completed_on = CURDATE() WHERE id = 5;

# Get all tasks, which deadline is tomorrow
SELECT * FROM tasks WHERE deadline = NOW() + INTERVAL 1 DAY;

# Rename task by id
UPDATE tasks SET name = 'Заказать офигенную пиццу' WHERE id = 6;
