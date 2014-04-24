<?php

/**
*
* @class Ligrila_Bbva
* 
* @author Leandro López <leandro@ligrila.com>
* @copyright     Copyright (c) Ligrila Software. (http://www.ligrila.com)
* @link          http://www.ligrila.com Ligrila Software
* @license       http://www.opensource.org/licenses/mit-license.php MIT License
*
*/


define('BBVA_CHECKOUT_URL','https://w3.grupobbva.com/TLPV/tlpv/TLPV_pub_RecepOpModeloServidor');
require_once('ligrila_bbva_utils.php');
require_once('conf.php');
//require_once('./../includes/funciones.inc.php');


class Ligrila_Bbva{

	function __construct(){
		debug("Construllendo el objeto Ligrila_Bbva");
		$this->bbvaUtils = new Ligrila_Bbva_Utils;
	}

	function getConfigData($var){
		return null;
	}
	
	function checkoutParams($order){
		$params = $this->bbvaUtils->checkoutParams($order);
		extract($order);
		extract($params);
		$peticion = "<tpv><oppago><idterminal>$idTerminal</idterminal><idcomercio>$idComercio</idcomercio><idtransaccion>$transactionID</idtransaccion><moneda>$moneda</moneda><importe>$importe</importe><urlcomercio>$urlRespuesta</urlcomercio><idioma>$idioma</idioma><pais>$pais</pais><urlredir>$urlRedireccion</urlredir><localizador>$localizador</localizador><firma>$firma</firma></oppago></tpv>";
		debug("Hemos creado la peticion $peticion");
		return $peticion;
	}


	function checkout($order){
		$peticion = $this->checkoutParams($order);
		echo $this->generateForm($peticion);
		exit;
	}


	function generateForm($peticion){
		if(defined('CAKE_CORE_INCLUDE_PATH')){
			$bbvaText = __('Ir al bbva');
			$loading = sprintf("<img src=\"%s\" alt=\"%s\"></img>",Router::url('/img/loading.gif',true),__('Cargando...'));
		} else{
			$bbvaText = 'Ir al pago seguro con tarjeta bancaria';
			$loading = sprintf("<!-- <img src=\"%s\" alt=\"%s\"></img> -->",'/tpv_bbva_php/img/loading.gif','Cargando...');
		}
		$form = '<html><body>
			'.$loading.'
			<form action="'.BBVA_CHECKOUT_URL.'" id="bbva_standard_checkout" name="Bbva" method="post">
			<input type="hidden" id="peticion" name="peticion" value="'.$peticion.'"></input>
			<!--<input type="submit" value="'.$bbvaText.'"></input>-->
   			<input type="image" alt="BBVA" SRC="img/tarjetas.gif" name="submit" />
			</form><!--<script type="text/javascript">document.getElementById("bbva_standard_checkout").submit(); </script>--></body></html>';
		return $form;
	}
}

?>
