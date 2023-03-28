<?php

    // Класс контроллера для обработки запросов
    class CardsController {
        private object $apiClient;

        public function __construct($apiClient) {
            $this->apiClient = $apiClient;
        }

        public function getCard($card_id) {
            try {
                $token = $this->apiClient->getToken();
                $card_data = $this->apiClient->getCardData($card_id, $token);
                return array_reverse($card_data);
            } catch (Exception $e) {
                die("Ошибка: " . $e->getMessage());
            }
        }
    }
