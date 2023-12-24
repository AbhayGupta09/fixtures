<?php


namespace App\classes;



/**
 * Summary of Database
 */
class Round
{
    private $db;

    public function __construct()
    {
        $this->db = Database::db();
    }

    /**
     * Check the last round number in the matches table
     *
     * @return int|bool The maximum round number, or false on failure
     */

    public function checkCurrentRound()
    {
        $sql = "SELECT MAX(round_number) as max_round FROM matches";
        $stmt = $this->db->prepare($sql);
        $result = $this->db->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $matchCount = $row['max_round'];
            return $matchCount;
        } else {
            echo "Error executing the query: " . $this->db->error;
            return false;
        }
    }
    public function checkRoundStatus($roundNumber)
    {
        $sql = "SELECT COUNT(*) AS total_matches
            FROM matches
            WHERE round_number = ? AND match_status = 1";

        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $roundNumber);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalMatchesWithStatus1 = (int) $row['total_matches'];

                // Get the total number of matches in the round
                $totalMatchesInRound = $this->getTotalMatchesInRound($roundNumber);

                $stmt->close();

                // Check if all matches in the round have match_status = 1
                if ($totalMatchesInRound != 0) {
                    if ($totalMatchesWithStatus1 === $totalMatchesInRound) {
                        return True;
                    }
                }
            }
        }

        return false;
    }

    // Function to get the total number of matches in a given round
    private function getTotalMatchesInRound($roundNumber)
    {
        $sql = "SELECT COUNT(*) AS total_matches
            FROM matches
            WHERE round_number = ?";

        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $roundNumber);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return (int) $row['total_matches'];
            }
        }

        return 0;
    }


}

