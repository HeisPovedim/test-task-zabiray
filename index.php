<?php
    try {
        // Параметры для получения токена
        $username = "test";
        $password = "test1234";
        $auth_url = "https://testapi.zabiray.ru/token";

        // Получение токена
        $ch = curl_init($auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "username" => $username,
            "password" => $password,
        ]));
        $response = curl_exec($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            die("Ошибка получения токена");
        }

        // Парсим ответ и получаем токен авторизации
        $response_data = json_decode($response);
        if (!$response_data || !isset($response_data->access_token) || !isset($response_data->token_type)) {
            die("Ошибка парсинга токена");
        }

        $token = $response_data->access_token;
        $token_type = $response_data->token_type;

        // Параметры для получения карты
        $card_id = 543;
        $cards_url = "https://testapi.zabiray.ru/cards";

        // Получения карты
        $data = array("id" => $card_id);
        $data_json = json_encode($data);

        $ch = curl_init($cards_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: $token_type $token"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        $response = curl_exec($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            die("Ошибка получения карты");
        }

        curl_close($ch);

        // парсим ответ и получаем данные о карте
        $card_data = json_decode($response);

        // получаем данные последней карты в списке
        $response_array = json_decode($response, true);
        $last_element = end($response_array);


    } catch (Exception $e) {
        die("Ошибка: " . $e->getMessage());
    }
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
