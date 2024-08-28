<?php
include('db_connect.php');

// Buscar todas as consultas
$sql_consultas = "
    SELECT c.id, p.nome AS paciente, t.nome AS terapeuta, th.nome AS terapia, c.data_consulta, c.horario_consulta, c.status 
    FROM consultas c
    JOIN pacientes p ON c.paciente_id = p.id
    JOIN terapeutas t ON c.terapeuta_id = t.id
    JOIN terapias th ON c.terapia_id = th.id
    WHERE c.status != 'Finalizado'
    ORDER BY c.data_consulta ASC";

$result_consultas = $conn->query($sql_consultas);

// Buscar consultas finalizadas
$sql_finalizadas = "
    SELECT c.id, p.nome AS paciente, t.nome AS terapeuta, th.nome AS terapia, c.data_consulta, c.horario_consulta, c.observacoes
    FROM consultas c
    JOIN pacientes p ON c.paciente_id = p.id
    JOIN terapeutas t ON c.terapeuta_id = t.id
    JOIN terapias th ON c.terapia_id = th.id
    WHERE c.status = 'Finalizado'";
$result_finalizadas = $conn->query($sql_finalizadas);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Cadastradas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .grid-container-moa {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 50px auto;
        }
        h3 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .grid-moa {
            margin-top: 30px;
            max-height: 400px;
            overflow-y: scroll;
        }
        .grid-moa table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }
        .grid-moa th, .grid-moa td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        .grid-moa th {
            background-color: #f8f9fa;
            color: #333;
        }
        .grid-moa tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .grid-moa tr:hover {
            background-color: #e0f7fa;
        }
    </style>
</head>
<body>
<div class="container mt-3 d-flex justify-content-center">
    <a href="index.php" class="btn btn-primary">Voltar à Página Inicial</a>
</div>

    <div class="grid-container-moa">
        <h3>Consultas Cadastradas</h3>
        <div class="grid-moa">
            <table>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Terapeuta</th>
                        <th>Terapia</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_consultas->num_rows > 0): ?>
                        <?php while($consulta = $result_consultas->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $consulta['paciente']; ?></td>
                                <td><?php echo $consulta['terapeuta']; ?></td>
                                <td><?php echo $consulta['terapia']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($consulta['data_consulta'])); ?></td>
                                <td><?php echo $consulta['horario_consulta']; ?></td>
                                <td><?php echo $consulta['status']; ?></td>
                                <td>
                                    <!-- Botão para abrir modal de observação -->
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalFinalizar<?php echo $consulta['id']; ?>">
                                        Finalizar Atendimento
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de Observações -->
                            <div class="modal fade" id="modalFinalizar<?php echo $consulta['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $consulta['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel<?php echo $consulta['id']; ?>">Finalizar Atendimento - <?php echo $consulta['paciente']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" action="final_atd.php">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="observacao">Observações</label>
                                                    <textarea class="form-control" name="observacoes" id="observacao" rows="3" required></textarea>
                                                </div>
                                                <input type="hidden" name="id" value="<?php echo $consulta['id']; ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Concluir Atendimento</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Nenhuma consulta cadastrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grid de Consultas Finalizadas -->
    <div class="grid-container-moa">
        <h3>Atendimentos Finalizados</h3>
        <div class="grid-moa">
            <table>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Terapeuta</th>
                        <th>Terapia</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_finalizadas->num_rows > 0): ?>
                        <?php while($finalizada = $result_finalizadas->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $finalizada['paciente']; ?></td>
                                <td><?php echo $finalizada['terapeuta']; ?></td>
                                <td><?php echo $finalizada['terapia']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($finalizada['data_consulta'])); ?></td>
                                <td><?php echo $finalizada['horario_consulta']; ?></td>
                                <td><?php echo $finalizada['observacoes']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhum atendimento finalizado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
