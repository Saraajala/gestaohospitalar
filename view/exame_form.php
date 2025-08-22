<h2>➕ Novo Pedido de Exame</h2>
<form method="post" action="index.php?acao=salvar">
    Paciente: <input type="text" name="paciente" required><br>
    Médico Solicitante: <input type="text" name="medico" required><br>
    Exame: <input type="text" name="exame" required><br>
    Data Pedido: <input type="date" name="data_pedido" required><br>
    <button type="submit">Salvar</button>
</form>
