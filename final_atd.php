<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $observacoes = $_POST['observacoes'];

    // Atualizar status e adicionar observações
    $sql_update = "UPDATE consultas SET status = 'Finalizado', observacoes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('si', $observacoes, $id);
    
    if ($stmt->execute()) {
        header('Location: consultas_cads.php'); // Redirecionar para a página original após concluir
        exit();
    } else {
        echo "Erro ao finalizar atendimento.";
    }
}
?>
