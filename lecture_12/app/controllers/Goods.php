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
class Goods extends App_Controller {

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
            'user_id' => [User::getLoggedUser()->id]
        ]);

        $list->setCollection(\App\Models\Goods::class);

        $list->addColumn(['name' => 'Name']);
        $list->addColumn(['price' => 'Price']);
        $list->addColumn(['is_active' => 'Is active?']);
        $list->addColumn(['created_on' => 'Created on']);
        $list->addColumn(['updated_on' => 'Updated on']);

        $list->addFinder('name');
        $list->addFinder('is_active', \App\Libraries\Core\List_Table::FINDER_TYPE_SELECT, [
            0 => 'No',
            1 => 'Yes'
        ]);

        $list->setDataFilter(function(\App\Libraries\Core\Data $data) {
            $data->setcreated_on(date('d.m.Y H:i:s', $data->getcreated_on()));
            $data->setis_active(($data->getis_active() == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>');
            return $data;
        });

        $list->addAction(site_url('goods/edit/{{id}}'), 'Edit', '<i class="fa fa-edit"></i>');
        $list->addAction(site_url('goods/delete/{{id}}'), 'Delete', '<i class="fa fa-remove"></i>', [
            'onclick' => 'return confirm(\'Are you sure?\')'
        ]);

        $list->init();

        $this->twiggy->set('table', $list);

        $this->twiggy->display('goods/index');
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

            $good = new \App\Models\Good();
            $good->name = $this->input->post('name');
            $good->price = $this->input->post('price');
            $good->is_active = $this->input->post('is_active');

            $good->save();

            if ($good->isValid()) {
                redirect('goods');
            } else {
                $this->twiggy->set('errors', $good->getErrors());
            }
        }

        $this->twiggy
                ->title()
                ->prepend('Create a good');

        $this->twiggy->set('action', 'Create a good');
        $this->twiggy->set('form_data', $data);
        $this->twiggy->display('goods/form');
    }

    function edit($id) {
        $good = new \App\Models\Good();

        if (!$good->find([
                    'id' => $id,
                    'user_id' => User::getLoggedUser()->id,
                ])) {
            redirect('goods');
        }

        $data = [
            'name' => $good->get('name'),
            'price' => $good->get('price'),
            'is_active' => $good->get('is_active')
        ];

        if ($this->input->post()) {
            $data = array_merge($data, $this->input->post());


            $good->name = $this->input->post('name');
            $good->price = $this->input->post('price');
            $good->is_active = $this->input->post('is_active');

            $good->save();

            if ($good->isValid()) {
                redirect('customers');
            } else {
                $this->twiggy->set('errors', $good->getErrors());
            }
        }

        $this->twiggy
                ->title()
                ->prepend(sprintf('Edit good %s', $good->name));

        $this->twiggy->set('action', sprintf('Edit good %s', $good->name));
        $this->twiggy->set('form_data', $data);
        $this->twiggy->display('goods/form');
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
    
    function find() {
        $customers = new \App\Models\Goods([
            'user_id' => [User::getLoggedUser()->id],
            'search' => $this->input->get('term')
        ]);

        $formated = [];

        while ($customers->haveRows()) {
            $customer = $customers->theRow();

            $formated[] = [
                'id' => $customer->getid(),
                'name' => $customer->getname(),
                'value' => $customer->getname(),
                'price' => $customer->getprice(),
            ];
        }

        echo json_encode($formated);
    }

}
