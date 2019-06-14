<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InconsistencyDetail extends Model
{

    protected $table = 'inconsistency_details';

    public function scopeOfficeId($query,$office_id)
    {
        if ($office_id<>"0")
        {
            $office_ids = explode(',',$office_id);
            if (count($office_ids)>1)
            {
                $query->whereIn('inconsistency_details.office',explode(',' ,$office_id));
            }else{
                $query->where('inconsistency_details.office',$office_id);
            }
        }

    }
}
