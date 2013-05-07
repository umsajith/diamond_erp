<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *   Diamond ERP - Complete ERP for SMBs
 *   
 *   @author Marko Aleksic <psybaron@gmail.com>
 *   @copyright Copyright (C) 2013  Marko Aleksic
 *   @link https://github.com/psybaron/diamond_erp
 *   @license http://opensource.org/licenses/GPL-3.0
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

class Orders_details extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('orders/co_model','co');
		$this->load->model('orders/cod_model','cod');
	}

	//AJAX - Adds New Product in Order Details
	public function ajxAddProduct()
	{
		if(!$this->cod->insert($_POST))
			$this->output->set_status_header(500);
		else
		{
			$this->output->set_status_header(201);
			air::flash('add');
		}
			
		exit;		
	}

	//AJAX - Edits the Quantity/Returned Qty of Products from an Order
	public function ajxEditQty()
	{
		$this->form_validation->set_rules('value','','numeric');

		if (($_POST['value'] < 0) OR (!$this->form_validation->run()))
		{
			$this->output->set_status_header(500,'Внесете валидна вредност');
		}
		else
		{
			if(!$this->cod->update($_POST['pk'],[$_POST['name']=>$_POST['value']],true))
				$this->output->set_status_header(500);
		}
		exit;	
	}
	
	//AJAX - Removes Products from an Order
	public function ajxRemoveProduct()
	{
		if(!$this->cod->delete($_POST['id']))
			$this->output->set_status_header(500);
		else
		{
			$this->output->set_status_header(200);
			air::flash('add');
		}
		
		exit;
	}
}