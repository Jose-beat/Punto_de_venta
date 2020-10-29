@extends('principal')
@section('contenido')


<main class="main">

 <div class="card-body">

  <h2 class="text-center">Detalle de Credito</h2><br/><br/><br/>


            <div class="form-group row">

                    <div class="col-md-4">

                        <label class="form-control-label" for="nombre">Cliente</label>

                        <p>{{$credito->nombre}}</p>


                    </div>


                    <div class="col-md-4">

                    <label class="form-control-label" for="documento">Documento</label>

                    <p>{{$credito->tipo_identificacion}}</p>

                    </div>

                    <div class="col-md-4">
                            <label class="form-control-label" name="id_cliente" id="id_cliente" for="num_venta">Número Venta</label>

                            <p>{{$credito->num_venta}}</p>
                    </div>

            </div>


            <br/><br/>

           <div class="form-group row border">

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
                        <th><p align="right">${{number_format($credito->total+($credito->total*20/100),2)}}</p></th>
                    </tr>
                </tfoot>

                <tbody>

                   @foreach($detalles as $det)

                    <tr>

                      <td>{{$det->producto}}</td>
                      <td>${{$det->precio}}</td>
                      <td>{{$det->descuento}}</td>
                      <td>{{$det->cantidad}}</td>
                      <td>${{number_format($det->cantidad*$det->precio - $det->cantidad*$det->precio*$det->descuento/100,2)}}</td>


                    </tr>


                   @endforeach

                </tbody>


                </table>
              </div>
              <div class="modal-footer form-group row" id="guardar">

            <div class="col-md">
                @if ($credito->estado != 'Anulado')
                <button class="btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#abrirmodal">
                    <i class="fa fa-money fa-2x"></i>&nbsp;&nbsp;Registrar Pago
                </button>
                @else
                <button class="btn btn-danger disabled btn-lg" type="button" data-toggle="modal" data-target="#abrirmodal">
                    <i class="fa fa-money fa-2x"></i>&nbsp;&nbsp;Pagado
                </button>
                @endif


            </div>

            </div>

            </div>


    </div><!--fin del div card body-->
    <div class="modal fade" id="abrirmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Detalles del Credito</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">






                                @include('creditos.transfer')


                        </div>

                    </div>
  </main>

@endsection
