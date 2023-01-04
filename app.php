<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["cat"] <> 1){
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
    <script src="vanilla-masker.min.js"></script>
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


    <!-- Add New pacient Modal Start -->
    <div class="modal fade" tabindex="-1" id="addNewUserModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Incluir novo paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-user-form" class="p-2" novalidate>
                        <div class="row mb-3 gx-3 ms-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="switchIdade" onclick="checkPais();">
                                <label class="form-check-label" for="flexSwitchCheckDefault">O paciente é menor de 18
                                    anos</label>
                            </div>
                        </div>
                        <div class="row mb-3 gx-3">

                            <div class="col">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" class="form-control form-control-md"
                                    placeholder="Ex. Luiz Fernando da Mota" required>
                                <div class="invalid-feedback">O nome é obrigatório!</div>
                            </div>
                            <div class="col">
                                <label for="naureza" class="form-label">Plano de saúde</label>
                                <select class="form-select" name="plano" id="plano" aria-label="Default select example">
                                    <option value="" selected disabled>Selecione...</option>
                                    <optgroup label="PLANOS">
                                        <option value="FUSEx">FUSEx</option>
                                    </optgroup>
                                    <optgroup label="PARTICULAR">
                                        <option value="particular">Particular</option>
                                        <option value="outros">Outros acordos</option>
                                    </optgroup>
                                </select>
                                <div class="invalid-feedback">É obrigatório inserir a modalidade de atendimento!</div>
                            </div>
                        </div>
                        <div class="row mb-3 gx-3" id="pais" style="display:none ;">
                            <div class="col">
                                <label for="nome_mae" class="form-label">Nome da mãe (ou responsável)</label>
                                <input type="text" name="nome_mae" id="nome_mae" class="form-control form-control-md"
                                    placeholder="Mãe ou responsável legal">
                                <div class="invalid-feedback">O nome da mãe ou responsavel legal é obrigatório</div>
                            </div>
                            <div class="col">
                                <label for="nome_pai" class="form-label">Nome do pai</label>
                                <input type="text" name="nome_pai" class="form-control form-control-md"
                                    placeholder="Pai">
                                <div class="invalid-feedback">Optativo</div>
                            </div>
                        </div>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="dt_nascimento" class="form-label">Data de nascimento</label>
                                <input type="text" name="dt_nascimento" id="dt_nascimento"
                                    class="form-control form-control-md" placeholder="DD/MM/AAAA" required>
                                <div class="invalid-feedback">Data nascimento obrigatoria!</div>
                            </div>
                        </div>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control form-control-md"
                                    placeholder="Ex. 000.000.000-00" required>
                                <div class="invalid-feedback">Inserir o CPF</div>
                            </div>
                            <div class="col">
                                <label for="rg" class="form-label">RG</label>
                                <input type="text" name="rg" id="rg" class="form-control form-control-md"
                                    placeholder="Apenas numeros" required>
                                <div class="invalid-feedback">Inserir o RG</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereco</label>
                            <input type="text" name="endereco" id="endereco" class="form-control form-control-md"
                                placeholder="Av. Mato Grosso, 321, CPAII, Cuiaba-MT" required>
                            <div class="invalid-feedback">Insira o endereço</div>
                        </div>
                        <div class="mb-3">
                            <label for="contato" class="form-label">Telefone</label>
                            <input type="text" name="contato" id="contato" class="form-control form-control-md"
                                placeholder="Ex. (65) 99335-7752" required>
                            <div class="invalid-feedback">Telefone obrigatorio</div>
                        </div>
                        <hr>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="diaAtendimento" class="form-label">Dia para atendimento</label>
                                <select class="form-select" name="data" id="data" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                    <option value="Sábado">Sábado</option>
                                </select>
                                <div class="invalid-feedback">Escolha um dia</div>
                            </div>
                            <div class="col">
                                <label for="horaAtendimento" class="form-label">Hora para atendimento</label>
                                <select class="form-select" name="hora" id="hora" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                </select>
                                <div class="invalid-feedback">A hora também!</div>
                            </div>
                        </div>
                        <center>
                            <div class="mb-3 ">
                                <input type="submit" value="Cadastrar" class="btn btn-primary btn-block btn-md"
                                    id="add-user-btn">
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add New pacient Modal End -->

    <!-- Edit pacient Modal Start -->
    <div class="modal fade" tabindex="-1" id="editPacientModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar registro de paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-user-form" class="p-2" novalidate>
                        <div class="row mb-3 gx-3">
                            <input type="hidden" name="id" id="idEdit">
                            <div class="col">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" id="nomeEdit" class="form-control form-control-md"
                                    placeholder="Ex. Luiz Fernando da Mota" required>
                                <div class="invalid-feedback">O nome é obrigatório!</div>
                            </div>

                            <div class="col">
                                <label for="naureza" class="form-label">Plano de saúde</label>
                                <select class="form-select" name="plano" id="plano" aria-label="Default select example">
                                    <option value="" selected disabled>Selecione...</option>
                                    <optgroup label="PLANOS">
                                        <option value="FUSEx">FUSEx</option>
                                    </optgroup>
                                    <optgroup label="PARTICULAR">
                                        <option value="particular">Particular</option>
                                        <option value="outros">Outros acordos</option>
                                    </optgroup>
                                </select>
                                <div class="invalid-feedback">É obrigatório inserir a modalidade de atendimento!</div>
                            </div>
                        </div>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" name="cpf" id="cpfEdit" class="form-control form-control-md"
                                    placeholder="Ex. 000.000.000-00" required>
                                <div class="invalid-feedback">Inserir o CPF</div>
                            </div>
                            <div class="col">
                                <label for="rg" class="form-label">RG</label>
                                <input type="text" name="rg" id="rgEdit" class="form-control form-control-md"
                                    placeholder="Apenas numeros" required>
                                <div class="invalid-feedback">Inserir o CPF</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="contato" class="form-label">Endereco</label>
                            <input type="text" name="endereco" id="enderecoEdit" class="form-control form-control-md"
                                placeholder="Av. Mato Grosso, 321, CPAII, Cuiaba-MT" required>
                            <div class="invalid-feedback">Insira o endereço</div>
                        </div>
                        <div class="mb-3">
                            <label for="contato" class="form-label">Telefone</label>
                            <input type="text" name="contato" id="contatoEdit" class="form-control form-control-md"
                                placeholder="Ex. (65) 99335-7752" required>
                            <div class="invalid-feedback">Telefone obrigatorio</div>
                        </div>
                        <hr>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="diaSemanaEdit" class="form-label">Dia para atendimento</label>
                                <select class="form-select" name="diasemana" id="diaSemanaEdit"
                                    aria-label="Default select example" required>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                    <option value="Sábado">Sábado</option>
                                </select>
                                <div class="invalid-feedback">Escolha um dia</div>
                            </div>
                            <div class="col">
                                <label for="horaAtendimento" class="form-label">Hora para atendimento</label>
                                <select class="form-select" name="hora" id="horaEdit"
                                    aria-label="Default select example" required>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                </select>
                                <div class="invalid-feedback">a hora</div>
                            </div>
                        </div>

                        <center>
                            <div class="mb-3 ">
                                <input type="submit" value="Cadastrar" class="btn btn-primary btn-block btn-md"
                                    id="edit-user-btn">
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit pacient Modal End -->

    <!-- Delete pacient Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Atenção, esta ação apagar o paciente do sistema. <br>OBS.: Será impossível
                    retornar esta operação.
                    <hr><b>Deseja realmente continuar? </b>
                </div>
                <div class="modal-footer">
                    <form id="delete-form">
                        <input type="hidden" name="id" id="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Excluir" id="delete-user-btn">
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete pacient Modal end -->


    <!-- Add New schedule Modal Start -->
    <div class="modal fade" tabindex="-1" id="addNewScheduleModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Incluir Atendimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-schedule-form" class="p-2" novalidate>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <select onchange="getPacientData();getProfissionalData();" class="form-select"
                                    name="nomesAtendimento" id="nomesAtendimento" aria-label="Default select example">
                                    <option value="" disabled>Selecione...</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="diasemanaatendimento" class="form-label">Dia da Semana</label>
                                <select class="form-select" name="diasemanaatendimento" id="diasemanaatendimento"
                                    aria-label="Default select example" required disabled>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">Terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                    <option value="Sábado">Sábado</option>
                                </select>
                                <div class="invalid-feedback">VERIFICAR CADASTRO!</div>
                            </div>
                            <div class="col mb-3">
                                <label for="horaatendimento" class="form-label">Hora do atendimento</label>
                                <select class="form-select" name="horaatendimento" id="horaatendimento"
                                    aria-label="Default select example" required disabled>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                </select>
                                <div class="invalid-feedback">VERIFICAR CADASTRO</div>
                            </div>

                            <hr>
                            <div class="mb-3">
                                <label for="especialidadeatendimento" class="form-label">Especialidade</label>
                                <select class="form-select" name="especialidadeatendimento"
                                    id="especialidadeatendimento" aria-label="Default select example" required disabled>
                                    <option selected disabled>Selecione...</option>
                                </select>
                                <div class="invalid-feedback">Escolher uma modalidade</div>
                            </div>
                            <div class="mb-3">
                                <label for="ocorrenciasatendimento" class="form-label">Quantas consultas?</label>
                                <select class="form-select" name="ocorrenciasatendimento" id="ocorrenciasatendimento"
                                    aria-label="Default select example" required>
                                    <option selected disabled>Selecione...</option>
                                    <option value="1ª">1ª Vez</option>
                                    <option value="1">1 Consulta</option>
                                    <option value="2">2 Consultas</option>
                                    <option value="3">3 Consultas</option>
                                    <option value="4">4 Consultas</option>
                                    <option value="5">5 Consultas</option>
                                    <option value="6">6 Consultas</option>
                                    <option value="7">7 Consultas</option>
                                    <option value="8">8 Consultas</option>
                                </select>
                                <div class="invalid-feedback">Tem que escolher quantas vezes</div>
                            </div>
                        </div>
                        <center>
                            <div class="mb-3 ">
                                <input type="submit" value="Cadastrar" class="btn btn-primary btn-block btn-md"
                                    id="add-schedule-btn">
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add New schedule Modal End -->

    <!-- Edit Schedule Modal Start -->
    <div class="modal fade" tabindex="-1" id="editScheduleModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar registro de atendimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-schedule-form" class="p-2" novalidate>
                        <div class="row mb-3 gx-3">
                            <input type="hidden" name="id" id="idScheduleEdit">
                            <div class="col">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" id="nomeScheduleEdit" readonly="readonly"
                                    class="form-control form-control-md" placeholder="Ex. Luiz Fernando da Mota"
                                    required>
                                <div class="invalid-feedback">O nome é obrigatório!</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contato" class="form-label">Contato</label>
                            <input type="text" name="contato" id="contatoScheduleEdit" readonly="readonly"
                                class="form-control form-control-md" placeholder="Ex. (65) 99335-7752" required>
                            <div class="invalid-feedback">Telefone obrigatorio</div>
                        </div>
                        <hr>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="data" class="form-label">Novo dia de atendimento</label>
                                <input type="text" name="data" id="dataScheduleEdit"
                                    class="form-control form-control-md" placeholder="DD/MM/AAAA" required>
                                <div class="invalid-feedback">Inserir nova data de atendimento</div>
                            </div>
                            <div class="col">
                                <label for="horaAtendimento" class="form-label">Hora para atendimento</label>
                                <select class="form-select" name="hora" id="horaScheduleEdit"
                                    aria-label="Default select example" required>
                                    <option value="" selected disabled>Escolha...</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                </select>
                                <div class="invalid-feedback">a hora</div>
                            </div>
                        </div>

                        <center>
                            <div class="mb-3 ">
                                <input type="submit" value="Cadastrar" class="btn btn-primary btn-block btn-md"
                                    id="edit-schedule-btn">
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Schedule Modal End -->

    <!-- Delete schedule Modal -->
    <div class="modal fade" id="confirmDeleteScheduleModal" tabindex="-1"
        aria-labelledby="confirmDeleteScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteScheduleModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Atenção, esta ação irá apagar o atendimento do sistema. <br>OBS.: Será impossível
                    retornar esta operação.
                    <hr><b>Deseja realmente continuar? </b>
                </div>
                <div class="modal-footer">
                    <form id="deleteSchedule-form">
                        <input type="hidden" name="idatendimento" id="idatendimento">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Excluir" id="delete-schedule-btn">
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete schedule Modal end -->



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


    <!-- Add New employee Modal Start -->
    <div class="modal fade" tabindex="-1" id="addNewEmployeeModal">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Incluir novo funcionário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-employee-form" class="p-2" novalidate>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" class="form-control form-control-md"
                                    placeholder="Ex. Luiz Fernando da Mota" required>
                                <div class="invalid-feedback">O nome é obrigatório!</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="categoria" class="form-label">Modalidade</label>
                                <select class="form-select" name="categoria" id="categoria"
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
                                <div class="invalid-feedback">Escolha uma categoria</div>
                            </div>
                        </div>
                        <center>
                            <div class="mb-3 ">
                                <input type="submit" value="Cadastrar" class="btn btn-primary btn-block btn-md"
                                    id="add-employee-btn">
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add New employee Modal End -->

    <!-- Edit employee Modal Start -->
    <div class="modal fade" tabindex="-1" id="editEmployeeModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar registro de profissional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-employee-form" class="p-2" novalidate>
                        <div class="row mb-3 gx-3">
                            <input type="hidden" name="id" id="idEmployeeEdit">
                            <div class="col">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" id="nomeEmployeeEdit"
                                    class="form-control form-control-md" placeholder="Ex. Luiz Fernando da Mota"
                                    required>
                                <div class="invalid-feedback">O nome é obrigatório!</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3 gx-3">
                            <div class="col">
                                <label for="especialidadeatendimento2" class="form-label">Especialidade</label>
                                <select class="form-select" name="categoriaEdit" id="categoriaEdit"
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
                                <div class="invalid-feedback">Escolher uma modalidade</div>
                            </div>
                        </div>
                        <center>
                            <div class="mb-3 ">
                                <input type="submit" value="Editar" class="btn btn-primary btn-block btn-md"
                                    id="edit-employee-btn">
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit pacient Modal End -->

    <!-- Delete profissional Modal -->
    <div class="modal fade" id="confirmDeleteEmployeeModal" tabindex="-1"
        aria-labelledby="confirmDeleteEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteEmployeeModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Atenção, esta ação irá apagar o profissional do sistema. <br>OBS.: Será impossível
                    retornar esta operação.
                    <hr><b>Deseja realmente continuar? </b>
                </div>
                <div class="modal-footer">
                    <form id="deleteEmployee-form">
                        <input type="hidden" name="idemployee" id="idemployee">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Excluir" id="delete-employee-btn">
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete profissional Modal end -->



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
                    <a href="#" class="list-group-item list-group-item-action text-success" data-bs-dismiss="offcanvas"
                        data-bs-toggle="modal" data-bs-target="#addNewUserModal"><span
                            class="material-symbols-outlined bi">person_add</span> Novo paciente</a>
                    <a href="#" class="list-group-item list-group-item-action text-danger" data-bs-dismiss="offcanvas"
                        data-bs-toggle="modal" data-bs-target="#addNewEmployeeModal"><span
                            class="material-symbols-outlined bi">badge</span> Novo profissional</a>
                    <a href="#" onclick="fetchPacients();" class="list-group-item list-group-item-action"
                        data-bs-toggle="modal" data-bs-target="#addNewScheduleModal" data-bs-dismiss="offcanvas"><span
                            class="material-symbols-outlined bi">format_list_bulleted_add</span> Novo atendimento</a>
                    <br>
                    <a href="#" onclick="fetchAllUsers();" data-bs-dismiss="offcanvas"
                        class="list-group-item list-group-item-action text-primary"><span
                            class="material-symbols-outlined bi">account_circle</span> Pacientes cadastrados</a>
                    <a href="#" onclick="fetchAllEmployees();" data-bs-dismiss="offcanvas"
                        class="list-group-item list-group-item-action text-primary"><span
                            class="material-symbols-outlined bi">groups</span> Profissionais cadastrados</a>
                    <br>
                    <a href="#" class="list-group-item list-group-item-action" data-bs-dismiss="offcanvas"
                        data-bs-toggle="modal" data-bs-target="#openFilterModal"><span
                            class="material-symbols-outlined bi">menu_book</span> Agendas Profissionais</a>
                    <a href="#" data-bs-dismiss="offcanvas" class="list-group-item list-group-item-action "><span
                            class="material-symbols-outlined bi">view_agenda</span> Todos os atendimentos</a>
                    <br>
                    <br>
                    <a href="login/register.php" data-bs-dismiss="offcanvas" class="list-group-item list-group-item-action "><span
                            class="material-symbols-outlined bi">manage_accounts</span> Novo usuario Kupper+</a>
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
                            <p class="card-text">Novo paciente</p>
                            <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal"
                                data-bs-target="#addNewUserModal">
                                <span class="material-symbols-outlined bi">person_add</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Novo atendimento</p>
                            <a href="#" onclick="fetchPacients();" class="list-group-item list-group-item-action"
                                data-bs-toggle="modal" data-bs-target="#addNewScheduleModal">
                                <span class="material-symbols-outlined bi">format_list_bulleted_add</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Novo profissional</p>
                            <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal"
                                data-bs-target="#addNewEmployeeModal">
                                <span class="material-symbols-outlined bi">badge</span></a>
                        </div>
                    </div>
                </div>
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
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Pacientes cadastrados</p>
                            <a href="#" onclick="fetchAllUsers();" class="list-group-item list-group-item-action">
                                <span class="material-symbols-outlined bi">account_circle</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Todos os atendimentos</p>
                            <a href="#" onclick="fetchAllSchedule()" class="list-group-item list-group-item-action">
                                <span class="material-symbols-outlined bi">view_agenda</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Profissionais</p>
                            <a href="#" onclick="fetchAllEmployees()" class="list-group-item list-group-item-action">
                                <span class="material-symbols-outlined bi">groups</span></a>
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


    <div class="container-xxl" id='pacientTable' style="display:none">
        <div class="row mt-5">
            <div class="col-lg-12">
                <div id="showAlert"></div>
            </div>
            <div class="d-flex flex-row">
                <div class="p-2 bd-highlight">
                    <button class="btn btn-outline-primary" id="btn-retornar">Retornar</button>
                    <button class="btn btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#addNewUserModal">Novo paciente</button>
                </div>
                <div class="p-2 bd-highlight">
                    <h4 class="text-primary text-center align-middle" id='PacientTableTitle'>tableTitle</h4>
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
                                        <div class="table-responsive table-scroll my-2 mx-2"
                                            data-mdb-perfect-scrollbar="true" style="position: relative; height: 700px">
                                            <table class="table table-striped" id="pacientsTable">
                                                <thead style="background-color: #30702B;">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Prontuario</th>
                                                        <th>Nome</th>
                                                        <th>CPF</th>
                                                        <th>RG</th>
                                                        <th>Endereço</th>
                                                        <th>Contato</th>
                                                        <th>Plano de saúde</th>
                                                        <th>Dia Atend.</th>
                                                        <th>Hora Atend.</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pacients">

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
    <script src="main.js"></script>
    <script src="assets/js/jquery-3.5.1.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="mask.js"></script>
    <script src="assets/evo-calendar/js/evo-calendar.js"></script>

</body>

</html>