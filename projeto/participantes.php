<?
/*
  IFPB - Curso Superior em Tecnologia de Redes de Computadores
  Disciplina: Desenvolvimento Web
  Professor: Luiz Carlos
  Projeto: Grafico de acesso a participantes do PTT-SP
  Aluno: Bruno Lopes F. Cabral

  Codigo para voltar a lista de participantes do PTT
  TODO: permitir selecionar qual PTT (campo localidade da mesma pagina)
  TODO: voltar tambem o nome do participante (segunda coluna)
*/

require_once("goutte.phar");

header("Content-type: application/json");

use Goutte\Client;
$client = new Client();
//$client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 5);

@$crawler = $client->request('GET', 'http://ptt.br/particip/sp');
$status_code = $client->getResponse()->getStatus();

if($status_code==200){
  $array = $crawler->filter('table.branco:nth-child(2) > tr:nth-child(n+3) > td:first-child')->each(function ($node) {
    return intval($node->text());
  });
} else 
  $array = array();
echo json_encode($array);

