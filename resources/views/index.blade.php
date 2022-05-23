<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>OEIT - DIRESA</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="description" content="PAGINA DIRESA PASCO">
        <meta name="keywords" content="OEIT DIRESA-PASCO">
        <link rel="shortcut icon" href="./img/logo.jpg">

        <link rel="stylesheet" href="{{ asset('./css/estilos.css') }}"/>
        <!-- link para iconos -->
        <link rel="stylesheet" href="{{ asset('./css/materialdesignicons.css') }}"/>
        <link rel="stylesheet" href="{{ asset('./css/materialdesignicons.min.css') }}"/>

        <!-- bootstrap -->
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

        <!-- JQUERY -->
        {{-- <script src="./js/jquery-3.6.0.min.js"></script> --}}

        <!-- notificaciones toastr -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    </head>
    <body id="mi_body">
        <div class="login-box">
            <img src="img/logo.png" class="avatar" alt="Avatar Image"  >
            <h1>-- BIENVENIDO --</h1>
            <P></P>
            <form name="f1" action="index1.php" method="POST">
                <!-- USERNAME INPUT -->
                <div class="input-wrapper">
                    <input type="text" class="form-control input" name="usuario" id="usuario" placeholder="Ingrese Usuario">
                    <i class="mdi mdi-account input-icon-login font-18 text-white"></i>
                </div>
                <!-- PASSWORD INPUT -->
                <div class="input-wrapper">
                    <input type="password" class="form-control input" name="password" id="password" placeholder="Ingrese Contraseña">
                    <i class="mdi mdi-eye input-icon-login font-18 text-white" id="icon-pas"></i>
                </div>
                <!-- ROL INPUT -->
                <select class="select-login font-13 form-select" name="role" id="role" aria-label="Default select example">
                    <option value="0" selected>Seleccione Rol</option>
                    <option value="ADMINISTRADOR">Administrador</option>
                    <option value="SUPERVISOR">Supervisor</option>
                    <option value="COORDINADOR">Coordinador</option>
                </select>
                <br>
                <div class="col-12 text-center pb-3">
                    <button type="button" name="Buscar" class="btn text-white btn-primary rounded-pill" id="btn_buscar" style="width: 100%; font-size: 13px;">Iniciar Sesion</button>
                </div>
                <div class="row text-center">
                    <div class="col-md-6">
                        <a href="#">Olvide mi contraseña</a>
                    </div>
                    <div class="col-md-6">
                        <a href="#">No tengo cuenta</a>
                    </div>
                </div>
            </form>
        </div>
    </body>
    <script src="./js/records_menu.js"></script>
    <script src="./js/login.js"></script>
</html>