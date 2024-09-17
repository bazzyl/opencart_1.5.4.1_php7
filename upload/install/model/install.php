<?php
class ModelInstall extends Model {
    public function mysql($data) {
        $connection = mysqli_connect($data['db_host'], $data['db_user'], $data['db_password'], $data['db_name']);

        if (!$connection) {
            die('Error: Could not connect to the database. ' . mysqli_connect_error());
        }

        mysqli_set_charset($connection, 'utf8');

        $file = DIR_APPLICATION . 'opencart.sql';

        if ($sql = file($file)) {
            $query = '';

            foreach($sql as $line) {
                $tsl = trim($line);

                if (($sql != '') && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {
                    $query .= $line;

                    if (preg_match('/;\s*$/', $line)) {
                        $query = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'], $query);
                        $query = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . $data['db_prefix'], $query);
                        $query = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $query);

                        $result = mysqli_query($connection, $query);

                        if (!$result) {
                            die(mysqli_error($connection));
                        }

                        $query = '';
                    }
                }
            }

            mysqli_query($connection, "SET @@session.sql_mode = 'MYSQL40'");

            mysqli_query($connection, "DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");

            mysqli_query($connection, "INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . mysqli_real_escape_string($connection, $data['username']) . "', salt = '" . mysqli_real_escape_string($connection, $salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . mysqli_real_escape_string($connection, sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '1', email = '" . mysqli_real_escape_string($connection, $data['email']) . "', date_added = NOW()");

            mysqli_query($connection, "DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
            mysqli_query($connection, "INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_email', value = '" . mysqli_real_escape_string($connection, $data['email']) . "'");

            mysqli_query($connection, "DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_url'");
            mysqli_query($connection, "INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_url', value = '" . mysqli_real_escape_string($connection, HTTP_OPENCART) . "'");

            mysqli_query($connection, "DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
            mysqli_query($connection, "INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_encryption', value = '" . mysqli_real_escape_string($connection, md5(mt_rand())) . "'");

            mysqli_query($connection, "UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");

            mysqli_close($connection);
        }
    }
}
