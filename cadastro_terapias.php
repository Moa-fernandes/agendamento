<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $terapeuta_id = $_POST['terapeuta'];
    $terapia_nome = $_POST['terapia'];

    // Insere a relação da terapia com o terapeuta
    $sql = "INSERT INTO terapias (terapeuta_id, nome, data_entrada) VALUES ('$terapeuta_id', '$terapia_nome', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>
                <i class='fas fa-check-circle'></i> Cadastro de Terapia Associada feito com sucesso!
              </div>";
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'index.php';
                }, 2000);
              </script>";
    } else {
        echo "Erro ao cadastrar a terapia: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Terapia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Aumenta a largura do container */
            margin: 40px auto; /* Reduz a margem superior */
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
        .grid-container {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
            padding: 10px;
        }
        .grid-header, .grid-row {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .grid-header {
            background-color: #2e7d32;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .grid-row:nth-child(even) {
            background-color: #f9f9f9;
        }
        .grid-item {
            flex: 1;
            text-align: center;
            padding: 5px;
        }
        .grid-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
        }
        .back-button {
            margin-bottom: 20px;
            text-align: center;
        }
        .back-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .back-button a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-plus-circle"></i> Cadastro de Terapia</h2>

    <div class="back-button">
        <a href="index.php">
            <i class="fas fa-arrow-left"></i> Voltar para a página inicial
        </a>
    </div>

    <form action="cadastro_terapias.php" method="POST">
        <div class="form-group">
            <label for="terapeuta"><i class="fas fa-user-md"></i> Terapeuta</label>
            <select class="form-control" id="terapeuta" name="terapeuta" required>
                <option value="" disabled selected>Selecione o Terapeuta</option>
                <?php
                $result = $conn->query("SELECT id, nome FROM terapeutas");
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['id']."'>".$row['nome']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="terapia"><i class="fas fa-heartbeat"></i> Terapia</label>
            <select class="form-control" id="terapia" name="terapia" required>
                <option value="" disabled selected>Selecione a Terapia</option>
                <option value="Acupuntura">Acupuntura</option>
                <option value="Aromaterapia">Aromaterapia</option>
                <option value="Quiropraxia">Quiropraxia</option>
                <option value="Reiki">Reiki</option>
                <option value="Homeopatia">Homeopatia</option>
                <option value="Fisioterapia">Fisioterapia</option>
                <option value="Massoterapia">Massoterapia</option>
                <option value="Terapia Ocupacional">Terapia Ocupacional</option>
                <option value="Psicoterapia">Psicoterapia</option>
                <option value="Musicoterapia">Musicoterapia</option>
                <option value="Arteterapia">Arteterapia</option>
                <option value="Hipnoterapia">Hipnoterapia</option>
                <option value="Terapia Cognitivo-Comportamental">Terapia Cognitivo-Comportamental</option>
                <option value="Terapia Familiar">Terapia Familiar</option>
                <option value="Reflexologia">Reflexologia</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Cadastrar Terapia
        </button>
    </form>

    <!-- Grid-Tabela para mostrar as terapias cadastradas -->
    <div class="grid-container">
        <div class="grid-header">
            <div class="grid-item"><i class="fas fa-procedures"></i> Terapia</div>
            <div class="grid-item"><i class="fas fa-user-md"></i> Terapeuta</div>
            <div class="grid-item"><i class="fas fa-calendar-alt"></i> Data de Entrada</div>
        </div>
        <?php
        $sql = "SELECT t.nome as terapia_nome, te.nome as terapeuta_nome, t.data_entrada 
                FROM terapias t 
                JOIN terapeutas te ON t.terapeuta_id = te.id
                ORDER BY t.data_entrada DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Verifica se o campo 'data_entrada' não está vazio antes de formatar
                if (!empty($row['data_entrada'])) {
                    // Supondo que o formato da data seja 'YYYY-MM-DD'
                    $data = explode('-', $row['data_entrada']);
                    if (count($data) == 3) {
                        // Formata a data para 'dia/mês/ano'
                        $data_formatada = $data[2] . '/' . $data[1] . '/' . $data[0];
                    } else {
                        $data_formatada = "Formato de data inválido";
                    }
                } else {
                    $data_formatada = "Data não disponível";
                }
        
                echo "<div class='grid-row'>
                        <div class='grid-item'>".$row['terapia_nome']."</div>
                        <div class='grid-item'>".$row['terapeuta_nome']."</div>
                        <div class='grid-item'>".$data_formatada."</div>
                      </div>";
            }
        }
        
            
         else {
            echo "<div class='grid-row'>
                    <div class='grid-item' colspan='3'>Nenhuma terapia cadastrada</div>
                  </div>";
        }
        ?>
    </div>
</div>
</body>
</html>
