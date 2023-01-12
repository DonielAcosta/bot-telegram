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

    private function start($chatid, $name){
        $response = 'Hola! '.'<b>'.$name.'</b>'.' Me has iniciado @Drocerca_bot';
        $this->sendMessages($chatid,$response);
    }

    private function inf($chatid){
        $response =  $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 14');
        $this->sendMessages($chatid,$response);
    }
    
    private function invMerida($chatid){
        $response = $this->datasis->dameval('SELECT descripcion FROM telegram WHERE id = 15');
        $this->sendMessages($chatid,$response);
        $url = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 15');
        $this->files($chatid,$url);  
    }

    private function invCentro($chatid){
        $response = $this->datasis->dameval('SELECT descripcion FROM telegram WHERE id = 16');
        $this->sendMessages($chatid,$response);
        $url = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 16');
        $this->files($chatid,$url);
    }

    private function invOriente($chatid){ 
        $response = $this->datasis->dameval('SELECT descripcion FROM telegram WHERE id = 17');
        $this->sendMessages($chatid,$response);
        $url = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 17');
        $this->files($chatid,$url);
    }
    private function direccionMerida($chatid){ 
        $response = $this->datasis->dameval('SELECT descripcion FROM telegram WHERE id = 21');
        $this->sendMessages($chatid,$response);
        $url = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 21');
        $this->sendMessages($chatid,$url);
    }
    private function direccionCentro($chatid){ 
        $response = $this->datasis->dameval('SELECT descripcion FROM telegram WHERE id = 22');
        $this->sendMessages($chatid,$response);
        $url = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 22');
        $this->sendMessages($chatid,$url);
    }
    private function direccionOriente($chatid){ 
        $response = $this->datasis->dameval('SELECT descripcion FROM telegram WHERE id = 23');
        $this->sendMessages($chatid,$response);
        $url = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 23');
        $this->sendMessages($chatid,$url);
    }
    
    private function sedes($chatid,$message){
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);
        switch(strtoupper($message)){
            case 'MERIDA':
                $this->invMerida($chatid);
                break;
             case 'CENTRO':
                 $this->invCentro($chatid);
                 break;
             case 'ORIENTE':
                 $this->invOriente($chatid);
                 break;
        }
    }
    private function direccionSedes($chatid,$message){
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);
        switch(strtoupper($message)){
            case 'DIRECCION DE MERIDA':
                $this->direccionMerida($chatid);
                break;
             case 'DIRECCION DE CENTRO':
                 $this->direccionCentro($chatid);
                 break;
             case 'DIRECCION DE ORIENTE':
                 $this->direccionOriente($chatid);
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

    public function sendMessages($chatid,$response){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 13');

        $link   = 'https://api.telegram.org/bot'.$token;
        $url = $link.'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.urlencode($response); 
        $resp = file_get_contents($url);
        
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
    public function struct($message,$chatid,$name){
        $message = strtoupper($message);
        
        switch($message){
            case 'prueba':
                
                $keyboard = [
                    'inline_keyboard' => [
                        [
                            [
                            'text' => 'Merida', 
                            'callback_data' => 'Inventario'
                            ],
                            [
                                'text' => 'Centro', 
                                'callback_data' => 'Centro'
                            ],
                            [
                                'text' => 'Orinte', 
                                'callback_data' => 'Orinte'
                            ],
                        ]
                    ]
                ];
                $encodedKeyboard = json_encode($keyboard);
                $parameters = 
                    array(
                        'chat_id' => $chatid, 
                        'text' => $message, 
                        'reply_markup' => $encodedKeyboard
                    );
                
                $this->send('sendMessage', $parameters); // function description Below
                break;
            case '/START':
                $this->start($chatid,$name);
                break;
            case '/INFO':
                $this->inf($chatid);
                break;
            case '/INVENTARIO':
                $this->sendMessages($chatid,$this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 10'));
                break;
            case '/DIRECCION':
                $this->sendMessages($chatid,$this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 20'));
                break;
            case '/IMAGEN':
                $this->img($chatid);
                break;
            case 'HOLA':
                $this->sendMessages($chatid,$this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 18'));
                break;
            case 'BIEN':
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