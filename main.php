<?php
include 'vendor/autoload.php';
use App\classes\Session;
use App\classes\Fixture;
use App\Classes\GetPlayerData;
use App\classes\Round;

$dataObj = new GetPlayerData;
$fixtureObj = new Fixture;
$roundObj = new Round;


function getPlayerNameById($playerId, $dataObj)
{
    $playerData = $dataObj->getPlayerDataById($playerId);
    return $playerData !== null ? $playerData['name'] : '';
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <header class="header">
        <div class="logo"><img src="/assets/logo.png" alt=""></div>
    </header>
    <h1>Fixture List</h1>
    <form action="insert.php" method="POST" style="margin-bottom: 5px;">
        <input type="text" name="roundNumber" value="1" hidden />
        <input type="submit" name="create_match" value="Create First Round Match">

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

    } ?>

    <div class="fixtureContainer">
        <div class="firstRound">
            <?php
            $roundNumber = 1;
            $result = $dataObj->getMatchesByRound($roundNumber);
            while ($data = mysqli_fetch_assoc($result)) {
                $player_id = $data['participant1_id'];
                $playerDataParticipantFrist = $dataObj->getPlayerDataById($player_id);
                $player_id = $data['participant2_id'];
                $playerDataParticipantSecond = $dataObj->getPlayerDataById($player_id);
                ?>
                <form action="process_input.php" method="POST" style="background-color: greenyellow;" class="formli">
                    <div>
                        <label>
                            <?= $data['match_data'] ?>.
                        </label>
                        <label>
                            <?= $playerDataParticipantFrist['name']; ?>
                        </label>
                        <span>VS </span>
                        <label>
                            <?= $playerDataParticipantSecond['name']; ?>
                        </label>
                    </div>
                    <div class="select">
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
                    </div>
                    <?php
                    if (empty($winner)) {
                        ?>
                        <input type="submit" name="submit_result" value="Submit Winner">
                        <?php
                    }
                    ?>
                </form>
                <hr>
                <?php
            }
            ?>
        </div>
        <div class="firstround">
            
        </div>



    </div>
    <div style="height: 140px;">
        <h1 style="display:inline;float:left;">Winner Bracket</h1>
        <h1 style="display:inline;float:right;">Loser Bracket</h1>
    </div>
    <?php
    $currentRound = $roundObj->checkCurrentRound();
    for ($i = 2; $i <= $currentRound; $i++) {
        ?>
       
        <div class="fixtureContainer">

            <div class="firstRound">
                <?php
                $roundNumber = $i;
                $result = $dataObj->getMatches($roundNumber, 'W');
                while ($data = mysqli_fetch_assoc($result)) {
                    $player_id = $data['participant1_id'];
                    $playerDataParticipantFrist = $dataObj->getPlayerDataById($player_id);
                    $player_id = $data['participant2_id'];
                    $playerDataParticipantSecond = $dataObj->getPlayerDataById($player_id);
                    ?>
                    <form action="process_input.php" method="POST" style="background-color: greenyellow;" class="formli">
                        <div>
                            <label>
                                <?= $data['match_data'] ?>.
                            </label>
                            <label>
                                <?= $playerDataParticipantFrist['name']; ?>
                            </label>
                            <span>VS </span>
                            <label>
                                <?= $playerDataParticipantSecond['name']; ?>
                            </label>
                        </div>
                        <div class="select">
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
                        </div>
                        <?php
                        if (empty($winner)) {
                            ?>
                            <input type="submit" name="submit_result" value="Submit Winner">
                            <?php
                        }
                        ?>
                    </form>
                    <hr>
                    <?php
                }
                ?>
            </div>
            <div class="firstRound">
                <?php
                $roundNumber = $i;
                $result = $dataObj->getMatches($roundNumber, 'L');
                while ($data = mysqli_fetch_assoc($result)) {
                    $player_id = $data['participant1_id'];
                    $playerDataParticipantFrist = $dataObj->getPlayerDataById($player_id);
                    $player_id = $data['participant2_id'];
                    $playerDataParticipantSecond = $dataObj->getPlayerDataById($player_id);
                    ?>
                    <form action="process_input.php" method="POST" style="background-color: greenyellow;" class="formli">
                        <div>
                            <label>
                                <?= $data['match_data'] ?>.
                            </label>
                            <label>
                                <?= $playerDataParticipantFrist['name']; ?>
                            </label>
                            <span>VS </span>
                            <label>
                                <?= $playerDataParticipantSecond['name']; ?>
                            </label>
                        </div>
                        <div class="select">
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
                        </div>
                        <?php
                        if (empty($winner)) {
                            ?>
                            <input type="submit" name="submit_result" value="Submit Winner">
                            <?php
                        }
                        ?>
                    </form>
                    <hr>
                    <?php
                }
                ?>
            </div>
        </div>
    <?php }
    ?>

</body>

</html>