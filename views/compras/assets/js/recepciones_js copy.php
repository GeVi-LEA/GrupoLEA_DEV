<script>
let clientes;
let legends_reqs = [];
let series_reqs = [];
let list_estatus = [];
let legends_reqs1 = [];
let list_reqs;
let detalle_series = {};
let series_estatus = [];
let latabla;
$(document).ready(function() {
    $("#cmbClientes").find("option").remove();

    // SE OBTIENEN LOS CLIENTES
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: __url__ + "?ajax&controller=Catalogo&action=getClientes",
        success: function(r) {
            var resp = r;
            console.log(resp);
            detalles = resp;
            clientes = resp.clientes;
            resp.clientes.forEach(cliente => {
                $("#cmbClientes").append('<option value="' + cliente['id'] + '">' + cliente['nombre'] + '</option>');
            });

        },
        error: function(xhr, status, error) {
            erpalert("error", "Algo salio mal, contacte al administrador....");
            console.log(xhr, status, error);

        },
    });
    $("#cmbClientes").select2({
        placeholder: 'Todos los clientes',
        width: 'resolve'
    });

    // SE OBTIENEN LAS REQUISICIONES
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: __url__ + "?ajax&controller=Compras&action=getRequisiciones",
        success: function(r) {
            var resp = r;
            console.log(resp);
            list_reqs = resp;
            for (var x = 0; x < list_reqs.requisiciones.length; x++) {
                if (legends_reqs.indexOf(list_reqs.requisiciones[x].solicitud.toUpperCase()) < 0) {
                    legends_reqs.push(list_reqs.requisiciones[x].solicitud.toUpperCase()); // + '{' + inventarios.inventarios[x].total + '}');
                }
                if (list_estatus.indexOf(list_reqs.requisiciones[x].estatus.toUpperCase()) < 0) {
                    list_estatus.push(list_reqs.requisiciones[x].estatus.toUpperCase()); // + '{' + inventarios.inventarios[x].total + '}');
                }
            }
            // LLENA SERIES
            for (c = 0; c < legends_reqs.length; c++) {
                //console.log(clientes[c]);
                if (detalle_series.hasOwnProperty(legends_reqs[c].toUpperCase()) <= 0) {
                    detalle_series[legends_reqs[c].toUpperCase()] = ({
                        name: legends_reqs[c].toUpperCase(),
                        type: 'bar',
                        stack: 'total',
                        label: {
                            show: true,
                            // formatter: function(param) {
                            // return param.data == 0 ? '' : numero2Decimales(param.data) + ' KG (TARIMAS:' + (Math.floor((param.data / 25) / 55)) + ' SACOS:' + (Math.round((((param
                            // .data /
                            // 25) / 55) -
                            // Math.floor((param.data / 25) / 55)) * 55)) + ') ';
                            // },
                            // shadowColor: 'rgba(0, 0, 0, 0.5)',
                            // shadowBlur: 10
                        },
                        emphasis: {
                            focus: 'series'
                        },
                        data: [],



                    });

                    let total = 0;
                    for (l = 0; l < list_estatus.length; l++) {
                        // console.log(productos[l]);
                        // legends_reqs.push(list_estatus[l]);
                        total = 0;
                        for (x = 0; x < list_reqs.requisiciones.length; x++) {
                            if ((legends_reqs[c].toUpperCase() == list_reqs.requisiciones[x].solicitud.toUpperCase()) && list_reqs.requisiciones[x].estatus.toUpperCase() == list_estatus[l]) {
                                total = total + 1;
                            }
                        }
                        detalle_series[legends_reqs[c].toUpperCase()].data.push(total);
                    }
                    // let total = 0;
                    // legends_lotes1 = [];
                    // for (l = 0; l < lotes.length; l++) {
                    // //console.log(lotes[l]);
                    // legends_lotes1.push(lotes[l]);
                    // total = 0;
                    // for (x = 0; x < inventarios.inventarios.length; x++) {
                    // if ((clientes[c] == inventarios.inventarios[x].Nombre_Cliente) && inventarios.inventarios[x].Lote == lotes[l]) {
                    // total = total + parseFloat(inventarios.inventarios[x].disponible);
                    // }
                    // }
                    // detalle_lotes[clientes[c]].data.push(total);
                    // }
                }

            }

            series_estatus = [];
            $.each(detalle_series, function(idx, obj) {
                series_estatus.push(obj);

            });

            chart_requisiciones();

        },
        error: function(xhr, status, error) {
            erpalert("error", "Algo salio mal, contacte al administrador....");
            console.log(xhr, status, error);
        },
    });
});

const chart_requisiciones = () => {

    //import * as echarts from 'echarts';
    $("#chart_requisiciones").attr("style", "height:" + (legends_reqs.length * 40) + "px");
    // $("#chart_nave").html("").attr("_echarts_instance_", "");
    console.log($("#chart_requisiciones"));
    var chartDom = document.getElementById('chart_requisiciones');
    var myChart = echarts.init(chartDom);
    var option;

    option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                // Use axis to trigger tooltip
                type: 'shadow' // 'shadow' as default; can also be 'line' or 'shadow'
            }
        },
        legend: {},
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value'
        },
        yAxis: {
            type: 'category',
            data: list_estatus
        },
        series: series_estatus

    };

    option && myChart.setOption(option);
    myChart.on('click', function(params) {
        // Print name in console
        console.log(params);
        /*obtiene detalles*/
        getDetalleGrafica(params.name, params.seriesName);
    });
}

function getDetalleGrafica(estatus = "", solicitud = "") {
    console.log("estatus: ", estatus, " solicitud: ", solicitud);
    let tabladetalle = [];
    let html = "";
    $("#tabla_estatus tbody").html("");
    $("#tituloestatus").html("");
    for (x = 0; x < list_reqs.requisiciones.length; x++) {
        if ((solicitud == list_reqs.requisiciones[x].solicitud.toUpperCase()) && list_reqs.requisiciones[x].estatus.toUpperCase() == estatus) {
            html += `
             <tr>
                    <td hidden>${list_reqs.requisiciones[x].id}</td>
                    <td ><strong>${list_reqs.requisiciones[x].folio}</strong></td>
                    <td >${list_reqs.requisiciones[x].proveedor}</td>
                    <td >${getCliente(list_reqs.requisiciones[x].cliente_id)}</td>
                    <td >${list_reqs.requisiciones[x].fecha_requerida == null ? '' : moment(list_reqs.requisiciones[x].fecha_requerida).format("DD/MM/YYYY hh:mm:ss")}</td>
                    <td >${showFirmas(list_reqs.requisiciones[x].firmas)}</td>
                    <td>
                        <div class="text-right">
                            ${(list_reqs.requisiciones[x].cotizacion) ? '<span hidden id="archivoCotizacion">${list_reqs.requisiciones[x].cotizacion}</span><span id="showCotizacion" class="i-clip material-icons">attach_file</span>' : ''}
                            ${((!(list_reqs.requisiciones[x].estatus_id == 4) || list_reqs.requisiciones[x].estatus_id == 5)) ? '<a href="<?= principalUrl ?>?controller=Compras&action=requisicion&id=${list_reqs.requisiciones[x].id}"><span id="" class="material-icons i-edit" title="Editar">edit</span></a>' : ''}
                            ${(!(list_reqs.requisiciones[x].estatus_id == 2 || list_reqs.requisiciones[x].estatus_id == 5)) ? '<span id="deleteReq" class="material-icons i-delete" title="Eliminar">delete_forever</span>' : ''}
                            <span id="showReq" class="i-document material-icons">description</span>
                        </div>
                    </td>
                    
             </tr>       `;
            // tabladetalle.push({
            // "ID": list_reqs.requisiciones[x].id,
            // "FOLIO": list_reqs.requisiciones[x].folio,
            // "PROVEEDOR": list_reqs.requisiciones[x].proveedor,
            // "FECHA_REQUERIDA": list_reqs.requisiciones[x].fecha_requerida,
            // "FIRMAS": showFirmas(list_reqs.requisiciones[x].firmas),
            // "ACCIONES": `<div class="text-right">
            // ${(list_reqs.requisiciones[x].cotizacion) ? '<span hidden id="archivoCotizacion">${list_reqs.requisiciones[x].cotizacion}</span><span id="showCotizacion" class="i-clip material-icons">attach_file</span>' : ''}
            // ${((!(list_reqs.requisiciones[x].estatus_id == 4) || list_reqs.requisiciones[x].estatus_id == 5)) ? '<a href="<?= principalUrl ?>?controller=Compras&action=requisicion&id=${list_reqs.requisiciones[x].id}"><span id="" class="material-icons i-edit" title="Editar">edit</span></a>' : ''}
            // ${(!(list_reqs.requisiciones[x].estatus_id == 2 || list_reqs.requisiciones[x].estatus_id == 5)) ? '<span id="deleteReq" class="material-icons i-delete" title="Eliminar">delete_forever</span>' : ''}
            // <span id="showReq" class="i-document material-icons">description</span>
            // </div>`
            // });
        }
        // }
    }
    $("#tituloestatus").html(estatus + " - " + solicitud);

    $("#tabla_estatus tbody").html(html);
    new DataTable('#tabla_estatus', {
        dom: 'Bfrtip',
        retrieve: true,
        scrollY: '40vh',
        columns: [null,
            {
                "width": "5%"
            },
            {
                "width": "15%"
            },
            {
                "width": "40%"
            },
            {
                "width": "40%"
            },
            {
                "width": "5%"
            },
            {
                "width": "5%"
            },
        ],
        // data: tabladetalle,
        // columns: [{
        //         data: 'ID'
        //     },
        //     {
        //         data: 'FOLIO'
        //     },
        //     {
        //         data: 'PROVEEDOR'
        //     },
        //     {
        //         data: 'FECHA_REQUERIDA'
        //     },
        //     {
        //         data: 'FIRMAS'
        //     },
        //     {
        //         data: 'ACCIONES'
        //     }
        // ],
        buttons: [
            'print',

            {
                extend: 'excelHtml5',
                // className: 'btn btnExcel',
                //${((cliente != "null") ? cliente : almacen)}
                title: `Reporte de Requisiciones  ${formatDate(new Date())}`
            },
            'pdf',


        ],
        language: {
            url: '<?php echo URL; ?>assets/libs/datatables/es-MX.json',
        },
    });
    // $("#tabla_estatus").DataTable({
    // dom: 'Bfrtip',
    // retrieve: true,
    // data: tabladetalle,
    // 
    // });



}

function showFirmas(firmas_json) {
    var str = '';
    // console.log(firmas);
    var firmas = JSON.parse(firmas_json);
    // if (is_array($firmasArray)) {
    $.each(firmas, function(i, item) {
        // console.log("item: ", item);
        if (item != 0) {
            str += '<i class="text-success fas fa-check pl-1"></i>';
        } else {
            str += '<i class="text-danger fas fa-times pl-1"></i>';
        }
        // console.log(str);
    });
    // firmas.forEach(f => {
    // if (f != 0) {
    // str += '<i class="text-success fas fa-check pl-1"></i>';
    // } else {
    // str += '<i class="text-danger fas fa-times pl-1"></i>';
    // }
    // });

    // }
    return str;
}

function getCliente(cliente_id) {
    var nombrecliente = "";
    if (cliente_id != null) {
        for (var x = 0; x < clientes.length; x++) {
            console.log(clientes[x].id, "==", cliente_id);
            if (clientes[x].id == cliente_id) {
                nombrecliente = clientes[x].nombre;
                break;
            }
        }
    }
    console.log("nombrecliente: ", nombrecliente);
    return nombrecliente;
}
</script>