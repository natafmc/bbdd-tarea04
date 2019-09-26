<?php
require APPPATH . 'libraries/REST_Controller.php';
require_once __DIR__ . '/../third_party/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Usuario extends REST_Controller
{
    public function __construct() {
        parent::__construct();     
        $this->load->model('Usuario_model', 'usuario');
    }
    public function index_get($id = null){
        $data['usuario'] = $this->usuario->getById($id);
        $data['usuarioList'] = $this->usuario->getAll();
        $this->load->view('add_usuario', $data);
    }

    public function add_post(){
        $nombre = $this->input->post('nombre');
        $apellido = $this->input->post('apellido');
        $telefono = $this->input->post('telefono');
        $nickname = $this->input->post('nickname');
        $password = md5($this->input->post('password'));
        $email = $this->input->post('email');
        $id = $this->input->post('id');
        $input = array(
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'telefono'=> $telefono,
            'nickname'=> $nickname,
            'password'=> $password,
            'email'=> $email,
        );
        $data['usuarioList'] = $this->usuario->getAll();
        if($id == null){
            $this->usuario->add($input);
            if(UI != 'si')
                $this->response("Usuario Creado", REST_Controller::HTTP_OK);
        }else{
            $this->usuario->update($input, $id);
            if(UI != 'si')
                $this->response("Usuario actualizado", REST_Controller::HTTP_OK);
        }
        redirect($this->index_get());
        
    }

    public function search_post(){
        $nickname = $this->input->post('nickname');
        $nombre = $this->input->post('nombre');
        $resultados = $this->usuario->search($nickname, $nombre);
        if(UI != 'si')
            $this->response($resultados, REST_Controller::HTTP_OK);
    }

    public function all_get(){
        $data['lista'] = $this->usuario->getAll();
        if(UI != 'si')
            $this->response($data['lista'], REST_Controller::HTTP_OK);        
    }

    public function addQty_get($id){
        $resp = $this->usuario->addQty($id);
        if(UI != 'si')
            $this->response($resp, REST_Controller::HTTP_OK);
    }  
    public function discountQty_get($id){
        $resp = $this->usuario->discountQty($id);
        if(UI != 'si')
            $this->response($resp, REST_Controller::HTTP_OK);
    } 

    public function perfil_get($id){
        $usuario = $this->usuario->getById($id);
        
        $queue_rpc = new RpcClient();
        /**OBTINENE el perfil de usuario */
        $queueUser = "PERFIl-".$id;
        $queue_rpc->setQueue($queueUser);
        $response = $queue_rpc->call($id);
        $data = array("USUARIO" => $usuario, "KUDOS" =>json_decode($response));
        if(UI != 'si')
            $this->response($data, REST_Controller::HTTP_OK);
    }

    public function delete_delete($id){
        $usuario = $this->usuario->getById($id);
        $this->usuario->delete($id);
        $queue_rpc = new RpcClient();
        $queue_rpc->setQueue('BorraUsuario');
        $response = $queue_rpc->call($id);
        $array = array("ELIMINADO " => $usuario, "KUDOS"=>json_decode($response));        
        $this->response($array, REST_Controller::HTTP_OK);
    } 
}