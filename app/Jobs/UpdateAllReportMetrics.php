<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;
use App\Models\ReportMetrics;
use App\Models\ReportFaq;
use Illuminate\Support\Facades\Log;
use DB;

class UpdateAllReportMetrics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $requestedMetrics;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($requestedMetrics)
    {
        $this->requestedMetrics = $requestedMetrics;
    }

    function getFirstPTag($string)
    {
        $start = strpos($string, '<p>');
        $end = strpos($string, '</p>', $start);
        $sub_str = substr($string, $start, $end-$start+4);
        return $sub_str;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $historical_year = $this->requestedMetrics['historical_year'];
            $forecast_year = $this->requestedMetrics['forecast_year'];
            $base_year = $this->requestedMetrics['base_year'];
            $forecast_period = $this->requestedMetrics['forecast_period'];
            
            /*if($historical_year){
                 $historical_year_text = 'Market size value in '.$historical_year;
                 DB::statement(DB::raw("UPDATE report_metrics AS t INNER JOIN (SELECT report_id,min(id) mid FROM report_metrics GROUP BY report_id) t1 
                    ON t.report_id = t1.report_id AND t.id = t1.mid 
                    SET meta_key = '".$historical_year_text."' 
                    WHERE t.meta_key like '%Market size value in%'"));
            }*/
            if($base_year){
                ReportMetrics::where('meta_key', 'Base year')->delete();

                $base_year_text = 'Market size value in '.$base_year;
                DB::statement(DB::raw("UPDATE report_metrics AS t 
                INNER JOIN 
                (SELECT report_id,min(id) mid FROM report_metrics where meta_key like '%Market size value in%' and deleted_at is null GROUP BY report_id) t1 
                ON t.report_id = t1.report_id AND t.id = t1.mid 
                SET t.meta_key='".$base_year_text."' where t.meta_key like '%Market size value in%' and deleted_at is null"));
            }
            if($forecast_year){
                $forecast_year_text = 'Market size value in '.$forecast_year;
                DB::statement(DB::raw("UPDATE report_metrics 
                SET meta_key = '".$forecast_year_text."'
                WHERE id IN 
                (select ID from (SELECT max(id) as id
                FROM report_metrics where meta_key like '%Market size value in%'
                GROUP by report_id 
                ORDER by id DESC) as c)"));
            }
            if($forecast_period){
                ReportMetrics::where('meta_key', 'Forecast period')->update(['meta_value' => $forecast_period]);
            }

            $reports = Report::select(['id','market_insights'])->where('report_type','SD')->get()->toArray();
            
            if($reports){
                for($r=0;$r<count($reports);$r++){
                    if(!empty($reports[$r])){
                        // add or update historical year
                        if($historical_year){                                
                            ReportMetrics::updateOrCreate(['meta_key' => 'Historical Year', 'report_id' => $reports[$r]['id']], ['meta_value' => $historical_year]);
                        }

                        $market_insights = $reports[$r]['market_insights'];
                        if($market_insights){
                            if($historical_year){
                                $pattern1 = "/ in \d{4}/";
                                $replacement1 = " in ".$historical_year;
                                $market_insights = preg_replace($pattern1, $replacement1, $market_insights);
                            }
                            if($forecast_year){
                                $pattern2 = "/ by \d{4}/";
                                $replacement2 = " by ".$forecast_year;
                                $market_insights = preg_replace($pattern2, $replacement2, $market_insights);
                            }
                            if($base_year){
                                $pattern3 = "/ in \d{4} to /";
                                $replacement3 = " in ".$base_year." to ";
                                $market_insights = preg_replace($pattern3, $replacement3, $market_insights);
                            }
                            if($forecast_period){
                                //$pattern4 = "/ forecast period \(\d{4}-\d{4}\)/";
                                $pattern4 = "/ forecast period \((.*)\)/";
                                $replacement4 = " forecast period (".$forecast_period.")";
                                $market_insights = preg_replace($pattern4, $replacement4, $market_insights);
                            }
                            // update
                            Report::where(['id' => $reports[$r]['id']])->update(['market_insights' => $market_insights]);

                            // update answer of first FAQ
                            $faq_answer1 = $this->getFirstPTag($market_insights);
                            if($faq_answer1){                                
                                $first_faq = ReportFaq::where('faq_question', 'like', '%What is the global market size of%')
                                    ->where('report_id', $reports[$r]['id'])->first();
                                if($first_faq){
                                    $first_faq->faq_answer = $faq_answer1;
                                    $first_faq->save();
                                }
                            }
                        }                    
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error while updating report forecast settings:'.$e->getMessage());
        }
    }
}
