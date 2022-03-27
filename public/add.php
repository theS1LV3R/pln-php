<?php
require("../database.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = Database::getConnection();

    $title = $db->escape_string($_POST['title']);
    $content = $db->escape_string($_POST['content']);
    $user = $db->escape_string($_SESSION['user']['id']);

    $query = "INSERT INTO pln.tbl_post (title, content, user) VALUES ('$title', '$content', '$user')";
    $result = $db->query($query);

    if ($result) {
        $post_id = $db->insert_id;

        header("Location: posts.php?id=$post_id&message=created");
    } else {
        echo "Error: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../components/head.php"); ?>
    <title>Add post</title>
</head>

<body>
    <?php require("../components/header.php"); ?>
    <main>
        <form action="" method="post" class="flex flex-col m-auto p-3 bg-slate-600 max-w-lg rounded-lg my-4">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="Title" class="m-2" />
            <label for="content">Content:</label>
            <textarea name="content" id="content" placeholder="Content" class="m-2"></textarea>
            <input type="submit" value="Submit" class="cursor-pointer bg-slate-700 m-2 rounded-md p-2">
        </form>
    </main>
</body>

</html>
