<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="assets/evo-calendar/css/evo-calendar.css" />
    <link rel="stylesheet" type="text/css" href="assets/evo-calendar/css/evo-calendar.midnight-blue.css" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="assets/js/dselect.js"></script>
    <style>
    html,
    body,
    .intro {
        height: 100%;
    }

    table td,
    table th {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    thead th {
        color: #fff;
    }

    .card {
        border-radius: .5rem;
    }

    .table-scroll {
        border-radius: .5rem;
    }

    .table-scroll table thead th {
        font-size: 0.8rem;
    }

    thead {
        top: 0;
        position: sticky;
    }

    .bi {
        vertical-align: -0.150em;
    }

    tbody {
        font-size: 0.8rem;
    }

    ::-webkit-scrollbar {
        width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" data-bs-toggle="offcanvas" href="#offcanvasMenu" role="button"
                aria-controls="offcanvasMenu">
                <img src="assets/img/k.png" alt="" width="24" height="30" class="d-inline-block align-text-top">
                Kupper+
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" data-bs-toggle="offcanvas"
                            href="#offcanvasMenu">Opções</a>
                    </li>
                    <li class="nav-item">

                    </li>
                </ul>
                <form class="d-flex">
                    <a href="login/logout.php" class=" btn btn-outline-danger ml-3">Sair da conta</a>
                </form>
            </div>
        </div>
    </nav>


    <!-- Filter agenda modal -->
    <div class="modal fade " tabindex="-1" id="openFilterModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selecione a área profissional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filter-form" class="p-2 needs-validation" novalidate>
                        <div class="row mb-3 gx-3">
                            <label for="mes" class="form-label">Agendas profissionais</label>
                            <select class="form-select" name="categoriaFilter" id="categoriaFilter"
                                aria-label="Default select example" required>
                                <option value="" selected disabled>Escolha...</option>
                                <option value="Psicoterapia individual Adulto">Psicoterapia individual Adulto
                                </option>
                                <option value="Psicoterapia individual Criança">Psicoterapia individual Criança
                                </option>
                                <option value="Psicoterapia Casal">Psicoterapia Casal</option>
                                <option value="Fonoaudiologia Adulto">Fonoaudiologia Adulto</option>
                                <option value="Fonoaudiologia Criança">Fonoaudiologia Criança</option>
                                <option value="Nutricionista Adulto">Nutricionista Adulto</option>
                                <option value="Nutricionista Criança">Nutricionista Criança</option>
                                <option value="Terapia ocupacional">Terapia ocupacional</option>
                            </select>
                            <div class="invalid-feedback">
                                Precisa de uma área
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="submit" value="Filtrar" class="btn btn-primary btn-block btn-md"
                                id="filter-btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter agenda end -->





    <div class="container">
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
            <div class="offcanvas-header d-flex">
                <img src="assets/img/logo.png" width="300px" heigth="50px" alt="">
                <!--<h5 class="offcanvas-title" id="offcanvasMenuLabel">Opções</h5>-->
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="mb-3">
                    Escolha uma das opções abaixo para começar
                </div>
                <div class="list-group justify-content-center">
                    <a href="#" class="list-group-item list-group-item-action" data-bs-dismiss="offcanvas"
                        data-bs-toggle="modal" data-bs-target="#openFilterModal"><span
                            class="material-symbols-outlined bi">menu_book</span> Agendas Profissionais</a>
                </div>
            </div>
            <div style="max-height: 60px; min-height: 60px" class="d-flex justify-content-center w-100">
                <div class="align-self-center text-center">
                    <span class="fs-6 fw-light text-black-50">Programado com ❤️ por</span><br>
                    <span class="fs-6 fw-light text-black-50">Luiz Fernando da Mota Carvalho</span>
                </div>
            </div>

        </div>
    </div>

    <!--WELCOME SCREEN -->
    <div class="col-lg-12">
        <div id="showAlert"></div>
    </div>
    <div class="row-cols-1 my-auto justify-content-center align-items-center" id="welcome">
        <figure class="figure text-center ">
            <img src="assets/img/welcome.jpg" class="figure-img img-fluid rounded " alt="...">
            <figcaption class="figure-caption text-center">Acesse uma das opções para começar. Ou clique no
                <b>Kupper+</b>
            </figcaption>
        </figure>
        <div class="container text-center">
            <div class="row justify-content-md-center">
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Agendas </p>
                            <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal"
                                data-bs-target="#openFilterModal">
                                <span class="material-symbols-outlined bi">menu_book</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-xxl" id='todayTable' style="display: block">
            <div class="row mt-5 d-flex justify-content-between align-items-center">
                <div class="col-lg-12 ">
                    <div class="p-2 bd-highlight">
                        <h4 class="text-primary text-center align-middle" id='todayTableTitle'>tableTitle
                        </h4>
                    </div>
                </div>
            </div>
            <section class="intro">
                <div class="bg-image h-100 mb-2" style="background-color: #f5f7fa;">
                    <div class="mask d-flex align-items-center h-100">
                        <div class="container-xxl">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive table-scroll  my-2 mx-2"
                                                data-mdb-perfect-scrollbar="true"
                                                style="position: relative; height: 400px">
                                                <table class="table table-striped mb-0" id="today">
                                                    <thead style="background-color: #002d72;">
                                                        <tr>
                                                            <th>Paciente</th>
                                                            <th>Tipo</th>
                                                            <th>Prontuario</th>
                                                            <th>Profissional</th>
                                                            <th>Estado</th>
                                                            <th>Plano de saúde</th>
                                                            <th>Data e hora</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="todayContent">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
    <!--WELCOME SCREEN -->

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist" style="display: none">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                type="button" role="tab" aria-controls="nav-home" aria-selected="true">Tabela</button>
            <button class="nav-link" id="nav-agenda-tab" data-bs-toggle="tab" data-bs-target="#nav-agenda" type="button"
                role="tab" aria-controls="nav-agenda" aria-selected="false">Agenda</button>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
            tabindex="0">
            <div class="container-xxl" id='ScheduleTable' style="display: none">
                <div class="row mt-5 d-flex justify-content-between align-items-center">
                    <div class="col-lg-12">
                        <div id="showAlert"></div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-outline-primary" id="btn-retornar2">Retornar</button>
                            <button class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#addNewScheduleModal">Novo atendimento</button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <h4 class="text-primary text-center align-middle" id='ScheduleTableTitle'>tableTitle
                            </h4>
                        </div>
                    </div>
                </div>
                <section class="intro">
                    <div class="bg-image h-100" style="background-color: #f5f7fa;">
                        <div class="mask d-flex align-items-center h-100">
                            <div class="container-xxl">
                                <div class="row justify-content-center">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="table-responsive table-scroll  my-2 mx-2"
                                                    data-mdb-perfect-scrollbar="true"
                                                    style="position: relative; height: 700px">
                                                    <table class="table table-striped mb-0" id="Schedules">
                                                        <thead style="background-color: #002d72;">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Paciente</th>
                                                                <th>Tipo</th>
                                                                <th>Prontuario</th>
                                                                <th>Profissional</th>
                                                                <th>Contato</th>
                                                                <th>Frequencia</th>
                                                                <th>Estado</th>
                                                                <th>Plano de saúde</th>
                                                                <th>Data e hora</th>
                                                                <th>Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="scheduleContent">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-agenda" role="tabpanel" aria-labelledby="nav-agenda-tab" tabindex="0">
            <div class="container-xxl mt-2 mask d-flex align-items-center h-100">
                <div id="calendar" style=" border-radius: 25px"></div>
            </div>

        </div>
    </div>






    <div class="container-xxl" id='employeeTable' style="display: none">
        <div class="row mt-5 d-flex justify-content-between align-items-center">
            <div class="col-lg-12">
                <div id="showAlert"></div>
            </div>
            <div class="d-flex flex-row">
                <div class="p-2 bd-highlight">
                    <button class="btn btn-outline-primary" id="btn-retornar3">Retornar</button>
                    <button class="btn btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#addNewEmployeeModal">Novo profissonal</button>

                </div>
                <div class="p-2 bd-highlight">
                    <h4 class="text-primary text-center align-middle" id='employeeTableTitle'>tableTitle
                    </h4>
                </div>
            </div>
        </div>
        <section class="intro">
            <div class="bg-image h-100" style="background-color: #f5f7fa;">
                <div class="mask d-flex align-items-center h-100">
                    <div class="container-xxl">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="table-responsive table-scroll  my-2 mx-2"
                                            data-mdb-perfect-scrollbar="true" style="position: relative; height: 700px">
                                            <table class="table table-striped mb-0" id="employees">
                                                <thead style="background-color: #000;">
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Cargo</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="employeeContent">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- CAT LOADING -->
    <div class="modal " tabindex="-1" id="loading">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Carregando conteúdo</h5>
                </div>
                <div class="modal-body">
                    <img src="assets/img/loading.gif" class="figure-img img-fluid rounded " alt="...">
                    <figcaption class="figure-caption text-center">Consultando o banco de dados...
                    </figcaption>
                </div>
            </div>
        </div>
    </div>
    <!-- CAT LOADING -->

    <!-- ERROR -->
    <div class="modal fade" id="conflictModal" name="conflictModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="conflictModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="conflictModalLabel">Erro!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="assets/img/erro.gif" class="rounded mx-auto d-block" alt="Erro">
                    <p class="text text-center">Existe conflito de horário para esta pessoa! Verifique os atendimentos.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ERROR -->


    <script>
    function checkPais() {
        if (document.getElementById('switchIdade').checked) {
            document.getElementById('pais').style.display = 'flex';
            document.getElementById('nome_mae').required = true;
        } else {
            document.getElementById('pais').style.display = 'none';
            document.getElementById('nome_mae').required = false;
        }
    }
    </script>
    <script src="consult.js"></script>
    <script src="assets/js/jquery-3.5.1.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/evo-calendar/js/evo-calendar.js"></script>

</body>

</html>