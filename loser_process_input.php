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
    if (isset($_POST['submit_result_loser'])) {
        $matchId = $_POST['match_id'];
        $roundNo = $_POST['round_no'];
        $winnerName = $_POST['winner_name'];


        $winnerId = $winnerName;

        // Insert the match result into the result table using prepared statement
        $sqlInsertResult = "INSERT INTO result (match_id, round_no, winner_id, updated_at)
                            VALUES (?, ?, ?, NOW())";
        $stmtInsertResult = $conn->prepare($sqlInsertResult);
        $stmtInsertResult->bind_param("iii", $matchId, $roundNo, $winnerId);
        $stmtInsertResult->execute();

        // Update winner_id in the matches table
        $matchSatus = 1;
        $bracket_status = 'W';
        $roundNumber=1;
        $sqlUpdateMatches = "UPDATE matches SET round_number=?,winner_id = ?,match_status=? ,bracket_type=? WHERE match_data= ?";
        $stmtUpdateMatches = $conn->prepare($sqlUpdateMatches);
        $stmtUpdateMatches->bind_param("siisi", $roundNumber, $winnerId, $matchSatus, $bracket_status, $matchId);
        $stmtUpdateMatches->execute();

        // Commit the transaction if all queries are successful
        $conn->commit();

        // echo "Match result successfully recorded. Winner updated in matches table.";
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