<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/model/agendamentomodel.php';

class AgendamentoController
{
    private $agendamentoModel;

    public function __construct($pdo)
    {
        $this->agendamentoModel = new AgendamentoModel($pdo);
    }

    // Agendar consulta
    public function agendar($paciente_id, $medico_id, $data_consulta)
    {
        try {
            $this->agendamentoModel->criarAgendamento($paciente_id, $medico_id, $data_consulta);
            echo "<p>✅ Agendamento realizado com sucesso!</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>".$e->getMessage()."</p>";
        }
    }

  // Listar todos os agendamentos
public function listar()
{
    return $this->agendamentoModel->listar(); 
}


    // Alterar status do agendamento
    public function mudarStatus($id, $status)
    {
        return $this->agendamentoModel->mudarStatus($id, $status);
    }

    // Histórico de um paciente
    public function historico($paciente_id)
    {
        return $this->agendamentoModel->historico($paciente_id);
    }
}
