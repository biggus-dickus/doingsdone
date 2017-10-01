<?php
require 'functions.php';
require 'init.php';

$link = connectToDb();

if (!$link) {
    print renderTemplate('templates/error.php', ['error' => mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($link, 'utf8');

$users = fetchData($link, 'SELECT id, email, name, password, userpic FROM users');
$projects = ['Все'];
$tasks = [];

session_start();
$userId = $_SESSION['user']['id'] ?? 0;

require_once 'db-queries.php';


// Validation
$errors = [];
$required = ['taskName', 'projectName', 'email', 'password', 'name', 'deadline'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'] ?? '';
    $taskName = $_POST['taskName'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $project = $_POST['project'] ?? '';

    $formName = $_POST['form_name'];

    $errors = validateForm($required, $users);

    if (count($errors)) {
        $_GET[$formName] = 1;
    } else {
        switch ($formName) {

            /*** ADD TASK ***/
            case 'add_task':
                if (isset($_FILES['task-attachment'])) {
                    $file_name = $_FILES['task-attachment']['name'];
                    $file_path = __DIR__ . '/uploads/';

                    move_uploaded_file($_FILES['task-attachment']['tmp_name'], $file_path . $file_name);
                }

                $taskName = parseUserInput($taskName);

                insertData($link, 'tasks', [
                    'project_id' => ($project) ? array_search($project, $projects) : 1,
                    'name' => $taskName,
                    'created_by' => $userId,
                    'created_on' => date('Y-m-d H:i:s'),
                    'deadline' => date('Y-m-d H:i:s', strtotime($_POST['deadline'])),
                    'attachment_url' => ($_FILES['task-attachment']['name'] !== '') ? '/uploads/' . $_FILES['task-attachment']['name'] : ''
                ]);

                header('Location: /');

                break;


            /*** ADD PROJECT ***/
            case 'add_project':
                $projectName = parseUserInput($_POST['projectName']);

                insertData($link, 'projects', [
                    'name' => $projectName,
                    'created_by' => $userId
                ]);

                header('Location: /');

                break;


            /*** SIGN IN ***/
            case 'sign_in':
                if (!empty($password) && $user = searchUserByEmail($email, $users)) {
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user'] = $user;
                        header('Location: /index.php');
                    }
                }

                break;


            /*** SIGN UP ***/
            case 'sign_up':
                $username = parseUserInput($username);

                insertData($link, 'users', [
                    'created_on' => date('Y-m-d H:i:s'),
                    'email' => $email,
                    'name' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);

                insertData($link, 'projects', [
                    'name' => 'Входящие',
                    'created_by' => mysqli_insert_id($link)
                ]);

                header('Location: /?sign_in&registration_success');

                break;

            default:
                return;
        }
    }
}


// Sign out
if (isset($_GET['sign_out'])) {
    unset($_SESSION['user']);
    header('Location: /');
}


// My little kooky dance
if (isset($_GET['show_completed'])) {
    setcookie('show_completed', $_GET['show_completed']);
    header('Location: /');
}


// Template data
$mainContent = renderTemplate('templates/index.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'showCompleted' => (isset($_COOKIE['show_completed']) && $_COOKIE['show_completed'])
]);

$templateData = [
    'indexTitle' => 'Дыра в порядке',
    'mainContent' => $mainContent,
    'projects' => $projects,
    'tasks' => $tasks,
    'errors' => $errors
];

print renderTemplate('templates/layout.php', $templateData);
