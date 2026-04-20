<nav class="navbar navbar-expand-lg bg-body-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <span style="color: Dodgerblue;">
                <i class="fa-brands fa-php fa-2xl"></i>
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Головна</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/guestbook">GuestBook</a>
                </li>
            </ul>

            <ul class="navbar-nav navbar-right">
                <?php if (!empty($_SESSION['auth'])): ?>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link active" aria-current="page">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="/register" class="nav-link active" aria-current="page">Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="/login" class="nav-link active" aria-current="page">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
