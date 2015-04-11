<?php

// --------------------------------------------------------------------------
// Вывод полученных заголовков с нормализованными названиями
// --------------------------------------------------------------------------

define('DEBUG', @$_GET['debug']);

// Выбор действия по значению DEBUG
switch(DEBUG) {
	// ----------------------------------------------------------------------
	case "source":
		print '<pre>';
		print_r($_SERVER);
		break;
	// ----------------------------------------------------------------------
	case "result":
		print '<pre>';
		print_r(parse_headers());
		break;
	// ----------------------------------------------------------------------
	default:
		if($_GET) {
			print '<pre>';
			print "-----------------------------------\n";
			print "GET REQUEST PARAMS FROM EXTERN HOST\n";
			print "-----------------------------------\n";
			print "?debug=source -- full php headers\n";
			print "?debug=result -- result headers\n";
			print "./ -- json\n";
			print "-----------------------------------\n";
		}
		else {
			print json_encode(parse_headers());
		}
		break;
	// ----------------------------------------------------------------------
}

// Сборка итогового списка значимых заголовков
function parse_headers() {
	$res = array('client'=>array(), 'request'=>array());
	foreach($_SERVER as $k=>$v) {
		if(substr($k, 0, 5) == 'HTTP_') {
			$k = strtolower(substr($k, 5));
			$k = str_replace('_', '-', $k);
			$i = 1;
			if(isset($res['client'][$k])) {
				while(isset($res['client'][$k.'_'.$i])) {
					$i++;
				}
				$k.= '_'.$i;
			}
			$res['client'][$k] = $v;
		}
	}
	$res['request']['server-name']    = @$_SERVER['SERVER_NAME'];
	$res['request']['server-port']    = @$_SERVER['SERVER_PORT'];
	$res['request']['server-proto']   = @$_SERVER['SERVER_PROTOCOL'];
	$res['request']['request-method'] = @$_SERVER['REQUEST_METHOD'];
	$res['request']['request-time']   = @$_SERVER['REQUEST_TIME'];
	$res['request']['remote-addr']    = @$_SERVER['REMOTE_ADDR'];
	$res['request']['remote-port']    = @$_SERVER['REMOTE_PORT'];

	$res['client']['cookie'] = 'hidden';
	$res['client']['x-forwarded-for'] = 'hidden';
	$res['client']['x-real-for'] = 'hidden';

	return $res;
}

?>