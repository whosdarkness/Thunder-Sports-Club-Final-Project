<?php
$con = mysqli_connect('localhost', 'root', '12345', 'clube_desp');
if (!$con) {
    echo 'conn fail';
    exit(1);
}
$tables = ['modalidade', 'inscricao_mod_socio', 'pedidos_treino', 'propostas_treino', 'socios', 'users'];
foreach ($tables as $table) {
    echo "TABLE: $table\n";
    $res = mysqli_query($con, "DESCRIBE $table");
    while ($row = mysqli_fetch_assoc($res)) {
        echo sprintf("%s %s %s %s %s %s\n", $row['Field'], $row['Type'], $row['Null'], $row['Key'], $row['Default'], $row['Extra']);
    }
    echo "\n";
}
?>