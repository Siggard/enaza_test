<?php
namespace common\models;

use common\interfaces\IAssortment;

class Drink
{
    private $_kinds = [];

    public function __construct(IAssortment $assortment)
    {
        $this->_kinds = $assortment->create();

        if (empty($this->_kinds)) {
            throw new \yii\base\InvalidConfigException('Kinds is empty');
        }
    }

    public function getRandomKind(): array
    {
        $key = array_rand($this->_kinds, 1);
        return [$key => $this->_kinds[$key]];
    }

    public function getAllKinds(): array
    {
        return $this->_kinds;
    }
}