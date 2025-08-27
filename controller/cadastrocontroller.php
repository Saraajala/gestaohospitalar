<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\model\cadastromodel.php';

class CadastroController
{
    private $cadastroModel;

    public function __construct($pdo)
    {
        $this->cadastroModel = new CadastroModel($pdo);
    }

    public function criarCadastro($nome, $email, $telefone, $sexo, $areaDeAtuacao, $senha, $tipo)
    {
        $usuario_id = $this->cadastroModel->criarUsuario($nome, $email, $telefone, $sexo, $senha, $tipo);

        if ($tipo === "médico") {
            $this->cadastroModel->criarMedico($usuario_id, $areaDeAtuacao);
        } elseif ($tipo === "paciente") {
            $this->cadastroModel->criarPaciente($usuario_id);
        }

        // Salva na sessão para login automático
        session_start();
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['usuario_tipo'] = $tipo;

        return $usuario_id;
    }

    public function listarCadastros()
    {
        return $this->cadastroModel->listarCadastros();
    }
}
