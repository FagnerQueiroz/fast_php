<?php
require_once('conexao.php');
if (isset($_POST['NOME_TABELA'])) {
    /*--------------------------------------GERA TELA MESTRE DETALHE FRONT END-------------------------------------------------*/
    $resultado = $conexao->query('DESCRIBE ' . $_POST['NOME_TABELA']);

    $campos_insert = '(';
    $bind_paramentros = '';
    $valores_insert = '(';
    $cabecalho_lista = '<th scope="col"><i data-bs-toggle="modal" data-bs-target="#modal_' . $_POST['NOME_TABELA_DETALHE'] . '"  style="color:#0d6efd;cursor:pointer"  onclick="abrir_registro(0)" class="fas fa-plus-square fa-lg"></i></th>';
    $select_lista = '';
    $linha_registro = '';
    $linha_registro_unico = '';
    $resultado_registro_unico = '';
    $valores_update = '';
    $form_edit = '';
    while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)) {
        if ($linha["Extra"] <> 'auto_increment') {
            $valores_update .= $linha["Field"] . '=:' . $linha["Field"] . ',';
            $campos_insert .= $linha["Field"] . ',';
            $valores_insert .= ':' . $linha["Field"] . ',';
        $bind_paramentros .= '$cadastrar->bindParam(":' . $linha["Field"] . '", $_POST["' . $linha["Field"] . '"], PDO::PARAM_STR);
    ';
        } else {
            $id_tabela = $linha["Field"];
        }


        $cabecalho_lista .= '<th scope="col">' . $linha["Field"] . '</th>
        ';
        $select_lista .= ' ' . $linha["Field"] . ',';

        $linha_registro .= '
        <td>".$linha["' . $linha["Field"] . '"]."</td>';
        $linha_registro_unico .= '"' . $linha["Field"] . '"=>$linha["' . $linha["Field"] . '"],';


        $resultado_registro_unico .= '$("#' . $linha["Field"] . '").val(retorno["' . $linha["Field"] . '"]);
        ';
        $form_edit .=  '<div class="form-group col-md-4">
       <label for="' . $linha["Field"] . '" class="form-label">' . ucwords(str_replace('_',' ',$linha["Field"])) . '</label>
       <input type="' . tipo_campo($linha["Type"]) . '" class="form-control" name="' . $linha["Field"] . '" id="' . $linha["Field"] . '" placeholder="' . $linha["Field"] . '"   ' . read_only($linha["Extra"]) . ' >
       </div>';
    }

    /*******************************************************DETALHE************************************************/
    $resultado_detalhe = $conexao->query('DESCRIBE ' . $_POST['NOME_TABELA_DETALHE']);

    $campos_insert_detalhe = '(';
    $bind_paramentros_detalhe = '';
    $valores_insert_detalhe = '(';
    $cabecalho_lista_detalhe = '<th scope="col"><i data-bs-toggle="modal" data-bs-target="#modal_' . $_POST['NOME_TABELA_DETALHE'] . '"  style="color:#0d6efd" id="btn_novo_detalhe" onclick="carrega_mestre_id()" class="fas fa-plus-square fa-lg"></i></th>';
    $select_lista_detalhe = '';
    $linha_registro_detalhe = '';
    $linha_registro_unico_detalhe = '';
    $resultado_registro_unico_detalhe = '';
    $valores_update_detalhe = '';
    $form_edit_detalhe = '';
    while ($linha_detalhe = $resultado_detalhe->fetch(PDO::FETCH_ASSOC)) {
        if ($linha_detalhe["Extra"] <> 'auto_increment') {
            $valores_update_detalhe .= $linha_detalhe["Field"] . '=:' . $linha_detalhe["Field"] . ',';
            $campos_insert_detalhe .= $linha_detalhe["Field"] . ',';
            $valores_insert_detalhe .= ':' . $linha_detalhe["Field"] . ',';
            $bind_paramentros_detalhe .= '$cadastrar->bindParam(":' . $linha_detalhe["Field"] . '", $_POST["' . $linha_detalhe["Field"] . '"], PDO::PARAM_STR);
        ';
        } else {
            $id_tabela_detalhe = $linha_detalhe["Field"];
        }


        $cabecalho_lista_detalhe .= '<th scope="col">' . ucwords(str_replace('_',' ',$linha_detalhe["Field"])) . '</th>
        ';
        $select_lista_detalhe .= ' ' . $linha_detalhe["Field"] . ',';

        $linha_registro_detalhe .= '
        <td>".$linha_detalhe["' . $linha_detalhe["Field"] . '"]."</td>';
        $linha_registro_unico_detalhe .= '"' . $linha_detalhe["Field"] . '"=>$linha_detalhe["' . $linha_detalhe["Field"] . '"],';


        $resultado_registro_unico_detalhe .= '$("#' .$_POST['NOME_TABELA_DETALHE'].'_'. $linha_detalhe["Field"] . '").val(retorno["' . $linha_detalhe["Field"] . '"]);
        ';
        $form_edit_detalhe .=  '<div class="mb-3S">
       <label for="' . $linha_detalhe["Field"] . '" class="form-label">' . ucwords(str_replace('_',' ',$linha_detalhe["Field"])) . '</label>
       <input type="' . tipo_campo($linha_detalhe["Type"]) . '" class="form-control" name="' . $linha_detalhe["Field"] . '" id="' . $_POST['NOME_TABELA_DETALHE'] . '_' . $linha_detalhe["Field"] . '" placeholder="' . $linha_detalhe["Field"] . '"   ' . read_only($linha_detalhe["Extra"]) . ' >
       </div>';
    }
}

    $form =  '
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
            <form id="form_' . $_POST['NOME_TABELA'] . '" class="row g-3">';
    $form .= $form_edit;
    $form .= '<div class="row">
    <div form-group col-md-2>
    <button id="btn_salvar_' . $_POST['NOME_TABELA'] . '"   type="button" class="btn btn-primary mb-3">Salvar</button>
    <button id="btn_novo_' . $_POST['NOME_TABELA'] . '"   type="button" class="btn btn-primary mb-3">Novo</button>
             
</div>
</div>
</form>
<!----------------------------------------------------------MODAL NOVO DETALHE---------------------------------------------->
<div class="modal fade" id="modal_' . $_POST['NOME_TABELA_DETALHE'] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_exclusaoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
      <form id="form_'.$_POST['NOME_TABELA_DETALHE'] .'"> 
      '.$form_edit_detalhe.'
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" onclick="novo_detalhe()" data-bs-dismiss="modal" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!----------------------------------------LISTA DETALHE------------------------------------------>
<div id="lista_detalhe">

<body>
    <div class="container-sm">
       <table class="table table-striped">
            <thead>
                <tr>
                ' . $cabecalho_lista_detalhe . '
                </tr>
                </thead> 
                <tbody id="tabela_' . $_POST['NOME_TABELA_DETALHE'] . '">
                </tbody>
         </table>      
     <div id="paginacao"></div>
     <div style="display:none" id="paginacao_selecionada"></div>
     
     </div>
     <!-- MODAL EXCLUSAO-->


<!------------------------------------------------------- Modal Exclusao --------------------->
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
        <button type="button" onclick="excluir_registro_detalhe()" data-bs-dismiss="modal" class="btn btn-primary">Excluir</button>
      </div>
    </div>
  </div>
</div>
</div>

<script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../libs/jquery/jquery-3.6.0.min.js"></script>  
<script src="../libs/sweetalert/sweetalert.js"></script>
<script src="../libs/sweetalert/fast_alert.js"></script>



<!---------------------------------------------FUNCAO Javascript CADASTRAR E EDITAR -------------------------------------------->
<script>
$(document).ready(function() {
    var mostrar = $("#' . $id_tabela . '").val();
 
    $("#btn_salvar_' . $_POST['NOME_TABELA'] . '").click(function() {
        var dados = $("#form_' . $_POST['NOME_TABELA'] . '").serialize();
        var registro_id= $("#' . $id_tabela . '").val();
        if(registro_id ==""){
            dados = dados+"&CADASTRAR=1";
        }else{
            
            dados = dados+"&EDITAR_REGISTRO="+registro_id;
        }
        
        $.post({
            type:"POST",
            data:dados,
            url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
            success:function(resultado){
                if(resultado != "0"){
                    $("#' . $id_tabela . '").val(resultado); 
                    $("#btn_novo_detalhe").show();
                    fast_alert_salvar();
                }else{
                    fast_alert_erro_salvar();

                }
           
            }
        });
    });
});

function carrega_mestre_id(){
    $("#form_' . $_POST['NOME_TABELA_DETALHE'] . '").each (function(){
        this.reset();
      });
    registro_id= $("#' . $id_tabela . '").val();
    $("#' . $_POST['NOME_TABELA_DETALHE'].'_'. $id_tabela . '").val(registro_id);

}


</script>


<script>
function lista_registros_detalhe(limit_sql) {
    registro_id= $("#' . $id_tabela . '").val();
    if(registro_id!=false){
        var dados = "LISTAR_REGISTROS_DETALHE="+limit_sql+\'&'.$id_tabela.'=\'+registro_id;
        $.post({
            type:"POST",
            data:dados,
            url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
            success:function(resultado){
                retorno=jQuery.parseJSON(resultado);
                $("#tabela_' . $_POST['NOME_TABELA_DETALHE'] . '").html(retorno[\'tabela\']);
                $("#paginacao").html(retorno[\'paginacao\']);
                $("#paginacao_selecionada").text(limit_sql);
            }
        }
        );
    }
};
$(document).ready(function() {
    lista_registros_detalhe(1);
});


function seleciona_mestre(id){
    var dados = "LISTA_UNICO_REGISTRO="+id;
          $.post({
              type:"POST",
              data:dados,
              url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
              success:function(resultado){
                 if(resultado != 0){
                retorno=jQuery.parseJSON(resultado);
                ' . $resultado_registro_unico . '
                lista_registros_detalhe(1);
                 }else{
                    fast_alert_erro_listar();

                 }
              }
          }
          );
  }


  function excluir_registro_detalhe(){
      var id_exclusao = $("#id_exclusao_registro").text();
      var dados = "EXCLUIR_REGISTRO_DETALHE="+id_exclusao;
          $.post({
              type:"POST",
              data:dados,
              url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
              success:function(resultado){
                 if(resultado == "1"){
                    lista_registros_detalhe($("#paginacao_selecionada").text());
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

function novo_detalhe(){

    var dados = $("#form_' . $_POST['NOME_TABELA_DETALHE'] . '").serialize();
        var registro_id= $("#'. $_POST['NOME_TABELA_DETALHE'] .'_'. $id_tabela_detalhe . '").val();
        if(registro_id ==""){
            dados = dados+"&CADASTRAR_DETALHE=1";
        }else{
            
            dados = dados+"&EDITAR_REGISTRO_DETALHE="+registro_id;
        }
        
        $.post({
            type:"POST",
            data:dados,
            url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
            success:function(resultado){
                if(resultado != "0"){
                    lista_registros_detalhe(1);
                    $("#'. $_POST['NOME_TABELA_DETALHE'] .'_'. $id_tabela_detalhe . '").val(resultado); 
                    $("#btn_novo_detalhe").show();
                    fast_alert_salvar();


                }else{
                    fast_alert_erro_salvar();

                }
           
            }
        });

}
function editar_detalhe(id){
    var dados = "LISTA_UNICO_REGISTRO_DETALHE="+id;
          $.post({
              type:"POST",
              data:dados,
              url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
              success:function(resultado){
                 if(resultado != 0){
                retorno=jQuery.parseJSON(resultado);
                ' . $resultado_registro_unico_detalhe . '
                 }else{
                   fast_alert_erro_listar();

                 }
              }
          }
          );
  }

  $("#btn_novo_' . $_POST['NOME_TABELA'] . '").click(function() {
    window.location.href = "' . $_POST['NOME_TABELA'] . '.php";
});
</script>
</body>
</html>';


    if (!is_dir($_POST['NOME_TABELA'])) {
        mkdir($_POST['NOME_TABELA'], 0777);
    }
    if (is_file($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '.php')) {

        unlink($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '.php');
    }

    if (is_file($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php')) {

        unlink($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php');
    }
    file_put_contents($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '.php', $form);

    
    /*--------------------------------------GERA LISTA -------------------------------------------------*/

    $lista = '<!doctype html>
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
                ' . $cabecalho_lista . '
                </tr>
                </thead> 
                <tbody id="tabela_' . $_POST['NOME_TABELA'] . '">
                </tbody>
         </table>      
     <div id="paginacao"></div>
     <div style="display:none" id="paginacao_selecionada"></div>
     
     </div>
     <!-- MODAL EXCLUSAO-->


<!------------------------------------------------------- Modal Exclusao -->
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

<div class="modal fade" id="modal_edicao_detalhe" tabindex="-1" aria-labelledby="modal_edicao_detalheLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_edicao_detalheLabel">Editar ' . $_POST['NOME_TABELA_DETALHE'] . '</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">' .
        $form_edit_detalhe . '
     
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
            url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
            success:function(resultado){
                retorno=jQuery.parseJSON(resultado);
                $("#tabela_' . $_POST['NOME_TABELA'] . '").html(retorno[\'tabela\']);
                $("#paginacao").html(retorno[\'paginacao\']);
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
              url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
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
    window.location.href = "' . $_POST['NOME_TABELA'] . '.php?master_id="+id;
      }else{
        window.location.href = "' . $_POST['NOME_TABELA'] . '.php"; 
      }
}

</script>
</body>
                ';

    if (is_file($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_lista.php')) {

        unlink($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_lista.php');
    }

    file_put_contents($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_lista.php', $lista);

    /*--------------------------------------GERA BACKEND REGRA DE NEGÓCIO-------------------------------------------------*/

    $campos_insert  = substr_replace($campos_insert, ')', -1);
    $valores_insert = substr_replace($valores_insert, ')";', -1);
    $valores_update = substr_replace($valores_update, '', -1);
    $backend = $campos_insert . ' VALUES ' . $valores_insert;

    $campos_insert_detalhe  = substr_replace($campos_insert_detalhe, ')', -1); 
    $valores_insert_detalhe = substr_replace($valores_insert_detalhe, ')";', -1);
    $valores_update_detalhe = substr_replace($valores_update_detalhe, '', -1);
    $backend_detalhe =$campos_insert_detalhe . ' VALUES ' . $valores_insert_detalhe;

    $insert = '<?php
require_once("../conexao.php");
/********************************INSERT NA TABELA*****************************/
if(isset($_POST["CADASTRAR"])){
$consulta = "INSERT INTO ' . $_POST['NOME_TABELA'] . ' ' . $backend . ' 
$cadastrar = $conexao->prepare($consulta);
' . $bind_paramentros . '
try{
$cadastrar ->execute();
$resultado = $conexao->query("SELECT LAST_INSERT_ID()");
$linha = $resultado->fetch(PDO::FETCH_ASSOC); 
echo $linha["LAST_INSERT_ID()"];
}catch(Exception $e){
    echo "0";
    //echo "Erro ao cadastrar: ". $e->getMessage();
}
}
/********************************INSERT NA TABELA DETALHE *****************************/
if(isset($_POST["CADASTRAR_DETALHE"])){
    $consulta = "INSERT INTO ' . $_POST['NOME_TABELA_DETALHE'] . ' ' . $backend_detalhe . ' 
    $cadastrar = $conexao->prepare($consulta);
    ' . $bind_paramentros_detalhe . '
try{
    $cadastrar ->execute();
    $resultado = $conexao->query("SELECT LAST_INSERT_ID()");
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    echo $linha["LAST_INSERT_ID()"];
}catch(Exception $e){
    echo "0";
    //echo "Erro ao cadastrar: ". $e->getMessage();
}
}

/*********************************EDITAR_REGISTRO********************************/
if(isset($_POST["EDITAR_REGISTRO"])){
    $consulta = "UPDATE ' . $_POST['NOME_TABELA'] . ' SET ' . $valores_update . '  WHERE ' . $id_tabela . '  = ".$_POST["EDITAR_REGISTRO"];
    $cadastrar = $conexao->prepare($consulta);
    '. $bind_paramentros .'
    try{
    $cadastrar ->execute();
    echo $_POST["EDITAR_REGISTRO"];
    }catch(Exception $e){
    
        echo "Erro ao editar: ". $e->getMessage();
    }
    }

    /*********************************EDITAR_REGISTRO DETALHE********************************/
    if(isset($_POST["EDITAR_REGISTRO_DETALHE"])){
        $consulta = "UPDATE ' . $_POST['NOME_TABELA_DETALHE'] . ' SET ' . $valores_update_detalhe . '  WHERE ' . $id_tabela_detalhe . '  = ".$_POST["EDITAR_REGISTRO_DETALHE"];
        $cadastrar = $conexao->prepare($consulta);
        ' . $bind_paramentros_detalhe . '
        try{
        $cadastrar ->execute();
        echo $_POST["EDITAR_REGISTRO_DETALHE"];
        }catch(Exception $e){
            echo "Erro ao editar: ". $e->getMessage();
        }
    }

    
    /********************************DELETE REGISTRO ***********************************/

    if(isset($_POST["EXCLUIR_REGISTRO"])){
        $consulta = "DELETE FROM ' . $_POST['NOME_TABELA'] . ' WHERE ' . $id_tabela . '  = ".$_POST["EXCLUIR_REGISTRO"];
        $cadastrar = $conexao->prepare($consulta);
 
        try{
        $resultado = $cadastrar ->execute();
        echo $resultado;
        }catch(Exception $e){
        
            echo "Erro ao deletar: ". $e->getMessage();
        }
        }
    

    /********************************DELETE REGISTRO DETALHE***********************************/

    if(isset($_POST["EXCLUIR_REGISTRO_DETALHE"])){
        $consulta = "DELETE FROM ' . $_POST['NOME_TABELA_DETALHE'] . ' WHERE ' . $id_tabela_detalhe . '  = ".$_POST["EXCLUIR_REGISTRO_DETALHE"];
        $cadastrar = $conexao->prepare($consulta);
 
        try{
        $resultado = $cadastrar ->execute();
        echo $resultado;
        }catch(Exception $e){
        
            echo "Erro ao deletar: ". $e->getMessage();
        }
        }
    

    
/********************************LISTA TABELA***********************************/

if(isset($_POST["LISTAR_REGISTROS"])){

    $consulta = "SELECT  COUNT(*) FROM ' . $_POST['NOME_TABELA'] . '"; 
    $resultado = $conexao->query( $consulta );
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    $count_tabela =  $linha["COUNT(*)"];
    $limit = "LIMIT ".(($_POST["LISTAR_REGISTROS"] - 1) * 10)." ,10";
     
      $quantidade_paginas = ceil($count_tabela / 10); 
      $i = ($_POST["LISTAR_REGISTROS"]-1);
      $paginacao = \'<nav aria-label="Page navigation example">
  <ul class="pagination">
  <li class="page-item"><a style="cursor:pointer" class="page-link" onclick="lista_registros(\'.($_POST["LISTAR_REGISTROS"]-1).\')">Anterior</a></li>
  \';
  $proxima_pagina = ($_POST["LISTAR_REGISTROS"]+1);
   if($proxima_pagina  > $quantidade_paginas ){

    $proxima_pagina = $quantidade_paginas;
   }
  if ($quantidade_paginas > 0)  {
      while($i < $quantidade_paginas and $i < ($_POST["LISTAR_REGISTROS"]+5) ){
        $i++;
        $paginacao.=\'<li class="page-item"><a style="cursor:pointer" class="page-link" onclick="lista_registros(\'.$i.\')">\'.$i.\'</a></li>\';
      }
  }
      $paginacao.=\'<li class="page-item"><a style="cursor:pointer"class="page-link" onclick="lista_registros(\'.$proxima_pagina.\')" >Próximo</a></li>
      </ul>
      
    </nav>
  \';

    $registros_tabela ="";
    $consulta = "SELECT  ' . substr_replace($select_lista, '', -1) . ' FROM ' . $_POST['NOME_TABELA'] . ' $limit ";
    $resultado = $conexao->query( $consulta );
    while($linha = $resultado->fetch(PDO::FETCH_ASSOC)){

        $registros_tabela .=  "' . "<tr>
        <td><i onclick='abrir_registro(" . '".' . '$linha[' . "'" . $id_tabela . "'" . '] .")' . " 'class='fas fa-edit'></i> 
        <i  data-bs-toggle='modal' data-bs-target='#modal_exclusao'onclick='seleciona_registro_excluir(" . '".' . '$linha[' . "'" . $id_tabela . "'" . '] .")' . " 'class='fas fa-trash-alt'></i> 
        </td>" . $linha_registro . '</tr>";
    
}

echo json_encode([\'tabela\'=>$registros_tabela,\'paginacao\'=>$paginacao]);
}


/********************************LISTA TABELA DETALHE***********************************/

if(isset($_POST["LISTAR_REGISTROS_DETALHE"])){
      $master_id = $_POST["'.$id_tabela.'"];
    $consulta = "SELECT  COUNT(*) FROM ' . $_POST['NOME_TABELA_DETALHE'] . 
    '  WHERE '.$id_tabela.' = $master_id";
    $resultado = $conexao->query( $consulta );
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    $count_tabela =  $linha["COUNT(*)"];
    $limit = "LIMIT ".(($_POST["LISTAR_REGISTROS_DETALHE"] - 1) * 10)." ,10";
     
      $quantidade_paginas = ceil($count_tabela / 10); 
      $i = ($_POST["LISTAR_REGISTROS_DETALHE"]-1);
      $paginacao = \'<nav aria-label="Page navigation example">
  <ul class="pagination">
  <li class="page-item"><a style="cursor:pointer" class="page-link" onclick="lista_registros_detalhe(\'.($_POST["LISTAR_REGISTROS_DETALHE"]-1).\')">Anterior</a></li>
  \';
  $proxima_pagina = ($_POST["LISTAR_REGISTROS_DETALHE"]+1);
   if($proxima_pagina  > $quantidade_paginas ){

    $proxima_pagina = $quantidade_paginas;
   }
  if ($quantidade_paginas > 0)  {
      while($i < $quantidade_paginas and $i < ($_POST["LISTAR_REGISTROS_DETALHE"]+5) ){
        $i++;
        $paginacao.=\'<li style="cursor:pointer" class="page-item"><a class="page-link" onclick="lista_registros_detalhe(\'.$i.\')">\'.$i.\'</a></li>\';
      }
  }
      $paginacao.=\'<li style="cursor:pointer" class="page-item"><a class="page-link" onclick="lista_registros_detalhe(\'.$proxima_pagina.\')" >Próximo</a></li>
      </ul>
    </nav>
  \';

    $registros_tabela ="";
    $consulta = "SELECT  ' . substr_replace($select_lista_detalhe, '', -1) . ' FROM ' . $_POST['NOME_TABELA_DETALHE'] .'   WHERE '.$id_tabela.' = $master_id";
    $resultado = $conexao->query( $consulta );
    while($linha_detalhe = $resultado->fetch(PDO::FETCH_ASSOC)){

        $registros_tabela .=  "' . "<tr>
        <td><i data-bs-toggle='modal' data-bs-target='#modal_" . $_POST['NOME_TABELA_DETALHE'] . "' onclick='editar_detalhe(" . '".' . '$linha_detalhe[' . "'" . $id_tabela_detalhe . "'" . '] .")' . " 'class='fas fa-edit'></i> 
        <i  data-bs-toggle='modal' data-bs-target='#modal_exclusao'onclick='seleciona_registro_excluir(" . '".' . '$linha_detalhe[' . "'" . $id_tabela_detalhe . "'" . '] .")' . " 'class='fas fa-trash-alt'></i> 
        </td>" . $linha_registro_detalhe . '</tr>";
    
}

echo json_encode([\'tabela\'=>$registros_tabela,\'paginacao\'=>$paginacao]);
}

/********************************LISTA UNICO REGISTRO***********************************/

if(isset($_POST["LISTA_UNICO_REGISTRO"])){

    $consulta = "SELECT  ' . substr_replace($select_lista, '', -1) . ' FROM ' . $_POST['NOME_TABELA'] . ' WHERE ' . $id_tabela . '  = ".$_POST["LISTA_UNICO_REGISTRO"];
    $resultado = $conexao->query( $consulta );
    if($linha = $resultado->fetch(PDO::FETCH_ASSOC)){

        $array_registro= [' . substr_replace( $linha_registro_unico, '', -1) . '];
        echo json_encode($array_registro);
    
}else{
    echo "0";
}

}


/********************************LISTA UNICO REGISTRO DETALHE***********************************/

if(isset($_POST["LISTA_UNICO_REGISTRO_DETALHE"])){

    $consulta = "SELECT  ' . substr_replace($select_lista_detalhe, '', -1) . ' FROM ' . $_POST['NOME_TABELA_DETALHE'] . ' WHERE ' . $id_tabela_detalhe . '  = ".$_POST["LISTA_UNICO_REGISTRO_DETALHE"];
    $resultado = $conexao->query( $consulta );
    if($linha_detalhe = $resultado->fetch(PDO::FETCH_ASSOC)){

        $array_registro= ['  . substr_replace( $linha_registro_unico_detalhe, '', -1). '];
        echo json_encode($array_registro);
    
}else{
    echo "0";
}

}

/**********************************PAGINAÇAO *********************************************/
if (isset($_POST["PAGINACAO"])) {
$consulta = "SELECT  COUNT(*) FROM ' . $_POST['NOME_TABELA'] . '"; 
    $resultado = $conexao->query( $consulta );
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    $count_tabela =  $linha["COUNT(*)"];

$quantidade_paginas = ceil($count_tabela / 4); 
    $i = 0;
    $paginacao = \'<nav aria-label="Page navigation example">
<ul class="pagination">
<li class="page-item"><a class="page-link" href="#">Previous</a></li>
\';

if ($quantidade_paginas > 0) {
    while($i < $quantidade_paginas ){
      $i++;
      $paginacao.=\'<li class="page-item"><a class="page-link" href="#">\'.$i.\'</a></li>\';
    
    }
}
    $paginacao.=\'<li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
\';
echo $paginacao;
}

/**********************************PAGINAÇAO *********************************************/
if (isset($_POST["PAGINACAO_DETALHE"])) {
$consulta = "SELECT  COUNT(*) FROM ' . $_POST['NOME_TABELA_DETALHE'] . '"; 
    $resultado = $conexao->query( $consulta );
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    $count_tabela =  $linha["COUNT(*)"];

$quantidade_paginas = ceil($count_tabela / 4); 
    $i = 0;
    $paginacao = \'<nav aria-label="Page navigation example">
<ul class="pagination">
<li class="page-item"><a class="page-link" href="#">Previous</a></li>
\';

if ($quantidade_paginas > 0) {
    while($i < $quantidade_paginas ){
      $i++;
      $paginacao.=\'<li class="page-item"><a class="page-link" href="#">\'.$i.\'</a></li>\';
    
    }
}
    $paginacao.=\'<li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
\';
echo $paginacao;
}
';
    file_put_contents($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php', $insert);


/****************************************************************FUNÇOES PHP*******************************************/

function tipo_campo($tipo_campo)
{
    if (substr($tipo_campo, 0, 3) == 'int' or substr($tipo_campo, 0, 8) == 'decimal') {
        return 'number';
    }
    if (substr($tipo_campo, 0, 5) == 'varchar') {
        return 'text';
    }

    if ($tipo_campo == 'date') {
        return 'date';
    }
    if ($tipo_campo == 'datetime') {
        return 'datetime-local';
    }
    if ($tipo_campo == 'time') {
        return 'time';
    }
    if (substr($tipo_campo, 0, 6) == 'varchar' or substr($tipo_campo, 0, 5) == 'text') {
        return 'text';
    } else {
        return 'text';
    }
}

function read_only($auto_incremento)
{
    if ($auto_incremento == 'auto_increment') {
        return 'readonly';
    } else {
        return '';
    }
}

