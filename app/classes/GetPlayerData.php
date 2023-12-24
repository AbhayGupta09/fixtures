<?php

namespace App\Classes;

use App\Classes\Database;

class GetPlayerData
{
    private $db;

    public function __construct()
    {
        $this->db = Database::db();
    }

    public function getMatchesByRound($roundNumber)
    {
        $sql = "SELECT * FROM `matches` WHERE round_number = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $roundNumber);
            $stmt->execute();

            // Return the result set directly
            return $stmt->get_result();
        } else {
            // Throw an exception for better error handling
            throw new \Exception("Error in prepared statement: " . $this->db->error);
        }
    }
    public function getMatches($roundNumber, $bracket)
    {
        $sql = "SELECT * FROM `matches` WHERE round_number = ? And bracket_type=?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("is", $roundNumber, $bracket);
            $stmt->execute();

            // Return the result set directly
            return $stmt->get_result();
        } else {
            // Throw an exception for better error handling
            throw new \Exception("Error in prepared statement: " . $this->db->error);
        }
    }
    public function getWinnerPlayer($roundNumber)
    {
        $sql = "SELECT * FROM `winner_braket` Join players p ON p.id=winner_braket.winner WHERE round_number = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $roundNumber);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $players[] = $row;
            }

            $stmt->close();

            return $players;
        } else {
            // Throw an exception for better error handling
            throw new \Exception("Error in prepared statement: " . $this->db->error);
        }
    }



    /**
     * Get player data by player ID
     *
     * @param int $playerId The player ID
     * @return array|null An associative array containing player data or null if not found
     */
    public function getPlayerDataById($playerId)
    {
        $sql = "SELECT * FROM `players` WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $playerId);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch player data
            $playerData = ($result->num_rows === 1) ? $result->fetch_assoc() : null;

            $stmt->close();
            return $playerData;
        } else {
            // Handle the error if needed
            echo "Error in prepared statement: " . $this->db->error;
            return null;
        }
    }
    public function getLoserPlayer($roundNumber)
    {
        $sql = "SELECT * FROM `loser_braket` Join players p ON p.id=loser_braket.loser WHERE round_number = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $roundNumber);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $players[] = $row;
            }

            $stmt->close();

            return $players;
        } else {
            // Throw an exception for better error handling
            throw new \Exception("Error in prepared statement: " . $this->db->error);
        }
    }
    public function getLastPlayer($type)
    {
        $roundSql = "SELECT MAX(round_number) as max_round FROM " . ($type == 1 ? "winner_braket" : "loser_braket");
        $roundResult = $this->db->query($roundSql);
        $roundData = $roundResult->fetch_assoc();
        $maxRoundNumber = $roundData['max_round'];
        if ($type == 1) {
            $sql = "SELECT * FROM `winner_braket` Join players p ON p.id=winner_braket.winner WHERE round_number = ?";
        } else if ($type == 0) {
            $sql = "SELECT * FROM `loser_braket` Join players p ON p.id=loser_braket.loser WHERE round_number = ?";
        }
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $maxRoundNumber);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $players[] = $row;
            }

            $stmt->close();

            return $players;
        } else {
            // Throw an exception for better error handling
            throw new \Exception("Error in prepared statement: " . $this->db->error);
        }
    }




}
