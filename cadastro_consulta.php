<?php
include('db_connect.php');

// Verificar a conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Depurar os dados enviados pelo formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
}

// Buscar todos os pacientes, terapeutas e terapias disponíveis
$sql_pacientes = "SELECT id, nome FROM pacientes";
$result_pacientes = $conn->query($sql_pacientes);

$sql_terapeutas = "SELECT id, nome FROM terapeutas WHERE dias_disponiveis IS NOT NULL";
$result_terapeutas = $conn->query($sql_terapeutas);

$sql_terapias = "SELECT id, nome FROM terapias";
$result_terapias = $conn->query($sql_terapias);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paciente_id'])) {
    // Preparando a consulta para inserção segura
    $stmt = $conn->prepare("INSERT INTO consultas (paciente_id, terapeuta_id, terapia_id, data_consulta, horario_consulta, status) VALUES (?, ?, ?, ?, ?, 'Aberto')");

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param("iiiss", $paciente_id, $terapeuta_id, $terapia_id, $data_consulta, $horario_consulta);

    // Definindo os valores
    $paciente_id = $_POST['paciente_id'];
    $terapeuta_id = $_POST['terapeuta_id'];
    $terapia_id = $_POST['terapia_id'];
    $data_consulta = $_POST['data_consulta'];
    $horario_consulta = $_POST['horario_consulta'];

    // Executando a consulta
    if ($stmt->execute()) {
        echo "Consulta agendada com sucesso!";
    } else {
        echo "Erro ao agendar a consulta: " . $stmt->error;
    }

    // Fechando o statement
    $stmt->close();
}

// Atualizar o status da consulta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status']) && isset($_POST['id'])) {
    // Preparando a consulta para atualização segura
    $stmt = $conn->prepare("UPDATE consultas SET status = ? WHERE id = ?");

    if (!$stmt) {
        die("Erro ao preparar a consulta de atualização: " . $conn->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param("si", $status, $consulta_id);

    // Definindo os valores
    $status = $_POST['status'];
    $consulta_id = $_POST['id'];

    // Executando a consulta
    if ($stmt->execute()) {
        echo "Status atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o status: " . $stmt->error;
    }

    // Fechando o statement
    $stmt->close();
}

// Buscar todas as consultas
$sql_consultas = "SELECT c.id, p.nome AS paciente, t.nome AS terapeuta, th.nome AS terapia, c.data_consulta, c.horario_consulta, c.status 
                  FROM consultas c
                  JOIN pacientes p ON c.paciente_id = p.id
                  JOIN terapeutas t ON c.terapeuta_id = t.id
                  JOIN terapias th ON c.terapia_id = th.id
                  ORDER BY c.data_consulta ASC";

$result_consultas = $conn->query($sql_consultas);

if (!$result_consultas) {
    die("Erro ao buscar consultas: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Consulta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container-moa {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-radius: 10px;
            max-width: 1300px;
            margin: 50px auto;
        }
        .form-container-moa {
            background-color: #ffffff;
            padding: 30px; /* Ajuste de padding */
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 45%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container-moa h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .form-group-moa {
            margin-bottom: 15px;
            width: 170%; /* Garante que os campos ocupem toda a largura */
        }
        .form-control-moa {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            width: 170%; /* Garante que os campos ocupem toda a largura */
            margin-left: -150px;
        }
        label-moa {
            font-size: 14px;
            color: #2e7d32;
            display: block;
            margin-bottom: 5px;
        }
        button-moa {
            width: 100%;
            padding: 10px;
            background-color: #2e7d32;
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button-moa:hover {
            background-color: #1b5e20;
        }
        .grid-container-moa {
            width: 50%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
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
        .status-container-moa {
            display: flex;
            justify-content: center;
        }
        .status-btn-moa {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin: 0 3px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .status-btn-moa:hover {
            transform: scale(1.1);
        }
        .status-aberto-moa.active {
            background-color: #4caf50;
        }
        .status-agendado-moa.active {
            background-color: #ffeb3b;
        }
        .status-dia-moa.active {
            background-color: #f44336;
        }
        .status-btn-moa i {
            color: white;
            font-size: 12px;
            line-height: 23px;
        }
        .success-message-moa {
            text-align: center;
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .link-container {
            text-align: center;
            margin-top: 20px;
        }

        .back-link {
            font-size: 18px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (isset($_POST['paciente_id'])): ?>
        <div class="success-message-moa">
            <h4>Consulta agendada com sucesso!</h4>
            <script>
                setTimeout(function() {
                    window.location.href = 'cadastro_consulta.php';
                }, 2000); // Redireciona após 2 segundos
            </script>
        </div>
    <?php endif; ?>

    <div class="link-container">
        <a href="index.php" class="back-link">Voltar para a Página Inicial</a>
    </div>

    <div class="container-moa">
        <div class="form-container-moa">
            <h2>Cadastro de Consulta</h2>
            <form action="cadastro_consulta.php" method="POST">
                <div class="form-group-moa">
                    <label-moa for="paciente_id">Paciente</label-moa>
                    <select class="form-control-moa" id="paciente_id" name="paciente_id" required>
                        <option value="">Selecione um paciente</option>
                        <?php if ($result_pacientes->num_rows > 0): ?>
                            <?php while($paciente = $result_pacientes->fetch_assoc()): ?>
                                <option value="<?php echo $paciente['id']; ?>"><?php echo $paciente['nome']; ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">Nenhum paciente disponível</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group-moa">
                    <label-moa for="terapeuta_id">Terapeuta</label-moa>
                    <select class="form-control-moa" id="terapeuta_id" name="terapeuta_id" required>
                        <option value="">Selecione um terapeuta</option>
                        <?php if ($result_terapeutas->num_rows > 0): ?>
                            <?php while($terapeuta = $result_terapeutas->fetch_assoc()): ?>
                                <option value="<?php echo $terapeuta['id']; ?>"><?php echo $terapeuta['nome']; ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">Nenhum terapeuta disponível</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group-moa">
                    <label-moa for="terapia_id">Terapia</label-moa>
                    <select class="form-control-moa" id="terapia_id" name="terapia_id" required>
                        <option value="">Selecione uma terapia</option>
                        <?php if ($result_terapias->num_rows > 0): ?>
                            <?php while($terapia = $result_terapias->fetch_assoc()): ?>
                                <option value="<?php echo $terapia['id']; ?>"><?php echo $terapia['nome']; ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">Nenhuma terapia disponível</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group-moa">
                    <label-moa for="data_consulta">Data da Consulta</label-moa>
                    <input type="date" class="form-control-moa" id="data_consulta" name="data_consulta" required>
                </div>
                <div class="form-group-moa">
                    <label-moa for="horario_consulta">Horário da Consulta</label-moa>
                    <input type="time" class="form-control-moa" id="horario_consulta" name="horario_consulta" required>
                </div>
                <button type="submit" class="btn btn-primary">Agendar Consulta</button>
            </form>
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
                                    <td>
                                        <div class="status-container-moa">
                                            <form method="post" action="cadastro_consulta.php">
                                                <button type="submit" name="status" value="Aberto" class="status-btn-moa status-aberto-moa <?php echo $consulta['status'] == 'Aberto' ? 'active' : ''; ?>">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <input type="hidden" name="id" value="<?php echo $consulta['id']; ?>">
                                            </form>
                                            <form method="post" action="cadastro_consulta.php">
                                                <button type="submit" name="status" value="Agendado" class="status-btn-moa status-agendado-moa <?php echo $consulta['status'] == 'Agendado' ? 'active' : ''; ?>">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                                <input type="hidden" name="id" value="<?php echo $consulta['id']; ?>">
                                            </form>
                                            <form method="post" action="cadastro_consulta.php">
                                                <button type="submit" name="status" value="Consulta do Dia" class="status-btn-moa status-dia-moa <?php echo $consulta['status'] == 'Consulta do Dia' ? 'active' : ''; ?>">
                                                    <i class="fas fa-calendar-day"></i>
                                                </button>
                                                <input type="hidden" name="id" value="<?php echo $consulta['id']; ?>">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Nenhuma consulta cadastrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
