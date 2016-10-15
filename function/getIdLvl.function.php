<?php
function getIdLvl($lvl0, $id, $lvl=false, $fields='', $field=false)
{
	//getIdLvl
	//v1.0
	//15.10.2016
	//-------------------------------------------------------------
	global $nc_core;

	$id= intval($id);
	$deffields= "Subdivision_ID AS id, Parent_Sub_ID AS parent";
	$fields_qq= ($fields?",".$nc_core->db->escape($fields):"");
	$rr= $nc_core->db->get_results("SELECT {$deffields} {$fields_qq} FROM Subdivision WHERE Subdivision_ID={$id} LIMIT 1", ARRAY_A);
	if(is_array($rr) && count($rr)==1) $doc= $rr[0]; else return false;
	$list[]= $doc;

	while($id!=$lvl0 && $doc['parent']!=$lvl0 && $doc['parent']>0)
	{
		$rr= $nc_core->db->get_results("SELECT {$deffields} {$fields_qq} FROM Subdivision WHERE Subdivision_ID={$doc[parent]} LIMIT 1", ARRAY_A);
		if(is_array($rr) && count($rr)==1) $doc= $rr[0]; else return false;
		$list[]= $doc;
	}
	if($doc['parent']==0)
	{
		$list[]= array('id'=>0);
	}elseif($doc['parent']==$lvl0){
		$rr= $nc_core->db->get_results("SELECT {$deffields} {$fields_qq} FROM Subdivision WHERE Subdivision_ID={$doc[parent]} LIMIT 1", ARRAY_A);
		if(is_array($rr) && count($rr)==1) $doc= $rr[0]; else return false;
		$list[]= $doc;
	}
	$list[]= false;
	$list= array_reverse($list);
	return ($lvl ? $list[$lvl][($field?$field:'id')] : $list);
}
