<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\ExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function handle(ExportService $exportService): void
    {
        $this->report->markAsProcessing();

        try {
            $filters = $this->report->filters ?? [];

            $filename = match($this->report->type) {
                'orders' => $exportService->exportOrdersToExcel($filters),
                'products' => $exportService->exportProductsToExcel($filters),
                'customers' => $exportService->exportCustomersToExcel($filters),
                'analytics' => $exportService->exportAnalyticsReport($filters),
                default => throw new \Exception('Invalid report type'),
            };

            $this->report->markAsCompleted($filename);
        } catch (\Exception $e) {
            $this->report->markAsFailed();
            throw $e;
        }
    }
}
