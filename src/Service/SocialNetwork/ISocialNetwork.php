<?php

declare(strict_types = 1);

namespace Service\SocialNetwork;

interface ISocialNetwork
{
    public const SOCIAL_NETWORK_VK = 'Vkontakte';
    public const SOCIAL_NETWORK_FACEBOOK = 'Facebook';

    /**
     * Отправка сообщения в соц.сеть
     *
     * @param string $message
     *
     * @return void
     */
    public function send(string $message): void;
}
