<?php
require_once __DIR__ . '/../model/LoginModel.php';

class LoginController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new LoginModel($pdo);
    }

    public function login($email, $senha)
    {
        session_start();
        $usuario = $this->model->getUsuarioPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            return true;
        }

        return false;
    }

    public function logout()
    {
        session_start();
        session_destroy();
    }
}
