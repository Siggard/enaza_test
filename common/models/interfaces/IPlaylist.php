<?php
namespace common\models\interfaces;

interface IPlaylist
{
    public function create($isKeys = false): array;
}