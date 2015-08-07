<?php
function DLOG($message)
{
	if(Config::IS_DEBUG)
	{
		echo "<div style='color:blue;'>[DLOG] $message<br/></div>";
	}
}
?>