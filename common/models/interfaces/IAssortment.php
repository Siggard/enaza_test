<?php
namespace common\models\interfaces;

interface IAssortment
{
    public function create($isKeys = false): array;
}