<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Traits\HasCrudAction;
use App\Traits\HasResponseJson;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\RouteDiscovery\Attributes\DoNotDiscover;
use Spatie\RouteDiscovery\Attributes\Route;

class BaseController extends Controller
{
    use HasResponseJson, HasCrudAction;

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

        return $this->responseJson($response);
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
        $payload = $this->populatePayload($request->all());

        $model = $this->model->findOrFail($id);
        $model->fill($payload);
        $model->save();

        return $this->responseJson($model);
    }

    #[Route('post', '/')]
    public function create(Request $request)
    {
        $payload = $this->populatePayload($request->all(), true);
        $payload = $this->beforeCreateHook($payload);

        $model = $this->model->fill($payload);
        $model->save();

        return $this->responseJson($model);
    }

    protected function beforeCreateHook($payload)
    {
        return $payload;
    }

}