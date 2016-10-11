<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("model/PadArquivoEscritorTXT.model.php");

db_app::import("exceptions.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->lista   = array();
switch ($oParam->exec) {

  case "getDadosTCE":

    $iAnoUsu        = db_getsession("DB_anousu");
    $codigoOrgao    = $oParam->orgao;
    $codigoUnidade  = $oParam->unidade;

    $sSqlUnidade = "select 
                      orgao, 
                      unidade, 
                      codigotribunal, 
                      coalesce(nometribunal, nomeinst) as o41_descr
                  from plugins.orcunidadecodigotce
                    left join orcunidade on o41_orgao   = orgao
                                        and o41_unidade = unidade
                                        and o41_anousu  = anousu
                    left join db_config  on codigo = instit 
                  where (orgao  = {$codigoOrgao}
                    and unidade = {$codigoUnidade}
                    and anousu  = {$iAnoUsu}) 
                      or
                    ({$codigoOrgao} = '00'
                        and codigo  = {$codigoUnidade})";
    $rsUnidade = db_query($sSqlUnidade);

    if(pg_num_rows($rsUnidade) == 0) {
      $oRetorno->msg    = urlencode("Não foi encontrado o código nem nome da unidade para o TCE, por favor, digite.");
      $oRetorno->status = 0;
      break;
    }

    $oUnidade  = db_utils::fieldsMemory($rsUnidade, 0);
    
    $codigoOrgaoTCE = $oUnidade->codigotribunal;
    $nomeUnidade = $oUnidade->o41_descr;
    
    $oRetorno->codigoOrgao = $codigoOrgaoTCE;
    $oRetorno->nomeUnidade = $nomeUnidade;

    break;

  case "processarSiai":
    try {

      $oEscritor      = new PadArquivoEscritorTXT();
      $iAnoUsu        = db_getsession("DB_anousu");
      $iInstituicao   = db_getsession("DB_instit");
      $sMesFinal      = str_pad($oParam->iPeriodo, 2, "0", STR_PAD_LEFT);
      $sMesInicial    = $sMesFinal -1;
      $sDataInicial   = "{$iAnoUsu}-{$sMesInicial}-01";
      $iUltimoDiaMes  = cal_days_in_month(CAL_GREGORIAN, $sMesFinal, $iAnoUsu);
      $sDataFinal     = "{$iAnoUsu}-{$sMesFinal}-{$iUltimoDiaMes}";
      $sDataGeracao   = date('d/m/Y', time());
      $sHoraGeracao   = date('H:i:s', time());
      $otxtLogger     = fopen("tmp/SIAI.log", "w");
      $codigoOrgaoTCE = $oParam->codigoOrgaoTCE;
      $nomeUnidade    = $oParam->nomeUnidadeTCE;
      $codigoOrgao    = $oParam->orgao;
      $codigoUnidade  = $oParam->unidade;
      $sBimRef        = "";
    
      switch ($oParam->iPeriodo) {

          case 2:
              $sBimRef = "{$iAnoUsu}01";
              break;
          case 4:
              $sBimRef = "{$iAnoUsu}02";
              break;
          case 6:
              $sBimRef = "{$iAnoUsu}03";
              break;
          case 8:
              $sBimRef = "{$iAnoUsu}04";
              break;
          case 10:
              $sBimRef = "{$iAnoUsu}05";
              break;
          case 12:
              $sBimRef = "{$iAnoUsu}06";
              break;
          default:
              $sBimRef = "Erro ao definir o bimestre de referência";
              break;
      }
      
      if (count($oParam->aArquivos) > 0) {

        foreach ($oParam->aArquivos as $sArquivo) {

          /**
           * Verifica dinamicamente se a classe existe, se Existe cria uma instancia da classe
           */
          if (file_exists("model/contabilidade/arquivos/siai/Siai{$sArquivo}.model.php")) {

            require_once("model/contabilidade/arquivos/siai/Siai{$sArquivo}.model.php");
            $sNomeClasse = "Siai{$sArquivo}";
            $oArquivo    = new $sNomeClasse;

            $oArquivo->setDataInicial($sDataInicial);
            $oArquivo->setDataFinal($sDataFinal);
            $oArquivo->setDataGeracao($sDataGeracao);
            $oArquivo->setHoraGeracao($sHoraGeracao);
            $oArquivo->setCodigoOrgaoTCE($codigoOrgaoTCE);
            $oArquivo->setNomeUnidade($nomeUnidade);

            $oArquivo->setCodigoOrgao($codigoOrgao);
            $oArquivo->setCodigoUnidade($codigoUnidade);

            $oArquivo->setTXTLogger($otxtLogger);
            $oArquivo->setBimReferencia($sBimRef);
            $oArquivo->gerarDados();

            $oEscritor->adicionarArquivo("tmp/{$oArquivo->getNomeArquivo()}", "{$oArquivo->getNomeArquivo()}");
          }
        }

        $oEscritor->zip("SIAI");
        $oEscritor->adicionarArquivo("tmp/SIAI.log", "SIAI.LOG");
        $oRetorno->lista = $oEscritor->getListaArquivos();

       }

    } catch (Exception $eErro) {

      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 0;
    }
    break;
}

echo $oJson->encode($oRetorno);
