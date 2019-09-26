<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends CI_Model {

  public function getAll(){
    $q = $this->db->get('usuario');
    $response = $q->result();
    return $response;
  }
  public function getById($id){
       $this->db->from('usuario');
    $this->db->where('id', $id);
    $query = $this->db->get();
    return $query->row();
  }
  public function add($input){
    $this->db->insert('usuario',$input);  
  }

  public function addQty($id){
    $this->db->select('qty');
    $this->db->from('kudos');
    $this->db->where('id', $id);
    $query = $this->db->get();
    $kudos = $query->row();
    return $this->db->query("UPDATE usuario SET qty = ".($kudos->qty + 1)."WHERE id = $id");    
  }
  public function discountQty($id){
    $this->db->select('qty');
    $this->db->from('kudos');
    $this->db->where('id', $id);
    $query = $this->db->get();
    $kudos = $query->row();
    return $this->db->query("UPDATE usuario SET qty = ".($kudos->qty - 1)." WHERE id = $id");    
  }
  public function update($input, $id){    
    $this->db->where('id', $id);
    $this->db->update('usuario',$input);
  }
  public function delete($id){
    $this->db->delete('usuario', array('id' => $id)); 
  }
  public function search($nickname, $nombre){
    $this->db->from('usuario');
    $this->db->group_start();
    if($nickname != "")
      $this->db->like('nickname', trim($nickname));
    if($nombre != "")
      $this->db->like('nombre', trim($nombre));    
    $this->db->group_end();

    $query = $this->db->get();    
    return $query->result();
  }


}