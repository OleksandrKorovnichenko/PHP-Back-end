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
    } elseif (mb_strlen($password) < 6) {
        $errors['password'] = 'Пароль має містити мінімум 6 символів.';
    }

    if (empty($errors)) {
        $db = getDbConnection($aConfig);
        $safeEmail = mysqli_real_escape_string($db, $email);
        $safePassword = mysqli_real_escape_string($db, $password);

        $checkQuery = "SELECT id FROM users WHERE email = '{$safeEmail}' LIMIT 1";
        $checkResponse = mysqli_query($db, $checkQuery);
        $existingUser = mysqli_fetch_assoc($checkResponse);

        if ($existingUser) {
            mysqli_close($db);
            $infoMessage = "Такий користувач уже існує! Перейдіть на сторінку входу. <a href='login.php'>Сторінка входу</a>";
        } else {
            $insertQuery = "
                INSERT INTO users (email, password, date)
                VALUES ('{$safeEmail}', '{$safePassword}', NOW())
            ";
            mysqli_query($db, $insertQuery);
            mysqli_close($db);

            header('Location: login.php');
            die;
        }
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
        <div class="card-header bg-success text-light">
            Register form
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
                    <input type="submit" class="btn btn-primary" value="Зареєструватися"/>
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