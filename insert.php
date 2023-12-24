<?php
include 'vendor/autoload.php';

use App\classes\Session;
use App\classes\Fixture;

$fixtureObj = new Fixture;
Session::init();
if (isset($_POST['create_match'])) {
    $roundValue = $_POST['roundNumber'];

    if (empty($roundValue)) {
        $round = 1;
    } else {
        $round = $roundValue;
    }
    $returTxt = $fixtureObj->generateDoubleEliminationFixture($round);
    Session::set('fixture_generated', true);

    // Redirect to the same page after generating the fixture
    $queryString = http_build_query(['data_key' => 'data_value']);

    // Get the current script name dynamically
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Redirect to the same page with the data as query parameters
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
}
if (isset($_POST['winner_create_match'])) {
    $roundValue = $_POST['roundNumber'];

    if (empty($roundValue)) {
        $round = 1;
    } else {
        $round = $roundValue;
    }
    $returTxt = $fixtureObj->generateDoubleEliminationFixtureWinnerBracket($round, 'W');
    Session::set('fixture_generated', true);

    // Redirect to the same page after generating the fixture
    $queryString = http_build_query(['data_key' => 'data_value']);

    // Get the current script name dynamically
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Redirect to the same page with the data as query parameters
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
}
if (isset($_POST['loser_create_match'])) {
    $roundValue = $_POST['roundNumber'];

    if (empty($roundValue)) {
        $round = 1;
    } else {
        $round = $roundValue;
    }
    $returTxt = $fixtureObj->generateDoubleEliminationFixtureLoserBracket($round, 'L');
    Session::set('fixture_generated', true);

    // Redirect to the same page after generating the fixture
    $queryString = http_build_query(['data_key' => 'data_value']);

    // Get the current script name dynamically
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Redirect to the same page with the data as query parameters
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
}
if (isset($_POST['final_create_match'])) {
    $roundValue = $_POST['roundNumber'];

    if (empty($roundValue)) {
        $round = 1;
    } else {
        $round = $roundValue;
    }
    $returTxt = $fixtureObj->generateDoubleEliminationFinalMatch($round,'F');
    Session::set('fixture_generated', true);

    // Redirect to the same page after generating the fixture
    $queryString = http_build_query(['data_key' => 'data_value']);

    // Get the current script name dynamically
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Redirect to the same page with the data as query parameters
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
}