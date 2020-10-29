<form action="{{route('venta.store')}}" method="POST" class="form-horizontal" >
{{csrf_field()}}
<div class="form-group row">
                <label  class="col-md-3 form-control-label" for="id_cliente">Cliente</label>
                <div class="col-md-9">
                    <input value="{{$credito->nombre}}"  type="text"   class="form-control" placeholder="Ingrese el Código" required >

                </div>
    </div>
    <input hidden value="{{$credito->idcliente}}" type="text" for = "id_cliente" id="id_cliente" name="id_cliente"  class="form-control" placeholder="Ingrese el Código" required>




    <div class="form-group row">
                <label class="col-md-3 form-control-label" for="codigo">Documento</label>
                <div class="col-md-9">
                    <input value="{{$credito->tipo_identificacion}}"  type="text" id="tipo_identificacion" name="tipo_identificacion" class="form-control" placeholder="Ingrese el Código" required >

                </div>
    </div>


    <div class="form-group row">
                <label class="col-md-3 form-control-label" for="codigo">Numero de Venta</label>
                <div class="col-md-9">
                    <input value="{{$credito->num_venta}}"  type="text" id="num_venta" name="num_venta" class="form-control" placeholder="Ingrese el Código" required pattern="[0-9]{0,15}">

                </div>
    </div>
    <div class="form-group row">
                <label class="col-md-3 form-control-label" for="codigo">Total a pagar</label>
                <div class="col-md-9">
                    <input value="{{$credito->total+($credito->total*20/100)}}"  type="text" id="total_pagar" name="total_pagar" class="form-control" placeholder="Ingrese el Código" ">

                </div>
    </div>



    <h3>Detalle de Ventas</h3>

<div class="table-responsive col-md-12">
  <table id="detalles" class="table table-bordered table-striped table-sm">
  <thead>
      <tr class="bg-success">


          <th>Producto</th>
          <th>Precio Venta (MXN$)</th>
          <th>Descuento (MXN$)</th>
          <th>Cantidad</th>
          <th>SubTotal (MXN$)</th>
      </tr>
  </thead>

  <tfoot>



     <!--<th><h2>TOTAL</h2></th>
     <th></th>
     <th></th>
     <th></th>
     <th><h4 id="total">{{$credito->total}}</h4></th>-->
     <tr>
          <th  colspan="4"><p align="right">TOTAL:</p></th>
          <th><p align="right">${{number_format($credito->total,2)}}</p></th>
      </tr>

      <tr>
          <th colspan="4"><p align="right">TOTAL IMPUESTO (20%):</p></th>
          <th><p align="right">${{number_format($credito->total*20/100,2)}}</p></th>
      </tr>

      <tr>
          <th  colspan="4"><p align="right">TOTAL PAGAR:</p></th>
          <th><p  align="right">${{number_format($credito->total+($credito->total*20/100),2)}}</p></th>
      </tr>
  </tfoot>

  <tbody>

     @foreach($detalles as $det)
     <input hidden value="{{$det->idproducto}}"   id="id_producto" name="id_producto" >
     <input hidden value="{{$det->producto}}"   id="product_selected" name="product_selected" >
     <input hidden value="{{$det->precio}}"   id="precio_venta" name="precio_venta" >
     <input hidden value="{{$det->descuento}}"   id="descuento" name="descuento" >
     <input hidden value="{{$det->cantidad}}"   id="cantidad" name="cantidad" >
      <tr>
        <td hidden id = "id_producto" name = "id_producto">{{$det->idproducto}}</td>
        <td id = "product_selected" name = "product_selected">{{$det->producto}}</td>
        <td id = "precio_venta" name = "precio_venta">${{$det->precio}}</td>
        <td id = "descuento" name = "descuento">{{$det->descuento}}</td>
        <td id = "cantidad" name = "cantidad">{{$det->cantidad}}</td>
        <td id = "total_pagar" name = "total_pagar">${{number_format($det->cantidad*$det->precio - $det->cantidad*$det->precio*$det->descuento/100,2)}}</td>


      </tr>


     @endforeach

  </tbody>


  </table>


    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-2x"></i> Cerrar</button>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <button type="submit" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>

    </div>
    </form>

@push('scripts')
 <script>

</script>
@endpush
