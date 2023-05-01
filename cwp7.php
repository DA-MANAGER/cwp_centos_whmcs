<?php
// whmcs module for cwp ver 1.7

function cwp7_ConfigOptions() {
    $configarray = array(
        "PACKAGE-NUMBER" => array( "Type" => "text", "Description" => "Package ID", "Default" => "1"),
        "inode" => array( "Type" => "text" , "Description" => "Max of inode", "Default" => "0",),
        "nofile" => array( "Type" => "text", "Description" => "Max of nofile", "Default" => "100", ),
        "nproc" => array( "Type" => "text" , "Description" => "Nproc limit - 40 suggested", "Default" => "40",),
    );
    return $configarray;
}
function cwp7_CreateAccount($params) {
    if ($params["server"] == 1) {
        $postvars = array(
            'package' => $params["configoption1"],
            'domain' => $params["domain"],
            'key' => $params["serveraccesshash"],
            'action' => 'add',
            'username' => $params["username"],
            'user' => $params["username"],
            'pass' => $params["password"],
            'email' => $params["clientsdetails"]["email"],
            'inode' => $params["configoption2"],
            'nofile' => $params["configoption3"],
            'nproc' => $params["configoption4"],
            'server_ips'=>$params["serverip"]
        );
        $postdata = http_build_query($postvars);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':2304/v1/account');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
        logModuleCall('cwpwhmcs','CreateAccount_UserAccount','https://' . $params["serverhostname"] . ':2304/v1/account/'.$postdata,$answer);
    }
    if(strpos($answer,"OK")!==false){$result='success';}else{$result=json_decode($answer,true); $result=$result['msj'];}
    return $result;
}
function cwp7_TerminateAccount($params) {
    if ($params["server"] == 1) {
        $postvars = array('key' => $params["serveraccesshash"],'action' => 'del','user' => $params["username"]);
        $postdata = http_build_query($postvars);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':2304/v1/account');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
        logModuleCall('cwpwhmcs','TerminateAccount','https://' . $params["serverhostname"] . ':2304/v1/account/'.$postdata,$answer);
    }
    if(strpos($answer,"OK")!==false){$result='success';}else{$result=json_decode($answer,true); $result=$result['msj'];}
    return $result;
}
function cwp7_SuspendAccount($params) {
    if ($params["server"] == 1) {
        $postvars = array('key' => $params["serveraccesshash"],'action' => 'susp','user' => $params["username"]);
        $postdata = http_build_query($postvars);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':2304/v1/account');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }
    if(strpos($answer,"OK")!==false){$result='success';}else{$result=json_decode($answer,true); $result=$result['msj'];}
    logModuleCall('cwpwhmcs','SuspendAccount','https://' . $params["serverhostname"] . ':2304/v1/account/'.$postdata,$result);
    return $result;
}
function cwp7_UnsuspendAccount($params) {
    if ($params["server"] == 1) {
        $postvars = array('key' => $params["serveraccesshash"],'action' => 'unsp','user' => $params["username"]);
        $postdata = http_build_query($postvars);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':2304/v1/account');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }
    if(strpos($answer,"OK")!==false){$result='success';}else{$result=json_decode($answer,true); $result=$result['msj'];}
    logModuleCall('cwpwhmcs','UnsuspendAccount','https://' . $params["serverhostname"] . ':2304/v1/account'.$postdata,$result);
    return $result;
}
function cwp7_ClientArea($params)
{
    $postvars = array('key' => $params["serveraccesshash"], 'action' => 'list', 'user' => $params["username"], 'timer' => 5);
    $postdata = http_build_query($postvars);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':2304/v1/user_session');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);
    $arry = (json_decode($answer, true)); //die;F
    $link = $arry['msj']['details'];
    $linkautologin = $link[0]['url'];
    logModuleCall('cwpwhmcs', 'cwp7_LoginLink', 'https://' . $params["serverhostname"] . ':2304/v1/user_session' . $postdata, $answer);

    return "<a href=\"{$linkautologin}\" target=\"_blank\" font-weight=\"700\" style=\"color:#cc0000\">Login to your Control Panel</a>";
}
function cwp7_AdminLink($params) {
    $code = '<form action="https://'.$params["serverhostname"].':2031" method="post" target="_blank">
        <input type="submit" value="Login to Control Panel" />
        </form>';
    return $code;
}


function cwp7_LoginLink($params) {
    $postvars = array('key' => $params["serveraccesshash"],'action' => 'list','user' => $params["username"],'timer'=>5);
    $postdata = http_build_query($postvars);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':2304/v1/user_session');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);
    $arry=(json_decode($answer,true)); //die;F
    $link=$arry['msj']['details'];
    $linkautologin=$link[0]['url'];
    logModuleCall('cwpwhmcs','cwp7_LoginLink','https://' . $params["serverhostname"] . ':2304/v1/user_session'.$postdata,$answer);

    echo "<a href=\"{$linkautologin}\" target=\"_blank\" style=\"color:#cc0000\">Control Panel</a>";
}



function cwp7_ChangePassword($params){
    $postvars = array('key' => $params["serveraccesshash"],'acction' => 'udp','user' => $params["username"], 'pass' =>$params["password"]);
    $postdata = http_build_query($postvars);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://'. $params["serverhostname"] . ':2304/v1/changepass');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);
    if(strpos($answer,"OK")!==false){$result='success';}else{$result=json_decode($answer,true); $result=$result['msj'];}
    logModuleCall('cwpwhmcs','ChangePassword','https://' . $params["serverhostname"] . ':2304/v1/changepass'.$postdata,$result);
    return $result;
}




function cwp7_ChangePackage($params){
    $postvars = array("key" => $params["serveraccesshash"],"action"=>'udp','user' => $params["username"],'package'=>$params["configoption1"].'@');
    $postdata = http_build_query($postvars);
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, 'https://'. $params["serverhostname"] . ':2304/v1/account');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);
    curl_close($curl);

    if(strpos($answer,"OK")!==false){$result='success';}else{$result=json_decode($answer,true); $result=$result['msj'];}
    logModuleCall('cwpwhmcs','ChangePackage','https://' . $params["serverhostname"] . ':2304/v1/packages'.$postdata,$answer);
    return $result;
}



function cwp7_UsageUpdate($params) {
    $postvars = array('key' => $params["serveraccesshash"],'action' => 'list');
    $postdata = http_build_query($postvars);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://'. $params["serverhostname"] . ':2304/v1/account');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
    }
    $answer = curl_exec($curl);
    $resp=json_decode($answer,true);

   if($resp['status']=='OK'){
        $results=$resp['msj'];
        for($i=0;$i<count($results);$i++){
            $date=date('Y-m-d H:i:s');
            if($results[$i]['diskusage']==''){$diskusage=0;}else{$diskusage=trim($results[$i]['diskusage']);}
            if($results[$i]['disklimit']==''){$disklimit=0;}else{$disklimit=trim($results[$i]['disklimit']);}
            if($results[$i]['bandwidth']==''){$bandwidth=0;}else{$bandwidth=trim($results[$i]['bandwidth']);}
            if($results[$i]['bwlimit']==''){$bwlimit=0;}else{$bwlimit=trim($results[$i]['bwlimit']);}
            $domian=trim($results[$i]['domain']);

            try {
                \WHMCS\Database\Capsule::table('tblhosting')
                    ->where('dedicatedip', $results[$i]['ip_address'])
                    ->where('domain', $domian)
                    ->update([
                        'diskusage' => $diskusage,
                        'disklimit' => $disklimit,
                        'bwusage' => $bandwidth,
                        'bwlimit' => $bwlimit,
                        'lastupdate' => date('Y-m-d H:i:S'),
                    ]);
            } catch (\Exception $e) {
                logActivity('ERROR: Unable to update server usage: ' . $e->getMessage());
            }


        }
    }
}

?>