<?php
namespace common\models\data;

use common\interfaces\IAssortment;
use common\models\guests\RussianGuest;

class Assortment implements IAssortment
{
    public function create(): array
    {
        return [
            'B52' => [],
            'VODKA' => [RussianGuest::class],
            'WHISKEY' => []
        ];
    }
}