<?php
class DBConnection
{
    private $host = 'localhost'; 
    private $username = 'root'; 
    private $password = ''; 
    private $dbname = 'inline_db'; 

    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function close()
    {
        $this->conn->close();
    }
}
?>
