<?php
/**
 * ProteoERP
 *
 * @autor    Doniel Acosta y Andres Hocevar
 * @license  GNU GPL v3
*/

//
//  Procesa y responde solicitudes del chat de telegram
class Chatbot extends controller {
	var $chatid;

	public function __construct() {
		parent::__construct();
		$this->load->library('rapyd');
	}

	public function index() {
		$mensajentrada = file_get_contents("php://input");
		if(empty($mensajentrada)){
			die('No se recibio ningun mensaje');
		}
		$entrada = json_decode($mensajentrada,true);

		// Guarda el mensaje entrante
		$this->guardalog($entrada);
		$chatid  = $entrada['message']['chat']['id'];
		$mensaje = $entrada['message']['text'];
		$nombre  = $entrada['message']['chat']['first_name'];

		$texto = strtolower($mensaje);
		if($texto == '/start'){
			$response = 'Hola! <b>'.$nombre.'</b>'.' '.'si necesita saber un poco mas escriba la palabra ayuda';
			$this->sendMessages($chatid,$response);
			die();
		}
		$this->chatid = $chatid;

		if(substr($mensaje,1) == '/') return;

		//Detecta si es un comando
		$respuesta = $this->comandos($mensaje);
		if (empty($respuesta)){
			// Procesa comando
			$msjlimpio = $this->limpia($mensaje);
			if (!empty($msjlimpio)){
				$respuesta = $this->buscainv($msjlimpio);
			} else
				$respuesta = 'No se encontro ningun resultado';
		}

		if(!empty($respuesta))
			$this->sendMessages( $chatid, $respuesta );
		else
			$this->sendMessages( $chatid, 'Gracias por contactarnos, como podemos seguir ayudando? (si necesita saber un poco mas escriba la palabra ayuda' );
	}


	/******************************************************************
	*       ESTOS SON LOS METODOS PARA PROBAR VAINAS
	*/
	//******************************************************************
	//prueba para funcionalidades
	function prueba1(){
		$mensaje = 'atamel;';
		//Detecta si es un comando
		$respuesta = $this->comandos($mensaje);
		if (empty($respuesta)){
			$msjlimpio = $this->limpia($mensaje);
			var_dump($msjlimpio);
			if (!empty($msjlimpio))
				$respuesta = $this->buscainv($msjlimpio);
			else
				$respuesta = 'No se encontro ningun resultado';
		}
		echo $respuesta;
		//$msj = $this->limpia($mensaje);
		//echo $this->buscainv($msj);
    }


	//******************************************************************
	// Para probar
	public function prueba($message,$chatid){
		$probando = $this->limpia($message,$chatid);
		$this->sendMessages($chatid,'paso por aqui');
		$this->sendMessages($chatid,$probando);
	}


	//******************************************************************
	// Envia mensajes
    public function sendMessages($chatid,$response){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 1');
        $link   = 'https://api.telegram.org/bot'.$token;
        $url    = $link.'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.urlencode($response);
        $resp   = file_get_contents($url);
    }

    //******************************************************************
	// guarda la informacion en base de datos
	public function guardalog($registro){
		$data = [];
		$data['message_id']     = $registro['message']['message_id'];
		$data['id_bot']         = $registro['message']['chat']['id'];
		$data['is_bot']         = $registro['message']['from']['is_bot'];
		$data['first_name']     = $registro['message']['chat']['first_name'];
		$data['text']           = $registro['message']['text'];
		$data['fecha']          = date('Y-m-d H:i:s', $registro['message']['date']);
		$data['last_name']      = $registro['message']['chat']['last_name'];
		$data['username']       = $registro['message']['from']['username'];
		$data['language_code']  = $registro['message']['from']['language_code'];
		$data['type']           = $registro['message']['chat']['type'];
		$data['xoffset']        = $registro['message']['entities'][0]['offset'];
		$data['length']         = $registro['message']['entities'][0]['length'];
		$data['entities_type']  = $registro['message']['entities'][0]['type'];
		$this->db->insert('logtelg',$data);
	}

    /******************************************************************
	*       ESTOS SON LOS METODOS PARA IMAGENES
	*/
	//******************************************************************
	//******************************************************************
	//envia una imagen
	public function img($chatid,$url){
		$resp  = $this->datasis->damereg('SELECT * FROM bots');
		$token = $resp['token'];
		$link  = 'https://api.telegram.org/bot'.$token;
		$data  = [
			'chat_id' => $chatid,
			// 'photo'   => 'https://drocerca.com/bottel/img/atamel.png',
			'photo'   => $url,

		];
		$resp = file_get_contents($link."/sendPhoto?".http_build_query($data) );
		return $resp;
	}

	//******************************************************************
	//envia una imagen version 2
	function sendPhoto($chatid, $url) {
		$resp  = $this->datasis->damereg('SELECT * FROM bots');
		$token = $resp['token'];
		$api_url = 'https://api.telegram.org/bot' . $token . '/sendPhoto';

		$post_fields = [
			'chat_id' => $chatid,
			'photo' => $url,
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type:multipart/form-data"
		));
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		$output = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}

		curl_close($ch);

		return $output;
	}


    //******************************************************************
    // envia la direccion de una sede
    public function direccionSedes($chatid,$message){
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);

        $comando = strtolower($message);
        $resp    = $this->datasis->damereg('SELECT * FROM botteleg  WHERE comando=?',array($comando));
        $resp2   = $this->datasis->us_ascii2html($resp['consulta']);

        switch(strtolower($message)){
            case 'direccion de merida':
                $this->sendMessages($chatid,$resp2);
                break;
             case 'direccion de centro':
                $this->sendMessages($chatid,$resp2);
                break;
             case 'direccion de oriente':
                $this->sendMessages($chatid,$resp2);
                break;
        }
    }

	//******************************************************************
	// Procesa comandos
	public function comandos($mensaje){
		$resulta = '';
		$comando = strtolower($mensaje);
		$resp    = $this->datasis->damereg("SELECT * FROM botteleg  WHERE comando=? LIMIT 1",array($comando));
		if(!empty($resp['funcion'])){
			eval($resp['funcion']);
			return $resulta;
		}
		if (!empty($resp))
			$resulta   = $this->datasis->us_ascii2html($resp['consulta']);
		return $resulta;

	}

    //******************************************************************
    // Envia el inventario de una sede
	public function inventariosedes($sede,$url){
		setlocale(LC_ALL, "en_US.utf8");
		$message = iconv("utf-8", "ascii//TRANSLIT", $sede);
		$chatid = $this->chatid;
		$this->files($chatid,$url);
		return 'Gracias por su interes';

	}

	//******************************************************************
    //envia un archivo xls,pdf word
    public function files($chatid,$url){
        $resp  = $this->datasis->damereg("SELECT * FROM bots ");
        $token = $resp['token'];

        $link  = 'https://api.telegram.org/bot'.$token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link ."/sendDocument?chat_id=" . $chatid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $url );
        $cFile = new CURLFile($url , $finfo);

        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "document" => $cFile
        ]);

        $result = curl_exec($ch);

        curl_close($ch);
    }

	//******************************************************************
	// Limpia de caracteres irrelevantes
	public function limpia($mensaje) {
		if(empty($mensaje)) return false;

		// Elimina caracteres del diccionario
		$simbolos = $this->datasis->dameareg('SELECT valor FROM botchar');
		foreach($simbolos AS $simbo){
			$mensaje = str_replace($simbo, '', $mensaje);
		}
        // Elimina espacios seguidos
		$tiene = strstr($mensaje,"  ");
		while ($tiene){
			$mensaje = str_replace('  ', ' ', $mensaje);
			$tiene   = strstr($mensaje,"  ");
		}

		// Elimina palabras del diccionario
		$mensaje  = strtolower($mensaje);
		$mensajeA = explode(" ",$mensaje);
		$palabras = $this->datasis->dameareg('SELECT palabra FROM botpexclu');
		foreach($palabras AS $pala){
			$esta = array_search($pala['palabra'], $mensajeA);
			if ($esta !== false ){
				unset($mensajeA[$esta]);
			}
		}

		// Elimina numeros
		foreach($mensajeA AS $ind => $msj){
			if ( is_numeric($msj) ){
				unset($mensajeA[$ind]);
			}
		}

		// Elimina palabras de menos de 3 letras
		foreach($mensajeA AS $ind => $msj){
			if ( strlen($msj) <= 3 ){
				unset($mensajeA[$ind]);
			}
		}

		return $mensajeA;
	}


	//******************************************************************
	// Busca medicamentos por sedes
	function construsql($comando,$mensaje){
		$dbcomando = $this->db->escape($comando);
		$mSQL = '';
		foreach($mensaje AS $busca){
			$consulta = $this->datasis->dameval('SELECT consulta FROM botteleg WHERE comando=?',array($comando));
			if ($mSQL == '')
				$mSQL .= str_replace('busqueda', $busca, $consulta);
			else
				$mSQL .= ' UNION ALL '.str_replace('busqueda', $busca, $consulta);
		}
		if(empty($mSQL)) 
			return '';
		else
			return $mSQL.' LIMIT 20';
	}

	//******************************************************************
	// Busca medicamentos por sedes
	public function buscainv($mensaje){
		$salida = '';

		$mSQL  = $this->construsql('merida',$mensaje);
		if(!empty($mSQL)){
			$query = $this->db->query($mSQL);
			if($query->num_rows() > 0){
				$response = '';
				foreach( $query->result() as $row ){
					$response .= $row->codigo;
					$response .= ' '.$row->descrip;
					$response .= ' (Ex.'.nformat($row->existen,0).')';
					$response .= "\n";
				}
				$salida .= "\n*****MERIDA*****\n".$response;
			}
		}

		$mSQL  = $this->construsql('centro',$mensaje);
		if(!empty($mSQL)){
		$query = $this->db->query(''.$mSQL.'');
			if($query->num_rows() > 0){
				$response = '';
				foreach( $query->result() as $row ){
					$response .= $row->codigo;
					$response .= ' '.$row->descrip;
					$response .= ' (Ex.'.nformat($row->existen,0).')';
					$response .= "\n";
				}
				$salida .= "\n*****CENTRO*****\n".$response;
			}
		}

		$mSQL  = $this->construsql('centro',$mensaje);
		if(!empty($mSQL)){
			$query = $this->db->query(''.$mSQL.'');
			if($query->num_rows() > 0){
				$response = '';
				foreach( $query->result() as $row ){
					$response .= $row->codigo;
					$response .= ' '.$row->descrip;
					$response .= ' (Ex.'.nformat($row->existen,0).')';
					$response .= "\n";
				}
				$salida .= "\n*****ORIENTE*****\n".$response;
			}
		}

		return $salida;
	}


	//******************************************************************
	// Procesa entrada
	public function procesaentrada($chatid,$mensaje,$nombre){
		$texto   = $this->limpia($mensaje);

		setlocale(LC_ALL, "en_US.utf8");
		$texto = iconv("utf-8", "ascii//TRANSLIT", $texto);

		$comando = strtolower($texto);
		$resp    = $this->datasis->damereg("SELECT * FROM botteleg  WHERE comando = '$comando'");
		$resp2   = $this->datasis->us_ascii2html($resp['consulta']);

		$texto = strtolower($texto);
		if($texto == '/start'){
			$response = 'Hola! <b>'.$nombre.'</b>'.' '.'si necesita saber un poco mas escriba la palabra ayuda';
			$this->sendMessages($chatid,$response);
		}
        if($texto == 'info'){
            $response =  $resp2;
            $this->sendMessages($chatid,$response);
        }
        if($texto == 'inventario'){
            $this->sendMessages($chatid,$resp2);
        }
        if($texto == 'direccion'){
            $this->sendMessages($chatid,$resp2);
        }

        switch($texto){
            case '/start':
                $response = 'Hola! <b>'.$nombre.'</b>'.' '.$resp2;
                $this->sendMessages($chatid,$response);
                break;
            case '/info':
                $response = $resp2;
                $this->sendMessages($chatid,$response);
                break;
            case '/inventario':
                $this->sendMessages($chatid,$resp2);
                break;
            case '/direccion':
                $this->sendMessages($chatid,$resp2);
                break;
            case '/imagen':
                $this->sendMessages($chatid,$resp2);
                $this->img($chatid);
                break;
            case 'hola':
                $this->sendMessages($chatid,$resp2);
                break;
            case 'bien':
                $this->sendMessages($chatid,$resp2);
                break;
            default:
                $this->buscainv($chatid,$mensaje);
            break;
        }
    }
}
