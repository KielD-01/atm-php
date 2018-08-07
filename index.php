<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ATM</title>
</head>

<body>
<?php
require_once('./vendor/autoload.php');

use ATM\Core\ATM;

$atm = ATM::getATMStatus();

if (isset($_POST['cash']) && $atm->isWorking) {
    $cashAmount = $_POST['cash'];
    $success = ATM::withdrawCash($cashAmount);
}

?>

<style>

    body {
        font-family: "Agency FB";
    }

    div.atm-skeleton {
        display: table;
        margin: 20vh 10vw;
        height: 60vh;
        width: 80vw;
    }

    div.atm-display {
        display: table-cell;
        vertical-align: middle;
        background: rgba(110, 55, 0, 0.08);
        border-radius: 35px;
    }

    div.atm-not-working, div.atm-works {
        font-size: 24px;
        text-align: center;
    }

    input.cash-input, button.withdraw-button {
        width: 10vw;
        padding: 12px;
        border: none;
        border-radius: 10px;
        text-outline: none;
    }

    button.withdraw-button {
        margin: 2vh;
    }

</style>

<div class="atm atm-skeleton">
    <div class="atm atm-display">
        <? if (!$atm->isWorking): ?>
            <div class="atm-not-working">
                <p><?= $atm->enoughCash ? 'This ATM does not work today.' : 'No Cash is in the ATM' ?><br/>Please, find another one</p>
            </div>
        <? else: ?>
            <div class="atm-works">
                <? if (isset($success) and $success): ?>
                    <p class="withdrawn-amount">You have withdrawn <?= $_POST['cash'] ?> UAH</p>
                <? endif; ?>

                <br>

                <p>Input amount to withdraw:</p>

                <form action="/" method="post">
                    <input type="number" min="10" max="<?= ATM::MAX_AMOUNT ?>" class="cash-input" name="cash"
                           title="Cash Amount">

                    <br>

                    <button type="submit" class="withdraw-button">Withdraw</button>
                </form>
            </div>
        <? endif; ?>
    </div>
</div>
</body>
</html>