        public function getdetails(Request $request){
        $inputs = $request->all();
        $datos = explode('**', $inputs['data']);
        list( $resultado , $resultadotwo, $BanderaProveedor ) = $this->repository->getdatosscore( $datos[0] , $datos[1]  );
        $BanderaProveedor == 1 ? $html = view('ScoreCard.forms.TablaEnviosXProveedor',compact('resultado'))->render()  : $html  = view('ScoreCard.forms.table',compact('resultado','resultadotwo'))->render(); 
        return response()->json($html);
    }
    
    public function getdatosscore( $provedor_id , $mes ){
        //sacamos la bandera para obtener la evaluacion dependiendo de la bandera del proveedor
     $Proveedor =  Proveedor::where('id', $provedor_id)->where('activo', 1)->first();
     if($Proveedor != null){
         if($Proveedor->EsPackingSlip == 1){
            //busqueda de envios x proveedor
            $result = $this->GetDatosBaseScorePackingSlip(array("ProveedorId" =>$provedor_id, "Mes" =>$mes));
            if($result->get() != null){
                //return  array($result->get(), []);
                $resultado = $result->get();
                $resultadotwo = [];
            }
         }else{
            //busqueda por  PO
            $tosql = F4311_::SELECT([
                DB::raw('identificadorkey'),
                DB::raw('OrderInvoice'),
                DB::raw('LineNumber'),
                DB::raw('F4311.2ndItemNumber as numparte'),
                DB::raw('proveedor_id'),
                DB::raw('DateOrderTransactionF'),
                DB::raw('DateRequestedF'),
                DB::raw('DATE_ADD(DATE_ADD(CONCAT(LEFT(DateRequestedF, 7), \'-01\'), INTERVAL 1 MONTH), INTERVAL 14 DAY) AS fecha_prorroga'),
                DB::raw('DateUpdatedf'),
                DB::raw('numparte_id'),
                DB::raw('F4311.BussinessUnit'),
                DB::raw('F4311.LineType'),
                DB::raw('StatusCodeNext'),
                DB::raw('StatusCodeLast'),
                DB::raw('(UnitsOrder / 10000 ) as  UnitsOrder'),
                DB::raw('(UnitsOpen / 10000 ) as  UnitsOpen'),
                DB::raw('(UnitsReceived / 10000 ) as  UnitsReceived'),
                DB::raw('updated_at'),
                DB::raw('DATEDIFF(DateUpdatedf, DateRequestedF) AS days'),
                DB::raw('IF( coalesce( ((UnitsReceived * 100) / UnitsOrder),0) > 100 , 100 , coalesce( ((UnitsReceived * 100) / UnitsOrder),0) ) AS porcentage')
            ])->where('Proveedor_id',DB::raw($provedor_id))
            ->where(DB::raw('trim(F4311.BussinessUnit)'),DB::raw(301)) 
            ->where(DB::raw('LEFT(DateRequestedF,7)'),DB::raw("'".$mes."'"))
            ->whereIn('StatusCodeLast',[DB::raw(400),DB::raw(999) , DB::raw(280) ])
            ->whereIn('F4311.LineType', [DB::raw("'X'"), DB::raw("'S'"), DB::raw("'x'"), DB::raw("'s'")])
            ->orderby('DateRequestedF','asc');
    
            $tosqlopenmenos = F4311_::SELECT([
                DB::raw('identificadorkey'),
                DB::raw('OrderInvoice'),
                DB::raw('LineNumber'),
                DB::raw('F4311.2ndItemNumber as numparte'),
                DB::raw('proveedor_id'),
                DB::raw('DateOrderTransactionF'),
                DB::raw('DateRequestedF'),
                DB::raw('DATE_ADD(DATE_ADD(CONCAT(LEFT(DateRequestedF, 7), \'-01\'), INTERVAL 1 MONTH), INTERVAL 14 DAY) AS fecha_prorroga'),
                DB::raw('DateUpdatedf'),
                DB::raw('numparte_id'),
                DB::raw('F4311.BussinessUnit'),
                DB::raw('F4311.LineType'),
                DB::raw('StatusCodeNext'),
                DB::raw('StatusCodeLast'),
                DB::raw('(UnitsOrder / 10000 ) as  UnitsOrder'),
                DB::raw('(UnitsOpen / 10000 ) as  UnitsOpen'),
                DB::raw('(UnitsReceived / 10000 ) as  UnitsReceived'),
                DB::raw('updated_at'),
                DB::raw('DATEDIFF(DateUpdatedf, DateRequestedF) AS days'),
                DB::raw('IF( coalesce( ((UnitsReceived * 100) / UnitsOrder),0) > 100 , 100 , coalesce( ((UnitsReceived * 100) / UnitsOrder),0) ) AS porcentage')
            ])->where('Proveedor_id',DB::raw($provedor_id))
            ->where(DB::raw('trim(F4311.BussinessUnit)'),DB::raw(301)) 
            ->where(DB::raw('LEFT(DateRequestedF,4)'),'>=',DB::raw("'2021'"))
            ->where(DB::raw('LEFT(DateRequestedF,7)'),'<',DB::raw("'".$mes."'"))
            //->where(DB::raw('LEFT(DateUpdatedf,7)'),'<',DB::raw("'".$mes."'"))
            ->whereIn('StatusCodeLast',[DB::raw(400),DB::raw(999) , DB::raw(280) ])
            ->whereIn('F4311.LineType', [DB::raw("'X'"), DB::raw("'S'"), DB::raw("'x'"), DB::raw("'s'")])
            ->where('UnitsOpen','>',DB::raw(0))
            ->orderby('DateRequestedF','asc');
    
    
            DB::unprepared('DROP TEMPORARY TABLE IF EXISTS TEMP_RESULTADO_SQL;');
            DB::unprepared("CREATE TEMPORARY TABLE IF NOT EXISTS TEMP_RESULTADO_SQL AS ( {$tosql->toSql()} );");
            DB::unprepared("INSERT INTO TEMP_RESULTADO_SQL ( {$tosqlopenmenos->toSql()} );");
    
            $tosqlopencerradas = F4311_::leftjoin(DB::raw('TEMP_RESULTADO_SQL as t'),function($join){
                $join->on('t.identificadorkey','=','F4311.identificadorkey');
            })
            ->SELECT([
                DB::raw('F4311.identificadorkey'),
                DB::raw('F4311.OrderInvoice'),
                DB::raw('F4311.LineNumber'),
                DB::raw('F4311.2ndItemNumber as numparte'),
                DB::raw('F4311.proveedor_id'),
                DB::raw('F4311.DateOrderTransactionF'),
                DB::raw('F4311.DateRequestedF'),
                DB::raw('DATE_ADD(DATE_ADD(CONCAT(LEFT(F4311.DateRequestedF, 7), \'-01\'), INTERVAL 1 MONTH), INTERVAL 14 DAY) AS fecha_prorroga'),
                DB::raw('F4311.DateUpdatedf'),
                DB::raw('F4311.numparte_id'),
                DB::raw('F4311.BussinessUnit'),
                DB::raw('F4311.LineType'),
                DB::raw('F4311.StatusCodeNext'),
                DB::raw('F4311.StatusCodeLast'),
                DB::raw('(F4311.UnitsOrder / 10000 ) as  UnitsOrder'),
                DB::raw('(F4311.UnitsOpen / 10000 ) as  UnitsOpen'),
                DB::raw('(F4311.UnitsReceived / 10000 ) as  UnitsReceived'),
                DB::raw('F4311.updated_at'),
                DB::raw('DATEDIFF(F4311.DateUpdatedf, F4311.DateRequestedF) AS days'),
                DB::raw('IF( coalesce( ((F4311.UnitsReceived * 100) / F4311.UnitsOrder),0) > 100 , 100 , coalesce( ((F4311.UnitsReceived * 100) / F4311.UnitsOrder),0) ) AS porcentage')
            ])->where('F4311.Proveedor_id',DB::raw($provedor_id))
            ->where(DB::raw('trim(F4311.BussinessUnit)'),DB::raw(301)) 
            ->whereNull('t.identificadorkey')
            ->where(DB::raw('LEFT(F4311.DateRequestedF,4)'),'>=',DB::raw("'2021'"))
            ->where(DB::raw('LEFT(F4311.DateRequestedF,7)'),'<',DB::raw("'".$mes."'"))
            ->where(DB::raw('LEFT(F4311.DateUpdatedf,7)'),'>=',DB::raw("'".$mes."'"))
            ->whereIn('F4311.StatusCodeLast',[DB::raw(400),DB::raw(999) , DB::raw(280) ])
            ->whereIn('F4311.LineType', [DB::raw("'X'"), DB::raw("'S'"), DB::raw("'x'"), DB::raw("'s'")])
            ->havingraw('DateUpdatedf > fecha_prorroga')
            ->orderby('F4311.DateRequestedF','asc');
            // dd( $tosqlopencerradas->toSql() );
            DB::unprepared('DROP TEMPORARY TABLE IF EXISTS TEMP_RESULTADO_SQL_TWO;');
            DB::unprepared("CREATE TEMPORARY TABLE IF NOT EXISTS TEMP_RESULTADO_SQL_TWO AS ( {$tosqlopencerradas->toSql()} );");
            DB::unprepared("INSERT INTO TEMP_RESULTADO_SQL (  SELECT * FROM TEMP_RESULTADO_SQL_TWO );");
            //dd( DB::table('TEMP_RESULTADO_SQL_TWO')->get() );
    
            $resultado = DB::table(DB::raw("TEMP_RESULTADO_SQL as temp"))
                    ->SELECT([
                        DB::raw('temp.OrderInvoice'),
                        DB::raw('temp.LineNumber'),
                        DB::raw('temp.numparte'),
                        DB::raw('temp.proveedor_id'),
                        DB::raw('temp.DateOrderTransactionF'),
                        DB::raw('temp.DateRequestedF'),
                        DB::raw('LEFT(temp.DateRequestedF, 4) as years'),
                        DB::raw('LEFT(temp.DateRequestedF, 7) as mes'),
                        DB::raw('temp.fecha_prorroga'),
                        DB::raw('temp.DateUpdatedf'),
                        DB::raw('temp.numparte_id'),
                        DB::raw('temp.numparte_id'),
                        DB::raw('temp.BussinessUnit'),
                        DB::raw('temp.LineType'),
                        DB::raw('temp.StatusCodeNext'),
                        DB::raw('temp.StatusCodeLast'),
                        DB::raw('temp.UnitsOrder'),
                        DB::raw('temp.UnitsOpen'),
                        DB::raw('temp.UnitsReceived'),
                        DB::raw('temp.updated_at'),
                        DB::raw('temp.days'),
                        DB::raw('if( DateUpdatedf <= fecha_prorroga  , round(temp.porcentage,2) , 0 ) as porcentage')
                    ])->get();
            // dd($resultado);
            $tosqltwo = F4311_::SELECT([
                DB::raw('OrderInvoice'),
                DB::raw('LineNumber'),
                DB::raw('F4311.2ndItemNumber as numparte'),
                DB::raw('proveedor_id'),
                DB::raw('DateOrderTransactionF'),
                DB::raw('DateRequestedF'),
                DB::raw('DATE_ADD(DATE_ADD(CONCAT(LEFT(DateRequestedF, 7), \'-01\'), INTERVAL 1 MONTH), INTERVAL 14 DAY) AS fecha_prorroga'),
                DB::raw('DateUpdatedf'),
                DB::raw('numparte_id'),
                DB::raw('F4311.BussinessUnit'),
                DB::raw('F4311.LineType'),
                DB::raw('StatusCodeNext'),
                DB::raw('StatusCodeLast'),
                DB::raw('(UnitsOrder / 10000 ) as  UnitsOrder'),
                DB::raw('(UnitsOpen / 10000 ) as  UnitsOpen'),
                DB::raw('(UnitsReceived / 10000 ) as  UnitsReceived'),
                DB::raw('updated_at'),
                DB::raw('DATEDIFF(DateUpdatedf, DateRequestedF) AS days'),
                DB::raw('IF( coalesce( ((UnitsReceived * 100) / UnitsOrder),0) > 100 , 100 , coalesce( ((UnitsReceived * 100) / UnitsOrder),0) ) AS porcentage')
            ])->where('Proveedor_id',DB::raw($provedor_id))
            //->where(DB::raw('trim(F4311.BussinessUnit)'),DB::raw(301)) 
            ->where(DB::raw('LEFT(DateRequestedF,7)'),DB::raw("'".$mes."'"))
            ->whereNotIn('StatusCodeLast',[DB::raw(400),DB::raw(999), DB::raw(280)])
            //->whereIn('F4311.LineType', [DB::raw("'X'"), DB::raw("'S'"), DB::raw("'x'"), DB::raw("'s'")])
            ->orderby('DateRequestedF','asc');
    
            $resultadotwo = DB::table(DB::raw("({$tosqltwo->toSql()}) as temp"))
                    ->SELECT([
                        DB::raw('temp.OrderInvoice'),
                        DB::raw('temp.LineNumber'),
                        DB::raw('temp.numparte'),
                        DB::raw('temp.proveedor_id'),
                        DB::raw('temp.DateOrderTransactionF'),
                        DB::raw('temp.DateRequestedF'),
                        DB::raw('LEFT(temp.DateRequestedF, 4) as years'),
                        DB::raw('LEFT(temp.DateRequestedF, 7) as mes'),
                        DB::raw('temp.fecha_prorroga'),
                        DB::raw('temp.DateUpdatedf'),
                        DB::raw('temp.numparte_id'),
                        DB::raw('temp.BussinessUnit'),
                        DB::raw('temp.LineType'),
                        DB::raw('temp.StatusCodeNext'),
                        DB::raw('temp.StatusCodeLast'),
                        DB::raw('temp.UnitsOrder'),
                        DB::raw('temp.UnitsOpen'),
                        DB::raw('temp.UnitsReceived'),
                        DB::raw('temp.updated_at'),
                        DB::raw('temp.days'),
                        DB::raw('if( DateUpdatedf <= fecha_prorroga  , round(temp.porcentage,2) , 0 ) as porcentage')
                    ])->get();
         }
     }
      

        return array( $resultado , $resultadotwo, $Proveedor->EsPackingSlip );
    }

    public function GetDatosBaseScorePackingSlip($DataFiltros){
       // dd($DataFiltros["ProveedorId"], $DataFiltros["Mes"]);
        $receipts = ShipmentReceiptDetail::select(['shipment_order_id', DB::raw('max(sr.updated_at) as created_at'), DB::raw('sum(shipment_receipt_details.quantity) as quantity')])
        ->join('shipment_receipts as sr', 'sr.id', '=', 'shipment_receipt_details.shipment_receipt_id')
        ->whereNull('sr.deleted_at')
        ->groupBy('shipment_receipt_details.shipment_order_id');
    $accords = ShipmentAccord::select(['shipment_order_id', DB::raw('sum(quantity) as quantity, count(id) as total'), DB::raw('max(created_at) as created_at')])->groupBy('shipment_order_id');
    $now = Carbon::now()->format('Y-m-d H:i:s');
    $ShipRows = ShipmentOrder::join('shipments as s', 's.id', 'shipment_orders.shipment_id')
        ->join('shipment_types as st', 'st.id', 's.shipment_type_id')
        ->join('shipment_destinations as sd', 'sd.id', 'shipment_orders.shipment_destination_id')
        ->join('numpartes as np', 'np.id', 'shipment_orders.numparte_id')
        ->leftJoin('clientes as cl', 'cl.id', 'sd.cliente_id')
        ->leftJoin('proveedors as pr', 'pr.id', 'sd.proveedor_id')
        ->leftJoin(DB::raw('(' . $receipts->toSql() . ') as receipts'), 'receipts.shipment_order_id', 'shipment_orders.id')->mergeBindings($receipts->getQuery())
        ->leftJoin(DB::raw('(' . $accords->toSql() . ') as accords'), 'accords.shipment_order_id', 'shipment_orders.id')->mergeBindings($accords->getQuery())
        ->where('s.estado_id', DB::raw('131'))
        ->whereNull('s.deleted_at')
        ->whereNull('sd.deleted_at')
        ->where('shipment_orders.quantity_shipped', '>', DB::raw('0'))
        ->with('shipment')
        ->select([
            'shipment_orders.id as id',
            'shipment_orders.shipment_id',
            's.folio_mat as OrderInvoice',
            's.shipment_type_id',
            DB::raw('concat(ifnull(cl.alias, ""), ifnull(pr.nombre, "")) as destino'),
            DB::raw('date(s.updated_at) as created_at_date'),
            DB::raw('time(s.updated_at) as created_at_time'),
            'shipment_orders.po',
            'shipment_orders.wo',
            'np.numero_parte as numparte',
            'shipment_orders.secuence',
            'shipment_orders.quantity_shipped as UnitsOrder',
            'shipment_orders.quantity_received as UnitsReceived',
            'shipment_orders.quantity_accorded',
            'pr.id as proveedor_id',
            'np.id as numparte_id ',
            DB::raw("DATE_ADD(DATE_ADD(CONCAT(LEFT(s.fecha_laboral, 7), '-01'), INTERVAL 1 MONTH), INTERVAL 14 DAY) AS fecha_prorroga"),
            DB::raw("shipment_orders.updated_at as DateUpdatedf"),
            DB::raw("DATE_ADD(s.fecha_laboral,interval  3 day) as DateRequestedF"),
            DB::raw('s.fecha_laboral as DateOrderTransactionF'),
            DB::raw('shipment_orders.Quantity_Shipped - shipment_orders.quantity_received as UnitsOpen'),
            DB::raw('ifnull(accords.total, 0) as total_acuerdos'),
            DB::raw('(shipment_orders.quantity_shipped - shipment_orders.quantity_received - shipment_orders.quantity_accorded) as quantity_pending'),
            DB::raw('greatest( 0, TIMESTAMPDIFF(DAY, s.updated_at, ifnull(receipts.created_at, s.updated_at) ) ) as diff_time'),
            DB::raw('greatest( 0, DATEDIFF("' . $now . '" , cast(s.updated_at as date)) ) as days'),
            DB::raw('ifnull(receipts.created_at, s.updated_at) as updated_at'),
            DB::raw('IF( coalesce( ((shipment_orders.quantity_received * 100) / shipment_orders.quantity_shipped),0) > 100 , 100 , coalesce( ((shipment_orders.quantity_received * 100) / shipment_orders.quantity_shipped),0) ) AS porcentage')
        ])
        ->where('sd.proveedor_id', DB::raw($DataFiltros["ProveedorId"]))
        ->where(DB::raw('LEFT(s.fecha_laboral,7)'), DB::raw("'".$DataFiltros["Mes"]."'"))
        ;
        return $ShipRows;

    }

}