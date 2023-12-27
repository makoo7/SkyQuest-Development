<?php

namespace App\Exports;

use App\Models\ContactUs;
use App\Models\EmailRestriction;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContactUsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            return[
                'Subject',
                'Message',
                'Legal Category'
            ];
        }else{
            return[
                'Name',
                'Email',
                'Company Name',
                'Phone',
                'Subject',
                'Message',
                'Legal Category'
            ];
        }
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();

        $contacts_us = new ContactUs;
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            $contacts_us = ContactUs::select('subject','message','email')->get();
            
            foreach ($contacts_us as $contact_us) 
            {
                $emailParts = explode('@', $contact_us->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $contact_us->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $contact_us->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $contact_us->legal_category = 'Corporate';
                        }
                    } else {
                        $contact_us->legal_category = 'Corporate';
                    }
                }
                unset($contact_us->email); 
            }
        }else{
            $contacts_us = ContactUs::select('name','email','company_name','phone', 'subject','message')->get();

            foreach ($contacts_us as $contact_us) 
            {
                $emailParts = explode('@', $contact_us->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $contact_us->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $contact_us->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $contact_us->legal_category = 'Corporate';
                        }
                    } else {
                        $contact_us->legal_category = 'Corporate';
                    }
                }
            }
        }
        return $contacts_us;
    }
}
