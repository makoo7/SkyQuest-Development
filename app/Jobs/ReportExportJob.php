<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $reportexport;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reportexport)
    {
        $this->reportexport = $reportexport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Excel::download(new ReportExport($this->reportexport), 'reportexport.xlsx');
    }
}
