<?php
 
    $token  = '5834526266:AAEvctxpy4qo-KQ330vLuJDe2R44aK4TzMU';
    $link   = 'https://api.telegram.org/bot'.$token;



    /* Obtiene la entrada del usuario. */
    $input = file_get_contents('php://input'); // for webhook  //lo que escriben en el bot de telegram

    /* Decodificando la respuesta json del bot de Telegram. */
    $update = json_decode($input, TRUE);
    /* Obtiene la identificación de chat del usuario. */
    $chatid = $update['message']['chat']['id'];
    /* Obtener el mensaje que el usuario envió al bot. */

    $message = $update['message']['text'];
    
    switch($message){
        /* Un caso de cambio que se ejecuta cuando el usuario envía el mensaje /start al bot. */
        case '/start':
            $response = 'Me has iniciado @drocercamerida_bot';
            sendMessage($chatid,$response);
            break;
        /* Un caso que se ejecuta cuando el usuario envía el mensaje /info al bot. */
        case '/info':
            $response = 'Somos una droguería con  17 años de experiencia en el mercado, dedicándonos a la comercialización y distribución de productos y artículos farmacéuticos, farmaquímicos, medicamentos para uso humano, misceláneos, equipos de uso médico, quirúrgicos y odontológicos a nivel nacional, teniendo como objetivo satisfacer las necesidades de nuestros clientes a través de productos de calidad y excelencia en el servicio.

            Despachamos a toda Venezuela desde nuestras sedes de Caracas y Mérida, seguimos orientados en ofrecer productos de calidad, variedad y a excelentes precios, logrando de esta manera ser un excelente aliado para las farmacias del país, además de garantizar el envío rápido y seguro de sus pedidos; ofreciendo el servicio que nuestros clientes merecen de la mano de un increíble talento humano capacitado, comprometido y motivado.';
            sendMessage($chatid,$response);
            break;
        case 'hola':
            $response = 'como estas?';
            sendMessage($chatid,$response);
            break;
        case 'bien':
            $response = 'en que te puedo ayudar ? neceistas /info ';
            sendMessage($chatid,$response);
        /* The default response when the bot doesn't understand the message. */
        default:
        $response = 'No entiendo que me quieres Decir';
            sendMessage($chatid,$response);
            break;
        }
    
    function sendMessage($chatid,$response){
        
        $url = $GLOBALS['link'].'/sendMessage?chat_id='.$chatid.'&parse_mode=HTML&text='.$response; 
        file_get_contents($url);
        //  return $send;
    }
?>