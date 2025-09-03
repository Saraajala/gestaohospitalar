<?php
class InternacoesPacienteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /** Pacientes **/
    public function listarPacientes() {
        $sql = "SELECT id, nome FROM usuarios WHERE tipo='paciente' ORDER BY nome";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Leitos **/
    public function listarLeitosLivres() {
        $sql = "SELECT id, numero_leito FROM leitos WHERE status='livre' ORDER BY numero_leito";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Criar internação **/
    public function criarInternacao($paciente_id, $leito_id) {
        $chk = $this->pdo->prepare("SELECT status FROM leitos WHERE id=?");
        $chk->execute([$leito_id]);
        if ($chk->fetchColumn() !== 'livre') {
            throw new Exception("Leito indisponível.");
        }

        $this->pdo->beginTransaction();
        try {
            $ins = $this->pdo->prepare("
                INSERT INTO internacoes_paciente (paciente_id, leito_id, data_entrada, status)
                VALUES (?, ?, NOW(), 'ativo')
            ");
            $ins->execute([$paciente_id, $leito_id]);

            $upd = $this->pdo->prepare("UPDATE leitos SET status='ocupado' WHERE id=?");
            $upd->execute([$leito_id]);

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /** Listar internações de um paciente **/
    public function listarInternacoes($paciente_id) {
        $st = $this->pdo->prepare("
            SELECT i.id, i.paciente_id, u.nome AS paciente,
                   i.leito_id, l.numero_leito AS leito, l.status AS status_leito,
                   i.data_entrada, i.data_alta, i.orientacoes
              FROM internacoes_paciente i
              JOIN usuarios u ON u.id = i.paciente_id
              JOIN leitos l ON l.id = i.leito_id
             WHERE i.paciente_id = ?
          ORDER BY i.data_entrada DESC
        ");
        $st->execute([$paciente_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Listar apenas internações ativas **/
    public function listarInternacoesAtivas() {
        $sql = "SELECT i.id, i.paciente_id, u.nome AS paciente,
                       i.leito_id, l.numero_leito AS leito, l.status AS status_leito,
                       i.data_entrada
                  FROM internacoes_paciente i
                  JOIN usuarios u ON u.id = i.paciente_id
                  JOIN leitos l ON l.id = i.leito_id
                 WHERE i.data_alta IS NULL
              ORDER BY i.data_entrada DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Obter internação **/
    public function obterInternacao($id) {
        $st = $this->pdo->prepare("SELECT * FROM internacoes_paciente WHERE id=?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    /** Obter paciente pela internação **/
    public function obterPacientePorInternacao($internacao_id) {
        $st = $this->pdo->prepare("SELECT paciente_id FROM internacoes_paciente WHERE id=?");
        $st->execute([$internacao_id]);
        return $st->fetchColumn();
    }

    /** Dar alta **/
    public function darAlta($internacao_id, $orientacoes = null) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("
                UPDATE internacoes_paciente
                SET data_alta = NOW(), orientacoes = ?, status = 'encerrada'
                WHERE id = ?
            ");
            $stmt->execute([$orientacoes, $internacao_id]);

            $stmt = $this->pdo->prepare("SELECT leito_id FROM internacoes_paciente WHERE id=?");
            $stmt->execute([$internacao_id]);
            $leito_id = $stmt->fetchColumn();

            if ($leito_id) {
                $stmt = $this->pdo->prepare("
                    UPDATE leitos 
                    SET status = 'limpeza', data_limpeza = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$leito_id]);
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /** Evoluções **/
    public function adicionarEvolucao($internacao_id, $descricao) {
        $st = $this->pdo->prepare("
            INSERT INTO evolucoes_paciente (internacao_id, descricao, data_registro)
            VALUES (?, ?, NOW())
        ");
        $st->execute([$internacao_id, $descricao]);
    }

    public function listarEvolucoes($internacao_id) {
        $st = $this->pdo->prepare("
            SELECT * FROM evolucoes_paciente
            WHERE internacao_id=?
            ORDER BY data_registro DESC
        ");
        $st->execute([$internacao_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Exames **/
    public function adicionarExame($internacao_id, $nome_exame, $resultado=null) {
        $paciente_id = $this->obterPacientePorInternacao($internacao_id);
        $st = $this->pdo->prepare("
            INSERT INTO exames_paciente (internacao_id, paciente_id, exame, resultado, data_registro)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $st->execute([$internacao_id, $paciente_id, $nome_exame, $resultado]);
    }

    public function listarExames($internacao_id) {
        $st = $this->pdo->prepare("
            SELECT id, internacao_id, paciente_id, exame AS nome_exame, resultado, data_registro
            FROM exames_paciente
            WHERE internacao_id=?
            ORDER BY data_registro DESC
        ");
        $st->execute([$internacao_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
