<?php
require_once 'env.php';

class Database
{
    private static string $db_host = DB_HOST;
    private static string $db_user = DB_USER;
    private static string $db_pass = DB_PASS;
    private static string $db_name = DB_NAME;

    private static Database $db;
    private mysqli $connection;

    private function __construct()
    {
        try {
            $this->connection = new MySQLi(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
            if ($this->connection->connect_errno) exit("Failed to connect to MySQL: " . $this->connection->connect_error);
        } catch (\Throwable $th) {
            exit("Failed to connect to MySQL: " . $th->getMessage());
        }
    }

    public static function getConnection(): mysqli
    {
        if (!isset(self::$db)) {
            self::$db = new Database();
        }
        return self::$db->connection;
    }

    function __destruct()
    {
        $this->selfDestruct();
    }

    private function selfDestruct() {
        $this->connection->close();
    }

    public static function getUser(string $username)
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM tbl_user WHERE username = '" . $username . "'";
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return false;
        }
    }
}
