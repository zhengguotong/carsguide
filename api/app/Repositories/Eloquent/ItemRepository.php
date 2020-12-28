<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Contracts\IItem;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Str;

class ItemRepository extends BaseRepository implements IItem
{
    public function model()
    {
        return Item::class;
    }

    public function create(array $data)
    {
        $data['guid'] = (string) Str::uuid();
       
        return parent::create($data);
    }
}
