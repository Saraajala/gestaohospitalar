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
        //* lógica para definir se o cadastrado é médico ou paciente *//
        if ($tipo == "médico") {
            $this->cadastroModel->criarMedico($nome, $email, $telefone, $sexo, $areaDeAtuacao, $senha);
            echo "Cadastro de médico realizado com sucesso!";
        } elseif ($tipo == "paciente") {
            $this->cadastroModel->criarPaciente($nome, $email, $telefone, $sexo, $senha);
            echo "Cadastro de paciente realizado com sucesso!";
        } else {
            echo "Cadastro não autorizado.";
        }
    }

    public function listarCadastros()
    {
        return $this->cadastroModel->listarCadastros();
    }

    public function exibirListaCadastros()
    {
        $cadastros = $this->cadastroModel->listarCadastros();
        include 'C:\Turma2\xampp\htdocs\gestaohospitalar\view\listar.php';
    }
}
