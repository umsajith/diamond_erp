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

class Invoices extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('invoices/Invoices_model','inv');	
	}
    
	public function index()
	{

	}
    
	public function insert()
	{
		
	}
    
	public function edit($id = false)
	{
		
	}
    
	public function delete($id = false)
	{
		
	}	
}