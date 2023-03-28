<?php
// Класс для работы с API
class ApiClient {
    private $username;
    private $password;
    private $auth_url;
    private $cards_url;

    public function __construct($username, $password, $auth_url, $cards_url) {
        $this->username = $username;
        $this->password = $password;
        $this->auth_url = $auth_url;
        $this->cards_url = $cards_url;
    }

    public function getToken() {
        $ch = curl_init($this->auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "username" => $this->username,
            "password" => $this->password,
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            throw new Exception("Ошибка получения токена");
        }

        $response_data = json_decode($response);
        if (!$response_data || !isset($response_data->access_token) || !isset($response_data->token_type)) {
            throw new Exception("Ошибка парсинга токена");
        }

        return $response_data->access_token;
    }

    public function getCardData($card_id, $token) {
        $data = array("id" => $card_id);
        $data_json = json_encode($data);

        $ch = curl_init($this->cards_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: Bearer $token"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        $response = curl_exec($ch);
        curl_close($ch);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            throw new Exception("Ошибка получения карты");
        }

        return json_decode($response, true);
    }
}
