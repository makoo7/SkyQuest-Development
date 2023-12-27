<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;
use App\Models\ReportPricing;
use Illuminate\Support\Facades\Log;

class UpdateAllReportPricing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $requestedPricing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($requestedPricing)
    {
        $this->requestedPricing = $requestedPricing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $reports = Report::pluck('id')->toArray();
            
            if($reports){                
                for($r=0;$r<count($reports);$r++){
                    if(!empty($reports[$r])){
                        $pricingExist = ReportPricing::where('report_id',$reports[$r])->first();
                        
                        // update
                        if($pricingExist){
                            ReportPricing::where(['license_type' => 'Single', 'file_type' => 'PPT', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['single_ppt_price']]);
                            ReportPricing::where(['license_type' => 'Single', 'file_type' => 'Word', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['single_word_price']]);
                            ReportPricing::where(['license_type' => 'Single', 'file_type' => 'Excel', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['single_excel_price']]);
                            ReportPricing::where(['license_type' => 'Single', 'file_type' => 'PowerBI', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['single_powerBI_price']]);
                            ReportPricing::where(['license_type' => 'Multiple', 'file_type' => 'PPT', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['multiple_ppt_price']]);
                            ReportPricing::where(['license_type' => 'Multiple', 'file_type' => 'Word', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['multiple_word_price']]);
                            ReportPricing::where(['license_type' => 'Multiple', 'file_type' => 'Excel', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['multiple_excel_price']]);
                            ReportPricing::where(['license_type' => 'Multiple', 'file_type' => 'PowerBI', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['multiple_powerBI_price']]);
                            ReportPricing::where(['license_type' => 'Enterprise', 'file_type' => 'PPT', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['enterprise_ppt_price']]);
                            ReportPricing::where(['license_type' => 'Enterprise', 'file_type' => 'Word', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['enterprise_word_price']]);
                            ReportPricing::where(['license_type' => 'Enterprise', 'file_type' => 'Excel', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['enterprise_excel_price']]);
                            ReportPricing::where(['license_type' => 'Enterprise', 'file_type' => 'PowerBI', 'report_id' => $reports[$r]])->update(['price' => $this->requestedPricing['enterprise_powerBI_price']]);

                        }else{
                            // create new pricing entries
                            ReportPricing::create(['license_type' => 'Single', 'file_type' => 'PPT', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['single_ppt_price']]);
                            ReportPricing::create(['license_type' => 'Single', 'file_type' => 'Word', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['single_word_price']]);
                            ReportPricing::create(['license_type' => 'Single', 'file_type' => 'Excel', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['single_excel_price']]);
                            ReportPricing::create(['license_type' => 'Single', 'file_type' => 'PowerBI', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['single_powerBI_price']]);
                            ReportPricing::create(['license_type' => 'Multiple', 'file_type' => 'PPT', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['multiple_ppt_price']]);
                            ReportPricing::create(['license_type' => 'Multiple', 'file_type' => 'Word', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['multiple_word_price']]);
                            ReportPricing::create(['license_type' => 'Multiple', 'file_type' => 'Excel', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['multiple_excel_price']]);
                            ReportPricing::create(['license_type' => 'Multiple', 'file_type' => 'PowerBI', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['multiple_powerBI_price']]);
                            ReportPricing::create(['license_type' => 'Enterprise', 'file_type' => 'PPT', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['enterprise_ppt_price']]);
                            ReportPricing::create(['license_type' => 'Enterprise', 'file_type' => 'Word', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['enterprise_word_price']]);
                            ReportPricing::create(['license_type' => 'Enterprise', 'file_type' => 'Excel', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['enterprise_excel_price']]);
                            ReportPricing::create(['license_type' => 'Enterprise', 'file_type' => 'PowerBI', 'report_id' => $reports[$r], 'price' => $this->requestedPricing['enterprise_powerBI_price']]);
                        }

                        
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error while updating report pricing.'.$e->getMessage());
        }
    }
}
