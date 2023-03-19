<?php

namespace helpers;

class Helpers
{
    /**
     * @param $date
     * @return string
     */
    public static function date_to_sql($date): string
    {
        return date('Y-m-d', strtotime($date));
    }

    /**
     * @param $string
     * @return string
     */
    public static function create_slug($string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    /**
     * @param $input
     * @return string
     */
    public static function sanitize_string($input): string
    {
        if (is_array($input)) {
            foreach($input as $key => $val) {
                $output[$key] = self::sanitizeString($val);
            }
        } else {
            $output = trim(string: strip_tags($input));
        }
        if (!empty($output)) {
            return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }

    /**
     * @param $inputEmail
     * @return string
     */
    public static function sanitize_email($inputEmail): string
    {
        $inputEmail = trim($inputEmail);
        $inputEmail = filter_var($inputEmail, FILTER_SANITIZE_EMAIL);
        return strtolower($inputEmail);
    }

    /**
     * @param $inputInt
     * @return mixed
     */
    public static function sanitize_int($inputInt): mixed
    {
        return filter_var($inputInt, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @param $inputURL
     * @return mixed
     */
    public static function sanitize_url($inputURL): mixed
    {
        $inputURL = trim($inputURL);
        return filter_var($inputURL, FILTER_SANITIZE_URL);
    }

    /**
     * @param $username
     * @return bool
     */
    public  static function is_valid_username($username): bool
    {

        if (strlen($username) < 4 || strlen($username) > 20) {
            return false;
        }

        if(!preg_match('/^\w+$/', $username)) {
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function is_valid_email($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param $phoneNumber
     * @return bool
     */
    public static function is_valid_phone($phoneNumber): bool
    {

        $phoneNumber = str_replace([' ', '-', '(', ')'], '', $phoneNumber);

        if (!str_starts_with($phoneNumber, '+33') && !str_starts_with($phoneNumber, '0')) {
            return false;
        }

        $phoneNumber = ltrim($phoneNumber, "+33");
        $phoneNumber = ltrim($phoneNumber, '0');

        if (strlen($phoneNumber) !== 9 || !ctype_digit($phoneNumber)) {
            return false;
        }

        $firstDigit = $phoneNumber[0];
        return !($firstDigit !== '1' && $firstDigit !== '6' && $firstDigit !== '7');
    }

    /**
     * @throws Exception
     */
    public function generate_idx($lenght = 9): string
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        }elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        }else{
            throw new RuntimeException("no cryptographically secure random function available");
        }
        $idx = substr(bin2hex($bytes), 0, $lenght);
        return strtoupper($idx);
    }

    /**
     * Génère un code numérique aléatoire de longueur variable
     *
     * @param int $length La longueur du code à générer
     * @return string Le code généré
     * @throws Exception
     */
    public static function generate_random_code(int $length): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= random_int(0, 9);
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public static function generate_token($length = 32): string
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    /**
     * @param $directoryPath
     * @param $permissions
     * @return void
     */
    public static function create_directory($directoryPath, $permissions = 0755): void
    {
        if (!is_dir($directoryPath) && !mkdir($directoryPath, $permissions, true) && !is_dir($directoryPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directoryPath));
        }
    }

    /**
     * @param $html
     * @param $maxChar
     * @return string
     */
    public static function cut_html_text($html, $maxChar): string
    {
        $doc = new DOMDocument();
        $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $text = "";
        $length = 0;
        $nodes = $doc->getElementsByTagName('body')->item(0)->childNodes;
        foreach($nodes as $node) {
            if($node->nodeName === '#text') {
                $nodeText = $node->nodeValue;
                $nodeLength = mb_strlen($nodeText);
                if($length + $nodeLength <= $maxChar) {
                    $text .= $nodeText;
                    $length += $nodeLength;
                } else {
                    $text .= mb_substr($nodeText, 0, $maxChar - $length);
                    break;
                }
            } else {
                $childHtml = $doc->saveXML($node);
                $childText = self::cutHtmlText($childHtml, $maxChar - $length);
                $text .= $childText;
                $length += mb_strlen($childText);
                if($length >= $maxChar) {
                    break;
                }
            }
        }
        return $text;
    }

    /**
     * Generate a strong password
     *
     * @param int $length The length of the password (default: 9)
     * @param bool $add_dashes Whether to add dashes to the password (default: false)
     * @param string $available_sets The sets of characters to use to generate the password (default: 'luds')
     *
     * @return string The generated password
     */
    public static function generate_strong_password(int $length = 12, bool $add_dashes = false, string $available_sets = 'luds'): string
    {

        // Ensure at least one set of characters is included
        if (!preg_match('/^[luds]+$/', $available_sets)) {
            $available_sets = 'luds';
        }

        $sets = array();
        if(strpos($available_sets, 'l') >= 0) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if(strpos($available_sets, 'u') >= 0) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if(strpos($available_sets, 'd') >= 0) {
            $sets[] = '23456789';
        }
        if(strpos($available_sets, 's') >= 0) {
            $sets[] = '!@#$%&*?';
        }

        $all = '';
        $password = '';
        foreach($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }
        $password = str_shuffle($password);

        if(!$add_dashes) {
            return $password;
        }

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    /**
     * @param $string
     * @param $minLength
     * @param $maxLength
     * @return bool
     */
    public static function check_string_length($string, $minLength, $maxLength): bool
    {
        $length = strlen($string);
        return !($length < $minLength || $length > $maxLength);
    }

    /**
     * @param $date1
     * @param $date2
     * @param $interval
     * @return float|bool|int
     */
    public static function get_date_diff($date1, $date2, $interval = 'days'): float|bool|int
    {
        $diff = date_diff(date_create($date1), date_create($date2));
        return match ($interval) {
            'minutes' => ($diff->days * 1440) + ($diff->h * 60) + $diff->i,
            'hours' => ($diff->days * 24) + $diff->h + ($diff->i / 60),
            'weeks' => floor($diff->format('%a') / 7),
            'months' => ($diff->y * 12) + $diff->m,
            'years' => $diff->y,
            default => $diff->days,
        };
    }

    /**
     * @throws Exception
     */
    public static function generate_password_hash($password): array
    {

        try {
            $salt = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            throw new RuntimeException(sprintf('Erreur : "%s" was created', $e));
        }

        $options = [
            'cost' => 10,
            'salt' => $salt
        ];

        $hash = password_hash($password, PASSWORD_BCRYPT, $options);

        return array('hash' => $hash, 'salt' => $salt);
    }


    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function password_verify($password, $hash): bool
    {
        return password_verify((string)$password, (string)$hash);
    }

    /**
     * @param $getHostByAddr
     * @return mixed
     */
    public static function get_ip($getHostByAddr = false): mixed
    {

        define("IP_HEADERS", array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ));

        foreach (IP_HEADERS as $header) {
            $ip = filter_input(INPUT_SERVER, $header, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
            if ($ip !== false) {

                if ($getHostByAddr) {
                    $cacheKey = 'host_' . $ip;

                    if (apc_exists($cacheKey)) {
                        $host = apc_fetch($cacheKey);
                    } else {
                        $host = getHostByAddr($ip);
                        apc_store($cacheKey, $host, 3600);
                    }
                    return $host;
                }

                return $ip;
            }
        }

        return null;
    }

    /**
     * @param $adresseIP
     * @return array|null
     */
    public static function geo_ip($adresseIP): ?array
    {
        $apiKey = "votre_clé_api";
        $url = "http://api.ipstack.com/" . $adresseIP . "?access_key=" . $apiKey;
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        if ($data["success"] == false) {
            return null;
        }

        $country_name = $data["country_name"];
        $city = $data["city"];
        $latitude = $data["latitude"];
        $longitude = $data["longitude"];

        return array(
            "country_name" => $country_name,
            "city" => $city,
            "latitude" => $latitude,
            "longitude" => $longitude
        );
    }

    /**
     * @return string
     */
    public static function get_canonical(): string
    {
        return "http://".$_SERVER['HTTP_HOST'].parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    }

    /**
     * @param $date
     * @return bool
     * @throws Exception
     */
    public static function verif_date($date): bool
    {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            return false;
        }

        list($annee, $mois, $jour) = explode('-', $date);

        if (!checkdate($mois, $jour, $annee)) {
            return false;
        }

        $dateCourante = new DateTime();
        $dateAVerifier = new DateTime($date);
        return $dateAVerifier <= $dateCourante;
    }

    /**
     * @param $date
     * @return string|null
     */
    public static function convert_date_sql($date): ?string
    {
        $dateTimeObj = DateTime::createFromFormat('d/m/Y|d.m.Y|d-m-Y', $date);
        return $dateTimeObj ? $dateTimeObj->format('Y-m-d') : null;
    }

    /**
     * @param $bytes
     * @param $decimals
     * @return string
     */
    public static function human_filesize($bytes, $decimals = 2): string
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / (1024 ** $factor)) . @$sz[$factor];
    }

    /**
     * @param $csv_file
     * @param $json_file
     * @param $delimiter
     * @return bool
     */
    public static function csv_to_json($csv_file, $json_file, $delimiter=','): bool
    {

        if (!file_exists($csv_file) || !is_readable($csv_file)) {
            return false;
        }

        if (!is_dir(dirname($json_file))) {
            if (!mkdir($concurrentDirectory = dirname($json_file), 0755, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        $header = null;
        $data = array();

        if (($handle = fopen($csv_file, 'rb')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $json_data = json_encode($data);

        return file_put_contents($json_file, $json_data) !== false;
    }

    /**
     * @param $csv_file
     * @param $xml_file
     * @param $root_element_name
     * @param $record_element_name
     * @return bool
     */
    public static function csv_to_xml($csv_file, $xml_file, $root_element_name = 'data', $record_element_name = 'record'): bool
    {
        if (!file_exists($csv_file)) {
            return false;
        }
        if (!is_dir(dirname($xml_file))) {
            if (!mkdir($concurrentDirectory = dirname($xml_file), 0755, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        $csv = file_get_contents($csv_file);
        $rows = str_getcsv($csv, "\n");
        $header = str_getcsv(array_shift($rows), ",");

        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$root_element_name}></{$root_element_name}>");

        foreach ($rows as $row) {
            $data = array_combine($header, str_getcsv($row, ","));
            $record = $xml->addChild($record_element_name);
            foreach ($data as $key => $value) {
                $record->addChild($key, $value);
            }
        }

        $result = $xml->asXML($xml_file);

        return $result !== false;
    }

    /**
     * @param $text
     * @return string
     */
    public static function calculate_reading_time($text): string
    {
        $words_per_minute = 200;
        $word_count = str_word_count($text);
        $reading_time = ceil($word_count / $words_per_minute);

        if ($reading_time == 1) {
            $reading_time .= ' minute';
        } else {
            $reading_time .= ' minutes';
        }

        return $reading_time;
    }

    /**
     * @param $email
     * @param $size
     * @param $type
     * @param $filename
     * @param $dest_path
     * @return bool
     */
    public static function download_gravatar_icon($email, $size, $type, $filename, $dest_path): bool
    {

        if (!is_dir($dest_path)) {
            if (!mkdir($dest_path, 0755, true) && !is_dir($dest_path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dest_path));
            }
        }

        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$size&d=$type";

        $image_data = file_get_contents($url);

        if ($image_data === false) {
            return false;
        }

        $result = file_put_contents($dest_path . '/' . $filename, $image_data);

        return $result !== false;
    }

    /**
     * @param $csv_path
     * @param $table_name
     * @param $has_header
     * @param $dest_path
     * @return string
     */
    public static function csv_to_sql($csv_path, $table_name, $has_header = true, $dest_path = null): string
    {

        if (!is_null($dest_path) && !is_dir($dest_path)) {
            if (!mkdir($dest_path, 0755, true) && !is_dir($dest_path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dest_path));
            }
        }

        $csv_data = array_map('str_getcsv', file($csv_path));

        $columns = array();
        if ($has_header) {
            $columns = $csv_data[0];
            array_shift($csv_data);
        } else {
            $num_columns = count($csv_data[0]);
            for ($i = 1; $i <= $num_columns; $i++) {
                $columns[] = "column" . $i;
            }
        }

        $create_table_sql = "CREATE TABLE `$table_name` (";
        foreach ($columns as $column) {
            $create_table_sql .= "`$column` TEXT,";
        }
        $create_table_sql = rtrim($create_table_sql, ",");
        $create_table_sql .= ");";

        $insert_data_sql = "INSERT INTO `$table_name` (";
        foreach ($columns as $column) {
            $insert_data_sql .= "`$column`,";
        }
        $insert_data_sql = rtrim($insert_data_sql, ",");
        $insert_data_sql .= ") VALUES ";

        foreach ($csv_data as $row) {
            $insert_data_sql .= "(";
            foreach ($row as $cell) {
                $insert_data_sql .= "'" . addslashes($cell) . "',";
            }
            $insert_data_sql = rtrim($insert_data_sql, ",");
            $insert_data_sql .= "),";
        }
        $insert_data_sql = rtrim($insert_data_sql, ",");
        $insert_data_sql .= ";";

        $sql = $create_table_sql . "\n\n" . $insert_data_sql;

        if (!is_null($dest_path)) {
            $file_path = $dest_path . "/" . $table_name . ".sql";
            file_put_contents($file_path, $sql);
            return $file_path;
        }

        return $sql;
    }


    /**
     * Convertit un fichier MP3 en OGA
     *
     * @param $input_file
     * @param $output_file
     * @return void
     * @throws Exception
     */
    public static function convert_mp3_to_oga($input_file, $output_file): void
    {

        if (!file_exists($input_file)) {
            throw new Exception("Le fichier d'entrée n'existe pas");
        }

        if (!shell_exec('command -v ffmpeg')) {
            throw new Exception("FFmpeg n'est pas installé sur ce système");
        }

        $output_dir = dirname($output_file);
        if (!is_dir($output_dir)) {
            if (!mkdir($output_dir, 0755, true) && !is_dir($output_dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $output_dir));
            }
        }

        $command = "ffmpeg -i $input_file -acodec libvorbis $output_file";
        shell_exec($command);
    }

    /**
     * Convertit un fichier MP4 en OGV
     *
     * @param string $input_file Le fichier MP4 d'entrée
     * @param string $output_file Le fichier OGV de sortie
     * @throws Exception si le fichier d'entrée n'existe pas ou si FFmpeg n'est pas installé
     * @throws RuntimeException si le répertoire de destination ne peut pas être créé
     */
    public static function convert_mp4_to_ogv(string $input_file, string $output_file): void
    {
        if (!file_exists($input_file)) {
            throw new Exception("Le fichier d'entrée n'existe pas");
        }

        if (!shell_exec('command -v ffmpeg')) {
            throw new Exception("FFmpeg n'est pas installé sur ce système");
        }

        $output_dir = dirname($output_file);
        if (!is_dir($output_dir)) {
            if (!mkdir($output_dir, 0755, true) && !is_dir($output_dir)) {
                throw new \RuntimeException(sprintf('Le répertoire "%s" n\'a pas pu être créé', $output_dir));
            }
        }

        $command = "ffmpeg -i $input_file -c:v libtheora -c:a libvorbis -q:v 6 -q:a 6 $output_file";
        shell_exec($command);
    }
}