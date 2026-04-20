<!DOCTYPE html>
<html>

<?php require __DIR__ . '/partials/head.php'; ?>

<body>
<div class="container">
    <?php require __DIR__ . '/partials/navbar.php'; ?>
    <br>

    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            Login form
        </div>
        <div class="card-body">
            <form method="post" action="/login">
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>"/>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label>Password</label>
                    <input class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" type="password" name="password"/>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Увійти"/>
                </div>
            </form>

            <?php if (!empty($infoMessage)): ?>
                <hr>
                <span class="text-danger"><?= $infoMessage ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
