<?php
session_start();
require("../database.php");
require("../constants.php");

$message = [];

// Get single post if id is set, otherwise get all posts
if (isset($_REQUEST['id'])) {
    $post = get_posts($_REQUEST['id'])[0];
} else {
    $posts = get_posts();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    delete_post($_POST['delete']);
}

function get_posts($id = null)
{
    $db = Database::getConnection();

    $id = $db->escape_string($id);

    if ($id) {
        $query = "SELECT p.id, p.content, p.date, p.title, u.id AS user_id, u.username FROM pln.tbl_post p INNER JOIN pln.tbl_user u ON p.user = u.id WHERE p.id = $id";
    } else {
        $query = "SELECT p.id, p.title, p.date, u.id AS user_id, u.username FROM pln.tbl_post p INNER JOIN pln.tbl_user u ON p.user = u.id ORDER BY p.date DESC";
    }

    $result = $db->query($query);

    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return null;
    }
}

function delete_post($id)
{
    $db = Database::getConnection();

    $id = $db->escape_string($id);

    $user = $_SESSION['user']["username"];

    $query = "DELETE FROM pln.tbl_post WHERE id = $id";

    if ($user !== "admin") {
        $query .= " AND user = " . $_SESSION['user']["id"];
    }

    $result = $db->query($query);

    if ($result) {
        header("Location: posts.php?msg=delete_success");
    } else {
        $message[] = "Failed to delete post";
    }
}

if ($_GET["msg"]) {
    switch ($_GET["msg"]) {
        case 'delete_success':
            $message[] = Constants::MSG_POST_DELETE_SUCCESS;
            break;
        default:
            $message[] = Constants::MSG_UNKNOWN;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../components/head.php"); ?>
    <title><?php
            if ($_GET["id"]) {
                echo htmlspecialchars($post["title"]) . " - PLN";
            } else {
                echo "Posts - PLN";
            } ?></title>
</head>

<body>
    <?php include("../components/header.php"); ?>

    <main class="flex flex-col">
        <?php if (sizeof($message)) : ?>
            <div class="bg-red-500 text-white p-2">
                <?php foreach ($message as $msg) : ?>
                    <?php echo $msg ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <?php if ($_GET["id"]) : ?>

            <div class="m-auto">
                <h1><?php echo htmlspecialchars($post["title"]) ?></h1>
                <p>
                    <a class="font-bold" href="/users.php?id=<?php echo $post["user_id"] ?>"><?php echo htmlspecialchars($post["username"]) ?></a> -
                    <?php echo $post["date"] ?>
                </p>
                <hr class="my-2 border-1 border-gray-400" />
                <p><?php echo htmlspecialchars($post["content"]) ?></p>
                <?php if ($_SESSION["user"]["username"] === "admin" || $_SESSION["user"]["id"] === $post["user_id"]) : ?>
                    <form action="/posts.php?id=<?php echo $post["id"] ?>" method="post">
                        <input type="hidden" name="delete" value="<?php echo $post["id"] ?>">
                        <input type="submit" value="Delete<?php if ($_SESSION["user"]["id"] !== $post["user_id"]) : ?> (admin)<?php endif ?>" class="p-2 my-4 bg-red-600 rounded-md hover:shadow-md">
                    </form>
                <?php endif; ?>
            </div>

        <?php else : ?>

            <a href="/add.php" class="m-4 bg-slate-600 p-3 rounded-md text-slate-300 text-center font-bold text-xl w-80 hover:shadow-lg transition">Add new post</a>
            <div class="postlist flex flex-row flex-wrap text-center justify-center">
                <?php foreach ($posts as $post) : ?>
                    <a href="/posts.php?id=<?php echo $post['id']; ?>">
                        <div class="post p-4 m-2 bg-slate-600 text-slate-300 rounded hover:shadow-lg transition" id="post-<?php echo $post['id']; ?>">
                            <h1><?php echo $post["title"]; ?></h1>
                            <h3><?php echo $post["username"]; ?></h3>
                            <p><?php echo $post["date"]; ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
