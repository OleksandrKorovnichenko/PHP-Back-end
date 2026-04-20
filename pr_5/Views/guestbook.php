<?php
$total = count($comments ?? []);
$totalPages = (int) ceil($total / ($perPage ?: 1));
$currentPage = max(1, min((int) ($page ?? 1), max(1, $totalPages)));
$offset = ($currentPage - 1) * ($perPage ?? 5);
$visibleComments = array_slice($comments ?? [], $offset, $perPage ?? 5);
?>
<!DOCTYPE html>
<html>

<?php require __DIR__ . '/partials/head.php'; ?>

<body>
<div class="container">
    <?php require __DIR__ . '/partials/navbar.php'; ?>
    <br>

    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <?php if (!empty($infoMessage)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($infoMessage) ?></div>
                    <?php endif; ?>

                    <form method="post" action="/guestbook">
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" type="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>"/>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label>Ім'я</label>
                            <input class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" type="text" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>"/>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label>Коментар</label>
                            <textarea class="form-control <?= isset($errors['text']) ? 'is-invalid' : '' ?>" name="text" rows="4"><?= htmlspecialchars($formData['text'] ?? '') ?></textarea>
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
            Comments
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <?php if (empty($visibleComments)): ?>
                        <p class="text-muted">Коментарів поки немає. Будьте першим!</p>
                    <?php else: ?>
                        <?php foreach ($visibleComments as $comment): ?>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-1 text-muted">
                                        <strong><?= htmlspecialchars($comment['name'] ?? '') ?></strong>
                                        &lt;<?= htmlspecialchars($comment['email'] ?? '') ?>&gt;
                                        <span class="float-end text-secondary small"><?= htmlspecialchars($comment['date'] ?? '') ?></span>
                                    </h6>
                                    <p class="card-text mt-2"><?= htmlspecialchars($comment['text'] ?? '') ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($totalPages > 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="/guestbook?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
