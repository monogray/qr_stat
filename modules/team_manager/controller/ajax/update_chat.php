<?php 
set_include_path('.'.PATH_SEPARATOR . '../../../../'
				.PATH_SEPARATOR.get_include_path());

require_once 'connect.php';
require_once 'settings.php';

$db_connect = new DBConnect();
$db_connect->connectToDB();

require_once 'model/task_traker/chat_messages.php';
$_mes = new ChatMessages();
$_mes->getMainListByChatIdAndLimit($_GET['id'], 40);

echo '<table class="tracker_issue_table"><tr><td>id</td><td>Message</td><td>Author</td><td>Date</td></tr>';
for ($i = 0; $i < $_mes->len; $i++) {
	echo '<tr>';
	echo '<td name="smal_font_td">';
		echo  $_mes->id[$i];
	echo '</td>';
	echo '<td>';
		echo  iconv( 'windows-1251', 'UTF-8', $_mes->message[$i] );
	echo '</td>';
	echo '<td class="chat_small_td">';
		echo  iconv( 'windows-1251', 'UTF-8', $_mes->author_entity[$i]->name[0] );
	echo '</td>';
	echo '<td class="chat_small_td">';
		echo  $_mes->date[$i];
	echo '</td>';
	echo '</tr>';

}
echo '</table>';