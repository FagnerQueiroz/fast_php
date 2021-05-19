<?php
include_once('conexao.php');
$sql_nome_tabela = "SHOW TABLES";

?>

<!doctype html>
<html lang="PT-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <title>FAST PHP</title>

</head>
<style>
    #formulario {
        margin-top: 20px;
    }

    .btn {

        margin-right: 20px;
    }
</style>

<body>
    <div class="container-sm">
        <form id='formulario' class="row g-3 ">
            <label >Tabela</label>
            <select id="nome_tabela" class="form-select" aria-label="Default select example">
                <option selected>Selecione...</option>
                <?php

                $resultado = $conexao->query($sql_nome_tabela);


                while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $linha["Tables_in_".$dbname] . '">' . $linha["Tables_in_".$dbname]  . '</option>';
                }
                ?>
            </select>
            <div class="col-auto">
                <button id="btn_gera_crud" type="button" class="btn btn-primary mb-3">GERAR CRUD</button>
            </div>

        </form>
        <div id="links_crud"></div>

    </div>
    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="libs/jquery/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#btn_gera_crud").click(function() {
                var dados = 'NOME_TABELA=' + $("#nome_tabela").val();
                //alert(dados);

                $.post({
                    type: "POST",
                    data: dados,
                    url: "gera_crud_backend.php",
                    success: function(resultado) {
                           console.log(resultado);
                        $("#links_crud").html("<button onclick='insert()'  class='btn btn-primary mb-3'>INSERT</button><button onclick='lista()' class='btn btn-primary mb-3'>LISTA</button>");

                    }
                });
            });
        });
    </script>
    <script>
        function insert() {
            var app = $("#nome_tabela").val()+'/'+$("#nome_tabela").val()+'.php';
            parent.addTabs({
                    id: $("#nome_tabela").val(),
                    title: $("#nome_tabela").val(),
                    close: true,
                    url: app,
                    urlType: "relative"
            });
        }

        function lista() {
            var app = $("#nome_tabela").val()+'/'+$("#nome_tabela").val()+'_lista.php';
            parent.addTabs({
                    id: $("#nome_tabela").val()+'_lista',
                    title: 'lista '+$("#nome_tabela").val(),
                    close: true,
                    url: app,
                    urlType: "relative"
            });
        }
    </script>


</body>

</html>