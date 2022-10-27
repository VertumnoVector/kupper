<?php

  require_once 'config.php';
  setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
  date_default_timezone_set('America/Sao_Paulo');

  class Database extends Config {

    // Insert Paciente Into Database
    public function insert($prontuario, $nome, $dt_nascimento, $cpf, $rg, $endereco, $contato, $plano, $diasemana, $hora) {
      $sql = 'INSERT INTO clientes (prontuario, nome, dt_nascimento, cpf, rg, endereco, contato, plano, diasemana, hora) VALUES (:prontuario, :nome, :dt_nascimento, :cpf, :rg, :endereco, :contato, :plano, :diasemana, :hora)';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'prontuario' => $prontuario,
        'nome' => $nome,
        'dt_nascimento' => $dt_nascimento,
        'cpf' => $cpf,
        'rg' => $rg,
        'endereco' => $endereco,
        'contato' => $contato,
        'plano' => $plano,
        'diasemana' => $diasemana,
        'hora' => $hora
      ]);
      return true;
    }

    // Fetch All Clientes From Database
    public function read() {
      $sql = 'SELECT * FROM clientes ORDER BY id DESC';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
    }

    // Fetch Single User From Database
    public function readOne($id) {
      $sql = 'SELECT * FROM clientes WHERE id = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['id' => $id]);
      $result = $stmt->fetch();
      return $result;
    }

    // Update Single User
    public function update($id, $nome, $cpf, $rg, $endereco, $contato, $plano, $diasemana, $hora) {
      $sql = 'UPDATE clientes SET nome = :nome, cpf = :cpf, rg = :rg, endereco = :endereco, contato = :contato, plano = :plano, diasemana = :diasemana, hora = :hora WHERE id = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'nome' => $nome,
        'cpf' => $cpf,
        'rg' => $rg,
        'endereco' => $endereco,
        'contato' => $contato,
        'plano' => $plano,
        'diasemana' => $diasemana,
        'hora' => $hora,
        'id' => $id
      ]);

      return true;
    }

    // Delete User From Database
    public function delete($id) {
      $sql = 'DELETE FROM clientes WHERE id = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['id' => $id]);
      return true;
    }


    public function readPacients() {
      $sql = 'SELECT * FROM clientes ORDER BY nome';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
    }


    public function readSchedule() {
      $sql = 'SELECT atendimento.idatendimento, atendimento.medico, atendimento.tipo, clientes.prontuario, clientes.nome, clientes.contato, atendimento.frequencia,atendimento.estado, clientes.plano, atendimento.dataconsulta, clientes.hora FROM atendimento INNER JOIN clientes ON atendimento.pacienteid = clientes.id ORDER BY id DESC';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
    }


    public function getPacientData($id) {
      $sql = 'SELECT diasemana, hora FROM clientes WHERE id = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['id' => $id]);
      $result = $stmt->fetchAll();
      return $result;
    }

    public function getProfissionalData() {
      $sql = 'SELECT * FROM profissional';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
    }


//READ
    public function readOneSchedule($id) {
      $sql = 'SELECT atendimento.idatendimento, atendimento.medico, atendimento.tipo, clientes.prontuario,clientes.nome, clientes.contato, atendimento.frequencia, clientes.plano, atendimento.dataconsulta FROM atendimento INNER JOIN clientes ON atendimento.pacienteid = clientes.id WHERE idatendimento = :idatendimento';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['idatendimento' => $id]);
      $result = $stmt->fetch();
      return $result;
    }

//CREATE
    public function insertSchedule($pacienteid, $tipo, $dataconsulta, $medico, $estado) {
      $sql = 'SELECT pacienteid, COUNT(pacienteid), dataconsulta, COUNT(dataconsulta) FROM atendimento WHERE pacienteid = :pacienteid AND dataconsulta = :dataconsulta GROUP BY pacienteid, dataconsulta HAVING (COUNT(pacienteid) > 1) AND (COUNT(dataconsulta) > 1)';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'pacienteid' => $pacienteid,
        'dataconsulta' =>$dataconsulta
      ]);
      $result = $stmt->fetchAll();
      if(sizeof($result) > 0){
        return false;

      } else {
        $sql = 'INSERT INTO atendimento (pacienteid, tipo, dataconsulta, medico, estado) VALUES (:pacienteid, :tipo, :dataconsulta, :medico, :estado)';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
          'pacienteid' => $pacienteid,
          'tipo' => $tipo,
          'dataconsulta' => $dataconsulta,
          'medico' => $medico,
          'estado' => $estado
        ]);
      }
      return true;
    }

//UPDATE
    public function updateSchedule($idatendimento, $dataconsulta) {
      $sql = 'UPDATE atendimento SET dataconsulta = :dataconsulta WHERE idatendimento = :idatendimento';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'dataconsulta' => $dataconsulta,
        'idatendimento' => $idatendimento
      ]);
      return true;
    }
    public function updateScheduleFrequence($idatendimento) {
      $sql = 'UPDATE atendimento SET frequencia = :frequencia WHERE idatendimento = :idatendimento';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'frequencia' => 'checked',
        'idatendimento' => $idatendimento
      ]);
      return true;
    }

//DELETE
    public function deleteSchedule($id) {
      $sql = 'DELETE FROM atendimento WHERE idatendimento = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['id' => $id]);
      return true;
    }


//EMPLOYEE
//CREATE
    public function insertEmployee($nome, $categoria) {
      $sql = 'INSERT INTO profissional (nome, cargo) VALUES (:nome, :categoria)';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'nome' => $nome,
        'categoria' => $categoria
      ]);
      return true;
    }
//READ
    public function readEmployee() {
      $sql = 'SELECT * FROM profissional';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
    }

//UPDATE
    public function readOneEmployee($id) {
      $sql = 'SELECT * FROM profissional WHERE id = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['id' => $id]);
      $result = $stmt->fetch();
      return $result;
    }
    
    public function updateEmployee($idemployee, $nome, $cargo) {
      $sql = 'UPDATE profissional SET cargo = :cargo, nome = :nome WHERE id = :id';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        'cargo' => $cargo,
        'nome' => $nome,
        'id' => $idemployee
      ]);
      return true;
    }
//DELETE
  public function deleteEmployee($id) {
    $sql = 'DELETE FROM profissional WHERE id = :id';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return true;
  }

//GET TODAY
  public function readToday() {
    $today = date("Y-m-d");
    $sql = 'SELECT atendimento.medico, atendimento.tipo, clientes.prontuario, clientes.nome, atendimento.estado, clientes.plano, atendimento.dataconsulta FROM atendimento INNER JOIN clientes ON atendimento.pacienteid = clientes.id WHERE atendimento.dataconsulta BETWEEN ? AND ? ORDER BY id DESC';    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      $today.' 07:00:00',
      $today.' 19:00:00'
    ]);
    $result = $stmt->fetchAll();
    return $result;
  }


    //Select by month
    public function select($mes,$ano){
      $sql = 'SELECT * FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE)';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetchAll();
      return $result;
      
    }

    //SUM Values receitas
    public function selectValueReceita($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza IN ("Outdoor e midias","Locação de salas","Locação Bar","Mensalidade Militares","Mensalidade Civis","Locação Salão de Festas","Locação de Churrasqueira","Locação Quiosque e Bosque","Locação Campo de Futebol","Locação Quadra","Dayuse","Consumo de energia","Outros")';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    //SUM Values Despesas
    public function selectValueDespesas($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza IN ("Apoio","Bens","Refeições","Consertos","Devoluções","Despesas Fixas","Despesas judiciais","Pessoal","Gasolina/Estacionamentos","Mnt Contas em banco","Mnt Piscina","Mat. Const. Mão de obra","Mat. Expediente","Mat. Limpeza","Taxas/Impostos")';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }


    public function selectValueOutdoor($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Outdoor e midias"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueSalas($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Locação de salas"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueBar($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Locação Bar"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMensMil($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mensalidade Militares"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMensCivil($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mensalidade Civis"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueSalao($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Locação Salão de Festas"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueChurras($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Aluguel de Churrasqueira"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueQuiosque($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Locação Quiosque e Bosque"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueCampo($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Locação Campo de Futebol"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueQuadra($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Locação Quadra"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueDayuse($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Dayuse"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueEnergia($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Consumo de energia"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueOutros($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Outros"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }





    //DESPESAS
    public function selectValueApoio($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Apoio"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueBens($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Bens"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueRefe($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Refeições"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueCons($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Consertos"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueDevo($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Devoluções"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueDespFixa($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Despesas Fixas"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValuDespJud($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Despesas judiciais"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValuePessoal($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Pessoal"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueGas($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Gasolina/Estacionamentos"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMntBco($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mnt Contas em banco"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMntPis($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mnt Piscina"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMatCons($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mat. Const. Mão de obra"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMatExp($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mat. Expediente"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueMatLimp($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Mat. Limpeza"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    public function selectValueTax($mes,$ano){
      $sql = 'SELECT SUM(valor) FROM users WHERE created_at BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) AND natureza = "Taxas/Impostos"';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$ano.'-'.$mes.'-01',$ano.'-'.$mes.'-31']);
      $result = $stmt->fetch();
      return $result;
      
    }
    
  }
  

?>