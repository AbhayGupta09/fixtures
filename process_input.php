<?php
include 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\classes\Database;

$conn = Database::db();

// Start a transaction
$conn->begin_transaction();

try {
    if (isset($_POST['submit_result'])) {
        $matchId = $_POST['match_id'];
        $roundNo = $_POST['round_no'];
        $winnerName = $_POST['winner_name'];

        // Assuming that $winnerName is the winner's ID
        $winnerId = $winnerName;

        // Insert the match result into the result table using prepared statement
        $sqlInsertResult = "INSERT INTO result (match_id, round_no, winner_id, updated_at)
            VALUES (?, ?, ?, NOW())";
        $stmtInsertResult = $conn->prepare($sqlInsertResult);
        $stmtInsertResult->bind_param("iii", $matchId, $roundNo, $winnerId);
        $stmtInsertResult->execute();

        // Update winner_id in the matches table
        $matchStatus = 1;
        $sqlUpdateMatches = "UPDATE matches SET winner_id = ?, match_status = ? WHERE match_data = ?";
        $stmtUpdateMatches = $conn->prepare($sqlUpdateMatches);
        $stmtUpdateMatches->bind_param("iii", $winnerId, $matchStatus, $matchId);
        $stmtUpdateMatches->execute();

        $bracket_type = 'W';

        // Insert the winner into the winner bracket table
        $sqlInsertWinner = "INSERT INTO winner_braket (winner, last_match_no, round_number, bracket_type)
            VALUES (?, ?, ?, ?)";
        $stmtInsertWinner = $conn->prepare($sqlInsertWinner);
        $stmtInsertWinner->bind_param("iiis", $winnerId, $matchId, $roundNo, $bracket_type);
        $stmtInsertWinner->execute();

        // Find loser data from the matches table
        $sqlSelectLoser = "SELECT participant1_id, participant2_id FROM matches WHERE match_data = ? And round_number=?";
        $stmtSelectLoser = $conn->prepare($sqlSelectLoser);
        $stmtSelectLoser->bind_param("ii", $matchId, $roundNo);
        $stmtSelectLoser->execute();
        $stmtSelectLoser->bind_result($participant1Id, $participant2Id);
        $stmtSelectLoser->fetch();
        $stmtSelectLoser->close();

        // Determine the loser based on the winner
        $loserId = ($winnerId == $participant1Id) ? $participant2Id : $participant1Id;

        // Insert the loser into the loser bracket table
        $bracket_type = 'L';
        $sqlInsertLoser = "INSERT INTO loser_braket (loser, last_match_no, round_number, bracket_type)
            VALUES (?, ?, ?, ?)";
        $stmtInsertLoser = $conn->prepare($sqlInsertLoser);
        $stmtInsertLoser->bind_param("iiis", $loserId, $matchId, $roundNo, $bracket_type);
        $stmtInsertLoser->execute();

        $conn->commit();

        echo "Match result successfully recorded. Winner and loser updated in matches, winner bracket, and loser bracket tables.";
    }
    if (isset($_POST['loser_submit_result'])) {
        $matchId = $_POST['match_id'];
        $roundNo = $_POST['round_no'];
        $winnerName = $_POST['winner_name'];

        // Assuming that $winnerName is the winner's ID
        $winnerId = $winnerName;

        // Insert the match result into the result table using prepared statement
        $sqlInsertResult = "INSERT INTO result (match_id, round_no, winner_id, updated_at)
            VALUES (?, ?, ?, NOW())";
        $stmtInsertResult = $conn->prepare($sqlInsertResult);
        $stmtInsertResult->bind_param("iii", $matchId, $roundNo, $winnerId);
        $stmtInsertResult->execute();

        // Update winner_id in the matches table
        $matchStatus = 1;
        $sqlUpdateMatches = "UPDATE matches SET winner_id = ?, match_status = ? WHERE match_data = ?";
        $stmtUpdateMatches = $conn->prepare($sqlUpdateMatches);
        $stmtUpdateMatches->bind_param("iii", $winnerId, $matchStatus, $matchId);
        $stmtUpdateMatches->execute();

        $bracket_type = 'W';

        // Insert the winner into the winner bracket table
        $sqlInsertWinner = "INSERT INTO loser_braket (loser, last_match_no, round_number, bracket_type)
            VALUES (?, ?, ?, ?)";
        $stmtInsertWinner = $conn->prepare($sqlInsertWinner);
        $stmtInsertWinner->bind_param("iiis", $winnerId, $matchId, $roundNo, $bracket_type);
        $stmtInsertWinner->execute(); 

        $conn->commit();

        echo "Match result successfully recorded. Winner and loser updated in matches, winner bracket, and loser bracket tables.";
    }


} catch (Exception $e) {
    // An error occurred, roll back the transaction
    $conn->rollback();
    echo "Error: " . $e->getMessage();
} finally {
    // Close prepared statements
    if (isset($stmtSelectWinner)) {
        $stmtSelectWinner->close();
    }
    if (isset($stmtInsertResult)) {
        $stmtInsertResult->close();
    }
    if (isset($stmtUpdateMatches)) {
        $stmtUpdateMatches->close();
    }
    if (isset($stmtInsertWinner)) {
        $stmtInsertWinner->close();
    }
    // Close the database connection
    $conn->close();
}
$url = "index.php";
if (isset($_SERVER['HTTP_REFERER'])) {
    $referringPage = $_SERVER['HTTP_REFERER'];

    // Redirect back to the referring page
    header("Location: $referringPage");
    exit;
} else {
    // If no referring page is set, redirect to a default page (e.g., index.php)
    header('Location: ' . $url);
    exit;
}
?>