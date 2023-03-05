<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        "category_id",
        "name",
    ];

    public static function obtenercontactos(){
        $contacts = Product::all();
        $results = array('' => 'Sin seleccionar');
        foreach ($contacts as $contact){
            $results[$contact->id] = $contact->name;
        }

        return $results;
    }
}
