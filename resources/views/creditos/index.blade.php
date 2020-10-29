@extends('principal')
@section('contenido')

<main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb bg-success" >
                <li class="breadcrumb-item active"><a href="/" class="text-light">BACKEND - SISTEMA DE COMPRAS - CREDITOS</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">

                       <h2>Listado de Creditos</h2><br/>

                       <a href="credito/create">

                        <button class="btn btn-primary btn-lg" type="button">
                            <i class="fa fa-plus fa-2x"></i>&nbsp;&nbsp;Agregar Credito
                        </button>

                        </a>

                        <a href="{{url('listarCreditoPdf')}}" target="_blank">
                            <button type="button" class="btn btn-success btn-lg">
                                <i class="fa fa-file fa-2x"></i>&nbsp;&nbsp;Reporte PDF

                            </button>

                        </a>

                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-6">
                            {!! Form::open(array('url'=>'credito','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
                                <div class="input-group">

                                    <input type="text" name="buscarTexto" class="form-control" placeholder="Buscar texto" value="{{$buscarTexto}}">
                                    <button type="submit"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            {{Form::close()}}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">

                                    <th>Ver Detalle</th>
                                    <th>Fecha Venta</th>
                                    <th>Número Venta</th>
                                    <th>Cliente</th>
                                    <th>Tipo de identificación</th>
                                    <th>Vendedor</th>
                                    <th>Total (MXN$)</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                    <th>Cambiar Estado</th>
                                    <!---<th>Descargar Reporte</th>--->

                                </tr>
                            </thead>
                            <tbody>

                              @foreach($creditos as $cred)

                                <tr>
                                    <td>

                                     <a href="{{URL::action('CreditoController@show',$cred->id)}}">
                                       <button type="button" class="btn btn-warning btn-md">
                                         <i class="fa fa-eye fa-2x"></i> Ver detalle
                                       </button> &nbsp;

                                     </a>
                                   </td>

                                    <td>{{$cred->fecha_venta}}</td>
                                    <td>{{$cred->num_venta}}</td>
                                    <td>{{$cred->cliente}}</td>
                                    <td>{{$cred->tipo_identificacion}}</td>
                                    <td>{{$cred->nombre}}</td>
                                    <td>${{number_format($cred->total,2)}}</td>
                                    <td>{{$cred->impuesto}}</td>
                                    <td>

                                      @if($cred->estado=="Registrado")
                                        <button type="button" class="btn btn-success btn-md">

                                          <i class="fa fa-check fa-2x"></i> Pendiente
                                        </button>

                                      @else

                                        <button type="button" class="btn btn-success btn-md">

                                          <i class="fa fa-check fa-2x"></i> Pagado
                                        </button>

                                       @endif

                                    </td>


                                    <td>

                                       @if($cred->estado=="Registrado")

                                        <button type="button" class="btn btn-danger btn-sm" data-id_venta="{{$cred->id}}" data-toggle="modal" data-target="#cambiarEstadoVenta">
                                            <i class="fa fa-times fa-2x"></i> Registrar Pago
                                        </button>

                                        @else

                                         <button type="button" class="btn btn-success btn-sm">
                                            <i class="fa fa-lock fa-2x"></i> Pagado
                                        </button>

                                        @endif

                                    </td>
<!---
                                    <td>

                                        <a href="{{url('pdfVenta',$cred->id)}}" target="_blank">

                                           <button type="button" class="btn btn-info btn-sm">

                                             <i class="fa fa-file fa-2x"></i> Descargar PDF
                                           </button> &nbsp;

                                        </a>

                                    </td>
                                    --->
                                </tr>

                                @endforeach

                            </tbody>
                        </table>

                        {{$creditos->render()}}

                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>


        <!-- Inicio del modal cambiar estado de venta -->
         <div class="modal fade" id="cambiarEstadoVenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Cambiar Estado de Venta</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                    <div class="modal-body">
                        <form action="{{route('credito.destroy','test')}}" method="POST">
                          {{method_field('delete')}}
                          {{csrf_field()}}

                            <input type="hidden" id="id_venta" name="id_venta" value="">

                                <p>Estas seguro de cambiar el estado?</p>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-2x"></i>Cerrar</button>
                                <button type="submit" class="btn btn-success"><i class="fa fa-lock fa-2x"></i>Aceptar</button>
                            </div>

                         </form>
                    </div>
                    <!-- /.modal-content -->
                   </div>
                <!-- /.modal-dialog -->
             </div>
            <!-- Fin del modal Eliminar -->


        </main>

@endsection
