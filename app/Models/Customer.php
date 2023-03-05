<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
    ];

    public static function obtenercontactos(){
        $contacts = Customer::all();
        $results = array('' => 'Sin seleccionar');
        foreach ($contacts as $contact){
            $results[$contact->id] = $contact->name;
        }

        return $results;
    }
}
