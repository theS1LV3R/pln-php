<?php
session_start();

$user = $_SESSION['user'] ?? null;
?>

<header>
    <ul>
        <li id="home_link"><a href="index.php">Home</a></li>
        <?php if ($user) : ?>
            <li><a href="posts.php">Posts</a></li>
            <li><a href="users.php">Users</a></li>
            <?php if ($user['username'] === "admin") : ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="me.php">Me</a></li>
        <?php else : ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</header>

<script>
    const elements = Array.from(document.querySelectorAll("header > ul > li")).map((el) => el.lastChild);

    let hasChanged = false;

    elements.forEach((el) => {
        if (window.location.href === el.href) {
            hasChanged = true
            el.classList.toggle("underline")
        }
    })

    if (!hasChanged && window.location.pathname === "/") document.querySelector("header ul>li#home_link>a").classList.toggle("underline")
</script>
