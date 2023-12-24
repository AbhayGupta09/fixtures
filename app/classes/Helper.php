<?php


namespace App\classes;


/**
 * Summary of Database
 */
class Helper
{
    private $db;

    public function __construct()
    {
        $this->db = Database::db();
    }

    /**
     * Summary of db
     * @return \mysqli|bool
     */
    public function checkMatchCount()
    {
        // Assuming $this->db is your database connection object
        $sql = "SELECT COUNT(*) AS match_count FROM matches";

        $result = $this->db->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $matchCount = $row['match_count'];
            return $matchCount;
        } else {
            // Handle the error if needed
            echo "Error executing the query: " . $this->db->error;
            return false;
        }
    }

}
