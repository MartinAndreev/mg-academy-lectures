<?php

use App\Models\User;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * The collection class. Used for data listing
 *
 * @author Martin Andreev <marto@www-you.com>
 * @version 1.0
 * @since 1.6
 * @package com.www-you.cms.system.core
 * @copyright (c) 2016, Martin Andreev
 */
class Invoices extends App_Controller {

    protected function _needsAuth(): bool {
        return true;
    }

    function __construct() {
        parent::__construct();

        $this->twiggy
                ->title()
                ->append('Goods');
    }

    function index() {

        $list = new \App\Libraries\Core\List_Table();
        $list->setCollectionParams([
            'user_id' => [User::getLoggedUser()->id],
            'type' => [\App\Models\Document::TYPE_INVOICE]
        ]);

        $list->setCollection(\App\Models\Documents::class);

        $list->addColumn(['invoice_number' => 'Number']);
        $list->addColumn(['invoice_date' => 'Date']);
        $list->addColumn(['customer_name' => 'Customer name'], false);
        $list->addColumn(['customer_bulstat' => 'Bulstat']);
        $list->addColumn(['updated_on' => 'Updated on']);
        $list->addColumn(['no_vat_total' => 'No vat total']);
        $list->addColumn(['total' => 'Total']);

        $list->addFinder('invoice_number');
        $list->addFinder('invoice_date', App\Libraries\Core\List_Table::FINDER_TYPE_DATEPICKER);
        $list->addFinder('is_active', \App\Libraries\Core\List_Table::FINDER_TYPE_SELECT, [
            0 => 'No',
            1 => 'Yes'
        ]);

        $list->setDataFilter(function(\App\Libraries\Core\Data $data) {
            $data->setcreated_on(date('d.m.Y H:i:s', $data->getcreated_on()));
            $data->setis_active(($data->getis_active() == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>');
            return $data;
        });

        $list->addAction(site_url('invoices/edit/{{id}}'), 'Edit', '<i class="fa fa-edit"></i>');
        $list->addAction(site_url('invoices/delete/{{id}}'), 'Delete', '<i class="fa fa-remove"></i>', [
            'onclick' => 'return confirm(\'Are you sure?\')'
        ]);

        $list->init();

        $this->twiggy->set('table', $list);

        $this->twiggy->display('invoices/index');
    }

    function add() {
        $supplier = App\Models\Contragent::getCurrent();
        $data = [
            'from_contragent_hash' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'bulstat' => $supplier->bulstat,
                'city' => $supplier->city,
                'country' => $supplier->country,
                'address' => $supplier->address,
            ],
            'to_contragent_hash' => [
                'id' => '',
                'name' => '',
                'bulstat' => '',
                'city' => '',
                'country' => '',
                'address' => '',
            ],
            'invoice_number' => '',
            'invoice_date' => '',
            'vat_rate' => 0,
            'vat_total' => 0,
            'no_vat_total' => 0,
            'total' => 0,
            'goods' => []
        ];

        if ($this->input->post()) {
            $data = array_merge($data, $this->input->post());

            $document = new \App\Models\Document();
            $document->type = \App\Models\Document::TYPE_INVOICE;
            $document->invoice_date = $this->input->post('invoice_date');
            $document->from_contragent_hash = $this->input->post('from_contragent_hash');
            $document->to_contragent_hash = $this->input->post('to_contragent_hash');
            $document->setGoods($this->input->post('goods'));
            $document->vat_rate = $this->input->post('vat_percent');
            $document->save();

            if ($document->isValid()) {
                redirect('invoices');
            } else {
                $this->twiggy->set('errors', $document->getErrors());
            }
        }

        $this->twiggy
                ->title()
                ->prepend('Create a good');

        $this->twiggy->set('action', 'Create a good');
        $this->twiggy->set('form_data', $data);
        $this->twiggy->display('invoices/form');
    }

    function edit($id) {
        $document = new \App\Models\Document();
        if (!$document->find([
                    'id' => $id,
                    'user_id' => User::getLoggedUser()->id,
                    'type' => \App\Models\Document::TYPE_INVOICE
                ])) {
            redirect('invoices');
        }

        $data = [
            'from_contragent_hash' => [
                'id' => $document->from_contragent_hash['id'],
                'name' => $document->from_contragent_hash['name'],
                'bulstat' => $document->from_contragent_hash['bulstat'],
                'city' => $document->from_contragent_hash['city'],
                'country' => $document->from_contragent_hash['country'],
                'address' => $document->from_contragent_hash['address'],
            ],
            'to_contragent_hash' => [
                'id' => $document->to_contragent_hash['id'],
                'name' => $document->to_contragent_hash['name'],
                'bulstat' => $document->to_contragent_hash['bulstat'],
                'city' => $document->to_contragent_hash['city'],
                'country' => $document->to_contragent_hash['country'],
                'address' => $document->to_contragent_hash['address'],
            ],
            'invoice_number' => $document->invoice_number,
            'invoice_date' => $document->invoice_date,
            'vat_rate' => $document->vat_rate,
            'vat_total' => $document->vat_total,
            'no_vat_total' => $document->no_vat_total,
            'total' => $document->total,
            'goods' => $document->getGoods()
        ];

        if ($this->input->post()) {
            $data = array_merge($data, $this->input->post());


            $document->type = \App\Models\Document::TYPE_INVOICE;
            $document->invoice_date = $this->input->post('invoice_date');
            $document->from_contragent_hash = $this->input->post('from_contragent_hash');
            $document->to_contragent_hash = $this->input->post('to_contragent_hash');
            $document->setGoods($this->input->post('goods'));
            $document->vat_rate = $this->input->post('vat_percent');
            $document->save();

            if ($document->isValid()) {
                redirect('invoices');
            } else {
                $this->twiggy->set('errors', $document->getErrors());
            }
        }

        $this->twiggy
                ->title()
                ->prepend('Create a good');

        $this->twiggy->set('action', 'Create a good');
        $this->twiggy->set('form_data', $data);
        $this->twiggy->display('invoices/form');
    }

    function delete($id) {
        $good = new \App\Models\Good();

        if ($good->find([
                    'id' => $id,
                    'user_id' => User::getLoggedUser()->id,
                ])) {
            $good->delete();
        }

        redirect('goods');
    }

}
