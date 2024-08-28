<?php
include('db_connect.php');

// Consultar terapeutas cadastrados
$sql = "SELECT nome, especialidade, numero_crm, dias_disponiveis, turnos_disponiveis FROM terapeutas";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $numero_crm = $_POST['numero_crm'];
    $dias_disponiveis = implode(', ', $_POST['dias_disponiveis']);
    $turnos_disponiveis = $_POST['turnos_disponiveis'];

    $sql_insert = "INSERT INTO terapeutas (nome, especialidade, numero_crm, dias_disponiveis, turnos_disponiveis) 
            VALUES ('$nome', '$especialidade', '$numero_crm', '$dias_disponiveis', '$turnos_disponiveis')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Terapeuta cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Terapeuta</title>
    <!-- Bootstrap e FontAwesome para ícones -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .container, .therapist-list {
            background-color: #e8f5e9; /* Mesma cor para ambos os contêineres */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 45%; /* Tamanho igual para ambos os contêineres */
        }
        .therapist-list {
            margin-right: 70px; 
            max-height: 630px; /* Altura máxima definida */
            overflow-y: auto; /* Scroll vertical */
        }
        h2, h3 {
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
            margin-right: 250px;
            color: #2e7d32;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        header a {
            text-decoration: none;
            font-size: 16px;
            color: #2e7d32;
        }
        .row {
            display: flex;
            justify-content: center; /* Centraliza os contêineres */
            gap: 1px; /* Espaço de 20px entre os contêineres */
            padding-top: 20px; /* Espaço superior de 20px */
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #e8f5e9;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .day-icon {
            margin-right: 5px;
            font-size: 16px;
        }

        .available-day {
            color: #28a745;
        }

        .unavailable-day {
            color: #dc3545;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><i class="fas fa-arrow-left"></i> Voltar à página inicial</a>
</header>

<div class="row">
    <div class="container">
        <h2><i class="fas fa-user-md"></i> Cadastro de Terapeuta</h2>
        <form action="cadastro_terapeuta.php" method="POST">
            <div class="form-group">
                <label for="nome"><i class="fas fa-user"></i> Nome do Terapeuta</label>
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
                <label for="numero_crm"><i class="fas fa-id-badge"></i> Número do CRM (ou equivalente)</label>
                <input type="text" class="form-control" id="numero_crm" name="numero_crm" required>
            </div>
            <div class="form-group">
                <label for="dias_disponiveis"><i class="fas fa-calendar-alt"></i> Dias Disponíveis</label><br>
                <input type="checkbox" name="dias_disponiveis[]" value="Segunda-feira"> Segunda-feira<br>
                <input type="checkbox" name="dias_disponiveis[]" value="Terça-feira"> Terça-feira<br>
                <input type="checkbox" name="dias_disponiveis[]" value="Quarta-feira"> Quarta-feira<br>
                <input type="checkbox" name="dias_disponiveis[]" value="Quinta-feira"> Quinta-feira<br>
                <input type="checkbox" name="dias_disponiveis[]" value="Sexta-feira"> Sexta-feira<br>
            </div>
            <div class="form-group">
                <label for="turnos_disponiveis"><i class="fas fa-clock"></i> Turno Preferencial</label>
                <select class="form-control" id="turnos_disponiveis" name="turnos_disponiveis">
                    <option value="Manhã">Manhã</option>
                    <option value="Tarde">Tarde</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cadastrar Terapeuta</button>
        </form>
    </div>

    <!-- Lista de Terapeutas -->
    <div class="therapist-list">
        <h3>Terapeutas Cadastrados</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Especialidade</th>
                    <th>CRM</th>
                    <th>Dias Disponíveis</th>
                    <th>Turno</th>
                    <th>Disponibilidade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td>" . $row["especialidade"] . "</td>";
                        echo "<td>" . $row["numero_crm"] . "</td>";
                        
                        // Dias disponíveis com ícones
                        $dias_disponiveis = explode(', ', $row["dias_disponiveis"]);
                        echo "<td>";
                        $dias = ['S', 'T', 'Q', 'Q', 'S', 'S', 'D'];
                        $nomes_dias = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];
                        foreach ($nomes_dias as $index => $dia) {
                            $classe = in_array($dia, $dias_disponiveis) ? 'available-day' : 'unavailable-day';
                            echo "<span class='day-icon $classe'>" . $dias[$index] . "</span>";
                        }
                        echo "</td>";

                        echo "<td>" . $row["turnos_disponiveis"] . "</td>";

                        // Condição para verificar disponibilidade (exemplo fictício)
                        $disponibilidade = ($row["turnos_disponiveis"] === "Manhã") ? "Disponível" : "Indisponível";
                        echo "<td>";
                        if ($disponibilidade === "Disponível") {
                            echo "<i class='fas fa-check-circle text-success'></i>";  // Ícone verde
                        } else {
                            echo "<i class='fas fa-times-circle text-danger'></i>";  // Ícone vermelho
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum terapeuta cadastrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
