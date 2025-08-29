<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/model/cadastromodel.php';

class CadastroController
{
    private $cadastroModel;

    public function __construct($pdo)
    {
        $this->cadastroModel = new CadastroModel($pdo);
    }

    public function criarCadastro($nome, $email, $telefone, $sexo, $areaDeAtuacao, $senha, $tipo)
    {
        return $this->cadastroModel->criarUsuario($nome, $email, $telefone, $sexo, $senha, $tipo, $areaDeAtuacao);
    }

    public function listarCadastros()
    {
        return $this->cadastroModel->listarCadastros();
    }
}
