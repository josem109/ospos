<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Appconfig class
 */
class Currency extends CI_Model {

public function __construct()
{
    $this->load->database();
}

public function get_currency_rate($currency_symbol, $currency_date)
{
    $query = $this->db->get_where('currencytable', array('currency_symbol' => $currency_symbol, 'currency_date' => $currency_date));
    return $query->row_array();
}

public function set_currency_rate($currency_rate, $currency_symbol, $currency_date)
{
    $data = array(
        'currency_rate' => $currency_rate,
        'currency_symbol' => $currency_symbol,
        'currency_date' => $currency_date
    );

    return $this->db->insert('currencytable', $data);
}

public function update_currency_rate($id, $currency_rate)
{
    $data = array(
        'currency_rate' => $currency_rate
    );

    $this->db->where('id', $id);
    return $this->db->update('currencytable', $data);
}

public function save_currency_rate($currency_rate, $currency_symbol, $currency_date)
{
    try {
        // Verificar si ya existe un registro con la misma currency_symbol y currency_date
        $query = $this->db->get_where('currencytable', array('currency_symbol' => $currency_symbol, 'currency_date' => $currency_date));

        if ($query->num_rows() > 0) {
            // Si el registro existe, actualizarlo
            $data = array('currency_rate' => $currency_rate);
            $this->db->where('currency_symbol', $currency_symbol);
            $this->db->where('currency_date', $currency_date);
            return $this->db->update('currencytable', $data);
        } else {
            // Si el registro no existe, insertar uno nuevo
            $data = array(
                'currency_rate' => $currency_rate,
                'currency_symbol' => $currency_symbol,
                'currency_date' => $currency_date
            );
            return $this->db->insert('currencytable', $data);
        }
    } catch (Exception $e) {
        // Manejar la excepción
        //error_log($e->getMessage());
        return false;
    }
}
}