<?php 
    include "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .box1 {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        th,
        td {
            text-align: center;
            font-size: 16px;
            padding: 15px;
        }

        th {
            background-color: #343a40;
            color: white;
            position: relative;
            font-size: 18px;
        }

        th i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }
        
    </style>
</head>
<body>

    <div class="container mt-4">
        <div class="text-center mt-4">
            <h3 style="font-size: 35px;">Ranking</h3>
        </div>
        <div class="container-fluid">
            <table id="ranking-table" class="table table-striped mt-4" >
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Pontuação <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody id="ranking-body">
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function atualizarRanking() {
                $.ajax({
                    url: 'atualizar_ranking.php',
                    type: 'GET',
                    success: function(data) {
                        $('#ranking-body').html(data);
                    },
                    error: function() {
                        console.log('Erro ao atualizar o ranking.');
                    }
                });
            }

            setInterval(atualizarRanking, 10);
        });
    </script>
</body>
</html>