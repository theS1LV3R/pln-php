<!-- Handle registration and include registration form -->
<?php
require("../database.php");

session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.html");
    exit;
}

$db = Database::getConnection();

// If the form has been submitted, then process the form and save it to the database
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // Get the form data
    $username = $db->escape_string($_POST['username']);
    $password = password_hash($db->escape_string($_POST['password']), PASSWORD_ARGON2ID);

    // Check if the username is already in the database
    // If the username is not in the database, then add it
    if (!Database::getUser($username)) {
        $query = "INSERT INTO pln.tbl_user (username, password) VALUES ('$username', '$password')";
        $result = $db->query($query);

        if ($result) {
            header("Location: login.php?message=registered");
            exit;
        } else {
            $message = "There was a problem registering your account. Please try again.";
        }
    } else {
        $message = "That username is already in use. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../components/head.php") ?>
    <title>Register</title>
</head>

<body>
    <?php require("../components/header.php"); ?>

    <main>
        <?php
        if (isset($message)) {
            echo "<p>" . $message . "</p>";
        }
        ?>

        <!-- Registration form -->
        <div class="flex w-auto mx-auto justify-center">
            <form action="" method="post" class="m-4 p-4 rounded-lg bg-slate-600 flex flex-col">
                <label for="username">Username:</label>
                <input name="username" type="text" id="username" required>
                <label for="password">Password:</label>
                <input name="password" type="password" id="password" required>
                <label for="repeat-password">Repeat Password:</label>
                <input name="repeat-password" type="password" id="repeat-password" required>
                <input name="submit" type="submit" value="Register" class="bg-slate-400">
            </form>
        </div>
    </main>

</body>

</html>
