<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Contracts\IItem;
use App\Repositories\Eloquent\BaseRepository;

class ItemRepository extends BaseRepository implements IItem
{
    public function model()
    {
        return Item::class;
    }
}
