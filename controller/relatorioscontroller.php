<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/model/relatoriosmodel.php';

class RelatoriosController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new RelatoriosModel($pdo);
    }

    public function getEspecialidades()
    {
        return $this->model->getEspecialidades();
    }

    public function gerarRelatorio($data_inicio, $data_fim, $especialidade)
    {
        return $this->model->getRelatorioConsultas($data_inicio, $data_fim, $especialidade);
    }

    public function getIndicadores($relatorios)
    {
        $total_consultas = count($relatorios);
        $pacientes_unicos = count(array_unique(array_column($relatorios, 'paciente')));
        return [
            'total_consultas' => $total_consultas,
            'pacientes_unicos' => $pacientes_unicos
        ];
    }
}
