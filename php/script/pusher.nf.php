<?php
require_once __DIR__ . '/../config/Global.config.php';
require_once ROOT_PATH . '/common/Debug.trait.php';
require_once ROOT_PATH . '/script/Pusher.class.php';

$obj = new NCDRFloodPusher;
$data = $obj->getDataToPush();
$pushMemberList = $obj->getPushableMemberList();
$detail = [];
if ($data && $pushMemberList) {
    foreach ($pushMemberList as $k => $memberInfo) {
        $detail[$k]['mid'] = $memberInfo['mid'];
        $detail[$k]['detail'] = json_decode($memberInfo['detail'], true);
    }
    foreach ($data as $info) {
        $sendToList = [];
        foreach ($detail as $k => $v) {
    	    if (in_array($info['area_code'], $v['detail']['area'])) {
	        $sendToList[] = $v['mid'];
	    }
        }
        if (!empty($sendToList)) {
	    $obj->pushData($sendToList, $info['area_code'], 'ncdr');
        }
    }
}
