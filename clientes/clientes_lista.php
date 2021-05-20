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
           <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col"><i data-bs-toggle="modal" data-bs-target="#modal_"  style="color:#0d6efd;cursor:pointer"  onclick="abrir_registro(0)" class="fas fa-plus-square fa-lg"></i></th><th scope="col">Cliente Id</th>
        <th scope="col">Tipo</th>
        <th scope="col">Nome</th>
        <th scope="col">Inscricao Federal</th>
        <th scope="col">Logradouro</th>
        <th scope="col">Data Nascimento</th>
        <th scope="col">Bairro</th>
        <th scope="col">Cidade</th>
        <th scope="col">Uf</th>
        <th scope="col">Cep</th>
        
                </tr>
                </thead> 
                <tbody id="tabela_clientes">
                </tbody>
         </table>      
     <div id="paginacao"></div>
     <div style="display:none" id="paginacao_selecionada"></div>
     
     </div>
     <!-- MODAL EXCLUSAO-->


<!------------------------------------------------------- Modal Exclusao ----------------------------------->
<div class="modal fade" id="modal_exclusao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_exclusaoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_exclusaoLabel">Atenção</h5>
      </div>
      <div class="modal-body">
        Confirma a exclusão do registro?
        <div style="display:none" id="id_exclusao_registro"></dvi>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" onclick="excluir_registro()" data-bs-dismiss="modal" class="btn btn-primary">Excluir</button>
      </div>
    </div>
  </div>
</div>
</div>
     <!---------------------------------------------MODAL EDICAO -------------------------------------------->
     <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
     <script src="../libs/jquery/jquery-3.6.0.min.js"></script>  
     <script src="../libs/sweetalert/sweetalert.js"></script>
     <script src="../libs/sweetalert/fast_alert.js"></script>
<script>
function lista_registros(limit_sql) {
        var dados = "LISTAR_REGISTROS="+limit_sql;
        $.post({
            type:"POST",
            data:dados,
            url:"../clientes/clientes_backend.php",
            success:function(resultado){
                retorno=jQuery.parseJSON(resultado);
                $("#tabela_clientes").html(retorno['tabela']);
                $("#paginacao").html(retorno['paginacao']);
                $("#paginacao_selecionada").text(limit_sql);
            }
        }
        );
};

$(document).ready(function() {
    lista_registros(1);
   });


  function excluir_registro(){
      var id_exclusao = $("#id_exclusao_registro").text();
    var dados = "EXCLUIR_REGISTRO="+id_exclusao;
          $.post({
              type:"POST",
              data:dados,
              url:"../clientes/clientes_backend.php",
              success:function(resultado){
                 if(resultado == "1"){
                    lista_registros($("#paginacao_selecionada").text());
                    fast_alert_excluir();
                 }else{
                    fast_alert_erro_excluir();

                 }
              }
          }
          );
  }
function seleciona_registro_excluir(id){
    $("#id_exclusao_registro").text(id);
}

function abrir_registro(id){
if(id > 0){
    window.location.href = "clientes.php?master_id="+id;
}else{
    window.location.href = "clientes.php";
}
}
</script>
</body>
                