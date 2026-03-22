<?php
session_start();

$commentsFile = 'comments.csv';

function loadComments(string $filename): array {
    $comments = [];
    if (file_exists($filename)) {
        $fileStream = fopen($filename, "r");
        while (!feof($fileStream)) {
            $jsonString = fgets($fileStream);
            $comment = json_decode($jsonString, true);
            if (empty($comment)) break;
            $comments[] = $comment;
        }
        fclose($fileStream);
    }
    return $comments;
}

function renderComments(array $comments, int $page, int $perPage): void {
    if (empty($comments)) {
        echo '<p class="text-muted">Коментарів поки немає. Будьте першим!</p>';
        return;
    }

    $reversed   = array_reverse($comments);
    $total      = count($reversed);
    $totalPages = (int) ceil($total / $perPage);
    $page       = max(1, min($page, $totalPages));
    $offset     = ($page - 1) * $perPage;
    $slice      = array_slice($reversed, $offset, $perPage);

    foreach ($slice as $comment) {
        $name  = htmlspecialchars($comment['name']  ?? '');
        $email = htmlspecialchars($comment['email'] ?? '');
        $text  = htmlspecialchars($comment['text']  ?? '');
        $date  = htmlspecialchars($comment['date']  ?? '');
        echo "
        <div class='card mb-2'>
            <div class='card-body'>
                <h6 class='card-subtitle mb-1 text-muted'>
                    <strong>{$name}</strong> &lt;{$email}&gt;
                    <span class='float-end text-secondary small'>{$date}</span>
                </h6>
                <p class='card-text mt-2'>{$text}</p>
            </div>
        </div>";
    }

    if ($totalPages > 1) {
        echo '<nav class="mt-3"><ul class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = ($i === $page) ? 'active' : '';
            echo "<li class='page-item {$active}'>
                    <a class='page-link' href='guestbook.php?page={$i}'>{$i}</a>
                  </li>";
        }
        echo '</ul></nav>';
    }
}

$errors = [];
$infoMessage = '';

if (!empty($_POST)) {
    $email = trim($_POST['email'] ?? '');
    $name  = trim($_POST['name']  ?? '');
    $text  = trim($_POST['text']  ?? '');

    if (empty($email)) {
        $errors['email'] = 'Email є обов\'язковим полем.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Невірний формат email.';
    }

    if (empty($name)) {
        $errors['name'] = 'Ім\'я є обов\'язковим полем.';
    } elseif (mb_strlen($name) < 2) {
        $errors['name'] = 'Ім\'я має містити мінімум 2 символи.';
    }

    if (empty($text)) {
        $errors['text'] = 'Текст коментаря є обов\'язковим полем.';
    } elseif (mb_strlen($text) < 5) {
        $errors['text'] = 'Коментар має містити мінімум 5 символів.';
    }

    if (empty($errors)) {
        $newComment = [
            'email' => $email,
            'name'  => $name,
            'text'  => $text,
            'date'  => date('d.m.Y H:i'),
        ];

        $jsonString = json_encode($newComment, JSON_UNESCAPED_UNICODE);
        $fileStream = fopen($commentsFile, 'a');
        fwrite($fileStream, $jsonString . "\n");
        fclose($fileStream);

        header('Location: guestbook.php?success=1');
        die;
    }
}

if (isset($_GET['success'])) {
    $infoMessage = 'Коментар успішно додано!';
}

$comments = loadComments($commentsFile);
$page     = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage  = 5;
?>

<!DOCTYPE html>
<html>

<?php require_once 'sectionHead.php' ?>

<body>

<div class="container">

    <!-- navbar menu -->
    <?php require_once 'sectionNavbar.php' ?>
    <br>

    <!-- guestbook section -->
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">

                    <?php if ($infoMessage): ?>
                        <div class="alert alert-success"><?= $infoMessage ?></div>
                    <?php endif; ?>

                    <form method="post" action="guestbook.php">

                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            />
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label>Ім'я</label>
                            <input
                                class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                type="text"
                                name="name"
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                            />
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label>Коментар</label>
                            <textarea
                                class="form-control <?= isset($errors['text']) ? 'is-invalid' : '' ?>"
                                name="text"
                                rows="4"
                            ><?= htmlspecialchars($_POST['text'] ?? '') ?></textarea>
                            <?php if (isset($errors['text'])): ?>
                                <div class="invalid-feedback"><?= $errors['text'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Надіслати"/>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="card card-primary">
        <div class="card-header bg-body-secondary text-dark">
            Сomments
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">

                    <?php renderComments($comments, $page, $perPage); ?>

                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
