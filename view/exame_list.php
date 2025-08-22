<h2>📋 Lista de Exames</h2>
<a href="index.php?acao=criar">Novo Exame</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Paciente</th><th>Exame</th><th>Médico</th><th>Etapa</th><th>Ações</th>
    </tr>
    <?php foreach($exames as $e): ?>
    <tr>
        <td><?= $e['id'] ?></td>
        <td><?= $e['paciente'] ?></td>
        <td><?= $e['exame'] ?></td>
        <td><?= $e['medico_solicitante'] ?></td>
        <td><?= $e['etapa'] ?></td>
        <td>
            <a href="index.php?acao=detalhar&id=<?= $e['id'] ?>">Ver</a> |
            <a href="index.php?acao=etapa&id=<?= $e['id'] ?>&etapa=coleta">Coleta</a> |
            <a href="index.php?acao=etapa&id=<?= $e['id'] ?>&etapa=analise">Análise</a> |
            <a href="index.php?acao=etapa&id=<?= $e['id'] ?>&etapa=laudo pronto">Laudo</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
