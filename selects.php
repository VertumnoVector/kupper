<?php
      require_once 'config.php';
      require_once 'db.php';
      
      $db = new Database;

      $user = $db->select($_POST['mes'],$_POST['ano']);
      echo json_encode($user);

      if (isset($_POST['select'])) {
            $mes = $_POST['mes'];
            $ano = $_POST['ano'];
            if ($db->select($mes,$ano)) {
              echo $util->showMessage('info', 'Registro excluido com sucesso!');
            } else {
              echo $util->showMessage('danger', 'Algo deu errado!');
            }
          }
?>