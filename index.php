<?php
include('db_connect.php');

// Query para buscar todos os terapeutas
$sql_terapeutas = "SELECT nome, especialidade, dias_disponiveis, turnos_disponiveis FROM terapeutas";
$result_terapeutas = $conn->query($sql_terapeutas);

// Query para buscar todas as consultas que não estão finalizadas
$sql_consultas = "
    SELECT c.id, c.data_consulta, c.horario_consulta, p.nome AS paciente_nome, t.nome AS terapeuta_nome, c.status
    FROM consultas c
    JOIN pacientes p ON c.paciente_id = p.id
    JOIN terapeutas t ON c.terapeuta_id = t.id
    WHERE c.status != 'Finalizado'
    ORDER BY c.data_consulta ASC";

$result_consultas = $conn->query($sql_consultas);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terapeutas e Consultas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #2e7d32;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 36px;
        }

        .navbar {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        .navbar li {
            display: inline;
            margin: 0 15px;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            font-size: 18px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .section-header {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #2e7d32;
        }

        .table-container {
            margin-top: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        .table th {
            background-color: #f8f9fa;
            color: #333;
        }

        .status-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .status-btn {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin: 0 3px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .status-btn:hover {
            transform: scale(1.1);
        }

        .status-aberto.active {
            background-color: #4caf50;
        }

        .status-agendado.active {
            background-color: #ffeb3b;
        }

        .status-dia.active {
            background-color: #f44336;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #2e7d32;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1><i>Agendamento de Pacientes e Terapeutas</i></h1>
        <br>
        <nav>
            <ul class="navbar">
                <li><a href="index.php">Início</a></li>
                <li><a href="consultas_cads.php">Agenda de Consultas</a></li>
                <li><a href="cadastro_consulta.php">Cadastrar Consulta</a></li>
                <li><a href="cadastro_terapias.php">Cadastrar Terapia</a></li>
                <li><a href="cadastro_paciente.php">Cadastrar Paciente</a></li>
                <li><a href="cadastro_terapeuta.php">Cadastrar Terapeuta</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <!-- Seção para exibir consultas cadastradas -->
        <section>
            <h2 class="section-header">Consultas</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Terapeuta</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_consultas->num_rows > 0): ?>
                            <?php while ($consulta = $result_consultas->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $consulta['paciente_nome']; ?></td>
                                    <td><?php echo $consulta['terapeuta_nome']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($consulta['data_consulta'])); ?></td>
                                    <td><?php echo $consulta['horario_consulta']; ?></td>
                                    <td>
                                        <div class="status-container">
                                            <div class="status-btn status-aberto <?php echo $consulta['status'] == 'Aberto' ? 'active' : ''; ?>"></div>
                                            <div class="status-btn status-agendado <?php echo $consulta['status'] == 'Agendado' ? 'active' : ''; ?>"></div>
                                            <div class="status-btn status-dia <?php echo $consulta['status'] == 'Consulta do Dia' ? 'active' : ''; ?>"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Nenhuma consulta cadastrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Seção para exibir terapeutas com status -->
        <section>
            <h2 class="section-header">Terapeutas (Disponibilidade)</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            <th>Dias Disponíveis</th>
                            <th>Turnos Disponíveis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_terapeutas->num_rows > 0): ?>
                            <?php while ($terapeuta = $result_terapeutas->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $terapeuta['nome']; ?></td>
                                    <td><?php echo $terapeuta['especialidade']; ?></td>
                                    <td><?php echo $terapeuta['dias_disponiveis']; ?></td>
                                    <td><?php echo $terapeuta['turnos_disponiveis']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Nenhum terapeuta disponível.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 <a href="https://github.com/Moa-fernandes" target="_blank" style="color: inherit; text-decoration: none;">Moa Fernandes</a></p>
    </footer>

</body>

</html>

<?php
$conn->close();
?>