<?php
// Функция отправки SMS
if(!function_exists('send_sms')) {
    function send_sms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array())
    {
        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1", "mail=1", "call=1", "viber=1", "soc=1");

        $m = _smsc_send_cmd("send", "cost=3&phones=" . urlencode($phones) . "&mes=" . urlencode($message) .
            "&translit=$translit&id=$id" . ($format > 0 ? "&" . $formats[$format] : "") .
            ($sender === false ? "" : "&sender=" . urlencode($sender)) .
            ($time ? "&time=" . urlencode($time) : "") . ($query ? "&$query" : ""), $files);

        // (id, cnt, cost, balance) или (id, -error)
        if (config('smsc.SMSC_DEBUG')) {
            if ($m[1] > 0)
                echo "Сообщение отправлено успешно. ID: $m[0], всего SMS: $m[1], стоимость: $m[2], баланс: $m[3].\n";
            else
                echo "Ошибка №", -$m[1], $m[0] ? ", ID: " . $m[0] : "", "\n";
        }

        return $m;
    }
}


// SMTP версия функции отправки SMS
if(!function_exists('send_sms_mail')) {
    function send_sms_mail($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = "")
    {
        return mail("send@send.smsc.ru", "",  config('smsc.SMSC_LOGIN') . ":" . config('smsc.SMSC_PASSWORD') . ":$id:$time:$translit,$format,$sender:$phones:$message", "From: " . config('smsc.SMTP_FROM') . "\nContent-Type: text/plain; charset=" . config('smsc.SMSC_CHARSET') . "\n");
    }
}

// Функция получения стоимости SMS
if(!function_exists('get_sms_cost')) {
    function get_sms_cost($phones, $message, $translit = 0, $format = 0, $sender = false, $query = "")
    {
        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1", "mail=1", "call=1", "viber=1", "soc=1");

        $m = _smsc_send_cmd("send", "cost=1&phones=" . urlencode($phones) . "&mes=" . urlencode($message) .
            ($sender === false ? "" : "&sender=" . urlencode($sender)) .
            "&translit=$translit" . ($format > 0 ? "&" . $formats[$format] : "") . ($query ? "&$query" : ""));

        // (cost, cnt) или (0, -error)

        if (config('smsc.SMSC_DEBUG')) {
            if ($m[1] > 0)
                echo "Стоимость рассылки: $m[0]. Всего SMS: $m[1]\n";
            else
                echo "Ошибка №", -$m[1], "\n";
        }

        return $m;
    }
}

// Функция проверки статуса отправленного SMS или HLR-запроса
if(!function_exists('get_status')) {
    function get_status($id, $phone, $all = 0)
    {
        $m = _smsc_send_cmd("status", "phone=" . urlencode($phone) . "&id=" . urlencode($id) . "&all=" . (int)$all);

        // (status, time, err, ...) или (0, -error)

        if (!strpos($id, ",")) {
            if (config('smsc.SMSC_DEBUG'))
                if ($m[1] != "" && $m[1] >= 0)
                    echo "Статус SMS = $m[0]", $m[1] ? ", время изменения статуса - " . date("d.m.Y H:i:s", $m[1]) : "", "\n";
                else
                    echo "Ошибка №", -$m[1], "\n";

            if ($all && count($m) > 9 && (!isset($m[$idx = $all == 1 ? 14 : 17]) || $m[$idx] != "HLR")) // ',' в сообщении
                $m = explode(",", implode(",", $m), $all == 1 ? 9 : 12);
        } else {
            if (count($m) == 1 && strpos($m[0], "-") == 2)
                return explode(",", $m[0]);

            foreach ($m as $k => $v)
                $m[$k] = explode(",", $v);
        }

        return $m;
    }
}

// Функция получения баланса
if(!function_exists('get_balance')) {
    function get_balance()
    {
        $m = _smsc_send_cmd("balance"); // (balance) или (0, -error)

        if (config('smsc.SMSC_DEBUG')) {
            if (!isset($m[1]))
                echo "Сумма на счете: ", $m[0], "\n";
            else
                echo "Ошибка №", -$m[1], "\n";
        }

        return isset($m[1]) ? false : $m[0];
    }
}

// Функция вызова запроса. Формирует URL и делает 5 попыток чтения через разные подключения к сервису
if(!function_exists('_smsc_send_cmd')) {
    function _smsc_send_cmd($cmd, $arg = "", $files = array())
    {
        $url = $_url = (config('smsc.SMSC_HTTPS') ? "https" : "http") . "://smsc.ru/sys/$cmd.php?login=" . urlencode(config('smsc.SMSC_LOGIN')) . "&psw=" . urlencode(config('smsc.SMSC_PASSWORD')) . "&fmt=1&charset=" . config('smsc.SMSC_CHARSET') . "&" . $arg;

        $i = 0;
        do {
            if ($i++)
                $url = str_replace('://smsc.ru/', '://www' . $i . '.smsc.ru/', $_url);

            $ret = _smsc_read_url($url, $files, 3 + $i);
        } while ($ret == "" && $i < 5);

        if ($ret == "") {
            if (config('smsc.SMSC_DEBUG'))
                echo "Ошибка чтения адреса: $url\n";

            $ret = ","; // фиктивный ответ
        }

        $delim = ",";

        if ($cmd == "status") {
            parse_str($arg, $m);

            if (strpos($m["id"], ","))
                $delim = "\n";
        }

        return explode($delim, $ret);
    }
}

// Функция чтения URL. Для работы должно быть доступно:
// curl или fsockopen (только http) или включена опция allow_url_fopen для file_get_contents
if(!function_exists('_smsc_read_url')) {
    function _smsc_read_url($url, $files, $tm = 5)
    {
        $ret = "";
        $post = config('smsc.SMSC_POST') || strlen($url) > 2000 || $files;

        if (function_exists("curl_init")) {
            static $c = 0; // keepalive

            if (!$c) {
                $c = curl_init();
                curl_setopt_array($c, array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT => $tm,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array("Expect:")
                ));
            }

            curl_setopt($c, CURLOPT_POST, $post);

            if ($post) {
                list($url, $post) = explode("?", $url, 2);

                if ($files) {
                    parse_str($post, $m);

                    foreach ($m as $k => $v)
                        $m[$k] = isset($v[0]) && $v[0] == "@" ? sprintf("\0%s", $v) : $v;

                    $post = $m;
                    foreach ($files as $i => $path)
                        if (file_exists($path))
                            $post["file" . $i] = function_exists("curl_file_create") ? curl_file_create($path) : "@" . $path;
                }

                curl_setopt($c, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($c, CURLOPT_URL, $url);

            $ret = curl_exec($c);
        } elseif ($files) {
            if (config('smsc.SMSC_DEBUG'))
                echo "Не установлен модуль curl для передачи файлов\n";
        } else {
            if (!config('smsc.SMSC_HTTPS') && function_exists("fsockopen")) {
                $m = parse_url($url);

                if (!$fp = fsockopen($m["host"], 80, $errno, $errstr, $tm))
                    $fp = fsockopen("212.24.33.196", 80, $errno, $errstr, $tm);

                if ($fp) {
                    stream_set_timeout($fp, 60);

                    fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]") . " HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP" . ($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($m['query']) : "") . "\r\nConnection: Close\r\n\r\n" . ($post ? $m['query'] : ""));

                    while (!feof($fp))
                        $ret .= fgets($fp, 1024);
                    list(, $ret) = explode("\r\n\r\n", $ret, 2);

                    fclose($fp);
                }
            } else
                $ret = file_get_contents($url);
        }

        return $ret;
    }
}
