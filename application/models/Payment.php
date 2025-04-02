<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Payment class
 */
class Payment extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get payment by ID
     */
    public function get_info($payment_id)
    {
        $this->db->from('ospos_payments');
        $this->db->where('payment_id', $payment_id);
        
        return $this->db->get()->row();
    }

    /**
     * Get all payments for a specific sale
     */
    public function get_sale_payments($sale_id)
    {
        try {
            // Usar la consulta SQL directa que sabemos que funciona
            $sql = "SELECT p.*, e.username as employee_name 
                    FROM ospos_payments as p 
                    LEFT JOIN ospos_employees as e ON p.employee_id = e.person_id 
                    WHERE p.sale_id = ? 
                    ORDER BY p.payment_date DESC";
            
            return $this->db->query($sql, array($sale_id));
        } 
        catch (Exception $e) {
            log_message('error', 'Excepción en get_sale_payments: ' . $e->getMessage());
            // Devolver un resultado vacío en lugar de false
            return $this->db->query("SELECT 1 as dummy WHERE 0=1");
        }
    }

    /**
     * Get all payments for a specific customer
     */
    public function get_customer_payments($customer_id)
    {
        $this->db->from('payments');
        $this->db->where('customer_id', $customer_id);
        $this->db->order_by('payment_date', 'desc');
        
        return $this->db->get();
    }

    /**
     * Save payment record
     * 
     * @param array $payment_data Array containing payment information
     * @return boolean TRUE if save successful, FALSE otherwise
     */
    public function save($payment_data)
    {
        try {
            // Sanitizar los datos
            $clean_data = array(
                'sale_id' => $this->security->xss_clean($payment_data['sale_id']),
                'payment_type' => $this->security->xss_clean($payment_data['payment_type']),
                'payment_amount' => $this->security->xss_clean($payment_data['payment_amount']),
                'payment_date' => $this->security->xss_clean($payment_data['payment_date']),
                'employee_id' => $this->security->xss_clean($payment_data['employee_id'])
            );
            
            return $this->db->insert('ospos_payments', $clean_data);
        }
        catch(Exception $e) {
            log_message('error', 'Error en Payment->save(): ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Delete payment
     */
    public function delete($payment_id)
    {
        $this->db->where('payment_id', $payment_id);
        return $this->db->delete('ospos_payments');
    }

    /**
     * Get total payments amount for a sale
     */
    public function get_total_by_sale($sale_id)
    {
        $this->db->select_sum('payment_amount');
        $this->db->from('ospos_payments');
        $this->db->where('sale_id', $sale_id);
        
        $result = $this->db->get()->row();
        return $result ? $result->payment_amount : 0;
    }

    /**
     * Get payments by date range
     */
    public function get_payments_by_date($start_date, $end_date)
    {
        $this->db->from('payments');
        $this->db->where('payment_date >=', $start_date);
        $this->db->where('payment_date <=', $end_date);
        $this->db->order_by('payment_date', 'desc');
        
        return $this->db->get();
    }

    /**
     * Verifica si hay pagos registrados para una venta específica
     * @param int $sale_id ID de la venta
     * @return boolean TRUE si hay pagos, FALSE si no hay
     */
    public function has_payments($sale_id)
    {
        $this->db->where('sale_id', $sale_id);
        $query = $this->db->get('ospos_payments');
        return $query->num_rows() > 0;
    }
} 