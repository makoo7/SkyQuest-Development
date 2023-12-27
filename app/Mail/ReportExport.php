<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportExport extends Mailable
{
    use Queueable, SerializesModels;
    public $reportExport;
    public $selectedFields = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportExport)
    {
        $this->reportExport = $reportExport;

        $fields = explode(",", $reportExport->fields);

        for($i=0;$i<count($fields);$i++){
            switch($fields[$i]){
                case 'title':
                    $this->selectedFields[] = 'Title';
                    break;
                case 'url':
                    $this->selectedFields[] = 'URL';
                    break;
                case 'product_code':
                    $this->selectedFields[] = 'Product Code';
                    break;
                case 'date':
                    $this->selectedFields[] = 'Date';
                    break;
                case 'length':
                    $this->selectedFields[] = 'Length';
                    break;
                case 'single_price':
                    $this->selectedFields[] = 'Price: Single User';
                    break;
                case 'site_price':
                    $this->selectedFields[] = 'Price: Site License';
                    break;
                case 'enterprise_price':
                    $this->selectedFields[] = 'Price: Enterprise License';
                    break;
                case 'description':
                    $this->selectedFields[] = 'Description (500+ words)';
                    break;
                case 'toc':
                    $this->selectedFields[] = 'Table of Content';
                    break;
                case 'categories':
                    $this->selectedFields[] = 'Categories';
                    break;
                case 'countries_covered':
                    $this->selectedFields[] = 'Countries Covered';
                    break;
                case 'companies_mentioned':
                    $this->selectedFields[] = 'Companies Mentioned';
                    break;
                case 'products_mentioned':
                    $this->selectedFields[] = 'Products Mentioned';
                    break;
                case '2021':
                    $this->selectedFields[] = '2021';
                    break;
                case '2022':
                    $this->selectedFields[] = '2022';
                    break;
                case '2030':
                    $this->selectedFields[] = '2030';
                    break;
                case 'cagr':
                    $this->selectedFields[] = 'CAGR %';
                    break;
                case 'currency':
                    $this->selectedFields[] = 'Currency';
                    break;
                case 'report_type':
                    $this->selectedFields[] = 'Report Type';
                    break;
                case 'sector':
                    $this->selectedFields[] = 'Sector';
                    break;
                case 'region':
                    $this->selectedFields[] = 'Region';
                    break;
                case '1st_2_lines':
                    $this->selectedFields[] = '1st 2 lines';
                    break;
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.reportexport')->subject(config('app.name') . " - You've a new report export request from (".$this->reportExport->admin->user_name.")");
    }
}
