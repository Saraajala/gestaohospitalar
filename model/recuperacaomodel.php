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

    // Atualizar senha diretamente
    public function atualizarSenha($email, $novaSenha)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha=? WHERE email=?");
        return $stmt->execute([$novaSenha, $email]);
    }
}
