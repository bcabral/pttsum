<?
/*
  IFPB - Curso Superior em Tecnologia de Redes de Computadores
  Disciplina: Desenvolvimento Web
  Professor: Luiz Carlos
  Projeto: Grafico de acesso a participantes do PTT-SP
  Aluno: Bruno Lopes F. Cabral

  Acesso ao banco de dados que eh alimentado pelo coletor de flows
  TODO: tratar array vazio
*/

  function getDBConnection($dbname = "netflow"){
    $user = "root";
    $password = "123";
    $db = "mysql";
    $host = "localhost";
    $dsn = ($dbname=="netflow")?"$db:dbname=$dbname;host=$host":"$db:host=$host";
    try { // Database Error Handling
      return new PDO($dsn, $user, $password);
    } catch (Exception $e) {
      echo 'Connection failed: ' . $e->getMessage();
    }
  }

  $connection = getDBConnection();

  function sumAll($array, $ptt){
    global $connection;
    $resultSet = $connection->prepare("SELECT SUM(DOCTETS) AS resultado FROM flows WHERE DST_AS " . ($ptt?"":"NOT") . " IN (:array)");	
    $resultSet->bindValue(":array", (string) $array);
    $resultSet->execute();
    return $resultSet->fetch();
  }

//$url = "http://localhost/projeto/participantes.php";
//$array = json_decode(file_get_contents($url), true);
//var_dump(sumAll($array,false));
//var_dump(sumAll($array,true));
?>

