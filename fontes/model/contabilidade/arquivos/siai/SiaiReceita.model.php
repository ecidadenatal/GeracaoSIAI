<?php
/*
 * E-cidade Software Publico para Gestao Municipal
 * Copyright (C) 2013 DBselller Servicos de Informatica
 * www.dbseller.com.br
 * e-cidade@dbseller.com.br
 *
 * Este programa e software livre; voce pode redistribui-lo e/ou
 * modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 * publicada pela Free Software Foundation; tanto a versao 2 da
 * Licenca como (a seu criterio) qualquer versao mais nova.
 *
 * Este programa e distribuido na expectativa de ser util, mas SEM
 * QUALQUER GARANTIA; sem mesmo a garantia implicita de
 * COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 * PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 * detalhes.
 *
 * Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 * junto com este programa; se nao, escreva para a Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307, USA.
 *
 * Copia da licenca no diretorio licenca/licenca_en.txt
 * licenca/licenca_pt.txt
 */
require_once(modification("interfaces/iPadArquivoTxtBase.interface.php"));
require_once(modification("model/contabilidade/arquivos/siai/SiaiArquivoBase.model.php"));
require_once(modification("libs/db_liborcamento.php"));
class SiaiReceita extends SiaiArquivoBase implements iPadArquivoTXTBase {
	protected $iCodigoLayout = 2000002;

	/**
	 * Busca os dados para gerar o Arquivo da Receita
	 */
	public function gerarDados() {
		$lDebug = true;
		$iNumLinha = 1;
		if ($lDebug) {
			$arqFinal	= fopen ( "tmp/A1R_".$this->sBimReferencia.".TXT", 'w+' );
			$arqRecurso = fopen ( "tmp/TESTE_RECEITA_RECURSO.TXT", 'w+' );
			$arqReceita = fopen ( "tmp/TESTE_RECEITA.TXT", 'w+' );
		}
		$this->setNomeArquivo("A1R_".$this->sBimReferencia.".TXT");
		/*
		 * HEADER
		 */
		$oDadosHeader = new stdClass ();
		
		$oDadosHeader->tipregistro    = "0";
		$oDadosHeader->nomearquivo    = str_pad("A1R_".$this->sBimReferencia, 10, " ", STR_PAD_RIGHT);
		$oDadosHeader->bimreferencia  = $this->sBimReferencia;
		$oDadosHeader->tipoarquivo 	  = "O";
		$oDadosHeader->datageracaoarq = $this->dtDataGeracao;
		$oDadosHeader->horageracaoarq = $this->dtHoraGeracao;
    	$oDadosHeader->codigoorgao    = $this->codigoOrgaoTCE;
    	$oDadosHeader->nomeorgao      = str_pad ( $this->nomeUnidade, 100, " ", STR_PAD_RIGHT );
    	$oDadosHeader->brancos        = str_repeat(" ", 269);
		$oDadosHeader->codigolinha 	  = 2000007;
		$oDadosHeader->NumRegistroLido = str_pad($iNumLinha, 10, "0", STR_PAD_LEFT);
		
		$this->aDados [] = $oDadosHeader;
		
		if ($lDebug) {
			$sLinhaHeader =  $oDadosHeader->tipregistro
			                .$oDadosHeader->nomearquivo
			                .$oDadosHeader->bimreferencia
			                .$oDadosHeader->tipoarquivo
			                .$oDadosHeader->datageracaoarq
			                .$oDadosHeader->horageracaoarq
			                .$oDadosHeader->codigoorgao
			                .$oDadosHeader->nomeorgao
			                .$oDadosHeader->brancos
			                .$oDadosHeader->NumRegistroLido;
			fputs ( $arqFinal, $sLinhaHeader . "\r\n" );
		}
		
		$oDaoOrcTipoRec    = db_utils::getDao ( "orctiporec" );
		

		/*$rsOrcTipoRec 	   = $oDaoOrcTipoRec->sql_record ( $oDaoOrcTipoRec->sql_query_file ( null, "(case when 
																											(select distinct fonte_tce from plugins.deparafontetce where fonte_orcamento = o15_codigo limit 1) is not null 
																										then (select distinct fonte_tce from plugins.deparafontetce where fonte_orcamento = o15_codigo limit 1) 
																									else cast(o15_codigo as varchar) end) as o15_codigo, o15_descr", "o15_codigo" ) );*/
		if (db_getsession('DB_anousu') >= 2017) {
			$rsOrcTipoRec      = pg_query("select distinct fonte_tce as o15_codigo, o15_descr 
											from plugins.deparafontetce 
												inner join orctiporec on o15_codigo = fonte_orcamento
											where exercicio = {$this->iAnoUso} 
											order by o15_codigo");																						
		} else {
			$rsOrcTipoRec 	   = $oDaoOrcTipoRec->sql_record ( $oDaoOrcTipoRec->sql_query_file ( null, "o15_codigo, o15_descr", "o15_codigo" ) );
		}

		$iLinhasOrcTipoRec = pg_num_rows($rsOrcTipoRec);

		if ($iLinhasOrcTipoRec > 0) {
			/*
			 * DETALHE 1
			 * Dados dos Recursos
			 *
			 */
			for($iInd = 0; $iInd < $iLinhasOrcTipoRec; $iInd ++) {

				$oDadosOrcTipoRec = db_utils::fieldsMemory ( $rsOrcTipoRec, $iInd );
								
				if(str_pad(substr($oDadosOrcTipoRec->o15_codigo, 0, 10)  , 10, "0", STR_PAD_LEFT) == "0000000000") {
					continue;
				}

				$iNumLinha++;
				$oDadosDetalhe1 = new stdClass ();
				$oDadosDetalhe1->tipregistro   = "1";
				$oDadosDetalhe1->tipocadastro  = "0";
				$oDadosDetalhe1->numerodafonte = str_pad(substr($oDadosOrcTipoRec->o15_codigo, 0, 10)  , 10, "0", STR_PAD_LEFT);
				$oDadosDetalhe1->descricao     = str_pad(substr($oDadosOrcTipoRec->o15_codigo != "000"? $oDadosOrcTipoRec->o15_descr : " ", 0, 350) , 350," ");
				$oDadosDetalhe1->brancos2      = str_repeat(" ", 47);
				$oDadosDetalhe1->NumRegistroLido = str_pad($iNumLinha, 10, "0", STR_PAD_LEFT);
				$oDadosDetalhe1->codigolinha   = 2000008;
				
				$this->aDados [] = $oDadosDetalhe1;
				
				if ($lDebug) {
					$sLinhaRecurso = $oDadosDetalhe1->tipregistro
									 ." | ".$oDadosDetalhe1->tipocadastro	
									 ." | ".$oDadosDetalhe1->numerodafonte
					                 ." | ".$oDadosDetalhe1->descricao
			                		 ." | ".$oDadosDetalhe1->brancos2
			                		 ." | ".$oDadosDetalhe1->NumRegistroLido;
					fputs ( $arqRecurso, $sLinhaRecurso . "\r\n" );
					fputs ( $arqFinal, str_replace(" | ", "", $sLinhaRecurso) . "\r\n" );
				}
			}
			
			/*
			 * DETALHE2
			 * Dados das Receitas
			 */
			$sWhere = ""; //"o70_instit = " . db_getsession ( "DB_instit" );
			$aReceitasIncluidas = array();

			$rsReceitaSaldo = db_receitasaldo (11, 1, 3, true, $sWhere, $this->iAnoUso, $this->dtDataInicial, $this->dtDataFinal, false);
			//db_criatabela($rsReceitaSaldo);
			for($i = 0; $i < $iLinhasOrcTipoRec; $i ++) {

				$oDadosOrcTipoRec = db_utils::fieldsMemory ( $rsOrcTipoRec, $i );

				if(str_pad(substr($oDadosOrcTipoRec->o15_codigo, 0, 10)  , 10, "0", STR_PAD_LEFT) == "0000000000") {
					continue;
				}

				
				$sql_receita = "select distinct 
									substr(coalesce(rec_tce, o57_fonte), 1, 9) as elemento,
									coalesce(fonte_tce, '{$oDadosOrcTipoRec->o15_codigo}') as fonterecursos,
									sum(saldo_inicial) as saldo_inicial, 
			                        sum(saldo_inicial_prevadic) as saldo_inicial_prevadic,
			                        sum(saldo_arrecadado) as saldo_arrecadado,
									sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado
								from work_receita
									inner join orcreceita on orcreceita.o70_codrec = work_receita.o70_codrec and orcreceita.o70_anousu = $this->iAnoUso 
									inner join orcfontes on o70_codfon = o57_codfon and o70_anousu = o57_anousu  
									 left join (select rec_ecidade, rec_tce from plugins.receita_siai_de_para) as T on T.rec_ecidade = substr(o57_fonte, 1, 9)||'0'
									inner join (select distinct fonte_orcamento, fonte_tce, exercicio 
												from plugins.deparafontetce 
										        where fonte_tce = '{$oDadosOrcTipoRec->o15_codigo}'
									 	  			 and exercicio = {$this->iAnoUso} limit 1) as de_para_fonte 
										on de_para_fonte.exercicio = o70_anousu
					                       and orcreceita.o70_codigo = de_para_fonte.fonte_orcamento::integer 
					            where work_receita.o70_codigo > 0 and abs(saldo_inicial + saldo_inicial_prevadic + saldo_arrecadado + saldo_arrecadado_acumulado) > 0
								group by substr(coalesce(rec_tce, o57_fonte), 1, 9), coalesce(fonte_tce, '{$oDadosOrcTipoRec->o15_codigo}')
								order by fonterecursos, elemento";

				$res_receita = db_query($sql_receita,0);
				/*if($oDadosOrcTipoRec->o15_codigo == 1000000000) {
					die($sql_receita);
				}*/
				//die(pg_last_error());
				//db_criatabela($res_receita);exit;	
	                        
	                        /*$sSqlReceita = "select o57_fonte, 
	                                               o70_codigo, 
	                                               sum(saldo_inicial) as saldo_inicial, 
	                                               sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, 
	                                               sum(saldo_arrecadado) as saldo_arrecadado, 
	                                               sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, 
	                                               array_to_string(array_accum(distinct o70_codrec), ',') as o70_codrec
	                                         from ({$rsReceitaSaldo}) as dados
	                                        group by o57_fonte,o70_codrec
	                                        order by o57_fonte,o70_codrec";
	                        $rsReceitaSaldo = db_query($sSqlReceita);*/
				$iLinhasOrcReceita = pg_num_rows($res_receita);
				for($iInd = 0; $iInd < $iLinhasOrcReceita; $iInd ++) {

					$oDadosOrcReceita = db_utils::fieldsMemory ( $res_receita, $iInd );
					/*
					 * Somente ir� constar as receitas analiticas e que possuirem vinculo na orcreceita
					 */
					if ($oDadosOrcReceita->elemento == "0" || $oDadosOrcReceita->elemento == "" || $oDadosOrcReceita->fonterecursos == "0" || $oDadosOrcReceita->fonterecursos == "") {
						continue;
					}
					
					// Se o C�digo da Receita n�o come�ar com "9" o caractere 11, ou seja, o nono caractere ser� em branco
					if (substr ( $oDadosOrcReceita->elemento, 0, 1 ) != "9") {
						$iCodigoReceita = substr ( $oDadosOrcReceita->elemento, 1, 8 );
					} else {
						$iCodigoReceita = substr ( $oDadosOrcReceita->elemento, 0, 8 );
					}

					if (in_array($oDadosOrcReceita->elemento.$oDadosOrcReceita->saldo_inicial
								.$oDadosOrcReceita->saldo_inicial_prevadic
								.$oDadosOrcReceita->saldo_arrecadado
								.$oDadosOrcReceita->saldo_arrecadado_acumulado, $aReceitasIncluidas)) {
						continue;
					}
					$aReceitasIncluidas[] = $oDadosOrcReceita->elemento
										   .$oDadosOrcReceita->saldo_inicial
										   .$oDadosOrcReceita->saldo_inicial_prevadic
										   .$oDadosOrcReceita->saldo_arrecadado
										   .$oDadosOrcReceita->saldo_arrecadado_acumulado;

					$iNumLinha++;
					$oDadosDetalhe2 = new stdClass ();
					$oDadosDetalhe2->tipregistro          = "2";
					$oDadosDetalhe2->brancos1             = " ";
					$oDadosDetalhe2->codigoreceita        = str_pad($iCodigoReceita, 10, "0", STR_PAD_RIGHT);
					
					if($this->codigoOrgaoTCE != "P088") {

						$oDadosDetalhe2->valorprevistoinicial = $this->formataValor(0, 14, "0");
						$oDadosDetalhe2->valorprevatualizado  = $this->formataValor(0, 14, "0");
						$oDadosDetalhe2->valorrealizadobim    = $this->formataValor(0, 14, "0");
						$oDadosDetalhe2->valorrealizadoexe    = $this->formataValor(0, 14, "0");

					} else {

						$oDadosDetalhe2->valorprevistoinicial = $this->formataValor( abs($oDadosOrcReceita->saldo_inicial)             , 14, "0");
						$oDadosDetalhe2->valorprevatualizado  = $this->formataValor( abs($oDadosOrcReceita->saldo_inicial_prevadic)    , 14, "0");
						$oDadosDetalhe2->valorrealizadobim    = $this->formataValor( abs($oDadosOrcReceita->saldo_arrecadado)          , 14, "0");
						$oDadosDetalhe2->valorrealizadoexe    = $this->formataValor( abs($oDadosOrcReceita->saldo_arrecadado_acumulado), 14, "0");
						
					}

					$oDadosDetalhe2->tipocadastro         = "0";
					$oDadosDetalhe2->fonterecursos        = str_pad(substr($oDadosOrcReceita->fonterecursos, 0, 10), 10 , "0", STR_PAD_LEFT);
					$oDadosDetalhe2->brancos2             = str_repeat(" ", 330);
					$oDadosDetalhe2->NumRegistroLido      = str_pad($iNumLinha, 10, "0", STR_PAD_LEFT);
					$oDadosDetalhe2->codigolinha          = 2000009;
					
					$this->aDados [] = $oDadosDetalhe2;
					
					if ($lDebug) {
						
						$sLinhaReceita = $oDadosDetalhe2->tipregistro
						                 . " | " . $oDadosDetalhe2->brancos1
						                 . " | " . $oDadosDetalhe2->codigoreceita 
						                 . " | " . $oDadosDetalhe2->valorprevistoinicial 
						                 . " | " . $oDadosDetalhe2->valorprevatualizado 
						                 . " | " . $oDadosDetalhe2->valorrealizadobim 
						                 . " | " . $oDadosDetalhe2->valorrealizadoexe 
						                 . " | " . $oDadosDetalhe2->tipocadastro
						                 . " | " . $oDadosDetalhe2->fonterecursos
						                 . " | " . $oDadosDetalhe2->brancos2
										 . " | " . $oDadosDetalhe2->NumRegistroLido;
						fputs ( $arqReceita, $sLinhaReceita . "\r\n" );
						fputs ( $arqFinal, str_replace(" | ", "", $sLinhaReceita) . "\r\n" );
						
					}
				}
			}
		}

		/*
		 * TRAILLER
		 */
		$iNumLinha++;
		$oDadosTrailler = new stdClass ();
		$oDadosTrailler->tipregistro = "9";
		$oDadosTrailler->brancos     = str_repeat(" ", 408);
		$oDadosTrailler->NumRegistroLido = str_pad($iNumLinha, 10, "0", STR_PAD_LEFT);
		$oDadosTrailler->codigolinha = 2000010;
		$this->aDados [] = $oDadosTrailler;
		
		if ($lDebug) {
		  $sLinhaTrailer = $oDadosTrailler->tipregistro . " | " . $oDadosTrailler->brancos . " | " . $oDadosTrailler->NumRegistroLido;	
		  fputs ( $arqFinal, str_replace(" | ", "", $sLinhaTrailer) . "\r\n" );
		  
		  fclose( $arqRecurso );
		  fclose( $arqReceita );
		  fclose( $arqFinal );
		}
	}
}
