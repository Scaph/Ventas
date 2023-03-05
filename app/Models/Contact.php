<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "phone_number",
        "age",
        "email",
    ];

    public static function obtenercontactos(){
        $contacts = Contact::all();
        $results = array('' => 'Sin seleccionar');
        foreach ($contacts as $contact){
            $results[$contact->id] = $contact->name;
        }
        // $contacts = DB::table('contacts')
        //         ->select('name', 'age')
        //         ->get();
        // foreach ($contacts as $contact){
        //     $results[$contact->age] = [$contact->name];
        //  }
        // dd($results);
        return $results;
    }
}
