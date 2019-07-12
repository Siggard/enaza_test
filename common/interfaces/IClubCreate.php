<?php
namespace common\interfaces;

interface IClubCreate
{
    /**
     * favorite genres
     * @param IPlaylist $music
     */
    public function musicSetting(IPlaylist $playlist): void;

    /**
     * favorite kind of drinks
     * @param IAssortment $drink
     */
    public function drinkSetting(IAssortment $assortment): void;
}