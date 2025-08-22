<?php
require_once "../config.php";
require_once "../model/internacoesmodel.php";

$model = new InternacoesModel($pdo);

// Pegar todas internações ativas
$stmt = $pdo->query("SELECT i.*, l.ala, l.numero 
                     FROM internacoes i
                     JOIN leitos l ON i.leito_id = l.id
                     WHERE i.data_alta IS NULL");
$internacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Medicamentos dos Pacientes Internados</h2>

<?php if (count($internacoes) > 0): ?>
    <?php foreach ($internacoes as $internacao): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
            <h3>Paciente #<?= $internacao['paciente_id'] ?> (Internação #<?= $internacao['id'] ?>)</h3>
            <p><b>Leito:</b> <?= $internacao['ala'] ?> - <?= $internacao['numero'] ?></p>
            
            <h4>💊 Medicamentos Prescritos</h4>
            <ul>
                <?php 
                $medicamentos = $model->listarMedicamentos($internacao['id']);
                if (count($medicamentos) > 0): 
                    foreach ($medicamentos as $med): ?>
                        <li>
                            <?= $med['nome'] ?> - <?= $med['dosagem'] ?> 
                            <small>(<?= date("d/m/Y H:i", strtotime($med['data_registro'])) ?>)</small>
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
