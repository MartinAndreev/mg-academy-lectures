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
class Customers extends App_Controller {

    protected function _needsAuth(): bool {
        return true;
    }

    function __construct() {
        parent::__construct();

        $this->twiggy
                ->title()
                ->append('Customers');
    }

    function index() {

        $list = new \App\Libraries\Core\List_Table();
        $list->setCollectionParams([
            'user_id' => [User::getLoggedUser()->id],
            'type' => [App\Models\Contragent::TYPE_CUSTOMER]
        ]);

        $list->setCollection(\App\Models\Contragents::class);

        $list->addColumn(['name' => 'Name']);
        $list->addColumn(['bulstat' => 'Bulstat']);
        $list->addColumn(['city' => 'City']);
        //$list->addColumn(['country' => 'Country']);
        //$list->addColumn(['mol' => 'Mol']);
        $list->addColumn(['is_active' => 'Is active?']);
        $list->addColumn(['created_on' => 'Created on']);
        //$list->addColumn(['updated_on' => 'Updated on']);

        $list->addFinder('bulstat');
        $list->addFinder('name');
        $list->addFinder('city');
        $list->addFinder('country');
        $list->addFinder('mol');
        $list->addFinder('is_active', \App\Libraries\Core\List_Table::FINDER_TYPE_SELECT, [
            0 => 'No',
            1 => 'Yes'
        ]);

        $list->setDataFilter(function(\App\Libraries\Core\Data $data) {
            $data->setcreated_on(date('d.m.Y H:i:s', $data->getcreated_on()));
            $data->setis_active(($data->getis_active() == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>');
            return $data;
        });

        $list->addAction(site_url('customers/edit/{{id}}'), 'Edit', '<i class="fa fa-edit"></i>');
        $list->addAction(site_url('customers/delete/{{id}}'), 'Delete', '<i class="fa fa-remove"></i>', [
            'onclick' => 'return confirm(\'Are you sure?\')'
        ]);

        $list->init();

        $this->twiggy->set('table', $list);

        $this->twiggy->display('customers/index');
    }

    function add() {
        $data = [
            'name' => '',
            'bulstat' => '',
            'city' => '',
            'country' => '',
            'address' => '',
            'mol' => '',
            'is_active' => 0
        ];

        if ($this->input->post()) {
            $data = array_merge($data, $this->input->post());

            $supplier = new \App\Models\Contragent();
            $supplier->name = $this->input->post('name');
            $supplier->bulstat = $this->input->post('bulstat');
            $supplier->city = $this->input->post('city');
            $supplier->country = $this->input->post('country');
            $supplier->address = $this->input->post('address');
            $supplier->mol = $this->input->post('mol');
            $supplier->type = \App\Models\Contragent::TYPE_CUSTOMER;
            $supplier->is_active = $this->input->post('is_active');

            $supplier->save();

            if ($supplier->isValid()) {
                redirect('customers');
            } else {
                $this->twiggy->set('errors', $supplier->getErrors());
            }
        }

        $this->twiggy
                ->title()
                ->prepend('Create a supplier');

        $this->twiggy->set('action', 'Create a customer');
        $this->twiggy->set('form_data', $data);
        $this->twiggy->display('customers/form');
    }

    function edit($id) {
        $supplier = new \App\Models\Contragent();

        if (!$supplier->find([
                    'id' => $id,
                    'user_id' => User::getLoggedUser()->id,
                ])) {
            redirect('suppliers');
        }

        $data = [
            'name' => $supplier->get('name'),
            'bulstat' => $supplier->get('bulstat'),
            'city' => $supplier->get('city'),
            'country' => $supplier->get('country'),
            'address' => $supplier->get('address'),
            'mol' => $supplier->get('mol'),
            'is_active' => $supplier->get('is_active')
        ];

        if ($this->input->post()) {
            $data = array_merge($data, $this->input->post());


            $supplier->name = $this->input->post('name');
            $supplier->bulstat = $this->input->post('bulstat');
            $supplier->city = $this->input->post('city');
            $supplier->country = $this->input->post('country');
            $supplier->address = $this->input->post('address');
            $supplier->mol = $this->input->post('mol');
            $supplier->is_active = $this->input->post('is_active');

            $supplier->save();

            if ($supplier->isValid()) {
                redirect('customers');
            } else {
                $this->twiggy->set('errors', $supplier->getErrors());
            }
        }

        $this->twiggy
                ->title()
                ->prepend(sprintf('Edit supplier %s', $supplier->name));

        $this->twiggy->set('action', sprintf('Edit supplier %s', $supplier->name));
        $this->twiggy->set('form_data', $data);
        $this->twiggy->display('customers/form');
    }

    function delete($id) {
        $supplier = new \App\Models\Contragent();

        if ($supplier->find([
                    'id' => $id,
                    'user_id' => User::getLoggedUser()->id,
                ])) {
            $supplier->delete();
        }

        redirect('customers');
    }

    function find() {
        $customers = new \App\Models\Contragents([
            'user_id' => [User::getLoggedUser()->id],
            'type' => [App\Models\Contragent::TYPE_CUSTOMER],
            'search' => $this->input->get('term')
        ]);

        $formated = [];

        while ($customers->haveRows()) {
            $customer = $customers->theRow();

            $formated[] = [
                'id' => $customer->getid(),
                'name' => $customer->getname(),
                'value' => $customer->getname(),
                'bulstat' => $customer->getbulstat(),
                'city' => $customer->getcity(),
                'country' => $customer->getcountry(),
                'address' => $customer->getaddress(),
                'mol' => $customer->getmol(),
            ];
        }

        echo json_encode($formated);
    }

}
