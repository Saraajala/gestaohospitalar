<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\model/recuperacaomodel.php';

class RecuperacaoController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new RecuperacaoModel($pdo);
    }

    // Verifica se o usuário existe
    public function usuarioExiste($email)
    {
        return $this->model->buscarUsuarioPorEmail($email) !== null;
    }

    // Redefine a senha
    public function redefinirSenha($email, $novaSenha)
    {
        return $this->model->atualizarSenha($email, $novaSenha);
    }
}
