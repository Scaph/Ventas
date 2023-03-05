<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Sale::all();
        $todos = Sale::join("contacts","contacts.id","=","sales.contact_id")
        ->join("customers","customers.id","=","sales.customer_id")
        ->join("products","products.id","=","sales.product_id")
        ->join("categorys","categorys.id","=","products.category_id")
        ->select("sales.id as id","sales.price","sales.amount","contacts.name as contactname", "contacts.email","categorys.name as categoryname","customers.name as customername","products.name as productname")
        ->get();

        // dd($todos);
        return view('sales.index', ['todos'=> $todos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'product_id' => 'required',
            'customer_id' => 'required',
            'contact_id' => 'required',
            'amount' => 'required|integer',
            'price' => 'required|integer'
        ]);
        // dd($request);
        // $data['user_id'] =auth()->id(); usuario autenticado
        // $data = $request->all();
        //con all() lo convierto en array
        // dd($data);
        Sale::create($data);   
        // $todo = new Sale;
        // $todo -> product_id = $request->product_id;
        // $todo -> customer_id = $request->customer_id;
        // $todo -> contact_id = $request->contact_id;
        // $todo -> amount = $request->amount;
        // $todo -> price = $request->price;
        // $todo ->save();

        return redirect()->route("sales.index")-> with('success','Venta agregada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $sale = Sale::find($id);
        // dd($sale);
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        // dd($request);
        $sale = Sale::find($id);
        $sale->product_id = $request->input('product_id');
        $sale->customer_id = $request->input('customer_id');
        $sale->contact_id = $request->input('contact_id');
        $sale->amount = $request->input('amount');
        $sale->price = $request->input('price');
        // dd($sale);
        $sale->update();
        return redirect()->route("sales.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $sale = Sale::find($id);
        $sale->delete();
        return redirect()->route('sales.index');
    }
}
