<h2>🔍 Detalhes do Exame</h2>
<p><b>Paciente:</b> <?= $exame['paciente'] ?></p>
<p><b>Médico:</b> <?= $exame['medico_solicitante'] ?></p>
<p><b>Exame:</b> <?= $exame['exame'] ?></p>
<p><b>Etapa:</b> <?= $exame['etapa'] ?></p>

<?php if ($exame['etapa'] != 'laudo pronto'): ?>
    <form method="post" action="index.php?acao=resultado&id=<?= $exame['id'] ?>">
        <textarea name="resultado" placeholder="Digite o resultado do exame"></textarea><br>
        <label><input type="checkbox" name="critico"> Resultado Crítico</label><br>
        <button type="submit">Salvar Resultado</button>
    </form>
<?php else: ?>
    <p><b>Resultado:</b> <?= $exame['resultado'] ?></p>
    <p><b>Crítico:</b> <?= $exame['resultado_critico'] ? 'Sim ⚠️' : 'Não' ?></p>
<?php endif; ?>

<a href="index.php">⬅ Voltar</a>
