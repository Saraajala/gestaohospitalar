<?php
class CadastroModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Função para verificar se o e-mail já existe
    private function emailExiste($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Cadastro de médico
    public function criarMedico($nome, $email, $telefone, $sexo, $area_de_atuacao, $senha)
    {
        if ($this->emailExiste($email)) {
            throw new Exception("Este e-mail já está cadastrado.");
        }

        $sql = "INSERT INTO usuarios (nome, email, telefone, sexo, area_de_atuacao, senha, tipo) 
                VALUES (?, ?, ?, ?, ?, ?, 'médico')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $telefone, $sexo, $area_de_atuacao, $senha]);
    }

    // Cadastro de paciente
    public function criarPaciente($nome, $email, $telefone, $sexo, $senha)
    {
        if ($this->emailExiste($email)) {
            throw new Exception("Este e-mail já está cadastrado.");
        }

        $sql = "INSERT INTO usuarios (nome, email, telefone, sexo, senha, tipo) 
                VALUES (?, ?, ?, ?, ?, 'paciente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $telefone, $sexo, $senha]);
    }

    // Listar todos os cadastros
    public function listarCadastros()
    {
        $sql = "SELECT * FROM usuarios";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
