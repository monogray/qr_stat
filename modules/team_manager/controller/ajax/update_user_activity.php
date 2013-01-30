<?php 
set_include_path('.'.PATH_SEPARATOR . '../../../../'
					.PATH_SEPARATOR . '../mono_framework/mono_lib/'
					.PATH_SEPARATOR.get_include_path());

require_once 'connect.php';
require_once 'settings.php';

$db_connect = new DBConnect();
$db_connect->connectToDB();
$db_connect->getUserData();

require_once 'model/task_traker/users_current_state.php';
$_act = new UserCurrentState();
$_act->updateByUserId($_GET['id']);
$_act->getMainList();

for ($i = 0; $i < $_act->len; $i++) {
	if(time() - $_act->last_activity[$i] < 30){
		echo iconv('windows-1251', 'UTF-8', $_act->user_entity[$i]->name[0]).'<br/>';
	}
}

// Chat state updating
include_once 'model/task_traker/chat.php';
$chat = Chat::getInstance();

$_str = '';
include_once 'model/task_traker/chat_messages.php';
include_once 'model/task_traker/chat_statistics.php';
for ($i = 0; $i < $chat->len; $i++) {
	// Get messages count
	$chat_messages = new ChatMessages();
	$_current_mes_count = $chat_messages->getCountByChatId($chat->id[$i]);
	
	$chat_stat = new ChatStatistics();
	$_old_mes_count = $chat_stat->getMessageCountByUserIdAndChatId($_GET['id'], $chat->id[$i]);
	
	if($_current_mes_count != $_old_mes_count){
		$_count = $_current_mes_count-$_old_mes_count;
		$_str .= '<a href="index.php?chapter=chat&action=edit&id='.$chat->id[$i].'">'.$chat->name[$i].'</a><br/><b>'.$_count.' messages</b><br/><br/>';
	}
}

if($_str != '')
	$_str = '<br/><b>You have new message!</b><br/><br/>'.$_str;
echo iconv('windows-1251', 'UTF-8', $_str); 
