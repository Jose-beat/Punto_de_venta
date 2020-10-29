<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Credito;
use App\DetalleCredito;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use DB;

class CreditoController extends Controller
{

    public function index(Request $request){

        if($request){

           $sql=trim($request->get('buscarTexto'));
            $creditos=Credito::join('clientes','creditos.idcliente','=','clientes.id')
           ->join('users','creditos.idusuario','=','users.id')
            ->join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
             ->select('creditos.id','creditos.tipo_identificacion',
             'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
             'creditos.estado','creditos.total','clientes.nombre as cliente','users.nombre')
            ->where('creditos.num_venta','LIKE','%'.$sql.'%')
            ->orderBy('creditos.id','desc')
            ->groupBy('creditos.id','creditos.tipo_identificacion',
            'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
            'creditos.estado','creditos.total','clientes.nombre','users.nombre')
            ->paginate(8);
/*
             $sql=trim($request->get('buscarTexto'));
            $creditos=Credito::join('clientes','creditos.idcliente','=','clientes.id')
            ->join('users','creditos.idusuario','=','users.id')
        ->select('creditos.id','creditos.tipo_identificacion',
             'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
             'creditos.estado','creditos.total','users.nombre')
            ->where('creditos.num_venta','LIKE','%'.$sql.'%')
            ->orderBy('creditos.id','desc')
            ->groupBy('creditos.id','creditos.tipo_identificacion',
            'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
            'creditos.estado','creditos.total','users.nombre')
            ->paginate(8);
            */

            return view('creditos.index',["creditos"=>$creditos,"buscarTexto"=>$sql]);
            //return $creditos;
        }


     }

        public function create(){

             /*listar las clientes en ventana modal*/
             $clientes=DB::table('clientes')->get();

             /*listar los productos en ventana modal*/
             $productos=DB::table('productos as prod')
             /*Omision por detalles*/
            /* ->join('detalle_compras','prod.id','=','detalle_compras.idproducto')*/
             ->select(DB::raw('CONCAT(prod.codigo," ",prod.nombre) AS producto'),'prod.id','prod.stock','prod.precio_venta')
             ->where('prod.condicion','=','1')
             /*->where('prod.stock','=','0')*/
            ->groupBy('producto','prod.id','prod.stock','prod.precio_venta')
             ->get();



             return view('creditos.create',["clientes"=>$clientes,"productos"=>$productos]);

        }

         public function store(Request $request){


             try{

                 DB::beginTransaction();
                 $mytime= Carbon::now('America/Mexico_city');

                 $credito = new Credito();
                 $credito->idcliente = $request->id_cliente;
                 $credito->idusuario = \Auth::user()->id;
                 $credito->tipo_identificacion = $request->tipo_identificacion;
                 $credito->num_venta = $request->num_venta;
                 $credito->fecha_venta = $mytime->toDateString();
                 $credito->impuesto = "0.20";
                 $credito->total=$request->total_pagar;
                 $credito->estado = 'Registrado';
                 $credito->save();

                 $id_producto=$request->id_producto;
                 $cantidad=$request->cantidad;
                 $descuento=$request->descuento;
                 $precio=$request->precio_venta;


                 //Recorro todos los elementos
                 $cont=0;

                  while($cont < count($id_producto)){

                     $detalle = new DetalleCredito();
                     /*enviamos valores a las propiedades del objeto detalle*/
                     /*al idcompra del objeto detalle le envio el id del objeto credito, que es el objeto que se ingresó en la tabla creditos de la bd*/
                     /*el id es del registro de la credito*/
                     $detalle->idcredito = $credito->id;
                     $detalle->idproducto = $id_producto[$cont];
                     $detalle->cantidad = $cantidad[$cont];
                     $detalle->precio = $precio[$cont];
                     $detalle->descuento = $descuento[$cont];
                     $detalle->save();
                     $cont=$cont+1;
                 }

                 DB::commit();

             } catch(Exception $e){

                 DB::rollBack();
             }

             return Redirect::to('credito');
         }



         public function show($id){

             //dd($id);
             //dd($request->all());

             /*mostrar credito*/

             //$id = $request->id;
              $credito = Credito::join('clientes','creditos.idcliente','=','clientes.id')
             ->join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
             ->select('creditos.id','creditos.tipo_identificacion','creditos.idcliente',
             'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
             'creditos.estado','clientes.nombre',
             DB::raw('sum(detalle_creditos.cantidad*precio - detalle_creditos.cantidad*precio*descuento/100) as total')
             )
             ->where('creditos.id','=',$id)
             ->orderBy('creditos.id', 'desc')
             ->groupBy('creditos.id','creditos.tipo_identificacion',
             'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
             'creditos.estado','clientes.nombre','creditos.idcliente')
             ->first();
             /*
             $credito = Credito::join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
             ->select('creditos.id','creditos.tipo_identificacion',
             'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
             'creditos.estado',
             DB::raw('sum(detalle_creditos.cantidad*precio - detalle_creditos.cantidad*precio*descuento/100) as total')
             )
             ->where('creditos.id','=',$id)
             ->orderBy('creditos.id', 'desc')
             ->groupBy('creditos.id','creditos.tipo_identificacion',
             'creditos.num_venta','creditos.fecha_venta','creditos.impuesto',
             'creditos.estado')
             ->first();
                     */
             /*mostrar detalles*/
             $detalles = DetalleCredito::join('productos','detalle_creditos.idproducto','=','productos.id')
             ->select('detalle_creditos.idproducto','detalle_creditos.cantidad','detalle_creditos.precio','detalle_creditos.descuento','productos.nombre as producto')
             ->where('detalle_creditos.idcredito','=',$id)
             ->orderBy('detalle_creditos.id', 'desc')->get();

             return view('creditos.show',['credito' => $credito,'detalles' =>$detalles]);
         }

         public function destroy(Request $request){

             $credito = Credito::findOrFail($request->id_venta);
             $credito->estado = 'Anulado';
             $credito->save();
             return Redirect::to('credito');

        }

         public function pdf(Request $request,$id){

             $credito = Credito::join('clientes','creditos.idcliente','=','clientes.id')
             ->join('users','creditos.idusuario','=','users.id')
             ->join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
             ->select('creditos.id','creditos.tipo_identificacion',
             'creditos.num_venta','creditos.created_at','creditos.impuesto',
             'creditos.estado',DB::raw('sum(detalle_creditos.cantidad*precio - detalle_creditos.cantidad*precio*descuento/100) as total'),'clientes.nombre','clientes.tipo_documento','clientes.num_documento',
             'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
             ->where('creditos.id','=',$id)
             ->orderBy('creditos.id', 'desc')
             ->groupBy('creditos.id','creditos.tipo_identificacion',
             'creditos.num_ventas','creditos.created_at','creditos.impuesto',
             'creditos.estado','clientes.nombre','clientes.tipo_documento','clientes.num_documento',
             'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
             ->take(1)->get();

             $detalles = DetalleCredito::join('productos','detalle_creditos.idproducto','=','productos.id')
             ->select('detalle_creditos.cantidad','detalle_creditos.precio','detalle_creditos.descuento',
             'productos.nombre as producto')
             ->where('detalle_creditos.idcredito','=',$id)
             ->orderBy('detalle_creditos.id', 'desc')->get();

             $numventa=Credito::select('num_venta')->where('id',$id)->get();

             $pdf= \PDF::loadView('pdf.credito',['credito'=>$credito,'detalles'=>$detalles]);
             return $pdf->download('credito-'.$numventa[0]->num_venta.'.pdf');
         }


         public function listarMesesPdf(){
            Carbon::setLocale('es');
           $mytime= Carbon::now('America/Mexico_city');
           $meses = [
               0 => '',
               1 => 'January',
               2 => 'February',
               3 => 'March',
               4 => 'April',
               5 => 'May',
               6 => 'June',
               7 => 'July',
               8 => 'August',
               9 => 'September',
               10=> 'October',
               11=> 'November',
               12=> 'December'
           ];

           $fecha = Carbon::parse($mytime);
           $mesIn=  $meses[$fecha->month];
            /*
           $venta = Credito::join('clientes','creditos.idcliente','=','clientes.id')
           ->join('users','creditos.idusuario','=','users.id')
           ->join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
           ->select('creditos.id','creditos.tipo_identificacion',
           'creditos.num_venta','creditos.created_at','creditos.impuesto',
           'creditos.estado',DB::raw('sum(detalle_creditos.cantidad*precio - detalle_creditos.cantidad*precio*descuento/100) as total'),'clientes.nombre','clientes.tipo_documento','clientes.num_documento',
           'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
           /*->where('creditos.id','=',$id)*/
           /*->orderBy('creditos.id', 'desc')
           ->groupBy('creditos.id','creditos.tipo_identificacion',
           'creditos.num_venta','creditos.created_at','creditos.impuesto',
           'creditos.estado','clientes.nombre','clientes.tipo_documento','clientes.num_documento',
           'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
           ->take(1)->get();*/

           $ventasmes=DB::select('SELECT monthname(v.fecha_venta) as mes, sum(v.total) as totalmes from creditos v where v.estado="Registrado" group by monthname(v.fecha_venta) order by month(v.fecha_venta) desc limit 12');


           $ventaG = Credito::join('users','creditos.idusuario','=','users.id')
           ->join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
           ->select('creditos.id','creditos.tipo_identificacion',
           'creditos.num_venta','creditos.created_at','creditos.impuesto',
           'creditos.estado',/*DB::raw('sum(detalle_creditos.cantidad*precio - detalle_creditos.cantidad*precio*descuento/100) as total')*/'creditos.total','users.usuario')
           /*->where('creditos.id','=',$id)*/
           ->orderBy('creditos.id', 'desc')
           ->groupBy('creditos.id','creditos.tipo_identificacion',
           'creditos.num_venta','creditos.total','creditos.created_at','creditos.impuesto',
           'creditos.estado','users.usuario')
           ->get();

           $ventaC = Credito::join('clientes','creditos.idcliente','=','clientes.id')
           ->join('users','creditos.idusuario','=','users.id')
           ->join('detalle_creditos','creditos.id','=','detalle_creditos.idcredito')
           ->select('creditos.id','creditos.tipo_identificacion',
           'creditos.num_venta','creditos.created_at','creditos.impuesto',
           'creditos.estado','creditos.total',/*DB::raw('sum(detalle_creditos.cantidad*precio - detalle_creditos.cantidad*precio*descuento/100) as total')*/'clientes.nombre','clientes.tipo_documento','clientes.num_documento',
           'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
           /*->where('creditos.id','=',$id)*/
           ->orderBy('creditos.id', 'desc')
           ->groupBy('creditos.id','creditos.tipo_identificacion',
           'creditos.num_venta','creditos.created_at','creditos.total','creditos.impuesto',
           'creditos.estado','clientes.nombre','clientes.tipo_documento','clientes.num_documento',
           'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
           ->get();


           $detalles = DetalleCredito::join('productos','detalle_creditos.idproducto','=','productos.id')
           ->select('detalle_creditos.cantidad','detalle_creditos.precio','detalle_creditos.descuento',
           'productos.nombre as producto')
           /*->where('detalle_creditos.idcredito','=',$id)*/
           ->orderBy('detalle_creditos.id', 'desc')->get();

           $numventa=Credito::select('num_venta')/*->where('id',$id)*/->get();

           $pdf= \PDF::loadView('pdf.pdfCreditosMes',['ventaC'=>$ventaC,'ventaG'=>$ventaG,'detalles'=>$detalles,'ventasmes'=>$ventasmes, 'mesIn'=>$mesIn]);
          return $pdf->download('creditos_del_mes'.$numventa[0]->num_venta.'.pdf');


        }

}
