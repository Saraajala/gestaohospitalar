<?php
class RecuperacaoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Buscar usuário pelo email
    public function buscarUsuarioPorEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar senha do usuário
    public function atualizarSenha($email, $novaSenha)
    {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha=? WHERE email=?");
        return $stmt->execute([$hash, $email]);
    }

    // Salvar token de recuperação
    public function salvarToken($email, $token)
    {
        $stmt = $this->pdo->prepare("INSERT INTO recuperacoes (email, token, usado) VALUES (?, ?, 0)");
        return $stmt->execute([$email, $token]);
    }

    // Buscar email pelo token (válido e não usado)
    public function buscarEmailPorToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT email FROM recuperacoes WHERE token=? AND usado=0 LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetchColumn();
    }

    // Marcar token como usado
    public function marcarTokenComoUsado($token)
    {
        $stmt = $this->pdo->prepare("UPDATE recuperacoes SET usado=1 WHERE token=?");
        return $stmt->execute([$token]);
    }
}
