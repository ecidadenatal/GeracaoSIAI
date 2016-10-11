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
require_once("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/siai/SiaiArquivoBase.model.php");
require_once ("libs/db_liborcamento.php");

class SiaiObras extends SiaiArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 2000005;

  /**
  * Busca os dados para gerar o Arquivo de Obras
  */
  public function gerarDados() {
  
    $sNomeArquivo = 'OBRAS';
    $lDebug = true;
    if ($lDebug) {
        $arqFinal = fopen ( "tmp/{$sNomeArquivo}.txt", 'w+' );
    }

    $this->setNomeArquivo($sNomeArquivo.".txt");
    //HEADER

    $oDadosHeader = new stdClass();
    
    $oDadosHeader->TipRegistro     = "0";
    $oDadosHeader->NomeArquivo     = str_pad("OBRAS", 10, " ", STR_PAD_RIGHT);
    $oDadosHeader->BimReferencia   = $this->sBimReferencia;
    $oDadosHeader->TipoArquivo     = "O";   
    $oDadosHeader->DataGeracaoArq  = $this->dtDataGeracao;
    $oDadosHeader->HoraGeracaoArq  = $this->dtHoraGeracao;
    $oDadosHeader->CodigoOrgao     = $this->codigoOrgaoTCE;
    $oDadosHeader->NomeUnidade     = str_pad(substr($this->nomeUnidade,0,100), 100, " ", STR_PAD_RIGHT);
    $oDadosHeader->Brancos         = " ";
    $oDadosHeader->NumRegistroLido = "0000000001";
        
    $oDadosHeader->codigolinha    = 2000757;

    $this->aDados[] = $oDadosHeader; 

    if ($lDebug) {
      $sLinhaHeader =  $oDadosHeader->TipRegistro    .
                       $oDadosHeader->NomeArquivo    .
                       $oDadosHeader->BimReferencia  .
                       $oDadosHeader->TipoArquivo    .
                       $oDadosHeader->DataGeracaoArq .
                       $oDadosHeader->HoraGeracaoArq .
                       $oDadosHeader->CodigoOrgao    .
                       $oDadosHeader->NomeUnidade    .
                       $oDadosHeader->Brancos       .
                       $oDadosHeader->NumRegistroLido;
      fputs($arqFinal, $sLinhaHeader."\r\n");
    }

    //DETALHE 1

    $oDadosDetalhe1 = new stdClass();
        
    $oDadosDetalhe1->TipRegistro        = "1";
    $oDadosDetalhe1->IdObra             = "111";
    $oDadosDetalhe1->Obra               = "123";
    $oDadosDetalhe1->Objetivo           = "1";
    $oDadosDetalhe1->Localizacao        = "111";
    $oDadosDetalhe1->IdCidade           = "123";
    $oDadosDetalhe1->Fonte1             = "1";
    $oDadosDetalhe1->Valor1             = "111";
    $oDadosDetalhe1->Fonte2             = "123";
    $oDadosDetalhe1->Valor2             = "1";
    $oDadosDetalhe1->Fonte3             = "111";
    $oDadosDetalhe1->Valor3             = "123";
    $oDadosDetalhe1->OrcamentoBase      = "1";
    $oDadosDetalhe1->ProjetosExistentes = "111";
    $oDadosDetalhe1->Obs                = "123";
    $oDadosDetalhe1->Latitude           = "111";
    $oDadosDetalhe1->Longitude          = "123";
    $oDadosDetalhe1->RDC                = "1";

    $oDadosDetalhe1->codigolinha        = 2000758;

    $this->aDados[] = $oDadosDetalhe1;
    

    //DETALHE 2

    $oDadosDetalhe2 = new stdClass();
     
    $oDadosDetalhe2->TipRegistro        = "2";   
    $oDadosDetalhe2->IdServico          = "344";
    $oDadosDetalhe2->IdObra             = "23";
    $oDadosDetalhe2->Servico            = "42";
    $oDadosDetalhe2->ProcessoLicitacao  = "2";   
    $oDadosDetalhe2->Empresa            = "344";
    $oDadosDetalhe2->CNPJ               = "23";
    $oDadosDetalhe2->NContrato          = "42";
    $oDadosDetalhe2->ValorContrato      = "2";   
    $oDadosDetalhe2->ExecutadoExercicio = "344";
    $oDadosDetalhe2->AExecutar          = "23";
    $oDadosDetalhe2->InicioContrato     = "23";
    $oDadosDetalhe2->FimContrato        = "42";
    $oDadosDetalhe2->ART                = "2";   
    $oDadosDetalhe2->ISS                = "344";
    $oDadosDetalhe2->CMA                = "23";
    $oDadosDetalhe2->INSS               = "344";
    $oDadosDetalhe2->FiscalContrato     = "23";

    $oDadosDetalhe2->codigolinha        = 2000759;

    $this->aDados[] = $oDadosDetalhe2;

    //DETALHE 3

    $oDadosDetalhe3 = new stdClass();
     
    $oDadosDetalhe3->TipRegistro        = "3";   
    $oDadosDetalhe3->idAcompnhamento    = "344";
    $oDadosDetalhe3->idServico          = "23";
    $oDadosDetalhe3->DataEvento         = "3";   
    $oDadosDetalhe3->ResponsaveisEvento = "344";
    $oDadosDetalhe3->SituacaoObra       = "23";
    $oDadosDetalhe3->Justificativa      = "3";    

    $oDadosDetalhe3->codigolinha        = 2000760;

    $this->aDados[] = $oDadosDetalhe3;

    //DETALHE 4

    $oDadosDetalhe4 = new stdClass();
     
    $oDadosDetalhe4->TipRegistro   = "4";   
    $oDadosDetalhe4->idAdtivo      = "344";
    $oDadosDetalhe4->idServico     = "23";
    $oDadosDetalhe4->NumeroAditivo = "42";
    $oDadosDetalhe4->DataAditivo   = "344";
    $oDadosDetalhe4->Prazo         = "23";
    $oDadosDetalhe4->PrazoAditado  = "42";
    $oDadosDetalhe4->Valor         = "344";
    $oDadosDetalhe4->ValorAditado  = "23";
    $oDadosDetalhe4->ART           = "42";
    $oDadosDetalhe4->Motivo        = "10";

    $oDadosDetalhe4->codigolinha     = 2000761;

    $this->aDados[] = $oDadosDetalhe4;

    //TRAILLER

    $oDadosTrailler = new stdClass();
     
    $oDadosTrailler->TipRegistro     = "9";
    $oDadosTrailler->Brancos         = "    ";
    $oDadosTrailler->NumRegistroLido = "0000000002";
        
    $oDadosTrailler->codigolinha = 2000762;
    
    $this->aDados[] = $oDadosTrailler;

    if ($lDebug) {
        $sLinhaTrailler = $oDadosTrailler->TipRegistro    
                          .$oDadosTrailler->Brancos        
                          .$oDadosTrailler->NumRegistroLido;
        fputs($arqFinal, $sLinhaTrailler."\r\n");
        fclose($arqFinal);
        
    }
  } 
}
