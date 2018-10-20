<?php

class func
{
    public static function chkLoginState($conn)
    {
        if (!isset($_SESSION['id']) || !isset($_COOKIE['PHPSESSID'])) {
            session_start();
        } elseif (isset($_COOKIE['id']) && isset($_COOKIE['token']) && isset($_COOKIE['serial'])) {

            $query = "SELECT * FROM user_session WHERE sess_uid = :userid AND sess_token = :token AND sess_sl = :serial;";

            $uid = $_COOKIE['Uid'];
            $token = $_COOKIE['token'];
            $serial = $_COOKIE['serial'];

            $stmt = $conn->prepare($query);

            $stmt->execute(array(':userid' => $uid, ':token' => $token, ':serial' => $serial));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['sess_uid'] > 0) {
                if (
                    $row['sess_uid'] == $_COOKIE['uid'] && $row['sess_token'] == $_COOKIE['token'] && $row['sess_sl'] == $_COOKIE['serial']
                )
                {
                    if (
                        $row['sess_uid'] == $_SESSION['uid'] && $row['sess_token'] == $_SESSION['token'] && $row['sess_sl'] == $_SESSION['serial']
                    )
                    {
                        return true;
                    }
                }
            }
        }
    }
}

?>