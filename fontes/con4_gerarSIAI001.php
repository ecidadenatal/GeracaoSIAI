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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

$periodo = array("2"   => " 1º Bimestre",
                 "4"   => " 2º Bimestre",
                 "6"   => " 3º Bimestre",
                 "8"   => " 4º Bimestre",
                 "10"  => " 5º Bimestre",
                 "12"  => " 6º Bimestre");

$unidades = array();

$rsUnidade = pg_query("select o40_orgao as orgao, o41_unidade as unidade, o41_descr from orcunidade 
      inner join orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
    where o41_instit = ".db_getsession("DB_instit")." and o41_anousu = ".db_getsession("DB_anousu"));

$rsInstit = pg_query("select '00' as orgao, codigo as unidade, nomeinst as o41_descr from db_config 
    where codigo = ".db_getsession("DB_instit"));

for ($i = 0; $i < pg_num_rows($rsUnidade); $i++) { 
  $oLinhaUnidade = db_utils::fieldsMemory($rsUnidade, $i);
  $unidades[$oLinhaUnidade->orgao.".".$oLinhaUnidade->unidade] = $oLinhaUnidade->orgao.".".str_pad($oLinhaUnidade->unidade, 2, "0", STR_PAD_LEFT)." - ".$oLinhaUnidade->o41_descr;
}

$oLinhaInstit = db_utils::fieldsMemory($rsInstit, 0);
$unidades[$oLinhaInstit->orgao.".".$oLinhaInstit->unidade] = $oLinhaInstit->orgao.".".str_pad($oLinhaInstit->unidade, 2, "0", STR_PAD_LEFT)." - ".$oLinhaInstit->o41_descr;

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <style>
    legend {
      font-weight: bold;
    }
    #siai {
      width: 1000px;
      height: 500px;
    }
    #arquivo-gerar {
      width: 748px;
      display: block;
      float: left;
      overflow: auto;
    }
    #lista-gerados {
      margin-top: 48px;
      width: 250px;
      float: left;
      overflow: auto;
    }
    #field-gerados {
      height: 430px;
    }

    .alinha-td-label {
      width: 200px;
      text-align: left;
    }

    .alinha-td-check {
      width: 30px;
      text-align: left;
    }

  </style>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div style="margin-top: 40px;"></div>
  <center>
    <fieldset id='siai'>
      <legend>Gerar SIAI</legend>
      <div id='arquivo-gerar' align="left" >
        <div style="display: block; height:48px;" >
          <table>
            <tr>
              <td><span style="font-weight: bold;">Período:</span></td>
              <td>
                <?
                  $mes = date("m",db_getsession("DB_datausu"));
                  //pega o último bimestre "fechado"
                  $periodosiai = ($mes%2 == 1 ? $mes-1 : $mes);
                  if(db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu"))){
                    $periodosiai = 12;
                  } 
                  else {
                    if($periodosiai == 0)
                      $periodosiai = 2;
                  }
                  db_select("periodosiai",$periodo,true,2);
                ?>
              </td>
              <td><span style="font-weight: bold;">Unidade:</span></td>
              <td>
                <?                  
                    db_select("unidadessiai", $unidades, true, 2, 'onchange="js_getDadosTCE()"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap><span style="font-weight: bold;">Cód. TCE:</span></td>
              <td><input type="text" readonly="true" maxlength="4" size="4" id='codUnidade' value='' name="codUnidade" /></td>
              <td nowrap><span style="font-weight: bold;">Nome TCE:</span></td>
              <td><input type="text" readonly="true" maxlength="50" size="60" id='nomeUnidade' value='' name="nomeUnidade" /></td>
            </tr>
          </table>
        </div>
        <fieldset id='field-arquivos'>
          <legend>Arquivos</legend>
          <table id='arquivos'>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='pessoas' value='Pessoas' name="Pessoas" />
              </td>
              <td class="alinha-td-label">
                <label for="Pessoas">Pessoas</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='receita' value='Receita' name="Receita" />
              </td>
              <td class="alinha-td-label">
                <label for="Receita">Anexo 01 - Receita</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='despesa' value='Despesa' name="Despesa" />
              </td>
	            <td class="alinha-td-label">
                <label for="Despesa">Anexo 01 - Despesa</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input  type="checkbox" id='licitacoes' value='Licitacoes' name="Licitacoes" />
              </td>
              <td class="alinha-td-label">
                <label for="Licitacoes">Anexo 13 - Licitações</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='empenhos' value='Empenhos' name="Empenhos" />
              </td>
	            <td class="alinha-td-label">
                <label for="Empenhos">Anexo 14 - Empenhos</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input  type="checkbox" id='obras' value='Obras' name="Obras" />
              </td>
              <td class="alinha-td-label">
                <label for="Obras">Anexo 23 - Obras</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='suprimento' value='Suprimento' name="SuprimentoDeFundos" />
              </td>
              <td class="alinha-td-label">
                <label for="Suprimento">Anexo 25 - Suprimento de Fundos</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='contacorrente' value='Conta Corrente' name="ContaCorrente" />
              </td>
              <td class="alinha-td-label">
                <label for="ContaCorrente">Anexo 26 - Conta Corrente</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='veiculos' value='Veículos' name="Veiculos" />
              </td>
              <td class="alinha-td-label">
                <label for="Veiculos">Anexo 28 - Veículos</label>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset id='field-financeiro' style="display: none;">
          <legend>Financeiro</legend>
        </fieldset>
        <fieldset id='field-rh' style="display: none;">
          <legend>Recursos Humanos</legend>
        </fieldset>
      </div>
      <div id='lista-gerados'>
        <fieldset id='field-gerados'>
          <legend>Arquivos Gerados</legend>
          <div style='overflow:auto; text-align: left;' id='retorno'></div>
        </fieldset>
      </div>
      <div style="clear: both; margin-top: 20px">

      </div>
    </fieldset>
    <div style="margin-top: 10px;">
        <input type="button" id='selecionar-todos' value='Selecionar Todos' name='Selecionar Todos'
               onclick="js_marcaTodos();"/>
        <input type="button" id='limpar-selecao' value='Limpar Seleção' name='Limpar Seleção' onclick="js_desmarcar();" />
        <input type="button" id='processar' value='Processar' name='Processar' onclick="js_processar();" />
    </div>
  </center>

  <script type="text/javascript">

    var sURL = "con4_processarSIAI.RPC.php";

    window.onload = function() {
      js_getDadosTCE();
    }

    function js_processar() {

      if(!confirm("Deseja gerar os dados selecionados para a unidade "+document.getElementById('unidadessiai').options[document.getElementById('unidadessiai').selectedIndex].text+" no período "+document.getElementById('periodosiai').options[document.getElementById('periodosiai').selectedIndex].text+"?")) {
        return false;
      }

      var oParam           = new Object();
      oParam.exec          = "processarSiai";
      oParam.iPeriodo      = $F('periodosiai');
      if ($('unidadessiai')) {
        var codigo_unidade = $F('unidadessiai').split(".");
        oParam.orgao   = codigo_unidade[0];
        oParam.unidade = codigo_unidade[1];
      }
      oParam.codigoOrgaoTCE = document.getElementById('codUnidade').value
      oParam.nomeUnidadeTCE = document.getElementById('nomeUnidade').value
      oParam.aArquivos  = new Array();
      var aArquivos     = $$("input[type='checkbox']");
      aArquivos.each(function (oCheckbox, id) {

        with (oCheckbox) {

          if (checked) {
            oParam.aArquivos.push(oCheckbox.name);
          }
        }

      });

      if (oParam.aArquivos.length == 0) {

        alert("Selecione ao menos uma Opção.");
        return false;
      }

      js_divCarregando('Aguarde, Processando Arquivos', 'msgBox');
      var oAjax = new Ajax.Request(sURL,
                                   {
                                     method:'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete:js_retornoProcessaSiai
                                   }
                                 );
   }

   function js_retornoProcessaSiai(oAjax) {

     js_removeObj('msgBox');
     var oRetorno = eval("("+oAjax.responseText+")");
     if (oRetorno.status == 1) {

       var sRetorno = "";
       
       for (var i = 0; i < oRetorno.lista.length; i++) {

         with (oRetorno.lista[i]) {

          sRetorno += "<a  href='db_download.php?arquivo="+caminho+"'>"+nome+"</a><br>";
         }
       }

       $('retorno').innerHTML = sRetorno;
     } else {

       $('retorno').innerHTML = '';
       alert(oRetorno.message.urlDecode());
       return false;
     }
   }

   function js_marcaTodos() {

  	  var aCheckboxes = $$('input[type=checkbox]');
  	  aCheckboxes.each(function(oCheckbox) {
  	    oCheckbox.checked = true;
  	  });
  	}

   function js_desmarcar() {

  	  var aCheckboxes = $$('input[type=checkbox]');
  	  aCheckboxes.each(function (oCheckbox) {
  	    oCheckbox.checked = false;
  	  });
  	}

  function js_getDadosTCE() {
    var oParam           = new Object();
    oParam.exec          = "getDadosTCE";
    if ($('unidadessiai')) {
      var codigo_unidade = $F('unidadessiai').split(".");
      oParam.orgao   = codigo_unidade[0];
      oParam.unidade = codigo_unidade[1];
    }

    js_divCarregando('Aguarde, obtendo os dados', 'msgBox');
    var oAjax = new Ajax.Request(sURL,
      {
        method:'post',
        parameters:'json='+Object.toJSON(oParam),
        onComplete:js_retornoDadosTCE
      });
  }

  function js_retornoDadosTCE(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
      document.getElementById('codUnidade').value  = oRetorno.codigoOrgao.urlDecode().substring(0, 4);
      document.getElementById('nomeUnidade').value = oRetorno.nomeUnidade.urlDecode().substring(0, 50);
      document.getElementById('nomeUnidade').readOnly = false;
    } else {
      document.getElementById('codUnidade').readOnly  = false;
      document.getElementById('nomeUnidade').readOnly = false;
      document.getElementById('codUnidade').value  = "";
      document.getElementById('nomeUnidade').value = "";
      alert(oRetorno.msg.urlDecode());
      document.getElementById('codUnidade').focus();
      return false;
    }
  }

  </script>
  </body>
</html>
<? db_menu(db_getsession("DB_id_usuario"),
           db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
