<?php
class PerfilModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUsuario($id) {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, telefone, sexo, tipo FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMedico($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT area_de_atuacao FROM medicos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPaciente($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT data_nascimento, plano_saude, alergias, observacoes FROM pacientes WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
