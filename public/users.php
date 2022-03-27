<?php
session_start();
require("../database.php");

const base_query = "SELECT u.id, u.username, u.create_time from pln.tbl_user u";
$db = Database::getConnection();


function get_user(int $id)
{
    $db = Database::getConnection();


    $query = base_query . " WHERE u.id = $id";

    $result = $db->query($query)->fetch_all(MYSQLI_ASSOC)[0];

    return $result;
}

function get_all_users()
{

    $db = Database::getConnection();

    $query = base_query;

    $result = $db->query($query)->fetch_all(MYSQLI_ASSOC);

    return $result;
}

if (isset($_GET["id"])) {
    $result = get_user($db->escape_string($_GET["id"]));
} else {
    $result = get_all_users();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("../components/head.php"); ?>
    <title>Users</title>
</head>

<body>
    <?php require("../components/header.php"); ?>

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

    <main>
        <?php if (isset($_GET["id"])) : ?>
            <table>
                <tr>
                    <td>User ID</td>
                    <td><?php echo $result["id"]; ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?php echo $result["username"]; ?></td>
                </tr>
                <tr>
                    <td>Registration time</td>
                    <td><?php echo $result["create_time"]; ?></td>
                </tr>
            </table>
        <?php else : ?>
            <h1 class="text-center">Users</h1>
            <div class="postlist flex flex-row flex-wrap text-center justify-center">
                <?php foreach ($result as $user) : ?>
                    <div class="post p-4 m-2 bg-slate-600 text-slate-300 rounded hover:shadow-lg transition" id="user-<?php echo $user['id']; ?>">
                        <table>
                            <tr>
                                <td>ID</td>
                                <td><?php echo $user["id"]; ?></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td> <?php echo $user["username"]; ?></td>
                            </tr>
                            <tr>
                                <td>Creation time</td>
                                <td> <?php echo $user["create_time"]; ?></td>
                            </tr>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif ?>
    </main>
</body>

</html>
