<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Productos</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;
        }
        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 1px solid #c2cfd6;
        }
        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #c2cfd6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #c2cfd6;
        }
        .total {
            vertical-align: bottom;
            border-bottom: 2px solid #518ae7;
        }
        .table-bordered thead th, .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #c2cfd6;
        }
        th, td {
            display: table-cell;
            vertical-align: inherit;
        }
        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
        }


        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .izquierda{
            float:left;
        }
        .derecha{
            float:right;
        }
    </style>
</head>
<body>
    <h2>Agrovet "Las Palmas" <span class="derecha"></span></h2>

    <div>
        <h3>Lista de Ventas Generales <span class="derecha">Fecha: {{now()}}</span></h3>
    </div>
    <div>

    </div>
    <div>
        <table class="table table-bordered table-striped table-sm">
            <thead>

                <tr>
                    <th>id de la venta </th>
                    <th>Numero de venta</th>
                    <th>Cliente</th>
                    <th>Fecha de la venta</th>
                    <th>Totales</th>

                </tr>
            </thead>
            <tbody>
                @foreach($ventaG as $a)
                @if(empty($a->tipo_identificacion))
                    <tr>
                    <td>{{$a->id}}</td>
                    <td>{{$a->num_venta}}</td>
                    <td>Sin Cliente</td>
                    <td>{{$a->created_at}}</td>
                    <td>$ {{$a->total}}</td>
                    </tr>
                @endif
                @endforeach

                @foreach($ventaC as $a)
                <tr>
                    <td>{{$a->id}}</td>
                    <td>{{$a->num_venta}}</td>
                    <td>{{$a->nombre}}</td>
                    <td>{{$a->created_at}}</td>
                    <td>$ {{$a->total}}</td>
                    </tr>

                @endforeach

              <!---  <tr>
                <td class="total">
                TOTAL MENSUAL
                </td>
                <td class="total"></td>
                <td class="total"></td>
                <td class="total"></td>
                <td class="total">




                </td>
                </tr> ----->


            </tbody>
        </table>


        <table class="table table-bordered table-striped table-sm">
            <thead>
            <tr>
                <th>Mes</th>
                <th>Total de Venta</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($ventasmes as $vm)

            <tr>
                <td>{{ $vm->mes }}</td>
                <td>$ {{$vm->totalmes}}</td>
            </tr>




            @endforeach

            </tbody>
        </table>
    </div>



</body>
</html>


