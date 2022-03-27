<?php
require("../database.php");

session_start();

if (!isset($_SESSION["user"]))
    return header("Location: login.php");

$db = Database::getConnection();

$query = "SELECT u.username, u.create_time, u.id FROM pln.tbl_user u WHERE u.id =" . $_SESSION['user']['id'] . ";";

$result = $db->query($query)->fetch_all(MYSQLI_ASSOC)[0];

$post_query = "SELECT * FROM pln.tbl_post p WHERE p.user =" . $_SESSION['user']['id'] . ";";

$posts = $db->query($post_query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../components/head.php"); ?>
    <title><?php echo htmlspecialchars($result["username"]); ?> - PLN</title>
</head>

<body>
    <?php include("../components/header.php"); ?>

    <main class="flex justify-center flex-col text-center mx-auto">
        <style>
            table {
                border-collapse: collapse;
                border: 1px currentColor solid;
            }

            th,
            td {
                border: 1px currentColor solid;
                padding: 5px;
            }

            tr td:nth-child(1) {
                border-right: 3px currentColor solid;
            }
        </style>

        <h1>User info</h1>

        <table>
            <tr>
                <td>User ID</td>
                <td>
                    <?php echo $result["id"]; ?>
                </td>
            </tr>
            <tr>
                <td>Username</td>
                <td>
                    <?php echo $result["username"]; ?>
                </td>
            </tr>
            <tr>
                <td>Registration time</td>
                <td>
                    <?php echo $result["create_time"]; ?>
                </td>
            </tr>
        </table>

        <h1>Posts</h1>
        <?php if (empty($posts)) : ?>
            <p>No posts</p>
        <?php else : ?>
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

        <a href="logout.php" class="bg-red-900 rounded font-bold p-2 m-2 mx-auto">Log out</a>
    </main>
</body>

</html>
