<?php
namespace common\models\interfaces;

use common\models\data\Club;

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

    /**
     * @param Club $club
     * @return mixed
     */
    public function create(Club $club);

    /**
     * @param Club $oldClub
     * @return mixed
     */
    public function loadSingle(Club $oldClub);
}