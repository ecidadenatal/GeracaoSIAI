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
require_once ("std/DBString.php");

class SiaiVeiculos extends SiaiArquivoBase implements iPadArquivoTXTBase {
  
  protected $iCodigoLayout     = 2000004;

  /**
  * Busca os dados para gerar o Arquivo de Empenhos
  */
  public function gerarDados() {
  
    $sNomeArquivo = 'A28_'.$this->sBimReferencia;
    $this->setNomeArquivo($sNomeArquivo.".txt");

    $iAnoSessao     = db_getsession('DB_anousu');
    $iInstituicaoSessao = db_getsession('DB_instit');

    $nLinhasArquivo = 1;
    
    $lDebug = true;
    if ($lDebug) {
    	$arqFinal = fopen ( "tmp/{$sNomeArquivo}.txt", 'w+' );
    }

    
    /*
     * HEADER
     */
    $oDadosHeader = new stdClass();
    $oDadosHeader->TipRegistro        = "0";
    $oDadosHeader->NomeArquivo        = str_pad($sNomeArquivo, 10, " ", STR_PAD_RIGHT);
    $oDadosHeader->BimReferencia      = $this->sBimReferencia;
    $oDadosHeader->TipoArquivo        = "O";   
    $oDadosHeader->DataGeracaoArq     = $this->dtDataGeracao;
    $oDadosHeader->HoraGeracaoArq     = $this->dtHoraGeracao;
    $oDadosHeader->CodigoOrgao        = $this->codigoOrgaoTCE;
    $oDadosHeader->NomeOrgao          = str_pad(substr($this->nomeUnidade,0,100), 100, " ", STR_PAD_RIGHT);
    $oDadosHeader->Brancos1           = str_repeat(" ", 10);
    $oDadosHeader->NumRegistroLido    = str_pad($nLinhasArquivo, 10, "0", STR_PAD_LEFT);
    $oDadosHeader->codigolinha        = "";

    $this->aDados[] = $oDadosHeader; 
    
    if ($lDebug) {
      $sLinhaHeader =  $oDadosHeader->TipRegistro    .
                       $oDadosHeader->NomeArquivo    .
                       $oDadosHeader->BimReferencia  .
                       $oDadosHeader->TipoArquivo    .
                       $oDadosHeader->DataGeracaoArq .
                       $oDadosHeader->HoraGeracaoArq .
                       $oDadosHeader->CodigoOrgao    .
                       $oDadosHeader->NomeOrgao      .
                       $oDadosHeader->Brancos1       .
                       $oDadosHeader->NumRegistroLido;
      fputs($arqFinal, $sLinhaHeader."\r\n");
    }

    $iLinhas = 0;
    if($iLinhas > 0) {
    	
       /*	
        * DETALHE 1
        * AQUISICAO DE VEICULOS
        */
       $nLinhasArquivo++;
       $oDadosDetalhe1 = new stdClass();
       $oDadosDetalhe1->TipRegistro         = "1";
       $oDadosDetalhe1->ProcessoOrigem      = str_pad("",20," ");
       $oDadosDetalhe1->Cnpj                = str_pad("0",14,"0",STR_PAD_LEFT);
       $oDadosDetalhe1->NomeContratado      = str_pad("",50," ");
       $oDadosDetalhe1->DataAquisicao       = "00/00/0000";
       $oDadosDetalhe1->ValorAquisicao      = str_pad("0",14,"0",STR_PAD_LEFT);
       
       $oDadosDetalhe1->codigolinha         = "";
       
       $this->aDados[] = $oDadosDetalhe1;
       
       if ($lDebug) {
         $sLinhaDetalhe1 = $oDadosDetalhe1->TipRegistro   .   
                           $oDadosDetalhe1->ProcessoOrigem.
                           $oDadosDetalhe1->Cnpj          .
                           $oDadosDetalhe1->NomeContratado.
                           $oDadosDetalhe1->DataAquisicao .
                           $oDadosDetalhe1->ValorAquisicao;    
         fputs($arqFinal, $sLinhaDetalhe1."\r\n");
       }
       
       /*
        * DETALHE 2
        * LOCACAO DE VEICULOS
        */
       $nLinhasArquivo++;
       $oDadosDetalhe2 = new stdClass();
       $oDadosDetalhe2->TipRegistro         = "2";
       $oDadosDetalhe2->ProcessoOrigem      = str_pad("",20," ");
       $oDadosDetalhe2->Cnpj                = str_pad("0",14,"0",STR_PAD_LEFT);
       $oDadosDetalhe2->NomeLocatario       = str_pad("",50," ");
       $oDadosDetalhe2->DataContrato        = "00/00/0000";
       $oDadosDetalhe2->InicioLocacao       = "00/00/0000";
       $oDadosDetalhe2->TerminoLocacao      = "00/00/0000";
       $oDadosDetalhe2->ValorLocacao        = str_pad("0",14,"0",STR_PAD_LEFT);
        
       $oDadosDetalhe2->codigolinha         = "";
        
       $this->aDados[] = $oDadosDetalhe2;
        
       if ($lDebug) {
       	$sLinhaDetalhe2 = $oDadosDetalhe2->TipRegistro   .   
                          $oDadosDetalhe2->ProcessoOrigem.
                          $oDadosDetalhe2->Cnpj          .
                          $oDadosDetalhe2->NomeLocatario .
                          $oDadosDetalhe2->DataContrato  .
                          $oDadosDetalhe2->InicioLocacao .
                          $oDadosDetalhe2->TerminoLocacao.
                          $oDadosDetalhe2->ValorLocacao  ;
       	fputs($arqFinal, $sLinhaDetalhe2."\r\n");
       }
       
       /*
        * DETALHE 3
        * CESSÃO DE VEICULOS
        */
       $nLinhasArquivo++;
       $oDadosDetalhe3 = new stdClass();
       $oDadosDetalhe3->TipRegistro         = "3";
       $oDadosDetalhe3->ProcessoOrigem      = str_pad("",20," ");
       $oDadosDetalhe3->CnpjCedente         = str_pad("0",14,"0",STR_PAD_LEFT);
       $oDadosDetalhe3->OrgaoCedente        = str_pad("",100," ");
       $oDadosDetalhe3->InicioCessao        = "00/00/0000";
       $oDadosDetalhe3->TerminoCessao       = "00/00/0000";
       
       $oDadosDetalhe3->codigolinha         = "";
       
       $this->aDados[] = $oDadosDetalhe3;
       
       if ($lDebug) {
       	$sLinhaDetalhe3 = $oDadosDetalhe3->TipRegistro    .     
                          $oDadosDetalhe3->ProcessoOrigem .     
                          $oDadosDetalhe3->CnpjCedente    .     
                          $oDadosDetalhe3->OrgaoCedente   .     
                          $oDadosDetalhe3->InicioCessao   .     
                          $oDadosDetalhe3->TerminoCessao; 
       	fputs($arqFinal, $sLinhaDetalhe3."\r\n");
       }
       
       /*
        * DETALHE 4
        * VEICULOS
        */
       $nLinhasArquivo++;
       $oDadosDetalhe4 = new stdClass();
       $oDadosDetalhe4->TipRegistro         = "4";
       $oDadosDetalhe4->Situacao            = "0";
       $oDadosDetalhe4->ProcessoOrigem      = str_pad("0",20,"0",STR_PAD_LEFT);
       $oDadosDetalhe4->IdEspecie           = "00";
       $oDadosDetalhe4->InicioCessao        = "00/00/0000";
       $oDadosDetalhe4->TerminoCessao       = "00/00/0000";
       $oDadosDetalhe4->IdTipo              = "00";
       $oDadosDetalhe4->IdMarcaModelo       = str_pad("0",6,"0",STR_PAD_LEFT);
       $oDadosDetalhe4->AnoFabricacao       = "0000";
       $oDadosDetalhe4->Placa               = str_pad("0000000",7,"0",STR_PAD_LEFT);
       $oDadosDetalhe4->Renavam             = str_pad("0",15,"0",STR_PAD_LEFT);
       $oDadosDetalhe4->IdCombustivel       = "00";
       $oDadosDetalhe4->Tanque              = str_pad("0",4,"0",STR_PAD_LEFT);
       $oDadosDetalhe4->IdCategoria         = "00";
       
       $oDadosDetalhe4->codigolinha         = "";
        
       $this->aDados[] = $oDadosDetalhe4;
        
       if ($lDebug) {
       	$sLinhaDetalhe4 = $oDadosDetalhe4->TipRegistro   .
                          $oDadosDetalhe4->Situacao      .
                          $oDadosDetalhe4->ProcessoOrigem.
                          $oDadosDetalhe4->IdEspecie     .
                          $oDadosDetalhe4->InicioCessao  .
                          $oDadosDetalhe4->TerminoCessao .
                          $oDadosDetalhe4->IdTipo        .
                          $oDadosDetalhe4->IdMarcaModelo .
                          $oDadosDetalhe4->AnoFabricacao .
                          $oDadosDetalhe4->Placa         .
                          $oDadosDetalhe4->Renavam       .
                          $oDadosDetalhe4->IdCombustivel .
                          $oDadosDetalhe4->Tanque        .
                          $oDadosDetalhe4->IdCategoria   ;
       	fputs($arqFinal, $sLinhaDetalhe4."\r\n");
       }

       
    }
        
    //TRAILLER

    $nLinhasArquivo++;
    $oDadosTrailler = new stdClass();
    $oDadosTrailler->TipRegistro     = "9";
    $oDadosTrailler->Brancos         = str_repeat(" ", 149);
    $oDadosTrailler->NumRegistroLido = str_pad($nLinhasArquivo, 10, "0", STR_PAD_LEFT);
        
    $oDadosTrailler->codigolinha     = "";
    
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
