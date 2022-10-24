<?php

namespace App\Services\Approval;

use App\Models\Approval;

class ApprovalQueryServices
{
    public function findAll(string $approvable_type)
    {
        $query = Approval::query();
        $query->where('approvable_type', $approvable_type);
        $query->orderBy('created_at', 'ASC')->get();
        return $query;
    }
}
