<?php

class                       Mail {
    public static           $senders = [
        'system'
    ];

    public static function  send($to, $from, $subject, $message, $replyTo = false) {
        foreach (self::$senders as $sender)
            if (self::$sender($to, $from, $subject, $message, $replyTo))
                return true;
        return false;
    }

    public static function  system($to, $from, $subject, $message, $replyTo = false) {
        if ($replyTo === false)
            $replyTo = $from;

        $boundary = "_----------=_mailpart_".md5(uniqid(rand()));

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: '.$from . "\r\n";
        $headers .= 'Reply-To: ' . $replyTo . "\r\n";
        $headers .= 'X-Sender: <'.Conf::get('mail.x-sender', $_SERVER['HTTP_HOST']).'>'."\r\n";
        $headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
        $headers .= 'Content-type: multipart/alternative; boundary="'.$boundary.'"; charset="utf-8"' . "\r\n";

        $mailContent = '';
        $mailContent .= "--".$boundary."\n";
        $mailContent .= "Content-Type: text/plain\n";
        $mailContent .= "charset=\"utf-8\"\n";
        $mailContent .= "Content-Transfer-Encoding: 8bit\n\n";
        $mailContent .= Html2Text::convert(utf8_decode($message));

        $mailContent .= "\n\n--".$boundary."\n";
        $mailContent .= "Content-Type: text/html; ";
        $mailContent .= "charset=\"utf-8\"; ";
        $mailContent .= "Content-Transfer-Encoding: 8bit;\n\n";
        $mailContent .= $message;

        $mailContent .= "\n--".$boundary."--";

        $subject = html_entity_decode($subject);
        $subject = mb_encode_mimeheader(utf8_decode($subject),"UTF-8", "B");
        return mail($to, $subject, $mailContent, $headers);
    }
}

?>