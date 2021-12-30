<?php

namespace App\Repositories\Contracts;

interface TableRepositoryInterface
{
    public function getTablesByTenantUuid(string $uuid);
    public function getTablesByTenantId(int $id);
    public function getTableByIdentify(string $identify);
}