<?
/*
  IFPB - Curso Superior em Tecnologia de Redes de Computadores
  Disciplina: Desenvolvimento Web
  Professor: Luiz Carlos
  Projeto: Grafico de acesso a participantes do PTT-SP
  Aluno: Bruno Lopes F. Cabral

  Obtem os totais (acessos PTT e de TRANSITO) e retorna em JSON para chartjs
  TODO: chamar diretamente participantes.php?
*/

  header("Content-type: application/json");
  require_once("db.php");

  $url = "http://localhost/projeto/participantes.php?".$_SERVER['QUERY_STRING'];
  $array = json_decode(file_get_contents($url), true);

  // PHP 5.3 needs temporary variable to access array element returned by func
  $tmp = sumAll($array,false);
  $resto = intval($tmp[0]);
  $tmp = sumAll($array,true);
  $ptt = intval($tmp[0]);

  // percentage calculation
  $pttp = intval( $ptt / ($ptt+$resto) * 100);
  $restop = 100 - $pttp;
  
  // format required by chartjs
  $data = array(
    array(
	"value" => $ptt, 
	"color" => "#46BFBD",
	"highlight" => "#5AD3D1",
	"label" => "PTT ".$_SERVER['QUERY_STRING']." ".$pttp."%"
    ),
    array(
	"value" => $resto,
	"color" => "#FDB45C",
	"highlight" => "#FFC870",
	"label" => "Transito ".$restop."%"
    ));
  echo json_encode($data);
?>
