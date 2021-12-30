<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TableResource;
use App\Http\Requests\Api\TenantFormRequest;
use App\Services\TableService;
use Illuminate\Http\Request;

class TableApiController extends Controller
{
    protected $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    public function tablesByTenant(TenantFormRequest $request)
    {
        $tables = $this->tableService->getTablesByUuid($request->token_company);

        return TableResource::collection($tables);
    }

    public function show(TenantFormRequest $request, $identify)
    {
        if(!$table = $this->tableService->getTableByIdentify($identify)){
            return response()->json(['message' => 'Table not found'], 404);
        }

        return new TableResource($table);
    }
}
