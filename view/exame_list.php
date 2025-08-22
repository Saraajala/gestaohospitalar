<h2>📋 Lista de Exames</h2>
<a href="exame_form.php">➕ Novo Exame</a>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th><th>Paciente</th><th>Exame</th><th>Solicitante</th><th>Data</th><th>Etapa</th><th>Ações</th>
</tr>
<?php if(!empty($exames)): ?>
    <?php foreach($exames as $e): ?>
    <tr>
        <td><?= $e['id'] ?></td>
        <td><?= $e['paciente'] ?></td>
        <td><?= $e['exame'] ?></td>
        <td><?= $e['solicitante'] ?></td>
        <td><?= $e['data_exame'] ?></td>
        <td><?= $e['etapa'] ?></td>
        <td>
            <a href="exame_detalhe.php?id=<?= $e['id'] ?>">Ver</a>
            <form style="display:inline" method="post">
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <button type="submit" name="etapa" value="coleta">Coleta</button>
                <button type="submit" name="etapa" value="analise">Análise</button>
                <button type="submit" name="etapa" value="laudo pronto">Laudo</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="7">Nenhum exame cadastrado</td></tr>
<?php endif; ?>
</table>
