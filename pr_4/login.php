<?php
session_start();
$aConfig = require_once 'config.php';

function getDbConnection(array $config)
{
    $db = mysqli_connect(
        $config['host'],
        $config['user'],
        $config['pass'],
        $config['name']
    );

    if (!$db) {
        die('Помилка підключення до БД: ' . mysqli_connect_error());
    }

    mysqli_set_charset($db, 'utf8mb4');
    return $db;
}

if (!empty($_SESSION['auth'])) {
    header('Location: index.php');
    die;
}

$infoMessage = '';
$errors = [];
$email = '';

if (!empty($_POST)) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $errors['email'] = 'Email є обов\'язковим полем.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Невірний формат email.';
    }

    if (empty($password)) {
        $errors['password'] = 'Пароль є обов\'язковим полем.';
    }

    if (empty($errors)) {
        $db = getDbConnection($aConfig);
        $safeEmail = mysqli_real_escape_string($db, $email);
        $safePassword = mysqli_real_escape_string($db, $password);

        $query = "SELECT id FROM users WHERE email = '{$safeEmail}' AND password = '{$safePassword}' LIMIT 1";
        $dbResponse = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($dbResponse);
        mysqli_close($db);

        if ($user) {
            $_SESSION['auth'] = true;
            $_SESSION['email'] = $email;
            header("Location: index.php");
            die;
        }
        $infoMessage = "Такого користувача не існує. Перейдіть на сторінку реєстрації. <a href='register.php'>Сторінка реєстрації</a>";
    }
}
?>


<!DOCTYPE html>
<html>

<?php require_once 'sectionHead.php' ?>

<body>

    <div class="container">

        <?php require_once 'sectionNavbar.php' ?>

        <br>

        <div class="card card-primary">
            <div class="card-header bg-primary text-light">
                Login form
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input
                            class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($email) ?>"
                        />
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input
                            class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                            type="password"
                            name="password"
                        />
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Увійти"/>
                    </div>
                </form>

                <?php
                    if ($infoMessage) {
                        echo '<hr>';
                        echo "<span class='text-danger'>$infoMessage</span>";
                    }
                ?>

            </div>
        </div>
    </div>


</body>
</html>

