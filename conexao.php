<?php
$drive ='mysql:';
$dbname ='pousada';
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