<?php

namespace App\Services;

use App\Models\Report;
use App\Jobs\GenerateReportJob;

class ReportService
{
    public function createReport(array $data): Report
    {
        $report = Report::create([
            'tenant_id' => $data['tenant_id'],
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'format' => $data['format'],
            'filters' => $data['filters'] ?? [],
            'status' => 'pending',
        ]);
        
        GenerateReportJob::dispatch($report);
        
        return $report;
    }

    public function getReportsForUser(int $userId, int $tenantId): \Illuminate\Database\Eloquent\Collection
    {
        return Report::where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getPendingReports(): \Illuminate\Database\Eloquent\Collection
    {
        return Report::pending()
            ->where('status', '!=', 'processing')
            ->get();
    }
}
