<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Credito;
use App\DetalleVenta;
use App\DetalleCredito;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use DB;

class TransferenciaController extends Controller
{
    public function store(Request $request){
         
         
        try{

            DB::beginTransaction();
            $mytime= Carbon::now('America/Mexico_city');
          
            $venta = new Venta();
            $venta->idcliente = $request->id_cliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_identificacion = $request->tipo_identificacion;
            $venta->num_venta = $request->num_venta;
            $venta->fecha_venta = $mytime->toDateString();
            $venta->impuesto = "0.20";
            $venta->total=$request->total_pagar;
            $venta->estado = 'Registrado';
            $venta->save();
            return $request->id_cliente;
            $id_producto=$request->id_producto;
            $cantidad=$request->cantidad;
            $descuento=$request->descuento;
            $precio=$request->precio_venta;
               
            
            //Recorro todos los elementos
            $cont=0;

             while($cont < count($id_producto)){

                $detalle = new DetalleVenta();
                /*enviamos valores a las propiedades del objeto detalle*/
                /*al idcompra del objeto detalle le envio el id del objeto venta, que es el objeto que se ingresó en la tabla ventas de la bd*/
                /*el id es del registro de la venta*/
                $detalle->idventa = $venta->id;
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

        return Redirect::to('venta');
    }

}
