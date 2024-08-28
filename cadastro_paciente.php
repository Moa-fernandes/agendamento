<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $numero_terapias = $_POST['numero_terapias'];
    $dias_da_semana = implode(', ', $_POST['dias_da_semana']);
    $turno = $_POST['turno'];
    $terapeuta_preferencial = $_POST['terapeuta_preferencial'];

    $sql = "INSERT INTO pacientes (nome, especialidade, numero_terapias, dias_da_semana, turno, terapeuta_preferencial) 
            VALUES ('$nome', '$especialidade', '$numero_terapias', '$dias_da_semana', '$turno', '$terapeuta_preferencial')";

    if ($conn->query($sql) === TRUE) {
        echo "Paciente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}

// Buscar terapeutas disponíveis
$sql_terapeutas = "SELECT nome FROM terapeutas WHERE dias_disponiveis IS NOT NULL";
$result_terapeutas = $conn->query($sql_terapeutas);

// Buscar pacientes cadastrados
$sql_pacientes = "SELECT nome, especialidade, numero_terapias FROM pacientes ORDER BY nome ASC";
$result_pacientes = $conn->query($sql_pacientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <!-- Bootstrap e FontAwesome para ícones -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .header-container {
            text-align: center;
            margin: 20px 0;
        }
        .main-container {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            max-width: 95%; /* Aumenta o espaço ocupado */
            margin: 0 auto; /* Centraliza os containers na tela */
        }
        .form-container, .patients-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 48%; /* Aumenta a largura dos containers para ocupar mais espaço */
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            padding: 8px;
            font-size: 14px;
            border-radius: 5px;
        }
        label {
            font-size: 14px;
            color: #2e7d32;
        }
        button {
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
        button:hover {
            background-color: #1b5e20;
        }
        .form-group i {
            margin-right: 8px;
            color: #2e7d32;
        }
        .patients-container h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .patients-table {
            width: 100%;
            border-collapse: collapse;
        }
        .patients-table th, .patients-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .patients-table th {
            background-color: #2e7d32;
            color: white;
        }
    </style>
</head>
<body>

<div class="header-container">
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar para a página inicial
    </a>
</div>

<div class="main-container">
    <!-- Formulário de Cadastro de Paciente -->
    <div class="form-container">
        <h2><i class="fas fa-user-plus"></i> Cadastro de Paciente</h2>
        <form action="cadastro_paciente.php" method="POST">
            <div class="form-group">
                <label for="nome"><i class="fas fa-user"></i> Nome do Paciente</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="especialidade"><i class="fas fa-briefcase-medical"></i> Especialidade</label>
                <select class="form-control" id="especialidade" name="especialidade">
                    <option value="Psicologia">Psicologia</option>
                    <option value="Psicopedagogia">Psicopedagogia</option>
                    <option value="Fisioterapia">Fisioterapia</option>
                    <option value="Terapia Ocupacional">Terapia Ocupacional</option>
                    <option value="Fonoaudiologia">Fonoaudiologia</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_terapias"><i class="fas fa-list-ol"></i> Número de Terapias</label>
                <input type="number" class="form-control" id="numero_terapias" name="numero_terapias" required>
            </div>
            <div class="form-group">
                <label for="dias_da_semana"><i class="fas fa-calendar-alt"></i> Dias da Semana</label><br>
                <input type="checkbox" name="dias_da_semana[]" value="Segunda-feira"> Segunda-feira<br>
                <input type="checkbox" name="dias_da_semana[]" value="Terça-feira"> Terça-feira<br>
                <input type="checkbox" name="dias_da_semana[]" value="Quarta-feira"> Quarta-feira<br>
                <input type="checkbox" name="dias_da_semana[]" value="Quinta-feira"> Quinta-feira<br>
                <input type="checkbox" name="dias_da_semana[]" value="Sexta-feira"> Sexta-feira<br>
            </div>
            <div class="form-group">
                <label for="turno"><i class="fas fa-clock"></i> Turno</label>
                <select class="form-control" id="turno" name="turno">
                    <option value="Manhã">Manhã</option>
                    <option value="Tarde">Tarde</option>
                </select>
            </div>
            <div class="form-group">
                <label for="terapeuta_preferencial"><i class="fas fa-user-md"></i> Terapeuta Preferencial</label>
                <select class="form-control" id="terapeuta_preferencial" name="terapeuta_preferencial" required>
                    <option value="">Selecione um terapeuta</option>
                    <?php if ($result_terapeutas->num_rows > 0): ?>
                        <?php while($terapeuta = $result_terapeutas->fetch_assoc()): ?>
                            <option value="<?php echo $terapeuta['nome']; ?>"><?php echo $terapeuta['nome']; ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">Nenhum terapeuta disponível</option>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cadastrar</button>
        </form>
    </div>

    <!-- Container de Pacientes Cadastrados -->
    <div class="patients-container">
        <h2><i class="fas fa-users"></i> Pacientes Cadastrados</h2>
        <table class="patients-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Especialidade</th>
                    <th>Número de Terapias</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_pacientes->num_rows > 0): ?>
                    <?php while($paciente = $result_pacientes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $paciente['nome']; ?></td>
                            <td><?php echo $paciente['especialidade']; ?></td>
                            <td><?php echo $paciente['numero_terapias']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Nenhum paciente cadastrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
