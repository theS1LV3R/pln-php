<?php
require("../database.php");
require("../constants.php");

session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== '') {
    header("Location: index.php");
}

function login(string $username, string $password)
{
    $user = Database::getUser($username);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user']['username'] = $user['username'];
        $_SESSION['user']['id'] = $user['id'];
        header("Location: index.php");
    } else {
        return "Invalid username or password";
    }
}

// If this is a post request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = Database::getConnection();

    // Get the username and password
    $username = $db->escape_string($_POST['username']);
    $password = $db->escape_string($_POST['password']);

    // Check that the username and password are not empty
    if (empty($username) || empty($password)) {
        $error = "Username or password is empty";
    } else {
        $error = login($username, $password);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../components/head.php") ?>
    <title>Login</title>
</head>

<body>
    <?php require("../components/header.php"); ?>

    <main>
        <?php if (isset($_REQUEST['message'])) {
            switch ($_REQUEST['message']) {
                case 'registered':
                    $msg = Constants::MSG_REGISTER_SUCCESS;
                    echo "<p>$msg</p>";
                    break;

                default:
                    break;
            }
        } ?>

        <?php if (isset($error)) {
            echo "<p>$error</p>";
            unset($error);
        } ?>
        <div class="wrapper flex justify-evenly">
            <form action="" method="post" class="bg-slate-600 flex flex-col max-h-min p-4 rounded-md m-4">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required />
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required />

                <input type="submit" value="Submit" class="bg-slate-400 rounded-b">
            </form>
        </div>
    </main>

    <script>
        function verify() {
            const usernamefield = document.getElementById("username")
            const passwordfield = document.getElementById("password")


        }
    </script>
</body>

</html>
