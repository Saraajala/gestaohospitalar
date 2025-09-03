<h2>📋 Minhas Consultas (Médico)</h2>

<?php if(!empty($consultas)): ?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Médico</th>
        <th>Paciente</th>
        <th>Data / Hora</th>
        <th>Status</th>
    </tr>
    <?php foreach($consultas as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= $c['medico_nome'] ?></td>
        <td><?= $c['paciente_nome'] ?></td>
        <td><?= $c['data_hora'] ?></td>
        <td><?= $c['status'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Você ainda não possui consultas agendadas.</p>
<?php endif; ?>
