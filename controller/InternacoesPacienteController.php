<?php
require_once __DIR__ . '/../model/InternacoesPacienteModel.php';

class InternacoesPacienteController {
    private $model;

    public function __construct($pdo) {
        $this->model = new InternacoesPacienteModel($pdo);
    }

    /** Listar internações de um paciente **/
    public function listar($paciente_id) {
        return $this->model->listarInternacoes($paciente_id);
    }

    /** Listar evoluções de uma internação **/
    public function listarEvolucoes($internacao_id) {
        return $this->model->listarEvolucoes($internacao_id);
    }

    /** Listar exames de uma internação **/
    public function listarExames($internacao_id) {
        return $this->model->listarExames($internacao_id);
    }

    /** Criar internação **/
    public function criarInternacao($paciente_id, $leito_id) {
        return $this->model->criarInternacao($paciente_id, $leito_id);
    }

    /** Adicionar evolução **/
    public function adicionarEvolucao($internacao_id, $descricao) {
        return $this->model->adicionarEvolucao($internacao_id, $descricao);
    }

    /** Adicionar exame **/
    public function adicionarExame($internacao_id, $nome_exame, $resultado=null) {
        return $this->model->adicionarExame($internacao_id, $nome_exame, $resultado);
    }

    /** Dar alta **/
    public function darAlta($internacao_id, $orientacoes = null) {
        return $this->model->darAlta($internacao_id, $orientacoes);
    }
}
