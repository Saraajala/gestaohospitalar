<?php
require_once __DIR__ . '/../model/PerfilModel.php';

class PerfilController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new PerfilModel($pdo);
    }

    public function mostrarPerfil($usuario_id)
    {
        $usuario = $this->model->getUsuario($usuario_id);

        if (!$usuario) {
            throw new Exception("Usuário não encontrado");
        }

        $usuario['area_de_atuacao'] = '';
        $usuario['data_nascimento'] = '';
        $usuario['plano_saude'] = '';
        $usuario['alergias'] = '';
        $usuario['observacoes'] = '';

        if ($usuario['tipo'] === 'médico') {
            $medico = $this->model->getMedico($usuario_id);
            if (is_array($medico)) $usuario = array_merge($usuario, $medico);
        }

        if ($usuario['tipo'] === 'paciente') {
            $paciente = $this->model->getPaciente($usuario_id);
            if (is_array($paciente)) $usuario = array_merge($usuario, $paciente);
        }

        return $usuario;
    }
}
