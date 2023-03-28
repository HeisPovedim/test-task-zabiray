<?php

    // Класс контроллера для обработки запросов
    class CardsController {
        private $apiClient;

        public function __construct($apiClient) {
            $this->apiClient = $apiClient;
        }

        public function getCard($card_id) {
            try {
                $token = $this->apiClient->getToken();
                $card_data = $this->apiClient->getCardData($card_id, $token);
                return end($card_data);
            } catch (Exception $e) {
                die("Ошибка: " . $e->getMessage());
            }
        }
    }
