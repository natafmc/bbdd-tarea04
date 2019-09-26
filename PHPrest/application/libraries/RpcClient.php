<?php
require_once __DIR__ . '/../third_party/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;
    private $queue;

    public function __construct()
    {
        if ( ! empty($config) ) {
            $this->initialize($config);
        }
        $this->connection = new AMQPStreamConnection(
            'localhost',
            5672,
            'guest',
            'guest'
        );
        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "",
            false,
            false,
            true,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            '',
            false,
            true,
            false,
            false,
            array(
                $this,
                'onResponse'
            )
        );
    }
    public function initialize($config=array()) {
	    foreach ($config as $key=>$value) {
		     $this->{$key} = $value;
	    }
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }
    public function setQueue($queue){
        $this->queue = $queue;
    }

    public function call($n)
    {              
        try{
            $this->response = null;
            $this->corr_id = uniqid();
            $msg = new AMQPMessage(
                (string) $n,
                array(
                    'correlation_id' => $this->corr_id,
                    'reply_to' => $this->callback_queue
                )
            );  
            $this->channel->basic_publish($msg, '', $this->queue);    
            while (!$this->response) {
                $this->channel->wait();
            }    
            return $this->response;
        }catch(Error $e){
            return "";
        }

    }
}