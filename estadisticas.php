<?php
include_once('config.php');
include_once('entidades/Encuesta.php');


if (isset($_GET['do']) && $_GET['do'] == 'cargarGraficos') {
    $dato_nombre = new Encuesta();
    $array_nombres = $dato_nombre->estadisticasNombres();

    $dato_hobby = new Encuesta();
    $array_hobby = $dato_hobby->estadisticasHobby();

    $dato_dedicacion = new Encuesta();
    $array_dedicacion = $dato_dedicacion->estadisticasDedicacion();

    $cant_hombres = new Encuesta();
    $cant_hombres->cantidadTotalPorGenero(Config::MUJER);

    $cant_mujeres = new Encuesta();
    $cant_mujeres->cantidadTotalPorGenero(Config::HOMBRE);

    $array_resultado = array(
        "data_hobby" => $array_hobby,
        "data_nombre" => $array_nombres,
        "cant_mujeres" => $cant_mujeres,
        "cant_hombres" => $cant_hombres,
        "data_dedicacion" => $array_dedicacion,

    );

    echo json_encode($array_resultado);
    exit;
}

if (isset($_GET['do']) && $_GET['do'] == 'cargarGrillaGrafico') {
    $request = $_REQUEST;
    $inicio = $request['start'];
    $registros_por_pagina = $request['length'];
    $encuesta = new Encuesta();
    $array_encuesta = $encuesta->obtenerGrilla($inicio, $registros_por_pagina);


    $data = array();

    if (count($array_encuesta) > 0)
        for ($i = 0; $i < count($array_encuesta); $i++) {

            $row = array();
            $row[] = $array_encuesta[$i]->nombre;
            $row[] = $array_encuesta[$i]->genero;
            $row[] = $array_encuesta[$i]->hobby;
            $row[] = $array_encuesta[$i]->dedicacion_hobby . ' horas';

            $data[] = $row;
        }

    $json_data = array(
        "draw" => intval($request['draw']),
        "recordsTotal" => count($array_encuesta), //cantidad total de registros sin paginar
        "recordsFiltered" => count($array_encuesta), //cantidad total de registros en la paginacion
        "data" => $data
    );
    echo json_encode($json_data);
    exit;
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <?php include_once('head.php'); ?>
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https:///cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
    <style>
        #grilla_wrapper input {
            border: 1px solid black;
            outline: #000;
        }

        .dt-button {
            background-color: #8B0000;
            color: white !important;
            border-radius: 10px;
            padding: 9px;
        }

        .dt-buttons {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="text-uppercase">Estadísticas</h1>
            </div>
            <div class="col-sm-6 col-12 mb-4">
                <div class="card">
                    <canvas id="myChartName" width="600" height="400"></canvas>
                </div>
            </div>
            <div class="col-sm-6 col-12  mb-4">
                <div class="card">
                    <canvas id="oilChart" width="600" class="pb-4" height="400"></canvas>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <div class="card">
                    <canvas id="verticalCanva" width="600" class="pb-4" height="400"></canvas>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <div class="card">
                    <canvas id="verticalDedicacion" width="600" class="pb-4" height="400"></canvas>
                </div>
            </div>
            <div class="col-12 mt-4">
                <table id="grilla" class="display">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Género</th>
                            <th>Hobby</th>
                            <th>Dedicación</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-12 form-group text-left mt-3">
                <button type="button" class="btn btn-primary" onclick="window.location.href='/encuesta/index.php'">Volver a la encuesta</button>
            </div>
        </div>
    </div>

    <script>
        window.onload = fCargarGraficos();

        var dataTable = $('#grilla').DataTable({
            "processing": false,
            "serverSide": true,
            "bFilter": true,
            "bInfo": true,
            "bSearchable": true,
            "pageLength": 25,
            "dom": 'Bfrtip',
            "buttons": [{
                extend: 'excel',
                text: 'Exportar excel',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    //columns: [ 1, 2, 3, 4, 7, 8, 10, 11]
                }
            }],
            "order": [
                [0, "desc"]
            ],
            "ajax": "/encuesta/estadisticas.php?do=cargarGrillaGrafico"
        });

        function fCargarGraficos() {
            $.ajax({
                type: "GET",
                url: "estadisticas.php?do=cargarGraficos",
                data: {},
                async: true,
                dataType: "json",
                success: function(data) {
                    //PREGUNTA 1
                    if (data['data_nombre']) {
                        var labels = []; {
                            for (var i = data['data_nombre'].length - 1; i >= 0; i--) {
                                labels[i] = data['data_nombre'][i].nombre;
                            }
                        }
                        console.log(labels);
                        var arrayDataSets = [];
                        var arrayDataSetsValor = [];
                        var dataSetAux = {};
                        var colors = [];
                        jQuery.each(data['data_nombre'], function(index, value) {


                            colors.push(colorHEX());
                            arrayDataSets.push(value.nombre);
                            arrayDataSetsValor.push(value.cantidad);
                        });

                        console.log(arrayDataSetsValor);

                        var ctx = document.getElementById("myChartName");
                        var pieChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: arrayDataSets,
                                datasets: [{
                                    data: arrayDataSetsValor,
                                    backgroundColor: colors,

                                }],
                            },
                            options: {
                                responsive: true,
                                title: {
                                    display: true,
                                    text: 'Gráficos de nombres (Pregunta 1)'
                                },
                                tooltips: {
                                    enabled: true,
                                    mode: 'single'
                                },
                            },
                            scales: {
                                xAxes: [{}]
                            },
                        });
                    }
                    //Pregunta 2

                    var oilCanvas = document.getElementById("oilChart");

                    var pieChart = new Chart(oilCanvas, {
                        type: 'pie',
                        data: {
                            labels: ['Mujer', 'Hombre'],
                            datasets: [{
                                data: [data['cant_mujeres'].cantidad, data['cant_hombres'].cantidad],
                                backgroundColor: ['#FFD600', '#3EE9BF'],

                            }],
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Cantidad de mujeres y hombres (Pregunta 2)'
                            },
                            tooltips: {
                                enabled: true,
                                mode: 'single'
                            },
                        },
                        scales: {
                            xAxes: [{}]
                        },
                    });

                    //Pregunta 3


                    var labels_hobby = []; {
                        for (var i = data['data_hobby'].length - 1; i >= 0; i--) {
                            labels_hobby[i] = data['data_hobby'][i].nombre;
                        }
                    }

                    var arrayDataSets = [];
                    var colors = [];
                    var dataSetAux = {};
                    jQuery.each(data['data_hobby'], function(index, value) {

                        arrayDataSets.push(parseInt(value.cantidad));
                        colors.push(colorHEX());
                    });


                    var verticalCanva = document.getElementById("verticalCanva");

                    var myChart = new Chart(verticalCanva, {
                        type: 'bar',
                        data: {
                            labels: labels_hobby,
                            datasets: [{
                                data: arrayDataSets,
                                borderColor: colors,
                                backgroundColor: colors,
                            }],
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Gráficos de Hobbies (Pregunta 3)'
                            },
                            legend: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    stacked: true,
                                }],
                                yAxes: [{
                                    stacked: true,
                                    ticks: {
                                        callback: function(valor, index, valores) {
                                            return valor;
                                        },
                                        beginAtZero: true,
                                        min: 0,
                                        // forces step size to be 5 units
                                        stepSize: 1,
                                    }
                                }]
                            }
                        },
                    });

                    //Pregunta 4


                    var labels_dedicacion = []; {
                        for (var i = data['data_dedicacion'].length - 1; i >= 0; i--) {
                            labels_dedicacion[i] = data['data_dedicacion'][i].nombre;
                        }
                    }

                    var arrayDataSets = [];
                    var colors = [];
                    var dataSetAux = {};
                    jQuery.each(data['data_dedicacion'], function(index, value) {

                        arrayDataSets.push(parseInt(value.cantidad));
                        colors.push(colorHEX());
                    });


                    var verticalDedicacion = document.getElementById("verticalDedicacion");

                    var myChartDedicacion = new Chart(verticalDedicacion, {
                        type: 'bar',
                        data: {
                            labels: labels_dedicacion,
                            datasets: [{
                                data: arrayDataSets,
                                borderColor: colors,
                                backgroundColor: colors,
                            }],
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Cantidad de horas dedicada a cada Hobby (Pregunta 4)'
                            },
                            legend: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    stacked: true,
                                }],
                                yAxes: [{
                                    stacked: true,
                                    ticks: {
                                        callback: function(valor, index, valores) {
                                            return valor;
                                        },
                                        beginAtZero: true,
                                        min: 0,
                                        // forces step size to be 5 units
                                        stepSize: 1,
                                    }
                                }]
                            }
                        },
                    });
                }

            });
        }

        function generarLetra() {
            var letras = ["a", "b", "c", "d", "e", "f", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
            var numero = (Math.random() * 15).toFixed(0);
            return letras[numero];
        }

        function colorHEX() {
            var coolor = "";
            for (var i = 0; i < 6; i++) {
                coolor = coolor + generarLetra();
            }
            return "#" + coolor;
        }
    </script>
</body>

</html>