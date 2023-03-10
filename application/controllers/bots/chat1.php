<?php

class Chatbot extends controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 1. Obtenga la actualización de la API de Telegram
     * 2. Comprueba si la actualización está vacía
     * 3. Decodificar la actualización
     * 4. Guarde la actualización en un archivo de registro
     * 5. Obtenga la identificación del chat
     * 6. Recibe el mensaje
     * 7. Llame a la función de estructura
     * 8. Llame a la función inventariosedes
     * 9. Llamar a la función direccionSedes
    */
    public function index() {
        $getupdate = file_get_contents("php://input");
        if(empty($getupdate)){
            die('No se recibio ningun mensaje');
        }

        $update  = json_decode($getupdate,true);
        $this->guardalog($update);
        $chatid  = $update['message']['chat']['id'];
        $message = $update['message']['text'];
        $name    = $update['message']['chat']['first_name'];

        $this->struct($chatid,$message,$name);
        $this->inventariosedes($chatid,$message);
        $this->direccionSedes($chatid,$message);
        // $this->prueba($message,$chatid);
        //  $this->sendMessages($chatid,'prueba de fallas');  // esto se ustiliza para probar cuando se presenta una falla y hay que detectar donde

        // // para pruebas locales
        // $message = "existen saber si hay en merida atamel?";
        // // $this->compara($message);
        // var_dump($this->compara($message));

    }

    /**
     * It sends a message to the user.
     * 
     * @param chatid The chat id of the user you want to send the message to.
     * @param response The message you want to send to the user.
     */
    public function sendMessages($chatid,$response){
        $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 1');
        $link   = 'https://api.telegram.org/bot'.$token;
        $url    = $link.'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.urlencode($response);
        $resp   = file_get_contents($url);
    }

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

   /**
    * Envía una foto al usuario.
    *
    * @param chatid El ID de chat del usuario al que desea enviar el mensaje.
    *
    * @return La respuesta de la API de Telegram.
   */
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
    /**
     * Toma una cadena, la convierte a ascii, luego la compara con un campo de la base de datos y, si
     * coincide, envía un mensaje al usuario.
     *
     * @param chatid La identificación de chat del usuario al que desea enviar el mensaje.
     * @param message El mensaje enviado por el usuario.
     */
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

    /**
     * Una función que le permite enviar un archivo al usuario.
     *
     * @param chatid El ID de chat del usuario que envió el mensaje.
     * @param message El mensaje enviado por el usuario.
    */
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

     /**
      * Envía un archivo al usuario.
      *
      * @param chatid El ID de chat del usuario al que desea enviar el mensaje.
      * @param url La URL del archivo a enviar.
     */
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

    //*********************************************************************
    //
   /**
    * It takes a string, removes some characters, removes some words, and then searches for the
    * remaining words in a database
    * 
    * @param mensaje The message to be analyzed.
    */
    public function compara($message) {
        if(!is_string($message)) return false;
        if(empty($message))      return false;
$message = 'EL PRECIO 54 DEL producto MaS & BONITO ATAMEL';
        // Elimina simbolos
        $simbolos = $this->datasis->dameareg('SELECT valor FROM caracteres');
        foreach($simbolos AS $simbo){
            $message = str_replace($simbo, '', $message);
        }
        $message = str_replace('  ', ' ', $message);
        $message = str_replace('  ', ' ', $message);
        $message = str_replace('  ', ' ', $message);
        $message = str_replace('  ', ' ', $message);

        // Elimina palabras
        $message  = strtolower($message);
        $mensajeT = explode(" ",$message);
        //print_r($mensajeT);echo '<br>';
        $palabras = $this->datasis->dameareg('SELECT palabra FROM pexcluidas');

        foreach($palabras AS $pala){
            $esta = array_search($pala['palabra'], $mensajeT); 
            if ($esta !== false ) unset($mensajeT[$esta]);
        }
        $salida = '';
        foreach($mensajeT as $x) {
            $x = '%'.trim($x).'%';
            $x = $this->db->escape($x);
            //var_dump($x);die();
            $query2 = $this->db->query("SELECT codigo, descrip, precio1, existen FROM sinv WHERE descrip LIKE ${x}");
            if ($query2->num_rows() > 0) {
                foreach( $query2 AS $prod ){
                    $salida .=  $prod."\n";
                }
            }
        }
    }

    /**
     * It searches for a product in the database and returns the result
     *
     * @param chatid The chat ID of the user you want to send the message to.
     * @param message The message sent by the user.
     */
    public function buscarM($chatid,$message){
        $merida = $this->datasis->dameval('SELECT consulta FROM telegram WHERE id = 11');
        $mSQL = str_replace('busqueda', $message, $merida); //para buscar la consulta en base de datos
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
            $this ->sendMessages($chatid,$this->datasis->dameval('SELECT comando FROM telegram WHERE id = 12'));
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
    }

    public function prueba($message,$chatid){
        $probando = $this->compara($message,$chatid);
        $this->sendMessages($chatid,'paso por aqui');
        $this->sendMessages($chatid,$probando);

    }
    
    ///****************************************************************
    // ?????
    public function struct($chatid,$message,$name){
        $texto   = $this->compara($message);
        $message = $texto;
        /* quita las tildes */
        setlocale(LC_ALL, "en_US.utf8");
        $message = iconv("utf-8", "ascii//TRANSLIT", $message);
        
        $comando = strtolower($message);
        $resp    = $this->datasis->damereg("SELECT * FROM telegram  WHERE comando = '$comando'");
        $resp2   = $this->datasis->us_ascii2html($resp['consulta']);
        // $message = $this->compara($message);
        $message = strtolower($message);
        if($message == 'start'){
            $response = 'Hola! <b>'.$name.'</b>'.' '.$resp2;
            $this->sendMessages($chatid,$response);
        }
        if($message == 'info'){
            $response =  $resp2;
            $this->sendMessages($chatid,$response);
        }
        if($message == 'inventario'){
            $this->sendMessages($chatid,$resp2);
        }
        if($message == 'direccion'){
            $this->sendMessages($chatid,$resp2);
        }

        switch($message){
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
                $this->sendMessages($chatid,$resp2);
                break;
            case 'bien':
                $this->sendMessages($chatid,$resp2);
                break;
            default:
                $this->buscarM($chatid,$message);
            break;
        }
    }
}