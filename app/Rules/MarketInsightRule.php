<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MarketInsightRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function string_between_two_string($str, $starting_word, $ending_word)
    {
        $subtring_start = strpos($str, $starting_word);
        $subtring_start += strlen($starting_word); 
        $size = strpos($str, $ending_word, $subtring_start) - $subtring_start; 
        return substr($str, $subtring_start, $size); 
    }
    
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start = strpos($value, '<p>');
        $end = strpos($value, '</p>', $start);
        $market_insights_para = substr($value, $start, $end-$start+4);

        $metrics['startyear']='';
        $metrics['startyear_size']='';
        $metrics['base_year']='';
        $metrics['baseyear_size']='';
        $metrics['endyear']='';
        $metrics['endyear_size']='';
        $metrics['growth_rate']='';
        $metrics['forecast_period']='';
        $metrics['forecast_unit']='';

        $contentArr = explode(" ",$market_insights_para);
        if(is_array($contentArr) && count($contentArr) >0) {            
            if(str_contains($market_insights_para, "valued") && str_contains($market_insights_para, "in")) {
                $syear_content = array();
                $sreg_exyear = "/in [0-9]{4}(,?)+ and/";
                if(preg_match_all($sreg_exyear, $market_insights_para, $syear_content)){
                    if($syear_content){
                        $metrics['startyear'] = trim(str_replace(",","",trim(trim($syear_content[0][0], 'in'), 'and')));
                    }
                }
            }

            if(strpos($market_insights_para, "CAGR")) {                                               
                $growth_rate = trim($this->string_between_two_string($market_insights_para, 'CAGR of', '%'));
                $metrics['growth_rate'] = ($growth_rate!='') ? $growth_rate."%" : '';
            }

            if(strpos($market_insights_para, "period")) {
                $forecast_period = rtrim(ltrim(strip_tags(trim($this->string_between_two_string($market_insights_para, 'period', '.'))),"("),")");
                $metrics['forecast_period'] = ($forecast_period!='') ? $forecast_period : '';
            }

            if(str_contains($market_insights_para, "in") && str_contains($market_insights_para, "to")) {
                $year_content = array();
                $reg_exyear = "/in [0-9]{4} to/";
                if(preg_match_all($reg_exyear, $market_insights_para, $year_content)){
                    if($year_content){
                        $metrics['base_year'] = trim(trim(trim($year_content[0][0], 'in'), 'to'));
                    }
                }                        
                if(!is_numeric($metrics['base_year'])) {
                    $metrics['base_year'] = '';
                }
            }

            if(str_contains($market_insights_para, "from") && str_contains($market_insights_para, "in")) {                        
                $result_array = explode('from', $market_insights_para);
                if (isset($result_array[1])){
                    $result_array1 = explode('in', $result_array[1]);
                    if (isset($result_array1)){
                        $metrics['baseyear_size'] = trim($result_array1[0]);
                    }
                }
            }
        }

        if(strpos($market_insights_para, "by")) {
            $endyear_pos = strpos($market_insights_para,"by");
            $endyear  = substr($market_insights_para, $endyear_pos+3, 4);
            $metrics['endyear'] = ($endyear!='') ? $endyear : '';
        }

        /* if(strpos($market_insights_para, "valued")) {
            $startyear_size = trim($this->string_between_two_string($market_insights_para, 'valued', 'in'));
            $startyear_arr = explode(" ",$startyear_size);                        
            $startyear_key = array_search("USD",$startyear_arr);
            $startyear_size = '';
            if($startyear_key){
                if(isset($startyear_arr[$startyear_key]))
                    $startyear_size = $startyear_arr[$startyear_key];
                if(isset($startyear_arr[$startyear_key+1]))
                    $startyear_size .= ' '.$startyear_arr[$startyear_key+1];
                if(isset($startyear_arr[$startyear_key+2]))
                    $startyear_size .= ' '.$startyear_arr[$startyear_key+2];
            }
            $metrics['startyear_size'] = $startyear_size;
        } */

        if((strpos($market_insights_para, "USD")!='') && (strpos($market_insights_para, "by")!='')) {
            $end_year_size = $this->string_between_two_string($market_insights_para, 'USD', 'by');                            
            $end_year_arr = explode(" ",$end_year_size);
            $end_year_key = array_search("USD",$end_year_arr);
            /* $endyear_size = '';
            if(isset($end_year_arr[$end_year_key+1]) && (isset($end_year_arr[$end_year_key+2]))){
                $endyear_size = $end_year_arr[$end_year_key].' '.$end_year_arr[$end_year_key+1].' '.$end_year_arr[$end_year_key+2];
            }
            $metrics['endyear_size'] = $endyear_size; */
            $year_content = array();
            $reg_exyear3 = "/to USD (.*) by/";
            if(preg_match_all($reg_exyear3, $market_insights_para, $year_content)){
                if($year_content){
                    $metrics['endyear_size'] = trim(trim(trim($year_content[0][0], 'to'), 'by'));
                }
            }
            $forecast_unit = '';
            if(isset($end_year_arr[$end_year_key+2])){
                $forecast_unit = $end_year_arr[$end_year_key].' '.ucfirst($end_year_arr[$end_year_key+2]);
            }
            $metrics['forecast_unit'] = $forecast_unit;
        }
        //dd($metrics); $metrics['startyear_size']=='' || 
        if($metrics['startyear']=='' || $metrics['base_year']=='' || $metrics['baseyear_size']== '' || $metrics['endyear']=='' || $metrics['endyear_size']=='' || $metrics['growth_rate']=='' || $metrics['forecast_period']=='' || $metrics['forecast_unit']==''){
            return false;
        }else{
            return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Can not fetch the metrics data';
    }
}
