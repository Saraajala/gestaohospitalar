<?php
// view/medicamentos_pacientes.php
require_once "../config.php";
require_once "../model/internacoesmodel.php";

$model = new InternacoesModel($pdo);

// Pegar todas internações ativas
$stmt = $pdo->query("
    SELECT i.id AS internacao_id, i.paciente_id, i.data_entrada, u.nome AS paciente,
           l.numero_leito, l.ala
    FROM internacoes i
    JOIN usuarios u ON i.paciente_id = u.id
    JOIN leitos l ON i.leito_id = l.id
    WHERE i.data_alta IS NULL
    ORDER BY i.data_entrada DESC
");
$internacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Medicamentos dos Pacientes Internados</h1>

<?php if (!empty($internacoes)): ?>
    <?php foreach ($internacoes as $i): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
            <h3>Paciente: <?= htmlspecialchars($i['paciente']) ?> (Internação #<?= $i['internacao_id'] ?>)</h3>
            <p><b>Leito:</b> Ala <?= htmlspecialchars($i['ala']) ?> - Leito <?= htmlspecialchars($i['numero_leito']) ?></p>
            <p><b>Internado desde:</b> <?= date("d/m/Y H:i", strtotime($i['data_entrada'])) ?></p>
            
            <h4>💊 Medicamentos Prescritos</h4>
            <ul>
                <?php 
                $medicamentos = $model->listarMedicamentos($i['internacao_id']);
                if (!empty($medicamentos)): 
                    foreach ($medicamentos as $md): ?>
                        <li>
                            <b><?= htmlspecialchars($md['nome']) ?></b>
                            <?php if($md['dosagem']): ?> (<?= htmlspecialchars($md['dosagem']) ?>)<?php endif; ?>
                            <?php if($md['horario_administracao']): ?> — <?= htmlspecialchars($md['horario_administracao']) ?><?php endif; ?>
                            <small>(<?= date("d/m/Y H:i", strtotime($md['data_registro'])) ?>)</small>
                        </li>
                    <?php endforeach; 
                else: ?>
                    <li><i>Nenhum medicamento prescrito.</i></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p><b>Nenhum paciente internado no momento.</b></p>
<?php endif; ?>
