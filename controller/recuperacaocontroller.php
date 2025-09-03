<?php
require_once __DIR__ . '/../model/recuperacaomodel.php';

class RecuperacaoController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new RecuperacaoModel($pdo);
    }

    // Solicitar recuperação de senha: gera token e salva
    public function solicitarRecuperacao($email)
    {
        $usuario = $this->model->buscarUsuarioPorEmail($email);
        if (!$usuario) {
            throw new Exception("Email não cadastrado.");
        }

        // Gerar token único
        $token = bin2hex(random_bytes(16));

        // Salvar token na tabela recuperacoes
        $this->model->salvarToken($email, $token);

        // Retornar link de redefinição (para enviar por e-mail)
        return "http://localhost/gestaohospitalar/redefinir_senha.php?token=" . $token;
    }

    // Buscar email válido pelo token
    public function buscarEmailPorToken($token)
    {
        return $this->model->buscarEmailPorToken($token);
    }

    // Redefinir senha usando token
    public function redefinirSenha($token, $novaSenha)
    {
        $email = $this->model->buscarEmailPorToken($token);
        if (!$email) {
            throw new Exception("Token inválido ou expirado.");
        }

        // Atualiza senha
        $this->model->atualizarSenha($email, $novaSenha);

        // Marca token como usado
        $this->model->marcarTokenComoUsado($token);

        return true;
    }
}
