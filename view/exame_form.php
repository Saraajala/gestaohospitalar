<form method="POST" action="lista_exames.php">
    <label>Paciente:</label>
    <select name="paciente_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Exame:</label>
    <select name="exame_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($exames as $x): ?>
            <option value="<?= $x['id'] ?>"><?= $x['nome'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Médico solicitante:</label>
    <select name="medico_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($medicos as $m): ?>
            <option value="<?= $m['id'] ?>"><?= $m['nome'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Data do exame:</label>
    <input type="date" name="data_exame" required><br><br>

    <button type="submit" name="salvar">Salvar</button>
</form>
