<?php
function show_header()
{
?>
	<div id="header">
		<div id="banner">
			<img src="image/banner.png" alt="logo"/>
		</div>
		<div id="topbar">
			<div id="menu">
				<ul>
					<li><a id="detail_link" href="detail.php">Detail</a></li><!--
					--><li><a id="category_link" href="category.php">Category Manager</a></li><!--
					--><li><a id="setting_link" href="setting.php">Setting</a></li><!--
					--><li><span id="blank_button">&nbsp;</span></li>
				</ul>
			</div>
			<div id="info">
				<p>Remain: <span id="remain_money">100.000 VND</span> - Available: <span id="available_money">23.000 VND</span></p>
			</div>
		</div>
	</div> <!-- End of header -->
<?php
} 
?>