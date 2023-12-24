<?php
include 'vendor/autoload.php';
use App\classes\Session;
use App\classes\Fixture;
use App\Classes\GetPlayerData;
use App\classes\Round;

$dataObj = new GetPlayerData;
$fixtureObj = new Fixture;

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
    <a href="winner.php">Winner</a>
    <form action="insert.php" method="POST">
        <input type="text" name="roundNumber" value="1" hidden />
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
        $roundNumber = 1;
        $result = $dataObj->getMatchesByRound($roundNumber);
        while ($data = mysqli_fetch_assoc($result)) {
            $player_id = $data['participant1_id'];
            $playerDataParticipantFrist = $dataObj->getPlayerDataById($player_id);
            $player_id = $data['participant2_id'];
            $playerDataParticipantSecond = $dataObj->getPlayerDataById($player_id);
            ?>
            <tr>
                <td>
                    <?= $data['match_data'] ?>.
                </td>
                <td>
                    <?= $playerDataParticipantFrist['name']; ?>
                </td>
                <td>
                    <?= $playerDataParticipantSecond['name']; ?>
                </td>
                <td>
                    <form action="process_input.php" method="POST">
                        <input type="hidden" name="match_id" value="<?= $data['match_data'] ?>">
                        <input type="hidden" name="round_no" value="<?= $data['round_number'] ?>">
                        <select name="winner_name" id="player">
                            <?php
                            $player1_id = $data['participant1_id'];
                            $player2_id = $data['participant2_id'];
                            $winner = $data['winner_id'];
                            if ($winner !== '' && $winner !== null) {
                                echo '<option value="' . $winner . '">' . getPlayerNameById($winner, $dataObj) . '</option>';
                            } else {
                                echo '<option value="">Select Winner</option>';
                                echo '<option value="' . $player1_id . '">' . getPlayerNameById($player1_id, $dataObj) . '</option>';
                                echo '<option value="' . $player2_id . '">' . getPlayerNameById($player2_id, $dataObj) . '</option>';
                            }
                            ?>
                        </select>
                        <?php
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
    if ($roundCheck) {
        $nextRoundNumber = $roundNumber + 1;
        ?>
        <a href="winner.php?round=<?= $nextRoundNumber; ?>" target="_blank">Next Round
            <?= $nextRoundNumber; ?>
        </a>
        <br>
        <a href="loser.php?round=<?= $nextRoundNumber; ?>" target="_blank">Next Round
            <?= $nextRoundNumber; ?>
        </a>
        <?php
    }
    ?>
</body>

</html>