<?php
require_once('conexao.php');
if (isset($_POST['NOME_TABELA'])) {
    /*--------------------------------------GERA TELA MESTRE DETALHE FRONT END-------------------------------------------------*/
    $resultado = $conexao->query('DESCRIBE ' . $_POST['NOME_TABELA']);

    $campos_insert = '(';
    $bind_paramentros = '';
    $valores_insert = '(';
    $cabecalho_lista = '<th scope="col">#</th>';
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


        $cabecalho_lista .= '<th scope="col">' . ucwords(str_replace('_',' ',$linha["Field"])) . '</th>
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
        <div style="display:none;position: absolute;"  id="sucesso"></div>
  
            <form id="form_' . $_POST['NOME_TABELA'] . '" class="row g-3">';
    $form .= $form_edit;
    $form .= '<div class="row">
    <div form-group col-md-2>
    <button id="btn_salvar_' . $_POST['NOME_TABELA'] . '"   type="button" class="btn btn-primary mb-3">Salvar</button>
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
                    fast_alert_salvar();
                }else{
                    fast_alert_erro_salvar();

                }
           
            }
        });
    });
});



</script>
<script>
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
</html>';
    $form_edit = '<form id="form_' . $_POST['NOME_TABELA'] . '">
' . $form_edit . '
<div class="col-auto">
<button id="btn_salvar_' . $_POST['NOME_TABELA'] . '"   type="button" class="btn btn-primary mb-3">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../libs/jquery/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
$("#btn_salvar_' . $_POST['NOME_TABELA'] . '").click(function() {
    var dados = $("#form_' . $_POST['NOME_TABELA'] . '").serialize();
    var registro_id= $("#' . $id_tabela . '").val();
    dados = dados+"&EDITAR_REGISTRO="+registro_id;
    $.post({
        type:"POST",
        data:dados,
        url:"../' . $_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php",
        success:function(resultado){
            lista_registros($("#paginacao_selecionada").text());
        
        }
    }
    );
});
});
</script>
';



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

    window.location.href = "' . $_POST['NOME_TABELA'] . '.php?master_id="+id;
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


/*********************************EDITAR_REGISTRO********************************/
if(isset($_POST["EDITAR_REGISTRO"])){
    $consulta = "UPDATE ' . $_POST['NOME_TABELA'] . ' SET ' . $valores_update . '  WHERE ' . $id_tabela . '  = ".$_POST["EDITAR_REGISTRO"];
    $cadastrar = $conexao->prepare($consulta);
    ' . $bind_paramentros . '
    try{
    $cadastrar ->execute();
    echo $_POST["EDITAR_REGISTRO"];
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
        
            echo "Erro ao cadastrar: ". $e->getMessage();
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
  <li class="page-item"><a class="page-link" onclick="lista_registros(\'.($_POST["LISTAR_REGISTROS"]-1).\')">Anterior</a></li>
  \';
  $proxima_pagina = ($_POST["LISTAR_REGISTROS"]+1);
   if($proxima_pagina  > $quantidade_paginas ){

    $proxima_pagina = $quantidade_paginas;
   }
  if ($quantidade_paginas > 0)  {
      while($i < $quantidade_paginas and $i < ($_POST["LISTAR_REGISTROS"]+5) ){
        $i++;
        $paginacao.=\'<li class="page-item"><a class="page-link" onclick="lista_registros(\'.$i.\')">\'.$i.\'</a></li>\';
      }
  }
      $paginacao.=\'<li class="page-item"><a class="page-link" onclick="lista_registros(\'.$proxima_pagina.\')" >Próximo</a></li>
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


/********************************LISTA UNICO REGISTRO***********************************/

if(isset($_POST["LISTA_UNICO_REGISTRO"])){

    $consulta = "SELECT  ' . substr_replace($select_lista, '', -1) . ' FROM ' . $_POST['NOME_TABELA'] . ' WHERE ' . $id_tabela . '  = ".$_POST["LISTA_UNICO_REGISTRO"];
    $resultado = $conexao->query( $consulta );
    if($linha = $resultado->fetch(PDO::FETCH_ASSOC)){

        $array_registro= [' . substr_replace($linha_registro_unico, '', -1) . '];
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




';
    file_put_contents($_POST['NOME_TABELA'] . '/' . $_POST['NOME_TABELA'] . '_backend.php', $insert);


//FUNÇOES PHP

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

