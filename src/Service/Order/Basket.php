<?php

declare(strict_types = 1);

namespace Service\Order;

use Model;
use Service\Billing\Card;
use Service\Billing\IBilling;
use Service\Communication\Email;
use Service\Communication\ICommunication;
use Service\User\ISecurity;
use Service\User\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Basket
{
/**
* Сессионный ключ списка всех продуктов корзины
*/
    private const BASKET_DATA_KEY = 'basket';

/**
* @var SessionInterface
     */
    private $session;
    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Добавляем товар в заказ
     *
     * @param int $product
     *
     * @return void
     */
    public function addProduct(int $product): void
    {
        $basket = $this->session->get(static::BASKET_DATA_KEY, []);
        if (!in_array($product, $basket, true)) {
            $basket[] = $product;
            $this->session->set(static::BASKET_DATA_KEY, $basket);
        }
    }

    /**
     * Проверяем, лежит ли продукт в корзине или нет
     *
     * @param int $productId
     *
     * @return bool
     */
    public function isProductInBasket(int $productId): bool
    {
        return in_array($productId, $this->getProductIds(), true);
    }

    /**
     * Получаем информацию по всем продуктам в корзине
     *
     * @return Model\Entity\Product[]
     */
    public function getProductsInfo(): array
    {
        $productIds = $this->getProductIds();
        return $this->getProductRepository()->search($productIds);
    }
    /**
     * Фабричный метод для репозитория Product
     *
     * @return Model\Repository\Product
     */
    protected function getProductRepository(): Model\Repository\Product
    {
        return new Model\Repository\Product();
    }

    /**
     * Получаем список id товаров корзины
     *
     * @return array
     */
    private function getProductIds(): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }
}

class FacadeF
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * Оформление заказа
     *
     * @return void
     * @throws \Service\Billing\Exception\BillingException
     * @throws \Service\Communication\Exception\CommunicationException
     */
    public function checkout(): void
    {
        $basket = new BasketBuilder();
        $security = new Security($this->session);
        $basket->setSecurity($security)
            ->setCommunication(new Email())
            ->setBilling(new Card());
        $checkout = $basket->build();
        $checkout->checkoutProcess($this->getBasket()->getProductsInfo());
    }
    /**
     * @return Basket
     */
    protected function getBasket(): Basket
    {
        return new Basket($this->session);
    }
}

class Checkout
{
    /**
     * @var IBilling
     */
    private $billing;
    /**
     * @var ISecurity
     */
    private $security;
    /**
     * @var ICommunication
     */
    private $communication;
    public function __construct(BasketBuilder $builder)
    {
        $this->billing = $builder->getBilling();
        $this->communication = $builder->getCommunication();
        $this->security = $builder->getSecurity();
    }
    /**
     * Проведение всех этапов заказа
     *
     * @param Model\Entity\Product[]
     * @return void
     * @throws \Service\Billing\Exception\BillingException
     * @throws \Service\Communication\Exception\CommunicationException
     */
    public function checkoutProcess(array $productList): void
    {
        $totalPrice = 0;
        foreach ($productList as $product) {
            $totalPrice += $product->getPrice();
        }
        $this->billing->pay($totalPrice);
        $user = $this->security->getUser();
        $this->communication->process($user, 'checkout_template');
    }
}

class BasketBuilder
{
    /**
     * @var IBilling
     */
    private $billing;
    /**
     * @var ISecurity
     */
    private $security;
    /**
     * @var ICommunication
     */
    private $communication;
    /**
     * @return IBilling
     */
    public function getBilling(): IBilling
    {
        return $this->billing;
    }
    /**
     * @param IBilling $billing
     * @return BasketBuilder
     */
    public function setBilling(IBilling $billing): BasketBuilder
    {
        $this->billing = $billing;
        return $this;
    }
    /**
     * @return ISecurity
     */
    public function getSecurity(): ISecurity
    {
        return $this->security;
    }
    /**
     * @param ISecurity $security
     * @return BasketBuilder
     */
    public function setSecurity(ISecurity $security): BasketBuilder
    {
        $this->security = $security;
        return $this;
    }
    /**
     * @return ICommunication
     */
    public function getCommunication(): ICommunication
    {
        return $this->communication;
    }
    /**
     * @param ICommunication $communication
     * @return BasketBuilder
     */
    public function setCommunication(ICommunication $communication): BasketBuilder
    {
        $this->communication = $communication;
        return $this;
    }
    public function build(): Checkout
    {
        return new Checkout($this);
    }
}