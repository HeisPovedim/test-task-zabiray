<?php

    $apiClient = new ApiClient("test", "test1234", "https://testapi.zabiray.ru/token", "https://testapi.zabiray.ru/cards");
    $cardsController = new CardsController($apiClient);
    $last_element = $cardsController->getCard("543");

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
                        $reversed_card = array_reverse($card_data);
                        foreach ($reversed_card as $card) {
                    ?>
                        <option value="<?= $card -> number ?>" data-creation="<?= $card -> action_date ?>"><?= $card -> number ?></option>
                    <?php } ?>
                </select>
                <div id="card-validity-period">
                    <!-- Здесь будет выводиться срок действия карты -->
                    Срок действия карты: <?= $last_element['action_date'] ?>
                </div>
                <?php if(date("m/Y") > $last_element['action_date']) { ?>
                    <div id="expiration-warning" style="color: brown; text-align: center">
                        Карта "<?= $last_element['number'] ?>",
                        является более не действительной на <?= date("m/Y") ?>,
                        так как срок ее действия прошел <?= $last_element['action_date'] ?>
                    </div>
                    <div id="btn-continue-card-valid" style="margin-top: 10px">
                        Продолжить, карта действительная
                    </div>
                    <br />
                    <button id="btn-submit" type="submit" disabled>Отправить заявку</button>
                <?php } else { ?>
                    <div id="expiration-warning" style="display: none; color: brown; text-align: center">
                        <!-- Здесь будет выводиться предупреждение об истекшем сроке действия карты -->
                    </div>
                    <div id="btn-continue-card-valid" style="display: none; margin-top: 10px">
                        Продолжить, карта действительная
                    </div>
                    <br />
                    <button id="btn-submit" type="submit">Отправить заявку</button>
                <?php } ?>
            </form>
            <div id="success-message" style="display: none; color: forestgreen">
                Заявка успешно отправлена.
            </div>
        </div>
    </body>
    <script src="includes/script.js"></script>
</html>
