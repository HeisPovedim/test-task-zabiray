<?php

    require_once('API/CardsController.php');
    require_once('API/ApiClient.php');

    $apiClient = new ApiClient("test", "test1234", "https://testapi.zabiray.ru/token", "https://testapi.zabiray.ru/cards");
    $cardsController = new CardsController($apiClient);
    $card_data = $cardsController->getCard("543");

    $isConditionMet = (date("m/Y") > $card_data[0]['action_date']) ? true : false;

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Форма заявки</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/css/main.css">
    </head>
    <body>
        <div class="form">
            <h1>Форма заявки</h1>
            <form id="application-form" onsubmit="submitApplication(event)">
                <select id="card-select" name="card" class="select-box">
                    <!-- Добавляем варианты выбора на основе данных API -->
                    <?php
                        foreach ($card_data as $card) {
                    ?>
                        <option value="<?= $card['number'] ?>" data-creation="<?= $card['action_date'] ?>"><?= $card['number'] ?></option>
                    <?php } ?>
                </select>
                <div id="card-validity-period">
                    <!-- Здесь будет выводиться срок действия карты -->
                    Срок действия карты: <?= $card_data[0]['action_date'] ?>
                </div>
                    <div id="expiration-warning" style="display: <?= $isConditionMet ? "" : "none" ?>; color: brown; text-align: center">
                        Карта "<?= $card_data[0]['number'] ?>",
                        является более не действительной на <?= date("m/Y") ?>,
                        так как срок ее действия прошел <?= $card_data[0]['action_date'] ?>
                    </div>
                    <div id="btn-continue-card-valid" style="display: <?= $isConditionMet ? "" : "none" ?>; margin-top: 10px">
                        Продолжить, карта действительная
                    </div>
                    <br />
                    <button id="btn-submit" type="submit" <?= $isConditionMet ? "disabled" : "" ?>>Отправить заявку</button>
                    <input id="form-valid" style="display: none" value="0">
            </form>
            <div id="success-message" style="display: none; color: forestgreen">
                Заявка успешно отправлена.
            </div>
        </div>
    </body>
    <script src="includes/script.js"></script>
</html>
