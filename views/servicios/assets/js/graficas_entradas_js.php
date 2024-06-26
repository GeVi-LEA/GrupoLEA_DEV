<script>
var series_estatus = [];
var detalle_lotes = {};
var datosGrafica = <?= json_encode($datosGrafica) ?>;
var arrayIdsTr = <?= json_encode($arrayIdsTr) ?>;
var data_estatus = [];
var data_colores = [];
var id_estatus_sel = 0;
var estatus_sel;
var clave_sel;
var table;
$(document).ready(function() {

    $("#div-lista").hide()
    $("#div-grafica").show();
    chart_productos();

    $(".btn-tab").unbind();
    $(".btn-tab").click(function() {
        showLoading_global();

        var tipo = $(this).data("tipo");
        switch (tipo) {
            case 'grafica':
                $("#div-lista").hide()
                $("#div-grafica").show()
                break;

            default:
                $("#div-lista").show()
                $("#div-grafica").hide()

                break;
        }
        setTimeout(() => {
            chart_productos();
        }, 1000);
    });

    $('#tabEntradas a').unbind();
    $('#tabEntradas a').on('click', function(e) {
        // console.log($(this)[0].id.replace("-tab", ""));
        showLoading_global();
        setTimeout(() => {
            chart_productos();
        }, 1000);
        // console.log($(this)[0].id);
    });

    setInterval(() => {
        if (!$(".swal2-html-container").is(":visible")) {
            if (id_estatus_sel == 0) {
                llenatablaestatus(datosGrafica[0].estatus_id, datosGrafica[0].estatus, datosGrafica[0].clave);
            } else {
                llenatablaestatus(id_estatus_sel, estatus_sel, clave_sel);
            }

            setTimeout(() => {
                chart_productos();
                if (id_estatus_sel == 0) {
                    llenatablaestatus(datosGrafica[0].estatus_id, datosGrafica[0].estatus, datosGrafica[0].clave);
                } else {
                    llenatablaestatus(id_estatus_sel, estatus_sel, clave_sel);
                }
            }, 1000);
        }
    }, 60000);
});
const chart_productos = () => {

    //import * as echarts from 'echarts';
    // console.log($("#chart_entradas"));
    $("#chart_entradas").html("").attr("_echarts_instance_", "");
    data_estatus = [];
    data_colores = [];
    series_estatus = [];
    detalle_lotes = {};
    $.each(datosGrafica, function(idx, obj) {
        data_estatus.push({
            'value': obj.cantidad,
            'name': obj.estatus,
            'id': obj.estatus_id,
            'clave': obj.clave
        });
        data_colores.push(obj.color_estatus);
    });

    // $("#chart_productos").html("").attr("_echarts_instance_", "");
    var chartDom = document.getElementById('chart_entradas');
    var myChart = echarts.init(chartDom);
    var option;

    option = {
        tooltip: {
            trigger: 'item'
        },
        legend: {
            top: '5%',
            left: 'center',

            textStyle: {
                color: getColorTheme().fontcolor
            },
            itemStyle: {
                borderColor: getColorTheme().bgcolor

            },
            selected: {
                'Liberada': false,
            },
        },
        series: [{
            name: 'Entradas',
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: false,
            selectedMode: 'single',
            itemStyle: {
                borderRadius: 10,
                borderColor: getColorTheme().bgcolor, //'#fff',
                borderWidth: 5
            },
            label: {
                show: true,
                position: 'right',
                formatter: '{b}：{{c}}  {d}%',
                borderColor: getColorTheme().bgcolor,
                color: getColorTheme().fontcolor
                // formatter: function(d) {
                // return d.name + ' { ' + d.data.value + ' }  {d}%' + d % ;
                // }
            },
            emphasis: {
                label: {
                    show: true,
                    fontSize: 15,
                    fontWeight: 'bold'
                }
            },
            labelLine: {
                show: false
            },
            color: data_colores, //["#7a7a7a", "#b5b228", "##1b8a05", "#d8813a", "#dad73f", "#2501a5", "#e4a700"],
            data: data_estatus,

        }]
    };
    option && myChart.setOption(option);
    myChart.on('click', function(params) {
        // Print name in console
        // console.log("estatus_id:", params.data.id);
        id_estatus_sel = params.data.id;
        estatus_sel = params.data.name;
        clave_sel = params.data.clave;
        llenatablaestatus(params.data.id, params.data.name, params.data.clave);
    });
    swal.close();

}
var html = "";
var servicios;
var table;

function llenatablaestatus(id_estatus, estatus, clave) {
    $("#tituloestatus").html("");
    jQuery.ajax({
        url: __url__ + '?ajax&controller=Servicios&action=getUnidadesEstatus',
        data: {
            id_estatus: id_estatus
        },
        method: 'post',
        dataType: "json",
    }).then(resp => {
        // console.log(resp);
        servicios = resp;
        datosGrafica = resp.datosGrafica;
        html = "";
        for (var x = 0; x < resp.servicios.length; x++) {
            html += `
                <tr id="showEnsacado">
                    <td id="" hidden>${resp.servicios[x].id}</td>
                    <td id="idEnsacado" hidden>${resp.servicios[x].id}</td>
                    <td class="w-td-30 p-0 m-0">
                        <span  class="material-icons i-recibir">${((jQuery.inArray(resp.servicios[x].tipo_transporte_id, arrayIdsTr)) >=0) ? 'directions_subway' : 'local_shipping'}</span>
                        <strong>${getOperacionServicios2(resp.servicios[x].entrada_salida)}</strong>    
                    </td>

                    <td class="px-0 mx-0"><strong>${resp.servicios[x].numeroUnidad}</strong></td>
                    <td><span>${resp.servicios[x].nombreCliente}</span></td>
                    <td><span>${(resp.servicios[x].ticket != null) ? '<span class="material-symbols-outlined i-correcto" data-bs-toggle="tooltip" data-bs-title="Ticket: '+resp.servicios[x].ticket+'">scale</span>':'<span class="material-symbols-outlined i-warning" data-bs-toggle="tooltip" data-bs-title="No ha sido pesada">scale</span>'}</span></td>
                    <td>${resp.servicios[x].fecha_entrada == null ? '' : moment(resp.servicios[x].fecha_entrada).format("DD/MM/YYYY hh:mm:ss")}</td>
                </tr>       `

        }
        $("#tituloestatus").html(estatus).removeClass().addClass(getClaseEstado(clave));
        $("#tabla_estatus tbody").html("");
        $('#tabla_estatus').DataTable().clear().destroy();
        $("#tabla_estatus tbody").html(html);

        $('#tabla_estatus tfoot th').each(function(i) {
            var title = $('#tabla_estatus thead th')
                .eq($(this).index())
                .text();
            $(this).html(
                '<input type="text" placeholder="' + title + '" data-index="' + i + '" />'
            );
        });
        table = new DataTable('#tabla_estatus', {
            dom: 'Bfrtip',
            retrieve: true,
            // responsive: true,
            // scrollCollapse: true,
            scrollY: '40vh',
            // scrollX: true,
            // fixedColumns: true,
            language: {
                url: '<?php echo URL; ?>assets/libs/datatables/es-MX.json',
            },
            order: [
                [5, 'desc']
            ],
            columns: [null, null, {
                    "width": "2%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "45%"
                },
                {
                    "width": "5%"
                },
                {
                    "width": "30%"
                },
            ],
            buttons: [
                'print',
                {
                    extend: 'excelHtml5',
                    // className: 'btn btnExcel',
                    title: `Reporte de entradas ${estatus} ${formatDate(new Date())}`
                },
                'pdf',
            ],
        });
        setTimeout(() => {

            // Filter event handler
            $(table.table().container()).on('keyup', 'tfoot input', function() {
                console.log($(this));
                table
                    .column($(this).data('index'))
                    .search(this.value)
                    .draw();
            });

        }, 1000);
    }).fail(resp => {}).catch(resp => {
        // mensajeError('Ocurrio un problema en la peticion en el servidor, favor de reportar a los administradores');
        erpalert("error", "Ocurrio un problema en la peticion en el servidor, favor de reportar a los administradores");
        console.log(resp);
    });
}
</script>