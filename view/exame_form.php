<?php
session_start();
require_once '../config.php';
require_once '../controller/examespacientecontroller.php';
require_once '../model/examespacientemodel.php';

// Para teste: definir manualmente o médico logado
$medico_logado = 1; // coloque o ID de um médico existente no banco
$controller = new ExamesPacienteController($pdo);

// Lista de exames disponíveis (fixa)
$exames_disponiveis = [
    ['id'=>1, 'exame'=>'Hemograma completo'],
    ['id'=>2, 'exame'=>'Raio-X torácico'],
    ['id'=>3, 'exame'=>'Ultrassom abdominal'],
    ['id'=>4, 'exame'=>'Eletrocardiograma'],
    ['id'=>5, 'exame'=>'Exame de glicemia']
];

// Buscar pacientes
$pacientes = $pdo->query("
    SELECT p.id, u.nome 
    FROM pacientes p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY u.nome
")->fetchAll(PDO::FETCH_ASSOC);

// Salvar exame se o form foi enviado
if (isset($_POST['salvar'])) {
    $paciente_id = $_POST['paciente_id'];
    $exame_id    = $_POST['exame_id'];
    $data_exame  = $_POST['data_exame'];

    // Pegar o nome do exame selecionado do array fixo
    $exame_nome = null;
    foreach($exames_disponiveis as $x){
        if($x['id'] == $exame_id){
            $exame_nome = $x['exame'];
            break;
        }
    }

    if ($exame_nome) {
        $salvo = $controller->criarExame($paciente_id, $medico_logado, $exame_nome, $data_exame);
        if ($salvo) {
            echo "<p style='color:green'>Exame agendado com sucesso!</p>";
        } else {
            echo "<p style='color:red'>Erro ao agendar exame.</p>";
        }
    } else {
        echo "<p style='color:red'>Exame inválido.</p>";
    }
}
?>

<h2>Agendar Exame (Teste)</h2>
<form method="POST">
    <!-- Pacientes -->
    <label>Paciente:</label>
    <select name="paciente_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Exames disponíveis -->
    <label>Exame:</label>
    <select name="exame_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($exames_disponiveis as $x): ?>
            <option value="<?= $x['id'] ?>"><?= htmlspecialchars($x['exame']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Data do exame:</label>
    <input type="date" name="data_exame" required><br><br>

    <button type="submit" name="salvar">Salvar</button>
</form>

<hr>

<h2>Exames Agendados (Teste)</h2>
<?php
$exames_agendados = $controller->listarPorMedico($medico_logado);

if ($exames_agendados):
?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Paciente</th>
        <th>Exame</th>
        <th>Data</th>
        <th>Resultado</th>
    </tr>
    <?php foreach($exames_agendados as $e): ?>
    <tr>
        <td><?= htmlspecialchars($e['paciente_nome']) ?></td>
        <td><?= htmlspecialchars($e['exame']) ?></td>
        <td><?= htmlspecialchars($e['data_exame']) ?></td>
        <td><?= htmlspecialchars($e['resultado'] ?? '-') ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Nenhum exame agendado.</p>
<?php endif; ?>
