<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $table = 'exchanges';
    protected $fillable = ['invoice_id', 'invoice_detail_id','date_admission','gie_id','gie_user','office','market','pdv_description','pdv_id','dni','type_commerce','location','invoice_number','campaigne_id','campaigne','brand','category','price','product_id','product','sell_out','quantity','amount','scale','award_id','award','document_id','document_type','client_id','client_type','client','exchange_master_id'];
}
