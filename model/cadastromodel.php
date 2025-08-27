<?php
class CadastroModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function emailExiste($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Cria um usuário genérico
    public function criarUsuario($nome, $email, $telefone, $sexo, $senha, $tipo)
    {
        if ($this->emailExiste($email)) {
            throw new Exception("Este e-mail já está cadastrado.");
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, telefone, sexo, senha, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $telefone, $sexo, $senhaHash, $tipo]);

        return $this->pdo->lastInsertId();
    }

    // Cria registro na tabela medicos
    public function criarMedico($usuario_id, $areaDeAtuacao, $crm = '')
    {
        $stmt = $this->pdo->prepare("INSERT INTO medicos (usuario_id, area_de_atuacao, crm) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $areaDeAtuacao, $crm]);
    }

    // Cria registro na tabela pacientes
    public function criarPaciente($usuario_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO pacientes (usuario_id) VALUES (?)");
        $stmt->execute([$usuario_id]);
    }

    // Lista todos os usuários
    public function listarCadastros()
    {
        $stmt = $this->pdo->query("SELECT id, nome, email, telefone, sexo, tipo FROM usuarios ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
