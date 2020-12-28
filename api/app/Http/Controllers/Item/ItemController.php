<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Http\Resources\ItemResource;
use App\Repositories\Contracts\IItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    use ApiResponser;

    protected const MAX_RECORD = 1000;
    protected $repository;

    protected $validationRules = [
        'name'  => 'required|string',
        'email' => 'required|email',
    ];

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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|string',
            'email' => 'required|email|unique:items',
        ]);

        $item = $this->repository->create($request->all());

        if (!$item) {
            return $this->errorResponse('Error in creating the item', Response::HTTP_CONFLICT);
        }

        return $this->successResponseWithData(new ItemResource($item), Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            return $this->errorResponse('Item Not Found', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData(new ItemResource($item));
    }
}
