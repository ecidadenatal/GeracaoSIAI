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

class SiaiLicitacoes extends SiaiArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 2000003;
  /**
  * Busca os dados para gerar o Arquivo de Licitacoes
  */
  public function gerarDados() {
  
    $sNomeArquivo = 'A13_'.$this->sBimReferencia.'.TXT';
    $lDebug = true;
    if ($lDebug) {
        $arqFinal = fopen ( "tmp/A13_".$this->sBimReferencia.".TXT", 'w+' );
    }
    $this->setNomeArquivo($sNomeArquivo);

    //HEADER
    $oDadosHeader = new stdClass();
    
    $oDadosHeader->TipRegistro        = "0";
    $oDadosHeader->NomeArquivo        = str_pad($sNomeArquivo, 10, " ", STR_PAD_RIGHT);
    $oDadosHeader->BimReferencia      = $this->sBimReferencia;
    $oDadosHeader->TipoArquivo        = "O";   
    $oDadosHeader->DataGeracaoArq     = $this->dtDataGeracao;
    $oDadosHeader->HoraGeracaoArq     = $this->dtHoraGeracao;
    $oDadosHeader->CodigoOrgao        = $this->codigoOrgaoTCE;
    $oDadosHeader->NomeUnidade        = str_pad(substr($this->nomeUnidade,0,100), 100, " ", STR_PAD_RIGHT);
    $oDadosHeader->Brancos            = "  ";
    $oDadosHeader->FornecedorSoftware = str_pad(substr("SOFTWARE PUBLICO BRASILEIRO",0,100), 100, "", STR_PAD_LEFT);
    $oDadosHeader->Brancos1           = str_repeat(" ", 27);
    $oDadosHeader->NumRegistroLido    = "0000000001";
        
    $oDadosHeader->codigolinha    = 2000749;

    $this->aDados[] = $oDadosHeader; 

    if ($lDebug) {
      $sLinhaHeader =  $oDadosHeader->TipRegistro        .
                       $oDadosHeader->NomeArquivo        .
                       $oDadosHeader->BimReferencia      .
                       $oDadosHeader->TipoArquivo        .
                       $oDadosHeader->DataGeracaoArq     .
                       $oDadosHeader->HoraGeracaoArq     .
                       $oDadosHeader->CodigoOrgao        .
                       $oDadosHeader->NomeUnidade        .
                       $oDadosHeader->Brancos            .
                       $oDadosHeader->FornecedorSoftware .
                       $oDadosHeader->Brancos1           .
                       $oDadosHeader->NumRegistroLido;
      fputs($arqFinal, $sLinhaHeader."\r\n");
    }
    //DETALHE 1

    $oDadosDetalhe1 = new stdClass();
        
    $oDadosDetalhe1->TipRegistro      = "1";
    $oDadosDetalhe1->BimReferencia    = "111";
    $oDadosDetalhe1->NumeroProcesso   = "123";
    $oDadosDetalhe1->Brancos          = "1";
    $oDadosDetalhe1->NumeroLicitacao  = "111";
    $oDadosDetalhe1->Objeto           = "123";
    $oDadosDetalhe1->Modalidade       = "1";
    $oDadosDetalhe1->FundamentoLegal  = "111";
    $oDadosDetalhe1->DataEmissaoAto   = "123";
    $oDadosDetalhe1->DataPublicacao   = "1";
    $oDadosDetalhe1->NumeroRecibo     = "111";
    $oDadosDetalhe1->ValordaLicitacao = 123;
    $oDadosDetalhe1->RDC              = "1";
    $oDadosDetalhe1->Brancos          = "111";
    $oDadosDetalhe1->NumRegistroLido  = "123";
    
    $oDadosDetalhe1->codigolinha      = 2000751;

    $this->aDados[] = $oDadosDetalhe1;
    

    //DETALHE 2

    $oDadosDetalhe2 = new stdClass();
     
    $oDadosDetalhe2->TipRegistro             = "2";   
    $oDadosDetalhe2->BimReferencia           = "344";
    $oDadosDetalhe2->NumeroProcesso          = "23";
    $oDadosDetalhe2->Brancos                 = "42";
    $oDadosDetalhe2->TipoDocumento           = "2";   
    $oDadosDetalhe2->NumeroDocumento         = "344";
    $oDadosDetalhe2->NomeParticipante        = "23";
    $oDadosDetalhe2->VenceuItens             = "42";
    $oDadosDetalhe2->ValorTotalItensVencidos = "2";   
    $oDadosDetalhe2->Brancos                 = "344";
    $oDadosDetalhe2->NumRegistroLido         = "23";

    $oDadosDetalhe2->codigolinha        = 2000752;

    $this->aDados[] = $oDadosDetalhe2;

    //DETALHE 3

    $oDadosDetalhe3 = new stdClass();
     
    $oDadosDetalhe3->TipRegistro     = "3";   
    $oDadosDetalhe3->BimReferencia   = "344";
    $oDadosDetalhe3->NumeroDocumento = "23";
    $oDadosDetalhe3->NumeroProcesso  = "3";   
    $oDadosDetalhe3->Brancos         = "344";
    $oDadosDetalhe3->NumeroContrato  = "23";
    $oDadosDetalhe3->ValorContrato   = "3";   
    $oDadosDetalhe3->InicioVigencia  = "344";
    $oDadosDetalhe3->TerminoVigencia = "23";
    $oDadosDetalhe3->DataAssinatura  = "3";   
    $oDadosDetalhe3->DataPublicacao  = "344";
    $oDadosDetalhe3->Brancos         = "23";
    $oDadosDetalhe3->NumRegistroLido = "23";

    $oDadosDetalhe3->codigolinha     = 2000754;

    $this->aDados[] = $oDadosDetalhe3;

    //DETALHE 4

    $oDadosDetalhe4 = new stdClass();
     
    $oDadosDetalhe4->TipRegistro     = "4";   
    $oDadosDetalhe4->BimReferencia   = "344";
    $oDadosDetalhe4->NumeroContrato  = "23";
    $oDadosDetalhe4->Brancos         = "42";
    $oDadosDetalhe4->NumeroAditivo   = "344";
    $oDadosDetalhe4->Objeto          = "23";
    $oDadosDetalhe4->FundamentoLegal = "42";
    $oDadosDetalhe4->Valor           = "344";
    $oDadosDetalhe4->DataInicio      = "23";
    $oDadosDetalhe4->DataTermino     = "42";
    $oDadosDetalhe4->DataAssinatura  = "344";
    $oDadosDetalhe4->DataPublicacao  = "23";
    $oDadosDetalhe4->Brancos         = "42";
    $oDadosDetalhe4->NumRegistroLido = "344";

    $oDadosDetalhe4->codigolinha        = 2000755;

    $this->aDados[] = $oDadosDetalhe4;

    //TRAILLER

    $oDadosTrailler = new stdClass();
     
    $oDadosTrailler->TipRegistro     = "9";
    $oDadosTrailler->Brancos         = str_repeat(" ", 280);
    $oDadosTrailler->NumRegistroLido = "0000000002";
        
    $oDadosTrailler->codigolinha = 2000756;
    
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