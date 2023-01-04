<?php 
session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php");
        exit;
    }
  require_once 'db.php';
  require_once 'util.php';
  setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
  date_default_timezone_set('America/Sao_Paulo');
  
  $db = new Database;
  $util = new Util;

  // CRUD PACIENTS 
  // Handle Add New Pacient Ajax Request
  if (isset($_POST['add'])) {
    
    $nome = $util->testInput($_POST['nome']);
    $dt_nascimento = $util->testInput($_POST['dt_nascimento']);
    $cpf = $util->testInput($_POST['cpf']);
    $rg = $util->testInput($_POST['rg']);
    $endereco = $util->testInput($_POST['endereco']);
    $contato = $util->testInput($_POST['contato']);
    $plano = $util->testInput($_POST['plano']);
    $diasemana = $util->testInput($_POST['data']);
    $hora = $util->testInput($_POST['hora']);
    $digits = 3;
    $prontuario = $util->testInput(date('Y').substr($cpf,'0','3').str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT));

    if ($db->insert($prontuario, $nome, $dt_nascimento, $cpf, $rg, $endereco, $contato, $plano, $diasemana, $hora)) {
      echo $util->showMessage('success', 'Novo Paciente inserido');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }

  // Handle Fetch All Pacient Ajax Request
  if (isset($_GET['read'])) {
    $users = $db->read();
    $output = '';
    if ($users) {
      foreach ($users as $row) {
        $output .= '<tr>
                      <td>' . $row['id'] . '</td>
                      <td>' . $row['prontuario'] . '</td>
                      <td>' . $row['nome'] . '</td>
                      <td>' . $row['cpf'] . '</td>
                      <td>' . $row['rg'] . '</td>
                      <td>' . $row['endereco'] . '</td>
                      <td>' . $row['contato'] . '</td>
                      <td>' . $row['plano'] . '</td>
                      <td>' . $row['diasemana'] . '</td>
                      <td>' . $row['hora'] . '</td>
                      <!--<td>' . date_format(date_create($row['created_at']),'d/m/y') . '</td>-->
                      <td> 
                        <!--<a href="print.php?id='. $row['id'] .'" id="" target:_blank' . $row['id'] . '" class="btn btn-primary btn-sm rounded-pill py-0 ">Imprimir</a>  -->
                        <a href="#" id="' . $row['id'] . '" class="btn btn-success btn-sm rounded-pill py-0 editLink" data-bs-toggle="modal" data-bs-target="#editPacientModal">Editar</a>
                        <a href="#" id="' . $row['id'] . '" class="btn btn-danger btn-sm rounded-pill py-0 deleteLink" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">Delete</a>
                      </td>
                    </tr>';
      }
      echo $output;
    } else {
      echo '<tr>
              <td colspan="6">Não existe registro no banco</td>
            </tr>';
    }
    
  }


  // Handle Edit Pacient Ajax Request
  if (isset($_GET['edit'])) {
    $id = $_GET['id'];

    $paciente = $db->readOne($id);
    echo json_encode($paciente);
  }

  // Handle Update User Ajax Request
  if (isset($_POST['update'])) {
    $id = $util->testInput($_POST['id']);
    $nome = $util->testInput($_POST['nome']);
    $plano = $util->testInput($_POST['plano']);
    $cpf = $util->testInput($_POST['cpf']);
    $contato = $util->testInput($_POST['contato']);
    $rg = $util->testInput($_POST['rg']);
    $endereco = $util->testInput($_POST['endereco']);
    $contato = $util->testInput($_POST['contato']);
    $diasemana = $util->testInput($_POST['diasemana']);
    $hora = $util->testInput($_POST['hora']);


    if ($db->update($id, $nome, $cpf, $rg, $endereco, $contato, $plano, $diasemana, $hora)) {
      echo $util->showMessage('success', 'Atualizado com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }

  // Handle Delete User Ajax Request
  if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    if ($db->delete($id)) {
      echo $util->showMessage('info', 'Registro excluido com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }





//CRUD SCHEDULES 

  //CREATE
  if (isset($_GET['readPacients'])){
    $users = $db->readPacients();
    $output = '';
    if($users) {
      foreach ($users as $row){
        $output .=
              '<option value='.$row['id'].'>'. $row['nome'].'</option>';
      }
      echo $output;
    }else{
      '<option value="">Não existe dados no banco</option>';
    }
  }
  
  if (isset($_GET['getPacientData'])){
    $id = $_GET['id'];

    $users = $db->getPacientData($id);
    $output = '';
    if($users) {
      foreach ($users as $row){
        $output .= 

              $row['diasemana'].'.'.
              $row['hora'];
      }
      echo $output;
    }else{
      '<option value="">Não existe dados no banco</option>';
    }
  }
  if (isset($_GET['getProfissionalData'])){

    $users = $db->getProfissionalData();
    $output = '';
    if($users) {
      foreach ($users as $row){
        $output .= 

              $row['cargo'].' - '. $row['nome'].',';
      }
      echo ($output);
    }else{
      '<option value="">Não existe dados no banco</option>';
    }
  }


  if (isset($_POST['addSchedule'])) {
    $pacienteid = $util->testInput($_POST['nomesAtendimento']);
    $diasemana = $util->testInput($_POST['diasemanaatendimento']);
    $hora = $util->testInput($_POST['horaatendimento']);
    $tipo = $util->testInput($_POST['especialidadeatendimento']);
    $especialidade = explode("-",$tipo);
    $medico = substr($tipo, strpos($tipo, "-") + 2); 
    $frequencia = $util->testInput($_POST['ocorrenciasatendimento']);

    switch ($diasemana) {
      case 'Segunda-feira':
        $diasemana = 1;
        break;
      case 'Terça-feira':
        $diasemana = 2;
        break;      
      case 'Quarta-feira':
        $diasemana = 3;
        break;
      case 'Quinta-feira':
        $diasemana = 4;
        break;      
      case 'Sexta-feira':
        $diasemana = 5;
        break;      
      case 'Sábado':
        $diasemana = 6;
        break;      
      
      default:
        $diasemana = 0;
        break;
    }

    function formatData($diasemana,$hora,$i){
      $data='';
      $multiplicador = ($i*7)-7;
      $primeirodia = '';

      if($diasemana > date('N')) {
      	 $weekday = $diasemana - date('N');
         $data = date('Y-m-d', strtotime($data. ' +'.$weekday.' days'));
         

      } else if ($diasemana < date('N') ) {
          $x = date('N') - $diasemana; 
          $data = date('Y-m-d', strtotime($data. '-'.$x.' days'));
          $data = date('Y-m-d', strtotime($data. '+7 days'));

      } else if ($diasemana == date('N')){
          $weekday = 0;
          $data = date('Y-m-d');
      }
        else {
        	$weekday = date('N') - 1;
      }

      if ($i <= 1) {
        $primeirodia = $data.' '.$hora;

      } else if ($i > 1) {
        $primeirodia = date('Y-m-d', strtotime($data. ' + '.$multiplicador.' days')).' '.$hora;
      }
     
	    return $primeirodia;
    }

    if ($frequencia == '1ª') {
      if ($db->insertSchedule($pacienteid, $especialidade[0], formatData($diasemana,$hora,$i),$medico,'1ª Consulta')) {
        echo $util->showMessage('success', 'Novo atendimento inserido');
      } else {
        echo $util->showMessage('danger', 'Algo deu errado!');
      } 
    } else {

    for ($i=1; $i <= $frequencia; $i++) { 
      if ($db->insertSchedule($pacienteid, $especialidade[0], formatData($diasemana,$hora,$i),$medico,'periodico')) {
        echo $util->showMessage('success', 'Novo atendimento inserido com sucesso');
      } else {
        echo $util->showMessage('danger', 'Existe conflito de horário para esta pessoa!');
      } 
    }
  }
  }

  //READ
  if (isset($_GET['readSchedule'])) {
    $users = $db->readSchedule();
    $output = '';
    if ($users) {
      foreach ($users as $row) {
        $output .= '' . ($row['frequencia'] == "checked" ? "<tr class='table-success'>" : (strftime("%Y-%m-%d",strtotime($row['dataconsulta'])) == date('Y-m-d') ? "<tr class='table-warning'>" : (strftime("%Y-%m-%d",strtotime($row['dataconsulta'])) < date('Y-m-d') ? "<tr class='table-danger'>" : "<tr>"))) . '
                      <td>' . $row['idatendimento'] . '</td>
                      <td>' . $row['nome'] . '</td>
                      <td>' . $row['tipo'] . '</td>
                      <td>' . $row['prontuario'] . '</td>
                      <td>' . $row['medico'] . '</td>
                      <td>' . $row['contato'] . '</td>
                      <td style="text-align: center; vertical-align: middle;"> <input type="checkbox" id="'. $row['idatendimento'] .'" class="form-check-input frequencia"'. $row['frequencia'] .'></td>
                      <td>' . $row['estado'] . '</td>
                      <td>' . $row['plano'] . '</td>
                      <td>' . utf8_encode(strftime("%a, %d/%b/%Y as %H:%M",strtotime($row['dataconsulta']))) . '</td>
                      <td>
                        '.((strftime("%Y-%m-%d",strtotime($row['dataconsulta'])) < date('Y-m-d')) && $row['frequencia'] <> "checked" ? "<a href='#' id=". $row['idatendimento'] . "  class='btn btn-primary btn-sm rounded-pill py-0 registerFault'>Faltou</a>" : "<!-- -->").' 
                        <!--<a href="print.php?id='. $row['idatendimento'] .'" id="" target:_blank' . $row['idatendimento'] . '" class="btn btn-primary btn-sm rounded-pill py-0 ">Imprimir</a>  -->
                        <a href="#" id="' . $row['idatendimento'] . '" class="btn btn-success btn-sm rounded-pill py-0 editLink" data-bs-toggle="modal" data-bs-target="#editScheduleModal">Editar</a>
                        <a href="#" id="' . $row['idatendimento'] . '" class="btn btn-danger btn-sm rounded-pill py-0 deleteScheduleLink" data-bs-toggle="modal" data-bs-target="#confirmDeleteScheduleModal">Delete</a>
                      </td>
                    </tr>';
      }
      echo ($output);
    } else {
      echo '<tr>
              <td colspan="6">Não existe registro no banco</td>
            </tr>';
    }
    
  }

  if (isset($_GET['readSchedule2'])) {
    $users = $db->readSchedule2();
    $output = '';
    if ($users) {
      echo json_encode(($users));
    } else {
      echo '<tr>
              <td colspan="6">Não existe registro no banco</td>
            </tr>';
    }
    
  }

  if (isset($_GET['readScheduleCat'])) {
    $users = $db->readScheduleCat($_GET['select']);
    $output = '';
    if ($users) {
      foreach ($users as $row) {
        $output .= '<tr>
                      <td>' . $row['idatendimento'] . '</td>
                      <td>' . $row['nome'] . '</td>
                      <td>' . $row['tipo'] . '</td>
                      <td>' . $row['prontuario'] . '</td>
                      <td>' . $row['medico'] . '</td>
                      <td>' . $row['contato'] . '</td>
                      <td style="text-align: center; vertical-align: middle;"> <input type="checkbox" id="'. $row['idatendimento'] .'" class="form-check-input frequencia"'. $row['frequencia'] .'></td>
                      <td>' . $row['estado'] . '</td>
                      <td>' . $row['plano'] . '</td>
                      <td>' . utf8_encode(strftime("%a, %d/%b/%Y as %H:%M",strtotime($row['dataconsulta']))) . '</td>
                      <td> 
                        <!--<a href="print.php?id='. $row['idatendimento'] .'" id="" target:_blank' . $row['idatendimento'] . '" class="btn btn-primary btn-sm rounded-pill py-0 ">Imprimir</a>  -->
                        <a href="#" id="' . $row['idatendimento'] . '" class="btn btn-success btn-sm rounded-pill py-0 editLink" data-bs-toggle="modal" data-bs-target="#editScheduleModal">Editar</a>
                        <a href="#" id="' . $row['idatendimento'] . '" class="btn btn-danger btn-sm rounded-pill py-0 deleteScheduleLink" data-bs-toggle="modal" data-bs-target="#confirmDeleteScheduleModal">Delete</a>
                      </td>
                    </tr>';
      }
      echo ($output);
    } else {
      echo '<tr>
              <td colspan="6">Não existe registro no banco</td>
            </tr>';
    }
    
  }

  
  if (isset($_GET['readScheduleCat2'])) {
    $users = $db->readScheduleCat2($_GET['select']);
    $output = '';
    if ($users) {
      echo json_encode($users);
    } else {
      echo '<tr>
              <td colspan="6">Não existe registro no banco</td>
            </tr>';
    }
    
  }



  //UPDATE
  if (isset($_GET['editSchedule'])) {
    $id = $_GET['id'];

    $paciente = $db->readOneSchedule($id);
    echo json_encode($paciente);
  }

  if (isset($_GET['editScheduleFrequence'])) {

    $idatendimento = $util->testInput($_GET['id']);

    if ($db->updateScheduleFrequence($idatendimento)) {
      echo $util->showMessage('success', 'Atualizado com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }
  
  if (isset($_GET['registerFault'])) {

    $idatendimento = $util->testInput($_GET['id']);

    if ($db->registerFault($idatendimento)) {
      echo $util->showMessage('success', 'Atualizado com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }


// Handle Update Schedule Ajax Request
  if (isset($_POST['updateSchedule'])) {
    $idatendimento = $util->testInput($_POST['id']);
    $data = $util->testInput($_POST['data']);
    $hora = $util->testInput($_POST['hora']);
    $dataconsulta = date('Y-m-d H:i',strtotime($data.' '.$hora));


    if ($db->updateSchedule($idatendimento, $dataconsulta)) {
      echo $util->showMessage('success', 'Atualizado com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }



  // Handle Delete Schedule Ajax Request
  if (isset($_GET['deleteSchedule'])) {
    $id = $_GET['id'];
    if ($db->deleteSchedule($id)) {
      echo $util->showMessage('info', 'Registro excluido com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }






//employees
//CREATE
if (isset($_POST['addEmployee'])) {
    
  $nome = $util->testInput($_POST['nome']);
  $categoria = $util->testInput($_POST['categoria']);

  if ($db->insertEmployee($nome, $categoria)) {
    echo $util->showMessage('success', 'Novo Profissional registrado');
  } else {
    echo $util->showMessage('danger', 'Algo deu errado!');
  }
}

//READ
if (isset($_GET['readEmployee'])) {
  $users = $db->readEmployee();
  $output = '';
  if ($users) {
    foreach ($users as $row) {
      $output .= '<tr>
                    
                    <td>' . $row['nome'] . '</td>
                    <td>' . $row['cargo'] . '</td>
                    <td> 
                      <!--<a href="print.php?id='. $row['id'] .'" id="" target:_blank' . $row['id'] . '" class="btn btn-primary btn-sm rounded-pill py-0 ">Imprimir</a>  -->
                      <a href="#" id="' . $row['id'] . '" class="btn btn-success btn-sm rounded-pill py-0 editLink" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">Editar</a>
                      <a href="#" id="' . $row['id'] . '" class="btn btn-danger btn-sm rounded-pill py-0 deleteEmployeeLink" data-bs-toggle="modal" data-bs-target="#confirmDeleteEmployeeModal">Delete</a>
                    </td>
                  </tr>';
    }
    echo $output;
  } else {
    echo '<tr>
            <td colspan="6">Não existe registro no banco</td>
          </tr>';
  }
  
}

  //UPDATE
  if (isset($_GET['editEmployee'])) {
    $id = $_GET['id'];

    $employee = $db->readOneEmployee($id);
    echo json_encode($employee);
  }

  if (isset($_POST['updateEmployee'])) {
    $idemployee = $util->testInput($_POST['id']);
    $nome = $util->testInput($_POST['nome']);
    $cargo = $util->testInput($_POST['categoriaEdit']);


    if ($db->updateEmployee($idemployee, $nome, $cargo)) {
      echo $util->showMessage('success', 'Atualizado com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }

  // DELETE
  if (isset($_GET['deleteEmployee'])) {
    $id = $_GET['id'];
    if ($db->deleteEmployee($id)) {
      echo $util->showMessage('info', 'Registro excluido com sucesso!');
    } else {
      echo $util->showMessage('danger', 'Algo deu errado!');
    }
  }



  //GET TODAY
  if (isset($_GET['readToday'])) {
    $users = $db->readToday();
    $output = '';
    if ($users) {
      foreach ($users as $row) {
        $output .= '<tr>
                      <td>' . $row['nome'] . '</td>
                      <td>' . $row['tipo'] . '</td>
                      <td>' . $row['prontuario'] . '</td>
                      <td>' . $row['medico'] . '</td>
                      <td>' . $row['estado'] . '</td>
                      <td>' . $row['plano'] . '</td>
                      <td>' . utf8_encode(strftime("%a, %d/%b/%Y as %H:%M",strtotime($row['dataconsulta']))) . '</td>
                    </tr>';
      }
      echo $output;
    } else {
      echo '<tr>
              <td colspan="6"><img src="assets/img/mad.gif" alt="" height=100 width=100></img> Não tem atendimento hoje 💔</td>
            </tr>';
    }
  }












  if (isset($_GET['selectVal'])){
    $valueGeral = $db->selectValueReceita($_GET['mes'],$_GET['ano']);
    $valueGeralDesp = $db->selectValueDespesas($_GET['mes'],$_GET['ano']);

    

    $valueOutdoor = $db->selectValueOutdoor($_GET['mes'],$_GET['ano']);
    $valueSalas = $db->selectValueSalas($_GET['mes'],$_GET['ano']);
    $valueBar = $db->selectValueBar($_GET['mes'],$_GET['ano']);
    $valueMensMil = $db->selectValueMensMil($_GET['mes'],$_GET['ano']);
    $valueMensCivil = $db->selectValueMensCivil($_GET['mes'],$_GET['ano']);
    $valueSalao = $db->selectValueSalao($_GET['mes'],$_GET['ano']);
    $valueChurras = $db->selectValueChurras($_GET['mes'],$_GET['ano']);
    $valueQuiosque = $db->selectValueQuiosque($_GET['mes'],$_GET['ano']);
    $valueCampo = $db->selectValueCampo($_GET['mes'],$_GET['ano']);
    $valueQuadra = $db->selectValueQuadra($_GET['mes'],$_GET['ano']);
    $valueDayuse = $db->selectValueDayuse($_GET['mes'],$_GET['ano']);
    $valueEnergia = $db->selectValueEnergia($_GET['mes'],$_GET['ano']);
    $valueOutros = $db->selectValueOutros($_GET['mes'],$_GET['ano']);

    $ValueApoio = $db->selectValueApoio($_GET['mes'],$_GET['ano']);
    $ValueBens = $db->selectValueBens($_GET['mes'],$_GET['ano']);
    $ValueRefe = $db->selectValueRefe($_GET['mes'],$_GET['ano']);
    $ValueCons = $db->selectValueCons($_GET['mes'],$_GET['ano']);
    $ValueDevo = $db->selectValueDevo($_GET['mes'],$_GET['ano']);
    $ValueDespFixa = $db->selectValueDespFixa($_GET['mes'],$_GET['ano']);
    $ValuDespJud = $db->selectValuDespJud($_GET['mes'],$_GET['ano']);
    $ValuePessoal = $db->selectValuePessoal($_GET['mes'],$_GET['ano']);
    $ValueGas = $db->selectValueGas($_GET['mes'],$_GET['ano']);
    $ValueMntBco = $db->selectValueMntBco($_GET['mes'],$_GET['ano']);
    $ValueMntPis = $db->selectValueMntPis($_GET['mes'],$_GET['ano']);
    $ValueMatCons = $db->selectValueMatCons($_GET['mes'],$_GET['ano']);
    $ValueMatExp = $db->selectValueMatExp($_GET['mes'],$_GET['ano']);
    $ValueMatLimp = $db->selectValueMatLimp($_GET['mes'],$_GET['ano']);
    $ValueTax = $db->selectValueTax($_GET['mes'],$_GET['ano']);

    switch ($_GET['mes']) {
      case 1:
         $_GET['mes'] = 'JANEIRO';
          break;
      case 2:
         $_GET['mes'] = 'FEVEREIRO';
          break;
      case 3:
         $_GET['mes'] = 'MARÇO';
          break;
      case 4:
         $_GET['mes'] = 'ABRIL';
          break;
      case 5:
         $_GET['mes'] = 'MAIO';
          break;
      case 6:
         $_GET['mes'] = 'JUNHO';
          break;
      case 7:
         $_GET['mes'] = 'JULHO';
          break;
      case 8:
         $_GET['mes'] = 'AGOSTO';
          break;
      case 9:
         $_GET['mes'] = 'SETEMBRO';
          break;
      case 10:
         $_GET['mes'] = 'OUTUBRO';
          break;
      case 11:
         $_GET['mes'] = 'NOVEMBRO';
          break;
      case 12:
         $_GET['mes'] = 'DEZEMBRO';
          break;
   }
    //totais
    echo $util->showMessage('info', 'TOTAL ARRECADADO EM '.$_GET['mes'].' DE '.$_GET['ano'].': R$ '.json_encode(str_replace('""',' ',number_format($valueGeral["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'TOTAL DE GASTO EM '.$_GET['mes'].' DE '.$_GET['ano'].': R$ '.json_encode(str_replace('""',' ',number_format($valueGeralDesp["SUM(valor)"],2))));

    
    echo '<br><p class="h4"> RECEITAS</p>';
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Outdoor e midias: R$ '.json_encode(str_replace('""',' ',number_format($valueOutdoor["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação de salas: R$ '.json_encode(str_replace('""',' ',number_format($valueSalas["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação Bar: R$ '.json_encode(str_replace('""',' ',number_format($valueBar["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Mensalidade Militares: R$ '.json_encode(str_replace('""',' ',number_format($valueMensMil["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Mensalidade Civis: R$ '.json_encode(str_replace('""',' ',number_format($valueMensCivil["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação Salão de Festas: R$ '.json_encode(str_replace('""',' ',number_format($valueSalao["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação de Churrasqueira: R$ '.json_encode(str_replace('""',' ',number_format($valueChurras["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação Quiosque e Bosque: R$ '.json_encode(str_replace('""',' ',number_format($valueQuiosque["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação Campo de Futebol: R$ '.json_encode(str_replace('""',' ',number_format($valueCampo["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Locação Quadra: R$ '.json_encode(str_replace('""',' ',number_format($valueQuadra["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Dayuse: R$ '.json_encode(str_replace('""',' ',number_format($valueDayuse["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Consumo de energia: R$ '.json_encode(str_replace('""',' ',number_format($valueEnergia["SUM(valor)"],2))));
    echo $util->showMessage('info', 'Total de renda em '.$_GET['mes'].' de '.$_GET['ano'].' com Outros: R$ '.json_encode(str_replace('""',' ',number_format($valueOutros["SUM(valor)"],2))));

    //despesas
    echo '<br><p class="h4"> DESPESAS</p>';
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Apoio: R$ '.json_encode(str_replace('""',' ',number_format($ValueApoio["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Bens: R$ '.json_encode(str_replace('""',' ',number_format($ValueBens["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Refeições: R$ '.json_encode(str_replace('""',' ',number_format($ValueRefe["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Consertos: R$ '.json_encode(str_replace('""',' ',number_format($ValueCons["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Devoluções: R$ '.json_encode(str_replace('""',' ',number_format($ValueDevo["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Despesas Fixas: R$ '.json_encode(str_replace('""',' ',number_format($ValueDespFixa["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Despesas judiciais: R$ '.json_encode(str_replace('""',' ',number_format($ValuDespJud["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Pessoal: R$ '.json_encode(str_replace('""',' ',number_format($ValuePessoal["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Gasolina/Estacionamentos: R$ '.json_encode(str_replace('""',' ',number_format($ValueGas["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Mnt Contas em banco: R$ '.json_encode(str_replace('""',' ',number_format($ValueMntBco["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Mnt Piscina: R$ '.json_encode(str_replace('""',' ',number_format($ValueMntPis["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Mat. Const. Mão de obra: R$ '.json_encode(str_replace('""',' ',number_format($ValueMatCons["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Mat. Expediente: R$ '.json_encode(str_replace('""',' ',number_format($ValueMatExp["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Mat. Limpeza: R$ '.json_encode(str_replace('""',' ',number_format($ValueMatLimp["SUM(valor)"],2))));
    echo $util->showMessage('danger', 'Total de despesa em '.$_GET['mes'].' de '.$_GET['ano'].' com Taxas/Impostos: R$ '.json_encode(str_replace('""',' ',number_format($ValueTax["SUM(valor)"],2))));
  }


  


?>