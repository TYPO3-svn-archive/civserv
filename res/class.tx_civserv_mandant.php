<?php 
/***************************************************************
* Copyright notice
*
* (c) 2004 ProService (osiris@ercis.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
* A copy is found in the textfile GPL.txt and important notices to the license
* from the author is found in LICENSE.txt distributed with these scripts.
*
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
* This class holds some functions used by the TYPO3 backend to ensure that only
* that data records are shown which belong to a given mandant. By this Typo3 is extended
* to the ability to handle several mandants within one system
*
* Some scripts that use this class: tca.php
* Depends on: -
*
* $Id$
*
* @author Georg Niemeyer (niemeyer@uni-muenster.de)
* @package TYPO3
* @subpackage tx_civserv
* @version 1.0
*
* Changes: 
*/
/**
* [CLASS/FUNCTION INDEX of SCRIPT]
*/

class tx_civserv_mandant{

	/**
	* Returning the Community_No in the Params-Array.
	* Was originally used to fill a selectorbox in the backend with the community-ID of the editing mandant
	*
	* @param	$params	Params-Array in which the Community-No is written 
	* @param	$pObj	Reference to the calling object
	*/
	function main(&$params, &$pObj) {
		$pid = intval($pObj->cachedTSconfig[$params['table'].':'.$params['row']['uid']]['_CURRENT_PID']);
		//debug($pObj);
		if ($pid > 0) $mandant = $this->get_mandant($pid);
		//debug($row);
		$params['items'][0] = Array ($mandant, $mandant);
	}

	/**
	* This function gets recursively to the top element of a mandant and returns the id of this node
	*
	* @param	int	$node is the name of the starting node from where we want to go up the tree
	* @param	int	Array of uids which are the root elements for the communities
	* @return	int the uid of the highest level in the tree for a given mandant (right under the rootline)
	*/
	function get_path($node,$valid_uids) { 
		// look up the parent of this node 
		$GLOBALS['TYPO3_DB']->debugOutput=TRUE;
		
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid, uid','pages','!deleted AND !hidden AND uid = '.$GLOBALS['TYPO3_DB']->quoteStr($node,'pages'),'','','',''); 
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		//debug($row[uid]);
		// save the path in this array
		$path = array(); 
		// only continue if this $node isn't the root node (that's the node with no parent) 
		if ($row['pid']!=0 && !in_array($row['uid'],$valid_uids)) {
			// the last part of the path to $node, is the name of the parent of $node 
			$path[] = $row['pid']; 
			// we should add the path to the parent of this node to the path 
			$path = array_merge($this->get_path($row['pid'],$valid_uids), $path);
		}
		//debug($path);
		return $path[0]; 		
	} 
	
	/*
	* Returns the Community ID for a given PID
	* @param	int	PID	is the node in the tree from where the mandant should be determined
	* @return	int	community-ID representing the mandant belonging to the given pid
	* @see get_path
	*/
	function get_mandant($pid) {
		if ($pid > 0){
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cm_uid','tx_civserv_conf_mandant','!deleted AND !hidden','','','',''); 
			$valid_uids = array();
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$valid_uids[]=$row['cm_uid'];
			}	
		 	$master_uid = $this->get_path($pid,$valid_uids);
		}
		
		//debug($master_uid);
		if ($master_uid == NULL) $master_uid = $pid;
		if ($master_uid > 0) {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cm_community_id, cm_community_name','tx_civserv_conf_mandant','cm_uid = '.$GLOBALS['TYPO3_DB']->quoteStr($master_uid,'tx_civserv_conf_mandant'),'','','',''); 
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
			//debug($row['cm_community_id']);
			return $row['cm_community_id'];
		} else return 0;
	}
	
	/*
	* Returns the Community Name for a given PID
	* @param	int	PID	is the node in the tree from where the mandant should be determined
	* @return	string	community-name representing the mandant belonging to the given pid
	* @see get_path
	*/
	function get_mandant_name($pid){
		if ($pid > 0) $this->get_mandant($pid);
		if ($master_uid == NULL) $master_uid = $pid;
		if ($master_uid > 0) {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cm_community_name','tx_civserv_conf_mandant','cm_uid = '.$GLOBALS['TYPO3_DB']->quoteStr($master_uid,'tx_civserv_conf_mandant'),'','','',''); 
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
			return $row['cm_community_name'];
		} else return "";
	}
			
	/*
	* Limits the items of an array to guarantee, that within an treenode only elements are shown, which belong to the same mandant
	* This function is central to assure the ability of handling several mandants within one Typo3-system
	* There had to be an ugly workaround for the navigation-elements (look additional_remove for further information)
	*
	* @param	string		$params are parameters sent along to alt_doc.php. This requires a much more details description which you must seek in Inside TYPO3s documentation API
	* @param	string		$pObj is a reference to the calling object
	* @return	void
	* @see manipulate_array, additional_remove
	*/
	function limit_items(&$params, &$pObj){
		$table = $params['config']['foreign_table'];
		$pid = intval($pObj->cachedTSconfig[$params['table'].':'.$params['row']['uid']]['_CURRENT_PID']);
		if ($pid > 0) $mandant = $this->get_mandant($pid);
		//debug($mandant,'Mandant');
		if(array_key_exists("",$params['items'])){
			$empty_entry=1;
		} $empty_entry=0;
		$params['items'] = $this->manipulate_array($mandant, $params,$table);	
		if ($params['table']=='tx_civserv_navigation'){
			$params['items']=$this->additional_remove($params['items'], $table, $pid);
		}
		if ($empty_entry) $params['items']=array_merge(Array(""),$params['items']);
	}
	
	/*
	* This function executes the limiting functionality to an element array for a given mandant
	*
	* @param string Mandant on which the elements should be limited
	* @param string Array of source elements
	* @param string Table name from where the source elements result from
	* @return int $target_array Array consisting only of elements containing to the given mandant
	*/
	function manipulate_array($mandant, $source_array, $table){
		$res_pids = $GLOBALS['TYPO3_DB']->exec_SELECTquery('distinct pid',$GLOBALS['TYPO3_DB']->quoteStr($table,$table),'!deleted AND !hidden','','','','');
		$valid_pid = '';
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_pids)) { 	
			if ($row['pid']>0 AND ($this->get_mandant($row['pid']) == $mandant)) {
				$valid_pid .= $row['pid'].', ';
			} 
		}
		$valid_pid = '('.substr($valid_pid,0,-2).')';
		//debug($valid_pid);
		//$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid',$GLOBALS['TYPO3_DB']->quoteStr($table,$table),'pid = '.$GLOBALS['TYPO3_DB']->quoteStr($valid_pid,$table),'','','','');
		$array_temp = array();
		if (strlen($valid_pid)>2){
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, pid',$GLOBALS['TYPO3_DB']->quoteStr($table,$table),'!deleted AND !hidden AND pid IN '.$valid_pid,'','','','');
		
			
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) { 	
				array_push($array_temp,$row['uid']);
			}
		}
		
		$i = 0;
		$target_array = array();
		$length = count($source_array['items']);
		while ($i < $length) {
		  if (in_array($source_array['items'][$i][1],$array_temp)){
		  	array_push($target_array,$source_array['items'][$i]);
		  }
		  $i++;
		}			
		return $target_array;
	}	
	
	/*
	* This function enlarges the limiting functionality to an element array for the navigation concept
	* Only elements with the demanded pid are shown
	*
	* @param string Array of source elements
	* @param string Table name from where the source elements result from
	* @param int PID to which the elements should be limited
	* @return int $target_array Array consisting only of elements containing to the given mandant
	*/
	function additional_remove($source_array, $table, $pid){
		$target_array = array();
		$valid_nav = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$GLOBALS['TYPO3_DB']->quoteStr($table,$table),'!deleted AND !hidden AND pid = '.intval($pid),'','','','');
		foreach ($source_array as $value){
			array_push($valid_nav, $value[1]);
		}
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) { 	
				//debug($source_array);
				if (in_array($row['uid'],$valid_nav))
					array_push($target_array,array($row['nv_name'], $row['uid']));
			}	
		return $target_array;
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/civserv/res/class.tx_civserv_mandant.php']);
}
?>