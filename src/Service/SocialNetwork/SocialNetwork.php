<?php

declare(strict_types = 1);

namespace Service\SocialNetwork;

class SocialNetwork
{
    /**
     * Получение класса соц.сети
     *
     * @param string $socialNetwork
     * @param string $courseName
     *
     * @return void
     */
    public function create(string $socialNetwork, string $courseName): void
    {
        // Реализовать паттерн Адаптер с названиями указанными ниже

        switch ($socialNetwork) {
            case ISocialNetwork::SOCIAL_NETWORK_VK:
                //$socialNetworkAdapter = new VKAdapter();
                break;

            case ISocialNetwork::SOCIAL_NETWORK_FACEBOOK:
                //$socialNetworkAdapter = new FacebookAdapter();
                break;

            default:
                //$socialNetworkAdapter = new VKAdapter();
        }

        $this->sendMessage($socialNetworkAdapter, $courseName);
    }

    /**
     * Отправка сообщения в соц.сеть
     *
     * @param ISocialNetwork $socialNetwork
     * @param string $courseName
     *
     * @return void
     */
    protected function sendMessage(ISocialNetwork $socialNetwork, string $courseName): void
    {
        $socialNetwork->send('Интересный ' . $courseName . ' курс');
    }
}
