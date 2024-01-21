<?php
include "header.php";
include "conexao.php";
include "adicionarPresenca.php";
include "temporizador.php";

$username = $_SESSION['username'];

$queryUser = "SELECT id, permission FROM usuarios WHERE nome = :username";
$stmtUser = $pdo->prepare($queryUser);
$stmtUser->bindParam(":username", $username);
$stmtUser->execute();

$resultUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

$userType = $resultUser['permission'];

if ($userType == 'limited') {
?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Presença</title>
    </head>

    <body>

        <div class="box1 mt-4 text-center">
            <h3 class="mt-4" style="font-size: 20px;">Listagem De Presença</h3>
        </div>
        <div class="container-fluid">
            <form id="presencaForm" action="adicionarPresenca.php" method="post">
                <div class="table-responsive-sm mt-4">
                    <table class="table table-sm table-hover table-striped" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Participante</th>
                                <th>Presente</th>
                                <th>Ausente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultUser) {
                                $userId = $resultUser['id'];
                                $queryPart = "SELECT p.nome AS participante
                                      FROM participantes AS p
                                      JOIN gerenciamento_sessao AS gs ON p.id = gs.id_participantes
                                      JOIN usuarios AS u ON gs.id_usuarios = u.id
                                      JOIN sessoes AS s ON gs.id_sessoes = s.id
                                      WHERE u.id = :userId AND s.situacao = 'Pendente'
                                      ORDER BY s.data_criacao DESC
                                      LIMIT 1";
                                $stmtPart = $pdo->prepare($queryPart);
                                $stmtPart->bindParam(":userId", $userId);
                                $stmtPart->execute();
                                $data = $stmtPart->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($data as $row) {
                                    echo "<tr>";
                                    echo "<td>{$row['participante']}</td>";
                                    echo "<td><input type='radio' name='presenca[{$row['participante']}]' value='Presente'></td>";
                                    echo "<td><input type='radio' name='presenca[{$row['participante']}]' value='Ausente'></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>Usuário não encontrado.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <input type="submit" class="btn btn-primary mt-4" data-bs-toggle="modal" onclick="return validarPresenca()" name="adicionarPresenca" data-bs-target="#exampleModal" value="Confirmar">
                </div>
            </form>
        </div>

        <div id="login-expired-message" style="color: black;"></div>
        <script>
            resetTimer();
        </script>
    </body>

    </html>
<?php
} else {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Lista de ausentes</title>
    </head>
    <body>
    <div class='box1 mt-4 text-center'>
        <h1 class='mt-4' style='font-size: 20px;'>Lista de participantes ausentes</h1>
        <h4 class='mt-4'></h4>
    </div>
        <div class='container-fluid'>
            <div class='table-responsive-sm mt-4' style='font-size: 12px;'>
                <table class='table table-sm table-hover table-striped mt-4'>
                    <thead>
                        <tr>
                            <th>Facilitador</th>
                            <th>Participante</th>
                            <th>Equipe</th>
                        </tr>
                    </thead>
                    <tbody>";

    $querySessao = "SELECT nome FROM sessoes WHERE situacao = 'Pendente' ORDER BY data_criacao DESC LIMIT 1";
    $stmtSessao = $pdo->prepare($querySessao);
    $stmtSessao->execute();
    $nomeSessao = $stmtSessao->fetchColumn();

    echo "<h4 class='mt-1 text-center mx-auto' style='background-color: #163387; color: white; max-width: 400px; font-size: 1.3em; padding:5px; border:solid #000;'> Sessão Atual: $nomeSessao</h4>";

    $query = "SELECT p.nome AS participante_nome, s.nome AS status_nome, e.nome AS equipe_nome, u.nome AS nome_facilitador FROM presenca AS pre
            JOIN status AS s ON pre.id_status = s.id
            JOIN participantes AS p ON pre.id_participantes = p.id
            JOIN gerenciamento_sessao AS gs ON p.id = gs.id_participantes
            JOIN usuarios AS u ON gs.id_usuarios = u.id
            JOIN sessoes AS ses ON pre.id_sessao = ses.id
            JOIN equipes AS e ON gs.id_equipe = e.id
            WHERE s.nome = 'Ausente' AND ses.situacao = 'Pendente' ORDER BY ses.data_criacao DESC LIMIT 1";

    $consulta = $pdo->prepare($query);
    $consulta->execute();
    $data = $consulta->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $row) {
        echo "<tr>";
        echo "<th>{$row['nome_facilitador']}</th>";
        echo "<th>{$row['participante_nome']}</th>";
        echo "<th>{$row['equipe_nome']}</th>";

        echo "</tr>";
    }

    echo "</tbody>
                </table>
            </div>
        </body>
        </html>";
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
