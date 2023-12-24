<?php
namespace App\classes;

use App\classes\Database;
use App\classes\Helper;
use App\Classes\GetPlayerData;

class Fixture
{
    private $db;
    private $helper;

    public function __construct()
    {
        $this->db = Database::db();
        $this->helper = new Helper();
    }



    public function generateDoubleEliminationFixture($round)
    {
        // Retrieve player data from the database
        $players = $this->getPlayers();

        $numParticipants = count($players);

        if ($numParticipants <= 1) {
            return [];
        }

        $fixture = [];

        // Shuffle the participants for randomization
        shuffle($players);

        // $round = 1; // Initialize the round number

        // Create the first round of matches (winners bracket)
        $numRounds = ceil(log($numParticipants, 2));
        $numMatches = pow(2, $numRounds - 1);
        $matchNumber = $this->helper->checkMatchCount();
        $matchNumber = $matchNumber + 1;

        // Ensure that you have enough participants for matches
        if ($numMatches > 0) {

            for ($i = 0; $i < $numMatches; $i++) {
                if (!isset($players[$i * 2], $players[$i * 2 + 1])) {
                    // Break the loop if there are not enough participants
                    break;
                }

                $match = [
                    'participant1_id' => $players[$i * 2]['id'],
                    'participant2_id' => $players[$i * 2 + 1]['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round, // Add the round number
                    // You can replace this with your match data logic
                ];
                $bracket_type = "I";
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO matches (participant1_id, participant2_id, match_data, round_number, bracket_type) VALUES (?, ?, ?, ?, ?)");

                $stmt->bind_param("iisis", $match['participant1_id'], $match['participant2_id'], $match['match_data'], $match['round_number'], $bracket_type);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }

            // If the total number of players is odd, add the last player to the second round
            if ($numParticipants % 2 != 0) {
                $lastPlayer = end($players);
                $match = [
                    'winner' => $lastPlayer['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round, // Second round
                ];
                $bracket_type = "I";
                $bye_status = 1;
                $matchNumer = intval($match['winner']);
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO winner_braket (winner, last_match_no,round_number,bracket_type,bye_status) VALUES (?, ?, ?, ?, ?)");

                $stmt->bind_param("iiisi", $matchNumer, $match['match_data'], $match['round_number'], $bracket_type, $bye_status);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }
        } else {
            // Handle the case when there are not enough participants for matches
            $return = "Not enough participants for matches.";
            return $return;
        }

        $return = "done";
        return $return;
    }

    public function generateDoubleEliminationFixtureWinnerBracket($round, $bracket_type)
    {
        $playerDataObj = new GetPlayerData();
        // Retrieve player data from the database
        $players = $playerDataObj->getWinnerPlayer($round - 1);

        $numParticipants = count($players);

        if ($numParticipants <= 1) {
            return [];
        }

        $fixture = [];

        // Shuffle the participants for randomization
        shuffle($players);

        // $round = 1; // Initialize the round number

        // Create the first round of matches (winners bracket)
        $numRounds = ceil(log($numParticipants, 2));
        $numMatches = pow(2, $numRounds - 1);
        $matchNumber = $this->helper->checkMatchCount();
        $matchNumber = $matchNumber + 1;

        // Ensure that you have enough participants for matches
        if ($numMatches > 0) {
            for ($i = 0; $i < $numMatches; $i++) {
                if (!isset($players[$i * 2], $players[$i * 2 + 1])) {
                    // Break the loop if there are not enough participants
                    break;
                }

                $match = [
                    'participant1_id' => $players[$i * 2]['id'],
                    'participant2_id' => $players[$i * 2 + 1]['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round, // Add the round number
                    // You can replace this with your match data logic
                ];

                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO matches (participant1_id, participant2_id, match_data, round_number,bracket_type) VALUES (?, ?, ?, ?,?)");

                $stmt->bind_param("iisis", $match['participant1_id'], $match['participant2_id'], $match['match_data'], $match['round_number'], $bracket_type);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }
            if ($numParticipants % 2 != 0) {
                $lastPlayer = end($players);
                $winnerPlayermatch = [
                    'winner' => $lastPlayer['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round,
                ];
                $bracket_type = "W";
                $bye_status = 1;
                $matchNumer = intval($winnerPlayermatch['winner']);
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO winner_braket (winner, last_match_no,round_number,bracket_type,bye_status) VALUES (?, ?, ?, ?, ?)");

                $stmt->bind_param("iiisi", $matchNumer, $winnerPlayermatch['match_data'], $winnerPlayermatch['round_number'], $bracket_type, $bye_status);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }
        } else {
            // Handle the case when there are not enough participants for matches
            $return = "Not enough participants for matches.";
            return $return;
        }



        $return = "done";
        return $return;
    }
    public function generateDoubleEliminationFixtureLoserBracket($round, $bracket_type)
    {

        $playerDataObj = new GetPlayerData();
        // Retrieve player data from the database
        $players = $playerDataObj->getLoserPlayer($round - 1);

        $numParticipants = count($players);

        if ($numParticipants <= 1) {
            return [];
        }

        $fixture = [];

        // Shuffle the participants for randomization
        shuffle($players);

        // $round = 1; // Initialize the round number

        // Create the first round of matches (winners bracket)
        $numRounds = ceil(log($numParticipants, 2));
        $numMatches = pow(2, $numRounds - 1);
        $matchNumber = $this->helper->checkMatchCount();
        $matchNumber = $matchNumber + 1;

        // Ensure that you have enough participants for matches
        if ($numMatches > 0) {
            for ($i = 0; $i < $numMatches; $i++) {
                if (!isset($players[$i * 2], $players[$i * 2 + 1])) {
                    // Break the loop if there are not enough participants
                    break;
                }

                $match = [
                    'participant1_id' => $players[$i * 2]['id'],
                    'participant2_id' => $players[$i * 2 + 1]['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round,
                    // You can replace this with your match data logic
                ];
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO matches (participant1_id, participant2_id, match_data, round_number,bracket_type) VALUES (?, ?, ?, ?,?)");

                $stmt->bind_param("iisis", $match['participant1_id'], $match['participant2_id'], $match['match_data'], $match['round_number'], $bracket_type);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }
            if ($numParticipants % 2 != 0) {
                $lastPlayer = end($players);
                $loserPlayermatch = [
                    'loser' => $lastPlayer['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round,
                ];
                $bracket_type = "L";
                $bye_status = 1;
                $matchNumer = intval($loserPlayermatch['loser']);
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO loser_braket (loser, last_match_no,round_number,bracket_type,bye_status) VALUES (?, ?, ?, ?, ?)");

                $stmt->bind_param("iiisi", $matchNumer, $loserPlayermatch['match_data'], $loserPlayermatch['round_number'], $bracket_type, $bye_status);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }
        } else {
            // Handle the case when there are not enough participants for matches
            $return = "Not enough participants for matches.";
            return $return;
        }



        $return = "done";
        return $return;
    }

    public function generateDoubleEliminationFinalMatch($round, $matchType)
    {
        $playerDataObj = new GetPlayerData();
        $players = $playerDataObj->getLastPlayer($round);
        $numParticipants = count($players);

        if ($numParticipants <= 1) {
            return [];
        }

        $fixture = [];

       

        // $round = 1; // Initialize the round number

        // Create the first round of matches (winners bracket)
        $numRounds = ceil(log($numParticipants, 2));
        $numMatches = pow(2, $numRounds - 1);
        $matchNumber = $this->helper->checkMatchCount();
        $matchNumber = $matchNumber + 1;

        // Ensure that you have enough participants for matches
        if ($numMatches > 0) {

            for ($i = 0; $i < $numMatches; $i++) {
                if (!isset($players[$i * 2], $players[$i * 2 + 1])) {
                    // Break the loop if there are not enough participants
                    break;
                }

                $match = [
                    'participant1_id' => $players[$i * 2]['id'],
                    'participant2_id' => $players[$i * 2 + 1]['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round, // Add the round number
                    // You can replace this with your match data logic
                ];
                $bracket_type = "I";
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO matches (participant1_id, participant2_id, match_data, round_number, bracket_type) VALUES (?, ?, ?, ?, ?)");

                $stmt->bind_param("iisis", $match['participant1_id'], $match['participant2_id'], $match['match_data'], $match['round_number'], $bracket_type);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }

            // If the total number of players is odd, add the last player to the second round
            if ($numParticipants % 2 != 0) {
                $lastPlayer = end($players);
                $match = [
                    'winner' => $lastPlayer['id'],
                    'match_data' => $matchNumber,
                    'round_number' => $round, // Second round
                ];
                $bracket_type = "I";
                $bye_status = 1;
                $matchNumer = intval($match['winner']);
                // Insert match data into the database using prepared statement
                $stmt = $this->db->prepare("INSERT INTO winner_braket (winner, last_match_no,round_number,bracket_type,bye_status) VALUES (?, ?, ?, ?, ?)");

                $stmt->bind_param("iiisi", $matchNumer, $match['match_data'], $match['round_number'], $bracket_type, $bye_status);
                $stmt->execute();
                $stmt->close();
                $matchNumber++;
            }
        } else {
            // Handle the case when there are not enough participants for matches
            $return = "Not enough participants for matches.";
            return $return;
        }

        $return = "done";
        return $return;
    }
    private function getPlayers()
    {
        $players = [];
        $sql = "SELECT id, name FROM players";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $players[] = $row;
            }
        }

        return $players;
    }




}
