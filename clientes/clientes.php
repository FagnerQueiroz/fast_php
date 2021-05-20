
    <?php
    if(isset($_GET["master_id"])){
?>
<script>
window.onload = function() {
    seleciona_mestre(<?php echo $_GET["master_id"] ?>);
   
}
</script>

<?php
}
?>

    
    <!doctype html>
    <html lang="PT-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../libs/fontawesome/css/all.css" rel="stylesheet"> 
        <title>FAST PHP</title>
    </head>
    <body>
        <div class="container-sm">
        <div style="display:none;position: absolute;"  id="sucesso"></div>
  
            <form id="form_clientes" class="row g-3"><div class="form-group col-md-4">
       <label for="cliente_id" class="form-label">Cliente Id</label>
       <input type="number" class="form-control" name="cliente_id" id="cliente_id" placeholder="cliente_id"   readonly >
       </div><div class="form-group col-md-4">
       <label for="tipo" class="form-label">Tipo</label>
       <input type="number" class="form-control" name="tipo" id="tipo" placeholder="tipo"    >
       </div><div class="form-group col-md-4">
       <label for="nome" class="form-label">Nome</label>
       <input type="text" class="form-control" name="nome" id="nome" placeholder="nome"    >
       </div><div class="form-group col-md-4">
       <label for="inscricao_federal" class="form-label">Inscricao Federal</label>
       <input type="text" class="form-control" name="inscricao_federal" id="inscricao_federal" placeholder="inscricao_federal"    >
       </div><div class="form-group col-md-4">
       <label for="logradouro" class="form-label">Logradouro</label>
       <input type="text" class="form-control" name="logradouro" id="logradouro" placeholder="logradouro"    >
       </div><div class="form-group col-md-4">
       <label for="data_nascimento" class="form-label">Data Nascimento</label>
       <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" placeholder="data_nascimento"    >
       </div><div class="form-group col-md-4">
       <label for="bairro" class="form-label">Bairro</label>
       <input type="text" class="form-control" name="bairro" id="bairro" placeholder="bairro"    >
       </div><div class="form-group col-md-4">
       <label for="cidade" class="form-label">Cidade</label>
       <input type="text" class="form-control" name="cidade" id="cidade" placeholder="cidade"    >
       </div><div class="form-group col-md-4">
       <label for="uf" class="form-label">Uf</label>
       <input type="text" class="form-control" name="uf" id="uf" placeholder="uf"    >
       </div><div class="form-group col-md-4">
       <label for="cep" class="form-label">Cep</label>
       <input type="text" class="form-control" name="cep" id="cep" placeholder="cep"    >
       </div><div class="row">
    <div form-group col-md-3>
    <button style="margin-top:10px" id="btn_salvar_clientes"   type="button" class="btn btn-primary mb-3">Salvar</button>
    <button style="margin-top:10px" id="btn_novo_clientes"   type="button" class="btn btn-primary mb-3">Novo</button>
</div>
</div>

<script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../libs/jquery/jquery-3.6.0.min.js"></script>  
<script src="../libs/sweetalert/sweetalert.js"></script>
<script src="../libs/sweetalert/fast_alert.js"></script>



<!---------------------------------------------FUNCAO Javascript CADASTRAR E EDITAR -------------------------------------------->
<script>
$(document).ready(function() {
    var mostrar = $("#cliente_id").val();
 
    $("#btn_salvar_clientes").click(function() {
        var dados = $("#form_clientes").serialize();
        var registro_id= $("#cliente_id").val();
        if(registro_id ==""){
            dados = dados+"&CADASTRAR=1";
        }else{
            
            dados = dados+"&EDITAR_REGISTRO="+registro_id;
        }
        
        $.post({
            type:"POST",
            data:dados,
            url:"../clientes/clientes_backend.php",
            success:function(resultado){
                if(resultado != "0"){
                    $("#cliente_id").val(resultado); 
                    fast_alert_salvar();
                }else{
                    fast_alert_erro_salvar();

                }
           
            }
        });
    });


    $("#btn_novo_clientes").click(function() {
        window.location.href = "clientes.php";
    });
});



</script>
<script>
function seleciona_mestre(id){
    var dados = "LISTA_UNICO_REGISTRO="+id;
          $.post({
              type:"POST",
              data:dados,
              url:"../clientes/clientes_backend.php",
              success:function(resultado){
                 if(resultado != 0){
                retorno=jQuery.parseJSON(resultado);
                $("#cliente_id").val(retorno["cliente_id"]);
        $("#tipo").val(retorno["tipo"]);
        $("#nome").val(retorno["nome"]);
        $("#inscricao_federal").val(retorno["inscricao_federal"]);
        $("#logradouro").val(retorno["logradouro"]);
        $("#data_nascimento").val(retorno["data_nascimento"]);
        $("#bairro").val(retorno["bairro"]);
        $("#cidade").val(retorno["cidade"]);
        $("#uf").val(retorno["uf"]);
        $("#cep").val(retorno["cep"]);
        
                 }else{
                    fast_alert_erro_listar();

                 }
              }
          }
          );
  }


function seleciona_registro_excluir(id){
    $("#id_exclusao_registro").text(id);
}
</script>
</body>
</html>