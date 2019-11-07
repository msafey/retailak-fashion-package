<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerComplains extends Model
{
    protected $fillable=['user_id','message','resolved','answered_admin_id','admin_answer','application_type'];

       public static function getPossibleTypes(){
        $type = DB::select(DB::raw('SHOW COLUMNS FROM customer_complains WHERE Field = "application_type"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $values = array();
        foreach(explode(',', $matches[1]) as $value){
            $values[] = trim($value, "'");
        }
        return $values;
    }

}
