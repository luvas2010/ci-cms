<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */

	class System {
		var $version ;
		var $revision;
		var $modules;
		var $obj;
		
		function System()
		{
			$this->obj =& get_instance();
			$this->obj->config->set_item('cache_path', './cache/');
			$dir = $this->obj->config->item('cache_path');
			$this->obj->load->library('cache', array('dir' => $dir));
			$this->get_settings();
			$this->check_update();
			$this->find_modules();
			$this->load_locales();
			$this->start();
		}
		
		function load_locales()
		{
			//overall locale
			$this->obj->load->library('locale');
			//$this->obj->locale->load_textdomain(APPPATH . 'locale/' . $this->obj->session->userdata('lang') . '.mo');
			
			
			foreach ($this->modules as $module)
			{
				$mofile = APPPATH . 'modules/'.$module['name'].'/locale/' . $this->obj->session->userdata('lang') . '.mo' ;
				if ( file_exists($mofile)) 
				{
					$this->obj->locale->load_textdomain($mofile, $module['name']);
				}
			}
		}
		
		function find_modules()
		{
			if ( !$modules = $this->obj->cache->get('modulelist', 'system') )
			{
				$this->obj->db->where('status', 1);
				$this->obj->db->order_by('ordering');
				$query = $this->obj->db->get('modules');
				foreach ($query->result_array() as $row)
				{
					$modules[ $row['name'] ] = $row;
				}
				$this->obj->cache->save('modulelist', $modules, 'system', 0);
			}
			
			$this->modules = $modules;
		}
		
		function check_update()
		{
			
			$fversion = @file_get_contents(APPPATH . "version.txt");
			
			if (isset($fversion) && $fversion > $this->version)
			{
				include (APPPATH . "update.php");
			}
			
			/*
			if ( $revision = @file_get_contents("http://ci-cms.googlecode.com/svn/") )
			{
				if ( ereg ("<title>(.*)</title>", $revision, $contents)) 
				{
					$this->latest_revision = $contents[1];
				}
			}*/
		}
		
		function start()
		{
			if ($this->cache && !$this->obj->user->logged_in && $this->obj->uri->segment(1) != 'admin')
			{
				$this->obj->output->cache($this->cache_time);
			}
			
			
			
			//update
			
		}
		
		function get_settings()
		{
			$query = $this->obj->db->get('settings');
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
			   {
			      $this->{$row->name} = $row->value;
			   }
			}			
		}
		
		function set($name, $value)
		{	
			//update only if changed
			if (!isset($this->$name)) {
				$this->$name = $value;
				$this->obj->db->insert('settings', array('name' => $name, 'value' => $value));
			}
			elseif ($this->$name != $value) 
			{
				$this->$name = $value;
				$this->obj->db->update('settings', array('value' => $value), "name = '$name'");
			}
		}
		
		function clear_cache()
		{
			$dir = $this->obj->config->item('cache_path');
			
			$handle = opendir($dir);

			if ($handle)
			{
				while ( false !== ($cache_file = readdir($handle)) )
				{
					// make sure we don't delete silly dirs like .svn, or . or ..
					
					if ($cache_file != 'index.html' && substr($cache_file, 0, 1) != "." && !is_dir($dir.$cache_file))
					{
						@unlink($dir.'/'.$cache_file);
					}
				}
			}
		}
	}


?>