<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'vendor/autoload.php';
use App\classes\Session;
use App\classes\Fixture;
use App\Classes\GetPlayerData;
use App\classes\Round;

Session::init();
$dataObj = new GetPlayerData;
$fixtureObj = new Fixture;
$roundNumber = Session::get('roundNumber');

function getPlayerNameById($playerId, $dataObj)
{
    $playerData = $dataObj->getPlayerDataById($playerId);
    return $playerData !== null ? $playerData['name'] : '';
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Re Fixture List</title>
</head>

<body>
    <h1>Fixture List</h1>
    <form action="insert.php" method="POST">
        <input type="text" name="roundNumber" value="<?= $roundNumber; ?>" hidden />
        <input type="submit" name="create_match" value="Create Match">
    </form>
    <?php
    $returTxt = Session::get('fixture_generated');
    if ($returTxt) {
        echo "Done";
        Session::unsetSession('fixture_generated');
    } else {
        Session::set('fixture_generated_fail', 'Fail to Generated Fixture');
        echo Session::get('fixture_generated');
        Session::unsetSession('ixture_generated_fail');

    }


    ?>
    <table>
        <?php
        $result = $dataObj->getMatchesByRound($roundNumber);
        while ($data = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td>Match No:
                    <?= $data['match_data'] ?>
                </td>
                <td>
                    <?php
                    $player_id = $data['participant1_id'];
                    $playerData = $dataObj->getPlayerDataById($player_id);

                    if ($playerData !== null) {
                        // Access player data, for example:
                        echo $playerData['name'];
                    } else {
                        // Handle the case when player data is not found
                        echo "Player not found.";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $player_id = $data['participant2_id'];
                    $playerData = $dataObj->getPlayerDataById($player_id);

                    if ($playerData !== null) {
                        // Access player data, for example:
                        echo $playerData['name'];
                    } else {
                        // Handle the case when player data is not found
                        echo "Player not found.";
                    }
                    ?>
                </td>
                <td>
                    <form action="process_input.php" method="POST">
                        <input type="hidden" name="match_id" value="<?= $data['match_data'] ?>">
                        <input type="hidden" name="round_no" value="<?= $data['round_number'] ?>">
                        <select name="winner_name" id="player">
                            <?php
                            // Function to get player data by ID
                            $player1_id = $data['participant1_id'];
                            $player2_id = $data['participant2_id'];

                            // Display winner (if available)
                            $winner = $data['winner_id'];
                            if ($winner !== '' && $winner !== null) {
                                echo '<option value="' . $winner . '">' . getPlayerNameById($winner, $dataObj) . '</option>';
                            } else {
                                echo '<option value="">Select Winner</option>';
                                // Display participant 1
                                echo '<option value="' . $player1_id . '">' . getPlayerNameById($player1_id, $dataObj) . '</option>';

                                // Display participant 2
                                echo '<option value="' . $player2_id . '">' . getPlayerNameById($player2_id, $dataObj) . '</option>';
                            }
                            ?>
                        </select>
                        <?php
                        // Display submit button only if a winner is not selected
                        if (empty($winner)) {
                            ?>
                            <input type="submit" name="submit_result" value="Submit Winner">
                            <?php
                        }
                        ?>
                    </form>


                </td>
            </tr>
            <?php
        }
        ?>


    </table>
    <?php
    $roundObj = new Round;
    $roundCheck = $roundObj->checkRoundStatus($roundNumber);
    echo $roundCheck;
    if ($roundCheck) {
        $nextRoundNumber = $roundNumber + 1;
        Session::set('roundNumber', $nextRoundNumber)
            ?>
        <a href="round.php">Next Round</a>
        <?php
    }
    ?>
</body>

</html>