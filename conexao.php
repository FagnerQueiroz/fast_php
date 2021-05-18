<?php
$drive ='mysql:';
$dbname ='nome_do_banco';
$host='host=127.0.0.1';
$db='dbname='.$dbname;
$password = '';
$dsn = $drive.$db.';'.$host;
$user = 'root';
$conexao = new PDO($dsn, $user, $password);
$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if($conexao == false){
    echo 'Sem conexão com banco de dados!';
}
?>
