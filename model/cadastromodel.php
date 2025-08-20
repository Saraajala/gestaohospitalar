<?php
class CadastroModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Cadastro de médico
    public function criarMedico($nome, $email, $telefone, $sexo, $areaDeAtuacao, $senha)
    {
        $sql = "INSERT INTO gestaohospitalar (nome, email, telefone, sexo, area_de_atuacao, senha, tipo) VALUES (?, ?, ?, ?, ?, ?, 'médico')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $telefone, $sexo, $areaDeAtuacao, $senha]);
    }

    // Cadastro de paciente
    public function criarPaciente($nome, $email, $telefone, $sexo, $senha)
    {
        $sql = "INSERT INTO gestaohospitalar (nome, email, telefone, sexo, senha, tipo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $telefone, $sexo, $senha, 'paciente']);
    }

    // Listar todos os cadastros
    public function listarCadastros()
    {
        $sql = "SELECT * FROM gestaohospitalar";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
