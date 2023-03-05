<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public static function obtenercontactos(){
        $contacts = DB::table('categorys')->get();
        $results = array('' => 'Sin seleccionar');
        foreach ($contacts as $contact){
            $results[$contact->id] = $contact->name;
        }
        // dd($results);
        return $results;
    }
}
