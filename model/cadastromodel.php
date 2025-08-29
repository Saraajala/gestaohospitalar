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

    public function criarUsuario($nome, $email, $telefone, $sexo, $senha, $tipo, $areaDeAtuacao = null)
    {
        if ($this->emailExiste($email)) {
            throw new Exception("Este e-mail já está cadastrado.");
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere na tabela usuarios
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, email, telefone, sexo, senha, tipo) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nome, $email, $telefone, $sexo, $senhaHash, $tipo]);

        $usuario_id = $this->pdo->lastInsertId();

        // Médicos
        if ($tipo === 'médico') {
            if (!$areaDeAtuacao) {
                throw new Exception("Selecione a área de atuação para médicos.");
            }
            $this->criarMedico($usuario_id, $areaDeAtuacao);
        }

        // Pacientes
        if ($tipo === 'paciente') {
            $this->criarPaciente($usuario_id);
        }

        return $usuario_id;
    }

    private function criarMedico($usuario_id, $areaDeAtuacao)
    {
        $stmt = $this->pdo->prepare("INSERT INTO medicos (usuario_id, area_de_atuacao) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $areaDeAtuacao]);
    }

    private function criarPaciente($usuario_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO pacientes (usuario_id) VALUES (?)");
        $stmt->execute([$usuario_id]);
    }

    public function listarCadastros()
    {
        $stmt = $this->pdo->query("SELECT id, nome, email, telefone, sexo, tipo FROM usuarios ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
