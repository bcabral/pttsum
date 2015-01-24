<?
/*
  IFPB - Curso Superior em Tecnologia de Redes de Computadores
  Disciplina: Desenvolvimento Web
  Professor: Luiz Carlos
  Projeto: Grafico de acesso a participantes do PTT-SP
  Aluno: Bruno Lopes F. Cabral

  Codigo para voltar a lista de localidades que tem PTT
*/

require_once("goutte.phar");

header("Content-type: application/json");

use Goutte\Client;
$client = new Client();
//$client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 5);

@$crawler = $client->request('GET', 'http://ptt.br/particip/sp');
$status_code = $client->getResponse()->getStatus();

if($status_code==200){
  $array = $crawler->filter('option[value]')->each(function ($node) {
   return array( "option" => $node->attr("value"), "text" => $node->text());
  });
//array_shift(&$array);
} else 
  $array = array();
echo json_encode($array);

