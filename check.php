<?php
$c=new PDO('mysql:host=localhost;dbname=finanzas_personales', 'root', '');
$s=$c->query('DESCRIBE categorias');
print_r($s->fetchAll(PDO::FETCH_ASSOC));
