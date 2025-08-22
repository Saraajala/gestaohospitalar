<?php
// controller/internacoescontroller.php
require_once __DIR__ . '/../config.php';            // define $pdo
require_once __DIR__ . '/../model/internacoesmodel.php';

$model = new InternacoesModel($pdo);

// Roteamento simples por 'action'
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'internar':
                $model->criarInternacao($_POST['paciente_id'], $_POST['leito_id']);
                break;

            case 'trocar_leito':
                $model->trocarLeito($_POST['internacao_id'], $_POST['novo_leito_id']);
                break;

            case 'alta':
                $model->darAlta($_POST['internacao_id'], $_POST['orientacoes'] ?? null);
                break;

            case 'evolucao':
                $model->adicionarEvolucao($_POST['internacao_id'], $_POST['descricao']);
                break;

            case 'exame':
                $model->adicionarExame($_POST['internacao_id'], $_POST['nome_exame'], $_POST['resultado'] ?? null);
                break;

            case 'medicamento':
                $model->adicionarMedicamento($_POST['internacao_id'], $_POST['nome_medicamento'], $_POST['dosagem'] ?? null, $_POST['horario_administracao'] ?? null);
                break;

            case 'procedimento':
                $model->adicionarProcedimento($_POST['internacao_id'], $_POST['descricao']);
                break;
        }
        // Redireciona de volta para a view
        header("Location: ../view/internacoes.php");
        exit;
    } catch (Throwable $e) {
        // Em produção, registre o erro. Aqui exibimos para facilitar o debug.
        die("Erro: " . $e->getMessage());
    }
}
