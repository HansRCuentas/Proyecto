<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{$titulo}}</title>
    <link rel="stylesheet" href="{{public_path('')}}">
    <link rel="stylesheet" href="{{public_path('css/style.css')}}" type="text/css">
</head>

<body>

    <div id="page_pdf">
        <table id="factura_head">
            <tr>
                <td class="logo_factura">
                    <div>
                        <img src="./images/Vlashey.jpeg" style="width: 120;">
                    </div>
                </td>
                <td class="info_empresa">
                    <div>
                        <span class="h2">IMPRENTA VLASHEY PRINT</span>
                        <p>Zn. Alto Chijini Av. 9 de Abro N°1036</p>
                        <p>Teléfono: 67117852</p>
                        <p>Email: vladimirAlvarado@gmail.com</p>
                    </div>
                </td>
                <td class="info_factura">
                    <div class="round">
                        <span class="h3">Comprobante de Venta</span>
                        <p>No. Compra: <strong>{{$nro_venta}}</strong></p>
                        <p>Fecha: {{date('Y-m-d')}}</p>
                        <p>
                        Hora: {{date('H:i:s')}}</p>
                        <p>Vendedor: {{$usuario}}</p>
                    </div>
                </td>
            </tr>
        </table>
        <table id="factura_cliente">
            <tr>
                <td class="info_cliente">
                    <div class="round">
                        <span class="h3">Datos</span>
                        <table class="datos_cliente">
                            <tr>
                                <td><label>Nit Cliente:</label>
                                    <p>{{$cliente->cedula}}</p>
                                </td>
                                <td><label>Teléfono:</label>
                                    <p>{{$cliente->celular}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Cliente:</label>
                                    <p>{{$cliente->nombre}}</p>
                                </td>
                                <td><label>Dirección:</label>
                                    <p>{{$cliente->direccion}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Observacion: </label>
                                    <p>{{$observacion}}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>

            </tr>
        </table>


        <table id="factura_detalle">
            <thead>
                <tr>
                    <th width="50px">Cant.</th>
                    <th class="textleft">Descripción</th>
                    <th class="textright" width="150px">Precio Unitario.</th>
                    <th class="textright" width="150px"> Precio Total</th>

                </tr>
            </thead>
            <tbody id="detalle_productos">
                @foreach ($cart as $item)
                <tr>
                    <td class="textcenter">{{$item->quantity}}</td>
                    <td>{{$item->name}}</td>
                    <td class="textright">{{$item->price}}</td>
                    <td class="textright">{{$item->quantity * $item->price}}</td>

                </tr>
                @endforeach

            </tbody>
            <tfoot id="detalle_totales">
                <tr>
                    <td colspan="3" class="textright"><span>SUBTOTAL Q.</span></td>
                    <td class="textright"><span>{{$subtotal}}</span></td>
                </tr>
                <tr>
                    <td colspan="3" class="textright"><span>AUMENTO</span></td>
                    <td class="textright"><span>{{$aumento}}</span></td>
                </tr>
                <tr>
                    <td colspan="3" class="textright"><span>DESCUENTO</span></td>
                    <td class="textright"><span>{{$descuento}}</span></td>
                </tr>
                <tr>
                    <td colspan="3" class="textright"><span>TOTAL Q.</span></td>
                    <td class="textright"><span>{{$subtotal+$aumento-$descuento}}</span></td>
                </tr>
        
            </tfoot>
        </table>
        <div>
            
            <h4 class="label_gracias">¡Gracias por su compra!</h4>
        </div>

    </div>

</body>

</html>