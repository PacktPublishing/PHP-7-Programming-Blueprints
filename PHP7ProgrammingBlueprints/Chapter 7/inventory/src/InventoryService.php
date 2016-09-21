<?php
namespace Packt\Chp7\Inventory;

class InventoryService
{
    private $stock = [
        1000 => 125,
        1001 => 12,
        1002 => 1
    ];

    public function checkArticle(int $articleNumber, int $amount = 1): bool
    {
        if (!array_key_exists($articleNumber, $this->stock)) {
            return false;
        }

        return $this->stock[$articleNumber] >= $amount;
    }

    public function takeArticle(int $articleNumber, int $amount = 1): bool
    {
        if (!$this->checkArticle($articleNumber, $amount)) {
            return false;
        }

        $this->stock[$articleNumber] -= $amount;
        return true;
    }
}