<?php

namespace App\Http\Controllers\Item;

use App\Events\ItemPubSubEvent;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Http\Resources\ItemResource;
use App\Repositories\Contracts\IItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;

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

    /**
     * Get All Items 
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $per_page = self::MAX_RECORD;
        if ($request->per_page) {
            $per_page = $request->per_page;
        }
        $records = $this->repository->paginate($per_page);
  
        return ItemResource::collection($records);
    }

    /**
     * Create new Item
     *
     * @param Request $request
     * @return void
     */
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
        
        broadcast(new ItemPubSubEvent($item, 'created'))->toOthers();

        return $this->successResponseWithData(new ItemResource($item), Response::HTTP_CREATED);
    }

    /**
     * Get an item information given by its id 
     *
     * @param integer $id
     * @return void
     */
    public function show(int $id)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            return $this->errorResponse('Item Not Found', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData(new ItemResource($item));
    }

    /**
     * Update Item information given by its id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'name'  => 'string',
            'email' => 'email',
        ]);

        $item = $this->repository->find($id);

        if (!$item) {
            return $this->errorResponse('Item Not Found', Response::HTTP_NOT_FOUND);
        }

        try {
            $item = $this->repository->update($id, $request->all());
        } catch (QueryException $exception) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if (!$item) {
            return $this->errorResponse('Error in updating item', Response::HTTP_BAD_REQUEST);
        }

        broadcast(new ItemPubSubEvent($item, 'updated'))->toOthers();

        return $this->successResponseWithData(new ItemResource($item), Response::HTTP_OK);
    }

    /**
     * Delete an item given by its id
     *
     * @param integer $id
     * @return void
     */
    public function destroy(int $id)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            return $this->errorResponse('Item Not Found', Response::HTTP_NOT_FOUND);
        }

        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Error in deleting the item', Response::HTTP_NOT_FOUND);
        }

        broadcast(new ItemPubSubEvent($item, 'deleted'))->toOthers();

        return $this->successResponseWithMessage('Item '.$id. ' deleted', Response::HTTP_ACCEPTED);
    }
}
