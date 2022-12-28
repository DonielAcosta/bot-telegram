<?php

class Chatbot extends controller {
        var $token;
        var $url;

    public function __construct() {
        parent::__construct();
    }
    

    public function index() {
 
        // $this->token = $this->datasis->dameval('SELECT token FROM bots WHERE bot='.$localidad);
        // var $url;
        
        $getupdate = file_get_contents("php://input"); 
        //memowrite($getupdate);
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
    }

    private function start($chatid, $name){
        $response = 'Hola! '.$name.' Me has iniciado @Drocerca_bot';
        $this->sendMessages($chatid,$response);
    }

    private function inf($chatid){
        $response = 'Somos una droguería con  17 años de experiencia en el mercado, dedicándonos a la comercialización y distribución de productos y artículos farmacéuticos, farmaquímicos, medicamentos para uso humano, misceláneos, equipos de uso médico, quirúrgicos y odontológicos a nivel nacional, teniendo como objetivo satisfacer las necesidades de nuestros clientes a través de productos de calidad y excelencia en el servicio.';
        $this->sendMessages($chatid,$response);
    }
    
    private function invMerida($chatid){
        $response = 'Inventario Merida';
        $this->sendMessages($chatid,$response);
        $url = 'https://drocerca.com/inventario/Merida.xlsx';
        $this->files($chatid,$url);  
    }

    private function invCentro($chatid){
        $response = 'Inventario Centro';
        $this->sendMessages($chatid,$response);
        $url = 'https://drocerca.com/inventario/Centro.xlsx';
        $this->files($chatid,$url);
    }

    private function invOriente($chatid){ 
        $response = 'Inventario Oriente';
        $this->sendMessages($chatid,$response);
        $url = 'https://drocerca.com/inventario/Oriente.xlsx';
        $this->files($chatid,$url);
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
            default:'pruebas';
        }
    }
    public function files($chatid,$url){
        // $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 1');
        $token  = '5962646144:AAEB075ahUqBJ4nMbL_2qpaZ7HmkVc9T-tA';
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
        // $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 1');
        $token  = '5962646144:AAEB075ahUqBJ4nMbL_2qpaZ7HmkVc9T-tA';
        $link   = 'https://api.telegram.org/bot'.$token;
        $url = $link.'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.urlencode($response); 
        memowrite($url);
        $resp = file_get_contents($url);
        
    }
  
    private function img($chatid){
        // $token  = $this->datasis->dameval('SELECT token FROM bots WHERE id = 1');

        $token  = '5962646144:AAEB075ahUqBJ4nMbL_2qpaZ7HmkVc9T-tA';
        $link   = 'https://api.telegram.org/bot'.$token;

        $data = [
            'chat_id' => $chatid,
            'photo' => 'https://drocerca.com/bottel/img/atamel.png',
        ];
        // $url = $link.'/sendPhoto?'.http_build_query($data).'&photo='; 
        $resp = file_get_contents($link."/sendPhoto?".http_build_query($data) );
        // $resp =   file_get_contents($url);
        return $resp;
    }
    public function struct($message,$chatid,$name){
        $info = 'Despachamos a toda Venezuela desde nuestras sedes de Caracas y Mérida, seguimos orientados en ofrecer productos de calidad, variedad y a excelentes precios, logrando de esta manera ser un excelente aliado para las farmacias del país, además de garantizar el envío rápido y seguro de sus pedidos; ofreciendo el servicio que nuestros clientes merecen de la mano de un increíble talento humano capacitado, comprometido y motivado.';

        $message = strtoupper($message);
        switch($message){
            case '/START':
                $this->start($chatid,$name);
                break;
            case '/INFO':
                $this->inf($chatid);
                break;
            case '/INVENTARIO':
                $this->sendMessages($chatid,'Desea Conocer el inventario de alguna Sede: escriba la Sede  Merida  Oriente Centro');
                break;
            case '/IMAGEN':
                // $response = 'como estas?';
                $this->img($chatid);
                break;
            case 'HOLA':
                $response = 'como estas?';
                $this->sendMessages($chatid,$response);
                break;
            case 'BIEN':
                $response = 'en que te puedo ayudar ? neceistas /info ';
                $this->sendMessages($chatid,$response);
            default:
                $mSQL  = 'SELECT a.codigo, a.descrip, b.existen, b.alma
                FROM sinv a JOIN itsinv b 
                ON a.codigo=b.codigo AND b.alma="0001"
                WHERE b.existen>0 AND a.descrip LIKE "'."%".$message."%".'" LIMIT 10';

                // $mSQL = $this->db->query('SELECT consulta FROM telegram WHERE id = 5');  //para buscar la consulta en base de datos 
                memowrite($mSQL);
                $query = $this->db->query($mSQL); 
                if($query->num_rows() > 0){
                    $response = '';
                    foreach( $query->result() as $row ){
                        $response .= $row->codigo;
                        $response .= ' '.$row->descrip;
                        $response .= ' (Ex.'.nformat($row->existen,0).')';
                        $response .= "\n";
                    }
                    $this ->sendMessages($chatid,$response);
                }else {
                    $response = $info;
                }
            break;
        }
    }


}

?>