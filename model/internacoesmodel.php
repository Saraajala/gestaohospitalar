<?php
class InternacoesModel {
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

    public function listarLeitos() {
        $sql = "SELECT * FROM leitos ORDER BY id";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Internar **/
    public function criarInternacao($paciente_id, $leito_id) {
        $chk = $this->pdo->prepare("SELECT status FROM leitos WHERE id=?");
        $chk->execute([$leito_id]);
        if ($chk->fetchColumn() !== 'livre') {
            throw new Exception("Leito indisponível.");
        }

        $this->pdo->beginTransaction();
        try {
            $ins = $this->pdo->prepare("
                INSERT INTO internacoes (paciente_id, leito_id, data_entrada, status)
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

    /** Internações ativas **/
    public function listarInternacoesAtivas() {
        $sql = "SELECT i.id, i.paciente_id, u.nome AS paciente,
                       i.leito_id, l.numero_leito AS leito, l.status AS status_leito,
                       i.data_entrada
                  FROM internacoes i
                  JOIN usuarios u ON u.id = i.paciente_id
                  JOIN leitos l ON l.id = i.leito_id
                 WHERE i.data_alta IS NULL
              ORDER BY i.data_entrada DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterInternacao($id) {
        $st = $this->pdo->prepare("SELECT * FROM internacoes WHERE id=?");
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    /** Troca de leito **/
    public function trocarLeito($internacao_id, $novo_leito_id) {
        $chk = $this->pdo->prepare("SELECT status FROM leitos WHERE id=?");
        $chk->execute([$novo_leito_id]);
        if ($chk->fetchColumn() !== 'livre') {
            throw new Exception("Novo leito não está livre.");
        }

        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare("SELECT leito_id FROM internacoes WHERE id=? FOR UPDATE");
            $st->execute([$internacao_id]);
            $leito_antigo = $st->fetchColumn();
            if (!$leito_antigo) {
                throw new Exception("Internação não encontrada.");
            }

            $upInt = $this->pdo->prepare("UPDATE internacoes SET leito_id=? WHERE id=?");
            $upInt->execute([$novo_leito_id, $internacao_id]);

            $this->pdo->prepare("UPDATE leitos SET status='livre' WHERE id=?")->execute([$leito_antigo]);
            $this->pdo->prepare("UPDATE leitos SET status='ocupado' WHERE id=?")->execute([$novo_leito_id]);

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /** Alta **/
    public function darAlta($internacao_id, $orientacoes = null) {
    $this->pdo->beginTransaction();
    try {
        // 1️⃣ Atualiza a internação para encerrada
        $stmt = $this->pdo->prepare("
            UPDATE internacoes
            SET data_alta = NOW(), orientacoes = ?, status = 'encerrada'
            WHERE id = ?
        ");
        $stmt->execute([$orientacoes, $internacao_id]);

        // 2️⃣ Pega o leito associado
        $stmt = $this->pdo->prepare("SELECT leito_id FROM internacoes WHERE id=?");
        $stmt->execute([$internacao_id]);
        $leito_id = $stmt->fetchColumn();

        // 3️⃣ Se existir leito, coloca em limpeza com data_limpeza = NOW()
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
            INSERT INTO evolucoes (internacao_id, descricao, data_registro)
            VALUES (?, ?, NOW())
        ");
        $st->execute([$internacao_id, $descricao]);
    }

    public function listarEvolucoes($internacao_id) {
        $st = $this->pdo->prepare("
            SELECT * FROM evolucoes
            WHERE internacao_id=?
            ORDER BY data_registro DESC
        ");
        $st->execute([$internacao_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Exames **/
    public function adicionarExame($internacao_id, $nome_exame, $resultado=null) {
        $st = $this->pdo->prepare("
            INSERT INTO exames (internacao_id, exame, resultado, data_registro)
            VALUES (?, ?, ?, NOW())
        ");
        $st->execute([$internacao_id, $nome_exame, $resultado]);
    }

    public function listarExames($internacao_id) {
        $st = $this->pdo->prepare("
            SELECT id, internacao_id, exame AS nome_exame, resultado, data_registro
            FROM exames
            WHERE internacao_id=?
            ORDER BY data_registro DESC
        ");
        $st->execute([$internacao_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Medicamentos **/
    public function adicionarMedicamento($internacao_id, $nome_medicamento, $dosagem=null, $horario=null) {
        $st = $this->pdo->prepare("
            INSERT INTO medicamentos (internacao_id, nome_medicamento, dosagem, horario_administracao, data_registro)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $st->execute([$internacao_id, $nome_medicamento, $dosagem, $horario]);
    }

    public function listarMedicamentos($internacao_id) {
        $st = $this->pdo->prepare("
            SELECT id, internacao_id, nome_medicamento AS nome, dosagem, horario_administracao, data_registro
            FROM medicamentos
            WHERE internacao_id=?
            ORDER BY data_registro DESC
        ");
        $st->execute([$internacao_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Procedimentos **/
    public function adicionarProcedimento($internacao_id, $descricao) {
        $st = $this->pdo->prepare("
            INSERT INTO procedimentos (internacao_id, descricao, data_registro)
            VALUES (?, ?, NOW())
        ");
        $st->execute([$internacao_id, $descricao]);
    }

    public function listarProcedimentos($internacao_id) {
        $st = $this->pdo->prepare("
            SELECT * FROM procedimentos
            WHERE internacao_id=?
            ORDER BY data_registro DESC
        ");
        $st->execute([$internacao_id]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
