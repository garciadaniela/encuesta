<?php

include_once('config.php');
include_once('entidades/Hobby.php');
include_once('entidades/Genero.php');
include_once('entidades/Encuesta.php');

$hobby = new Hobby();
$array_hobbies = $hobby->obtenerTodos();

$genero = new Genero();
$array_generos = $genero->obtenerTodos();

$finalizado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['valueForm'] == 'true') {

        $encuesta = new Encuesta();
        $encuesta->cargarRequest($_REQUEST);
        $encuesta->insertar();

        $msg['MSG'] = '¡Encuesta realizada correctamente!';
        $msg['ESTADO'] = 'SUCCESS';

        $finalizado = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta</title>

    <?php include_once('head.php'); ?>
</head>

<body>
    <div class="container py-5" id="form">
        <div class="wrap-contact100 p-sm-5 p-4">
            <form action="" method="POST">
                <input type="hidden" name="valueForm" id="valueForm" value="true">
                <div class="row">
                    <?php if (isset($msg['MSG']) && $msg['MSG'] != "") { ?>
                        <div class="col-12">
                            <div class="alert <?php echo $msg['ESTADO'] == 'ERROR' ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                                <?php echo $msg['MSG']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 mb-3 text-center">
                        <h1>Encuesta</h1>
                    </div>
                    <div class="col-12 mb-3 text-center">
                        <h2>Completá la siguente encuesta</h2>
                    </div>
                    <div class="col-sm-6 col-12 form-group">
                        <label for="txtNombre">1. Nombre:*</label>
                        <input type="text" class="form-control" id="txtNombre" name="txtNombre" required>
                    </div>
                    <div class="col-sm-6 col-12 form-group">
                        <label for="lstGenero">2. Género:*</label>
                        <select name="lstGenero" id="lstGenero" class="form-control selectpicker" data-live-searh="true" required>
                            <option value="" selected disabled>Seleccionar</option>
                            <?php for ($i = 0; $i < count($array_generos); $i++) : ?>
                                <option value="<?php echo $array_generos[$i]->idgenero; ?>"><?php echo $array_generos[$i]->nombre; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-12 form-group">
                        <label for="lstHobby">3. ¿Tienes algún hobby?:*</label>
                        <select name="lstHobby" id="lstHobby" onchange="fActivarDedicacion();" class="form-control selectpicker" data-live-searh="true" required>
                            <option value="" selected disabled>Seleccionar</option>
                            <?php for ($j = 0; $j < count($array_hobbies); $j++) : ?>
                                <option value="<?php echo $array_hobbies[$j]->idhobby; ?>"><?php echo $array_hobbies[$j]->nombre; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-12 form-group" style="display: none;" id="divDedicacion">
                        <label for="lstDedicacion">4. ¿Cuánto tiempo le dedicas al mes? (horas):*</label>
                        <input type="number" name="lstDedicacion" id="lstDedicacion" class="form-control">
                    </div>
                    <div class="col-12 form-group text-center mt-3">
                        <button type="submit" class="btn-enviar">Guardar</button>
                    </div>
                    <?php if ($finalizado) : ?>
                        <div class="col-12 form-group text-right mt-3">
                            <button type="button" class="btn btn-primary" onclick="window.location.href='/encuesta/estadisticas.php'">Siguiente</button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        function fActivarDedicacion() {
            hobby = $('#lstHobby option:selected').val();
            if (hobby > 1) {
                $('#divDedicacion').show();
                $('#lstDedicacion').prop('required', true);
            } else {
                $('#divDedicacion').hide();
                $('#lstDedicacion').prop('required', false);
            }

        }
    </script>
</body>

</html>