<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Repositories\Contracts\IItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected const MAX_RECORD = 1000;
    protected $repository;

    public function __construct(IItem $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $per_page = self::MAX_RECORD;
        if ($request->per_page) {
            $per_page = $request->per_page;
        }
        $records = $this->repository->paginate($per_page);
  
        return ItemResource::collection($records);
    }
}
