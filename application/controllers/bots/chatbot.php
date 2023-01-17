<?php

class Chatbot extends controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
 
        $getupdate = file_get_contents("php://input"); 
        if(empty($getupdate)){
            $response = 'No entiendo que me quieres Decir';
            $this->sendMessages($chatid,$response);
            die();
        }

        $update    = json_decode($getupdate,true);
        $chatid    = $update['message']['chat']['id'];
        $name      = $update['message']['chat']['first_name'];
        $message   = $update['message']['text'];

        $this->struct($message,$chatid,$name);
        $this->sedes($chatid,$message);
        $this->direccionSedes($chatid,$message);

    }

    public function sendMessages($chatid,$response){
        $resp = $this->datasis->damereg("SELECT * FROM bots ");
        $token = $resp['token'];
        $link   = 'https://api.telegram.org/bot'.$token;
        $url = $link.'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.urlencode($response); 
        $resp = file_get_contents($url);
        
    }
    private function img($chatid){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 13');

        $link   = 'https://api.telegram.org/bot'.$token;

        $data = [
            'chat_id' => $chatid,
            'photo' => 'https://drocerca.com/bottel/img/atamel.png',
        ];
        $resp = file_get_contents($link."/sendPhoto?".http_build_query($data) );
        return $resp;
    }
    private function sedes($chatid,$message){ 
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);

        $comando = strtolower($message);
        $resp = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
        $consu =$this->datasis->us_ascii2html($resp['descripcion']);
        $resp2 = $this->datasis->us_ascii2html($resp['consulta']);

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
    private function direccionSedes($chatid,$message){
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);

        $comando = strtolower($message);
        $resp = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
        $consu =$this->datasis->us_ascii2html($resp['descripcion']);
        $resp2 = $this->datasis->us_ascii2html($resp['consulta']);

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
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 13');

        $link   = 'https://api.telegram.org/bot'.$token;
  
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

    public function tecl($chatid){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 13');
        $reply = "telegram";

        $url = "https://api.telegram.org/bot$token/sendMessage";
        $keyboard = array(
                "inline_keyboard" => array(
                    array(
                        array(
                        "text" => "button",
                        "callback_data" => "button_0"
                        )
                    )
                )
            );
        $postfields = array(
            'chat_id' => "$chatid",
            'text' => "$reply",
            'reply_markup' => json_encode($keyboard)
        );

        if (!$curld = curl_init()) {
        exit;
        }

        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($curld, CURLOPT_URL,$url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curld);

        curl_close ($curld);

    }

    function send($method, $data){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 13');
        $url = "https://api.telegram.org/bot".$token. "/" . $method;

        if (!$curld = curl_init()) {
            exit;
        }
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        return $output;
    }

    public function struct($message,$chatid,$name){


        $comando = strtolower($message);
        $resp = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
        $consu =$this->datasis->us_ascii2html($resp['descripcion']);
        $resp2 = $this->datasis->us_ascii2html($resp['consulta']);

        if(strtolower($message) == 'start'){
            $response = 'Hola! <b>'.$name.'</b>'.' '.$resp2;
            $this->sendMessages($chatid,$response);
        }elseif(strtolower($message) == 'info'){
            $response =  $resp2;
            $this->sendMessages($chatid,$response);
        }elseif(strtolower($message) == 'inventario'){
            $this->sendMessages($chatid,$resp2);
        }elseif(strtolower($message) === 'imagen'){
            $this->sendMessages($chatid,$resp2);
            $this->img($chatid);
            die();
        }elseif(strtolower($message) == 'direccion'){
            $respuesta = 'Desea conocer la Direccion de una Sede Escriba: Direccion de "Nombre de la sede" ejemplo "direccion de merida"';
            $this->sendMessages($chatid,$respuesta);
        }elseif(strtolower($message) === $message){
            $this->sendMessages($chatid,$resp2);
            die();
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