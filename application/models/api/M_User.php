<?php

class M_User extends CI_Model
{
    protected $user;

    public function __construct()
    {
        $this->user = 'user';
    }

    // get payment method by id
    public function getPaymentMethod($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get($this->paymentMethod);

        return $data->result()[0];
    }

    public function addUser()
    {
      $this->dn
    }
}
