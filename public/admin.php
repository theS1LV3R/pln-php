<?php
require("../database.php");
require("../constants.php");
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
}

if ($_SESSION["user"]["username"] !== "admin") {
    header("Location: index.php");
}


function get_users()
{
    $db = Database::getConnection();

    $query = "SELECT * FROM pln.tbl_user";
    $column_query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'tbl_user' and TABLE_SCHEMA = 'pln' ORDER BY ORDINAL_POSITION";


    $result = $db->query($query);
    $column_result = $db->query($column_query);

    $num_rows = $result->num_rows;

    $html_output = '';
    $html_columns = "";

    if ($column_result) {
        $html_columns .= '<tr>';
        while ($row = $column_result->fetch_assoc()) {
            $html_columns .= '<th>' . $row['COLUMN_NAME'] . '</th>';
        }
        $html_columns .= '<th>Actions</th>';
        $html_columns .= '</tr>';
        $column_result->free();
    }

    if ($num_rows > 0) {
        foreach ($result as $row) {
            $id = 0;

            $is_first = true;

            $html_output .= '<tr>';
            foreach ($row as $value) {
                if ($is_first) {
                    $id = $value;
                    $is_first = false;
                }
                $html_output .= sprintf('<td>%s</td>', htmlspecialchars($value));
            }

            $html_output .= '<td><form onsubmit="return confirm(\'Are you sure you want to delete this user?\')" action="" method="post"><input type="submit" name="delete" id="delete" value="Delete" class="bg-red-600 p-2 font-bold" /><input type="number" hidden name="id" id="id" value="' . $id . '" /></form></td>';

            $html_output .= '</tr>';
        }
        $result->free();
    }

    return array("num_rows" => $num_rows, "html_output" => $html_output, "html_columns" => $html_columns);
}

function drop_user(int $id)
{
    $db = Database::getConnection();

    $query = "DELETE FROM pln.tbl_user u WHERE u.id = $id";

    $result = $db->query($query);

    if ($result) {
        header("Location: admin.php?msg=delete_success");
    } else {
        header("Location: admin.php?msg=delete_failed");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_REQUEST["delete"]) && isset($_REQUEST["id"])) {
        if ($_REQUEST["id"] === $_SESSION["user"]["id"] && $_SESSION["user"]["username"] === "admin") {
            header("Location: admin.php?msg=delete_failed_admin");
            return;
        }

        drop_user($_REQUEST["id"]);
    }
}

$getresult = get_users();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("../components/head.php") ?>
    <title>ADMIN PAGE</title>
</head>

<body>
    <?php include("../components/header.php"); ?>
    <main>

        <?php if (isset($_REQUEST["msg"])) : ?>
            <div>
                <h1>
                    <?php
                    switch ($_REQUEST["msg"]) {
                        case "delete_failed":
                            $msg = Constants::MSG_DELETE_FAILED;
                            echo $msg;
                            break;
                        case "delete_success":
                            $msg = Constants::MSG_DELETE_SUCCESS;
                            echo $msg;
                            break;
                        case "delete_failed_admin":
                            $msg = Constants::MSG_DELETE_FAILED_ADMIN;
                            echo $msg;
                            break;
                        default:
                            $msg = "idk something happened";
                            echo $msg;
                            break;
                    }
                    ?>
                </h1>
            </div>
        <?php endif ?>
        <p>Number of rows returned: <?php echo $getresult["num_rows"] ?> </p>

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

            tbody tr:nth-child(1) {
                border-bottom: 3px currentColor solid;
            }
        </style>
        <table>
            <?php echo $getresult["html_columns"]; ?>
            <?php echo $getresult["html_output"]; ?>
        </table>
    </main>
</body>

</html>
