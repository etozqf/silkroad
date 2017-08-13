<?php
$menuids = @file_get_contents($installlog);
if ($menuids)
{
	$menu->delete($menuids);
}
@unlink($installlog);