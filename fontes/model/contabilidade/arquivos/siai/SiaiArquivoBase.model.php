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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");

/**
 * 
 * Classe Basica para geração de arquivo SIAI
 *
 */
abstract class SiaiArquivoBase {
  
  protected $iAnoUso;
  protected $dtDataInicial;
  protected $dtDataFinal;
  protected $dtDataGeracao;
  protected $dtHoraGeracao;
  protected $sNomeArquivo;
  protected $sBimReferencia;
  protected $iCodigoLayout;
  protected $aDados = array();
  protected $codigoOrgaoTCE;
  protected $codigoOrgao;
  protected $codigoUnidade;
  protected $nomeUnidade;
  
  protected $rsLogger;
  function __construct() {
    
   $this->iAnoUso = db_getsession("DB_anousu");
  }
  
  /**
   * 
   * Retorna um array de com os dados do Arquivo
   * @return array 
   */
  public function getDados() {
    
    return $this->aDados;
  }
  
  /**
   * 
   * Seta a data Inicial
   * @param String $sDataInicial
   */
  public function setDataInicial($sDataInicial) {
    
    $this->dtDataInicial = $sDataInicial;
  }
  
  /**
   * 
   * Seta a data Final
   * @param String $sDataFinal
   */
  public function setDataFinal($sDataFinal) {
    
    $this->dtDataFinal = $sDataFinal;
  }
  
  public function setDataGeracao($sDataGeracao) {

    $this->dtDataGeracao = $sDataGeracao;

  }

  public function setHoraGeracao($sHoraGeracao) {

    $this->dtHoraGeracao = $sHoraGeracao;

  }

  public function setBimReferencia($sBimRef) {

    $this->sBimReferencia = $sBimRef;

  }

  /**
  * Seta o Nome do Arquivo
  */
  public function setNomeArquivo($nomeArquivo) {

    $this->sNomeArquivo = $nomeArquivo;
  }

  /**
  * Retorna o Nome do Arquivo
  */
  public function getNomeArquivo() {

    return $this->sNomeArquivo;
  }

  /**
  * Seta o Nome do Arquivo
  */
  public function setCodigoOrgaoTCE($codigoOrgaoTCE) {

    $this->codigoOrgaoTCE = $codigoOrgaoTCE;
  }

  /**
  * Retorna o Nome do Arquivo
  */
  public function getCodigoOrgaoTCE() {

    return $this->codigoOrgaoTCE;
  }

  /**
  * Seta o Nome do Arquivo
  */
  public function setCodigoOrgao($codigoOrgao) {

    $this->codigoOrgao = $codigoOrgao;
  }

  /**
  * Retorna o Nome do Arquivo
  */
  public function getCodigoOrgao() {

    return $this->codigoOrgao;
  }

  /**
  * Seta o Nome do Arquivo
  */
  public function setCodigoUnidade($codigoUnidade) {

    $this->codigoUnidade = $codigoUnidade;
  }

  /**
  * Retorna o Nome do Arquivo
  */
  public function getCodigoUnidade() {

    return $this->codigoUnidade;
  }

  /**
  * Seta o Nome do Arquivo
  */
  public function setNomeUnidade($nomeUnidade) {

    $this->nomeUnidade = $nomeUnidade;
  }

  /**
  * Retorna o Nome do Arquivo
  */
  public function getNomeUnidade() {

    return $this->nomeUnidade;
  }

  /**
   * Retorna o Código do Layout
   */
  public function setCodigoLayout($iCodigoLayout) {
    
    $this->iCodigoLayout = $iCodigoLayout;
  }
  
  /**
   * Retorna o Código do Layout
   */
  public function getCodigoLayout() {
   
    return $this->iCodigoLayout;
  }
  
  public function setTXTLogger($fp) {
    
    $this->rsLogger  = $fp; 
  }

  /**
   * Adiciona um registro de log no arquivo
   * @param string $sLog
   */
  public function addLog($sLog) {
    
    fputs($this->rsLogger, $sLog);
  }
  /**
   * 
   * Formata valor para o formato esperado no SIAI
   * @param numeric $nValor
   * @return string
   */
  protected function formataValor($nValor, $iTamanho = 0, $sCompleta = "0") {
  	
  	$nValor = number_format($nValor, 2, "", "");
  	if ($iTamanho > 0) {
  		
  	  if ($nValor < 0) {
  	  	$nValor = "-".str_pad(abs($nValor), $iTamanho-1, $sCompleta, STR_PAD_LEFT);
  	  } else {
  	  	$nValor = str_pad($nValor, $iTamanho, "{$sCompleta}", STR_PAD_LEFT);
  	  }
  	  
  	}
    return $nValor;
  }
  
  /**
   * 
   * Formata uma data para o padrao do SIAI
   * @param date $dtData
   * @param String $sParam1
   * @param String $sParam2
   */
  protected function formataData($dtData, $sParam1 = '/', $sParam2 = '-') {
  
    $sDataFormatada = implode($sParam1, array_reverse(explode($sParam2, $dtData)));
    return $sDataFormatada;
  }
}