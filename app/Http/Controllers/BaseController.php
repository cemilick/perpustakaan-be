<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Traits\HasResponseJson;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\RouteDiscovery\Attributes\DoNotDiscover;
use Spatie\RouteDiscovery\Attributes\Route;

class BaseController extends Controller
{
    use HasResponseJson;

    protected BaseModel $model;

    protected $class;

    #[DoNotDiscover]
    public function __construct()
    {
        $this->model = new $this->class;
    }

    public function index()
    {
        $data = $this->model->getAllData();
        $response = new LengthAwarePaginator($data, $data->count(), 10);

        return $this->responseJson($this->transformIndexData($response));
    }

    #[Route('get', '{id}')]
    public function detail($id)
    {
        $data = $this->model->getDataById($id);

        return $this->responseJson($data);
    }

    #[Route('post', '{id}')]
    public function update(Request $request, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($request->all());
        $model->save();

        return $this->responseJson($this->afterUpdateHook($model));
    }

    #[Route('post', '/')]
    public function create(Request $request)
    {
        $payload = $this->beforeCreateHook($request->all());

        if (!$payload) {
            return response()->json([
                'message' => 'Stok buku kosong',
                'status' => 400,
            ], 400);
        }

        $model = $this->model->fill($payload);
        $model->save();

        return $this->responseJson($this->afterCreateHook($model));
    }

    protected function beforeCreateHook($payload)
    {
        return $payload;
    }

    protected function afterCreateHook($model)
    {
        return $model;
    }

    protected function transformIndexData($data)
    {
        return $data;
    }

    protected function afterUpdateHook($model)
    {
        return $model;
    }

}
