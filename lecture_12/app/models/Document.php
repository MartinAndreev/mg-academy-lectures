<?php

namespace App\Models;

use App\Models\User;

class Document extends \App\Models\Core\Default_Object_Model {

    const TYPE_INVOICE = 'invoice';
    const TYPE_CREDIT_NOTE = 'credit_note';
    const TYPE_DEBIT_NOTE = 'debit_note';

    /**
     * Stors the invoice goods
     * @var type 
     */
    protected $_goods = [];
    protected $_oldRow = null;
    protected $_protected = ['user_id'];

    public function getPrimaryKey() {
        return 'id';
    }

    public function getTable(): string {
        return 'invoices';
    }

    public function setGoods(array $goods) {
        $this->_goods = $goods;
    }

    public function getGoods(): array {
        return $this->_goods;
    }

    public function getRules(): array {
        return [
            [
                'field' => 'invoice_date',
                'label' => 'invoice date',
                'rules' => 'required'
            ],
            [
                'field' => 'from_contragent_id',
                'label' => 'Supplier',
                'rules' => 'required|numeric'
            ],
            [
                'field' => 'to_contragent_id',
                'label' => 'Customer',
                'rules' => 'required|numeric'
            ],
        ];
    }

    protected function afterFind() {
        $this->_goods = $this->db
                ->where('invoice_id', $this->_primaryKeyValues['id'])
                ->get('invoces_x_goods')
                ->result_array();
        
        $this->_data['from_contragent_hash'] = unserialize($this->_data['from_contragent_hash']);
        $this->_data['to_contragent_hash'] = unserialize($this->_data['to_contragent_hash']);
    }

    protected function beforeValidation() {
        if (count($this->_goods) == 0) {
            $this->_errors[] = 'You cannot add an invoice without goods.';
        }

        $this->_data['from_contragent_id'] = $this->_data['from_contragent_hash']['id'];
        $this->_data['to_contragent_id'] = $this->_data['to_contragent_hash']['id'];

        $this->_data['from_contragent_hash'] = serialize($this->_data['from_contragent_hash']);
        $this->_data['to_contragent_hash'] = serialize($this->_data['to_contragent_hash']);
    }

    protected function beforeCreate() {
        $this->_data['created_on'] = strtotime('now');
        $invoiceNumber = 1;
        $maxNumber = $this->db
                ->query('SELECT MAX(invoice_number) AS max_invoice '
                        . ' FROM invoices'
                        . ' WHERE user_id = ? AND from_contragent_id = ?', [
                    User::getLoggedUser()->id, $this->_data['from_contragent_id']
                ])
                ->row();

        if ($maxNumber->max_invoice) {
            $invoiceNumber = $maxNumber->max_invoice + 1;
        }

        $this->_data['invoice_number'] = $invoiceNumber;

        if (User::isLoogedIn()) {
            $this->_data['user_id'] = User::getLoggedUser()->id;
        }
    }

    protected function beforeSave() {
        $this->_data['updated_on'] = strtotime('now');
        $this->_data['invoice_date'] = date('Y-m-d', strtotime($this->_data['invoice_date']));

        $this->_data['total'] = 0;
        $this->_data['no_vat_total'] = 0;
        $this->_data['vat_total'] = 0;

        foreach ($this->_goods as $good) {
            $this->_data['total'] += $good['quantity'] * $good['price'];
        }

        $this->_data['vat_total'] = $this->_data['total'] * ($this->_data['vat_rate'] / 100);
        $this->_data['no_vat_total'] = $this->_data['total'] - $this->_data['vat_total'];
    }

    protected function afterSave() {
        $this->db
                ->where([
                    'invoice_id' => $this->_primaryKeyValues['id'],
                    'user_id' => $this->_data['user_id']
                ])
                ->delete('invoces_x_goods');

        foreach ($this->_goods as & $good) {
            $good['total'] = $good['price'] * $good['quantity'];
            $good['created_on'] = strtotime('now');
            $good['updated_on'] = strtotime('now');
            $good['user_id'] = $this->_data['user_id'];
            $good['invoice_id'] = $this->_primaryKeyValues['id'];
        }

        $this->db->insert_batch('invoces_x_goods', $this->_goods);
    }

}
