<?php

declare(strict_types = 1);

namespace Service\Product;
use Model;

class Product
{
    /**
     * Получаем информацию по конкретному продукту
     *
     * @param int $id
     * @return Model\Entity\Product|null
     */
    public function getInfo(int $id): ?Model\Entity\Product
    {
        $product = $this->getProductRepository()->search([$id]);
        return count($product) ? $product[0] : null;
    }
    /**
     * Получаем все продукты
     *
     * @param string $sortType
     *
     * @return Model\Entity\Product[]
     */
    public function getAll(IStrategy $strategy): array
    {
        $productList = $this->getProductRepository()->fetchAll();

        // Применить паттерн Стратегия
        // $sortType === 'price'; // Сортировка по цене
        // $sortType === 'name'; // Сортировка по имени
        $productList = $strategy->sort($productList);
        return $productList;
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
}

interface IStrategy
{
    public function sort(array $productList);
}

class SortName implements IStrategy
{
    public function sort(array $productList)
    {
        usort($productList, function (\Model\Entity\Product $a, \Model\Entity\Product $b)
        {
            return $a->getName() <=> $b->getName();
        });
        return $productList;
    }
}

class SortPrice implements IStrategy
{
    public function sort(array $productList)
    {
        usort($productList, function (\Model\Entity\Product $a, \Model\Entity\Product $b)
        {
            return $a->getPrice() > $b->getPrice();
        });
        return $productList;
    }
}

class SortDefault implements IStrategy
{
    public function sort(array $productList)
    {
        return $productList;
    }
}