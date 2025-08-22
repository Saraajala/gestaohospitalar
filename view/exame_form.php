<form method="POST" action="lista_exames.php">
    <label>Nome do Paciente:</label>
    <input type="text" name="paciente" required><br><br>

    <label>Exame:</label>
    <select name="exame" required>
        <option value="">-- Selecione --</option>
        <option value="Hemograma">Hemograma</option>
        <option value="Raio-X">Raio-X</option>
        <option value="Ultrassom">Ultrassom</option>
        <option value="Ressonância Magnética">Ressonância Magnética</option>
        <option value="Eletrocardiograma">Eletrocardiograma</option>
    </select><br><br>

    <label>Quem solicitou:</label>
    <input type="text" name="solicitante" required><br><br>

    <label>Data do exame:</label>
    <input type="date" name="data_exame" required><br><br>

    <button type="submit" name="salvar">Salvar</button>
</form>
