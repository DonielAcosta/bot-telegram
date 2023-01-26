<?php

class Chatbot extends controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $getupdate = file_get_contents("php://input"); 
        if(empty($getupdate)){
            die('No se recibio ningun mensaje');
        }

        $update  = json_decode($getupdate,true);
        $this->guardalog($update);
        $chatid  = $update['message']['chat']['id'];
        $message = $update['message']['text'];

        $this->struct($update);
        $this->inventariosedes($chatid,$message);
        $this->direccionSedes($chatid,$message);

    }

    public function sendMessages($chatid,$response){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 13');

        $link   = 'https://api.telegram.org/bot'.$token;
        $url    = $link.'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.urlencode($response); 
        $resp   = file_get_contents($url);
        
    }
    
    public function img($chatid){
        $resp  = $this->datasis->damereg("SELECT * FROM bots ");
        $token = $resp['token'];

        $link  = 'https://api.telegram.org/bot'.$token;

        $data  = [
            'chat_id' => $chatid,
            'photo'   => 'https://drocerca.com/bottel/img/atamel.png',
        ];
        $resp = file_get_contents($link."/sendPhoto?".http_build_query($data) );
        return $resp;
    }

    public function inventariosedes($chatid,$message){ 
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);

        $comando = strtolower($message);
        $resp    = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
        $consu   = $this->datasis->us_ascii2html($resp['descripcion']);
        $resp2   = $this->datasis->us_ascii2html($resp['consulta']);

        switch(strtolower($message)){
            case 'inventario merida':
                $url = $resp2;
                $this->files($chatid,$url); 
                break;
             case 'inventario centro':
                $url = $resp2;
                $this->files($chatid,$url); 
                 break;
             case 'inventario oriente':
                $url = $resp2;
                $this->files($chatid,$url); 
                 break;
        }
    }

    public function direccionSedes($chatid,$message){
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);

        $comando = strtolower($message);
        $resp    = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
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
    
        var_dump($result);
        curl_close($ch);
    }

	// Guarda el mensaje recibido de telegram
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

        memowrite($this->db->insert_string('logtelg',$data));
        $this->db->insert('logtelg',$data);
	}

    public function struct($registro){

        $chatid         = $registro['message']['chat']['id'];
        $name           = $registro['message']['chat']['first_name'];
        $message        = $registro['message']['text'];

        $comando = strtolower($message);
        $resp = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
        $consu =$this->datasis->us_ascii2html($resp['descripcion']);
        $resp2 = $this->datasis->us_ascii2html($resp['consulta']);

        if(strtolower($message) == 'start'){
            $response = 'Hola! <b>'.$name.'</b>'.' '.$resp2;
            $this->sendMessages($chatid,$response);
        }
        if(strtolower($message) == 'info'){
            $response =  $resp2;
            $this->sendMessages($chatid,$response);
        }
        if(strtolower($message) == 'inventario'){
            $this->sendMessages($chatid,$resp2);
        }

        switch(strtolower($message)){
            case '/start':
                $response = 'Hola! <b>'.$name.'</b>'.' '.$resp2;
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
                $this->sendMessages($chatid,$this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 18'));
                break;
            case 'bien':
                $this->sendMessages($chatid,$this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 19'));
            default:
                $respuesta = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 11'); 
                $mSQL = str_replace('busqueda', $message, $respuesta); //para buscar la consulta en base de datos 
                $query = $this->db->query(''.$mSQL.''); 
                if($query->num_rows() > 0){
                    $response = '';
                    foreach( $query->result() as $row ){
                        $response .= $row->codigo;
                        $response .= ' '.$row->descrip;
                        $response .= ' (Ex.'.nformat($row->existen,0).')';
                        $response .= "\n";
                    }
                    $this ->sendMessages($chatid,$this->datasis->dameval('SELECT comando FROM telegram WHERE id = 11'));
                    $this ->sendMessages($chatid,$response);
                }
                $centro = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 12'); 
                $mSQL = str_replace('busqueda', $message, $centro); //para buscar la consulta en base de datos 
                $query = $this->db->query(''.$mSQL.''); 
                if($query->num_rows() > 0){
                    $response = '';
                    foreach( $query->result() as $row ){
                        $response .= $row->codigo;
                        $response .= ' '.$row->descrip;
                        $response .= ' (Ex.'.nformat($row->existen,0).')';
                        $response .= "\n";
                    }
                    $this ->sendMessages($chatid, $this->datasis->dameval('SELECT comando FROM telegram WHERE id = 12'));
                    $this ->sendMessages($chatid,$response);
                }
                $oriente = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 13'); 
                $mSQL = str_replace('busqueda', $message, $oriente); //para buscar la consulta en base de datos 
                $query = $this->db->query(''.$mSQL.''); 
                if($query->num_rows() > 0){
                    $response = '';
                    foreach( $query->result() as $row ){
                        $response .= $row->codigo;
                        $response .= ' '.$row->descrip;
						$response .= ' (Ex.'.nformat($row->existen,0).')';
                        $response .= "\n";
                    }
                    $this ->sendMessages($chatid,$this->datasis->dameval('SELECT comando FROM telegram WHERE id = 13'));
                    $this ->sendMessages($chatid,$response);
                }
            break;
        }
    }
}

?>
