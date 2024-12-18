<?php
defined('JPATH_BASE') or die;

$d = $displayData;

foreach ($d->data as $d)
{
	echo  '<div style="width:25px;height:25px;border-radius:20px;background-color:' . $d . '"></div>';
}
