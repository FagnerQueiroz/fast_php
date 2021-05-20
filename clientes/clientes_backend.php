<?php
require_once("../conexao.php");
/********************************INSERT NA TABELA*****************************/
if(isset($_POST["CADASTRAR"])){
$consulta = "INSERT INTO clientes (tipo,nome,inscricao_federal,logradouro,data_nascimento,bairro,cidade,uf,cep) VALUES (:tipo,:nome,:inscricao_federal,:logradouro,:data_nascimento,:bairro,:cidade,:uf,:cep)"; 
$cadastrar = $conexao->prepare($consulta);
$cadastrar->bindParam(":tipo", $_POST["tipo"], PDO::PARAM_STR);
$cadastrar->bindParam(":nome", $_POST["nome"], PDO::PARAM_STR);
$cadastrar->bindParam(":inscricao_federal", $_POST["inscricao_federal"], PDO::PARAM_STR);
$cadastrar->bindParam(":logradouro", $_POST["logradouro"], PDO::PARAM_STR);
$cadastrar->bindParam(":data_nascimento", $_POST["data_nascimento"], PDO::PARAM_STR);
$cadastrar->bindParam(":bairro", $_POST["bairro"], PDO::PARAM_STR);
$cadastrar->bindParam(":cidade", $_POST["cidade"], PDO::PARAM_STR);
$cadastrar->bindParam(":uf", $_POST["uf"], PDO::PARAM_STR);
$cadastrar->bindParam(":cep", $_POST["cep"], PDO::PARAM_STR);

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
    $consulta = "UPDATE clientes SET tipo=:tipo,nome=:nome,inscricao_federal=:inscricao_federal,logradouro=:logradouro,data_nascimento=:data_nascimento,bairro=:bairro,cidade=:cidade,uf=:uf,cep=:cep  WHERE cliente_id  = ".$_POST["EDITAR_REGISTRO"];
    $cadastrar = $conexao->prepare($consulta);
    $cadastrar->bindParam(":tipo", $_POST["tipo"], PDO::PARAM_STR);
$cadastrar->bindParam(":nome", $_POST["nome"], PDO::PARAM_STR);
$cadastrar->bindParam(":inscricao_federal", $_POST["inscricao_federal"], PDO::PARAM_STR);
$cadastrar->bindParam(":logradouro", $_POST["logradouro"], PDO::PARAM_STR);
$cadastrar->bindParam(":data_nascimento", $_POST["data_nascimento"], PDO::PARAM_STR);
$cadastrar->bindParam(":bairro", $_POST["bairro"], PDO::PARAM_STR);
$cadastrar->bindParam(":cidade", $_POST["cidade"], PDO::PARAM_STR);
$cadastrar->bindParam(":uf", $_POST["uf"], PDO::PARAM_STR);
$cadastrar->bindParam(":cep", $_POST["cep"], PDO::PARAM_STR);

    try{
    $cadastrar ->execute();
    echo $_POST["EDITAR_REGISTRO"];
    }catch(Exception $e){
    
        echo "Erro ao editar: ". $e->getMessage();
    }
    }
    
    /********************************DELETE REGISTRO ***********************************/

    if(isset($_POST["EXCLUIR_REGISTRO"])){
        $consulta = "DELETE FROM clientes WHERE cliente_id  = ".$_POST["EXCLUIR_REGISTRO"];
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

    $consulta = "SELECT  COUNT(*) FROM clientes"; 
    $resultado = $conexao->query( $consulta );
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    $count_tabela =  $linha["COUNT(*)"];
    $limit = "LIMIT ".(($_POST["LISTAR_REGISTROS"] - 1) * 10)." ,10";
     
      $quantidade_paginas = ceil($count_tabela / 10); 
      $i = ($_POST["LISTAR_REGISTROS"]-1);
      $paginacao = '<nav aria-label="Page navigation example">
  <ul class="pagination">
  <li style="cursor:pointer" class="page-item"><a  class="page-link" onclick="lista_registros('.($_POST["LISTAR_REGISTROS"]-1).')">Anterior</a></li>
  ';
  $proxima_pagina = ($_POST["LISTAR_REGISTROS"]+1);
   if($proxima_pagina  > $quantidade_paginas ){

    $proxima_pagina = $quantidade_paginas;
   }
  if ($quantidade_paginas > 0)  {
      while($i < $quantidade_paginas and $i < ($_POST["LISTAR_REGISTROS"]+5) ){
        $i++;
        $paginacao.='<li style="cursor:pointer" class="page-item"><a class="page-link" onclick="lista_registros('.$i.')">'.$i.'</a></li>';
      }
  }
      $paginacao.='<li style="cursor:pointer" class="page-item"><a class="page-link" onclick="lista_registros('.$proxima_pagina.')" >Próximo</a></li>
      </ul>
    </nav>
  ';

    $registros_tabela ="";
    $consulta = "SELECT   cliente_id, tipo, nome, inscricao_federal, logradouro, data_nascimento, bairro, cidade, uf, cep FROM clientes $limit ";
    $resultado = $conexao->query( $consulta );
    while($linha = $resultado->fetch(PDO::FETCH_ASSOC)){

        $registros_tabela .=  "<tr>
        <td><i onclick='abrir_registro(".$linha['cliente_id'] .") 'class='fas fa-edit'></i> 
        <i  data-bs-toggle='modal' data-bs-target='#modal_exclusao'onclick='seleciona_registro_excluir(".$linha['cliente_id'] .") 'class='fas fa-trash-alt'></i> 
        </td>
        <td>".$linha["cliente_id"]."</td>
        <td>".$linha["tipo"]."</td>
        <td>".$linha["nome"]."</td>
        <td>".$linha["inscricao_federal"]."</td>
        <td>".$linha["logradouro"]."</td>
        <td>".$linha["data_nascimento"]."</td>
        <td>".$linha["bairro"]."</td>
        <td>".$linha["cidade"]."</td>
        <td>".$linha["uf"]."</td>
        <td>".$linha["cep"]."</td></tr>";
    
}

echo json_encode(['tabela'=>$registros_tabela,'paginacao'=>$paginacao]);
}


/********************************LISTA UNICO REGISTRO***********************************/

if(isset($_POST["LISTA_UNICO_REGISTRO"])){

    $consulta = "SELECT   cliente_id, tipo, nome, inscricao_federal, logradouro, data_nascimento, bairro, cidade, uf, cep FROM clientes WHERE cliente_id  = ".$_POST["LISTA_UNICO_REGISTRO"];
    $resultado = $conexao->query( $consulta );
    if($linha = $resultado->fetch(PDO::FETCH_ASSOC)){

        $array_registro= ["cliente_id"=>$linha["cliente_id"],"tipo"=>$linha["tipo"],"nome"=>$linha["nome"],"inscricao_federal"=>$linha["inscricao_federal"],"logradouro"=>$linha["logradouro"],"data_nascimento"=>$linha["data_nascimento"],"bairro"=>$linha["bairro"],"cidade"=>$linha["cidade"],"uf"=>$linha["uf"],"cep"=>$linha["cep"]];
        echo json_encode($array_registro);
    
}else{
    echo "0";
}

}

/**********************************PAGINAÇAO *********************************************/
if (isset($_POST["PAGINACAO"])) {
$consulta = "SELECT  COUNT(*) FROM clientes"; 
    $resultado = $conexao->query( $consulta );
    $linha = $resultado->fetch(PDO::FETCH_ASSOC); 
    $count_tabela =  $linha["COUNT(*)"];

$quantidade_paginas = ceil($count_tabela / 4); 
    $i = 0;
    $paginacao = '<nav aria-label="Page navigation example">
<ul class="pagination">
<li class="page-item"><a class="page-link" href="#">Previous</a></li>
';

if ($quantidade_paginas > 0) {
    while($i < $quantidade_paginas ){
      $i++;
      $paginacao.='<li class="page-item"><a class="page-link" href="#">'.$i.'</a></li>';
    
    }
}
    $paginacao.='<li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
';
echo $paginacao;
}




