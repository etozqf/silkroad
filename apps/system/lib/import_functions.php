<?php
//just for test
function get_catid($id){
	return $id;
}

function get_sex($sex) {
	return ($sex == '男')?1:0;
}

function get_birthday($date) {
	return strtotime($date);
}

function get_title($title) {
	return substr($title,0,80);
}