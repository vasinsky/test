<?php

    //some unixes have a 8 character limit for usernames
    define ("MAX_UNIX_USERNAME_CHARS", 32); //linux usernames are max 32 chars, man utmp
    define ("MAX_MYSQL_USERNAME_CHARS", 16); //see mysql manual
    define ("MAX_MYSQL_DATABASENAME_CHARS", 64); //see mysql manual

    define ("MIN_PASSWORD_CHARS", 4);
    define ("MAX_PASSWORD_CHARS", 32);

    define ("MAX_DOMAIN_NAME_CHARS", 66); //actually it's 67 but we'll set the limit 66 just to be safe

    // (disabled in hl-yafm) include_once ("{$absolute_path}/include/country-iso-codes.inc");

    function validate_password ($password, $maxchars=MAX_PASSWORD_CHARS)
    {
        global $hl_errors;

        if (strlen($password)<MIN_PASSWORD_CHARS)
        {
            $msg="Too short password.";
            $hl_errors[]=$msg;
            error_file ("validate_password: $msg $password", "error");
            return false;
        }

        if (strlen($password)>$maxchars)
        {
            $msg="Password too long.";
            $hl_errors[]=$msg;
            error_file ("validate_password: $msg $password", "error");
            return false;
        }

        return true;
    }

    function validate_database_name ($name)
    {
        global $hl_errors;

        if (empty($name) || $name=="mysql" || $name=="test")
        {
            $msg="Invalid database name: $name";
            $hl_errors[]=$msg;
            error_file ("validate_database_name: $msg", "error");
            return false;
        }

        if (strlen($name)>MAX_MYSQL_DATABASENAME_CHARS)
        {
            $msg="Too long MySQL databasename: $name";
            $hl_errors[]=$msg;
            error_file ("validate_database_name: $msg", "error");
            return false;
        }

        return nice_string($name);
    }

    //no more than 16 characters
    function validate_mysql_username ($username)
    {
        global $hl_errors;

        if (strlen($username)>MAX_MYSQL_USERNAME_CHARS)
        {
            $msg="Too long MySQL username: $username";
            $hl_errors[]=$msg;
            error_file ("validate_mysql_username: $msg", "error");
            return false;
        }

        return nice_string($username);
    }

    function validate_email ($email)
    {
        global $hl_errors;

        //another regexp: /^\w+[\w|\.|-]*\w+@(\w+[\w|\.|-]*\w+\.[a-z]{2,4}|(\d{1,3}\.){3}\d{1,3})$/i
        if (!preg_match('/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-*)|(\w+\.))*\w+\.[a-zA-Z]{2,6}$/', $email))
        {
            $msg="Invalid email: $email";
            $hl_errors[]=$msg;
            error_file ("validate_email: $msg", "error");
            return false;
        }

        $domain = substr($email, strpos($email, '@')+1);
        return (validate_domain_name($domain));
    }

    /**
     * Validates the username (before the @ character) that can be used in an email address
     */
    function validate_mailbox_name ($email)
    {
        global $hl_errors;

        //another regexp: /^\w+[\w|\.|-]*\w+@(\w+[\w|\.|-]*\w+\.[a-z]{2,4}|(\d{1,3}\.){3}\d{1,3})$/i
        if (!preg_match('/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+$/', $email))
        {
            $msg="Invalid mailbox name: $email";
            $hl_errors[]=$msg;
            error_file ("validate_mailbox_name: $msg", "error");
            return false;
        }

        return true;
    }

    function validate_unix_username ($username)
    {
        global $hl_errors;

        if (strlen($username)>MAX_UNIX_USERNAME_CHARS)
        {
            $msg="Too long UNIX username: $username";
            $hl_errors[]=$msg;
            error_file ("validate_unix_username: $msg", "error");
            return false;
        }

        return nice_string($username, true); //only lowercase letters allowed!
    }

     /**
     * we don't want to accept paths like ../../etc/passwd or /etc/passwd
     */
    function validate_relative_path (&$path)
    {
        global $hl_errors;

        $pos = strstr($path, "..");
        if (!$pos)
        {
            if (substr($path, 0, 1)=='/')
            {
                $msg="relative path cannot begin with '/', path was $path";
                error_file ("validate_relative_path: $msg", "warn");
                $hl_errors[]=$msg;
                return false;
            }
            else
            {
                clean_relative_path($path);
                return true;
            }
        }
        else
        {
            $msg="relative path cannot contain '..', path was $path";
            error_file ("validate_relative_path: $msg", "warn");
            $hl_errors[]=$msg;
            return false;
        }
    }

    //shrinks double slashes and ././ stuff from a relative dirname
    //beginning and trailing slashes are removed!
    function clean_relative_path (&$path)
    {
        $entries = split('/', $path);
        //print_r ($entries);
        $res = '';
        foreach ($entries as $entry)
        {
            if ($entry!='.' && !empty($entry)) $res .= $entry . '/';
        }
        $path = preg_replace ('/\/$/', '', $res); //remove trailing slash
    }

    function validate_string ($label, $str)
    {
        global $hl_errors;

        if (!is_string($str))
        {
            $msg="Invalid value for $label.";
            error_file ("validate_string: $msg", "warn");
            $hl_errors[]=$msg;
            return false;
        }

        if (strlen ($str)<2)
        {
            $msg="$label is too short.";
            error_file ("validate_customer_name: $msg", "warn");
            $hl_errors[]=$msg;
            return false;
        }

        return true;
    }

    function validate_uint ($label, $uint)
    {
        global $hl_errors;

        if (!is_numeric($uint))
        {
            $msg="Invalid value for $label.";
            error_file ("validate_uint: $msg", "warn");
            $hl_errors[]=$msg;
            return false;
        }

        return true;
    }

    /*
    function validate_country_iso($iso_code)
    {
        global $hl_errors;

        $iso_codes = get_country_iso_codes();
        $iso_code = strtolower ($iso_code);
        if (empty($iso_codes[$iso_code]))
        {
            $msg="Unknown country ISO code: $iso_code";
            error_file ("validate_country_iso: $msg", "warn");
            $hl_errors[]=$msg;
            return false;
        }

        return true;
    }
    */

    function validate_domain_name($domain_name)
    {
        global $hl_errors;

        //another regexp: /^@(\w+[\w|\.|-]*\w+\.[a-z]{2,4}|(\d{1,3}\.){3}\d{1,3})$/i
        if (!preg_match('/^((\w+\-*)|(\w+\.))*\w+\.[a-zA-Z]{2,6}$/', $domain_name))
        {
            $msg="Invalid domain: $domain_name";
            $hl_errors[]=$msg;
            error_file ("validate_domain_name: $msg", "error");
            return false;
        }

        if (preg_match('/_/', $domain_name))
        {
            $msg="Invalid domain: $domain_name";
            $hl_errors[]=$msg;
            error_file ("validate_domain_name: (contains underscore!) $msg", "error");
            return false;
        }

        if (strlen($domain_name)>MAX_DOMAIN_NAME_CHARS)
        {
            $msg="Domain name too long: $domain_name";
            $hl_errors[]=$msg;
            error_file ("validate_domain_name: $msg", "error");
            return false;
        }

        return true;
    }

    /**
     * domain name rules:
     * 1. Must be two or more characters in length (although I know there are
     * single character domain names in existance).
     *
     * 2. First and last character must be alphanumeric.
     *
     * 3. Intervening characters may include alphanumerics and hyphens ("-").
     *
     * 4. Domains must be limited to 67 characters (including period and com/net/org).
     * So in actuality, 63 characters :)
     *
     * note: once character subdomains should be allowed! [done]
     */
    function validate_subdomain_name($subdomain_name)
    {
        //another regexp: '/^@(\w+[\w|\.|-]*\w+\.[a-z]{2,4}|(\d{1,3}\.){3}\d{1,3})$/i'
        if (!preg_match('/^([a-zA-Z0-9]|[a-zA-Z0-9]+[a-zA-Z0-9\-]*[a-zA-Z0-9]+)$/', $subdomain_name))
        {
            global $hl_errors;
            $msg="Invalid subdomain: $subdomain_name";
            $hl_errors[]=$msg;
            error_file ("validate_subdomain_name: $msg", "error");
            return false;
        }

        return true;
    }

    /**
     * taken from: http://www.zend.com/zend/spotlight/php-link-validation2.php
     * /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\//i
     * parse_url() parses but doesn't validate.
     * but fortunately we can validate those parsed values ourselves ;-)
     */
    function validate_url ($url)
    {
        global $hl_errors;

        $acceptable_protocols = array (
            'http', 'https', 'ftp', 'smb', 'rsync',
        );

        $url_items = parse_url($url);

        if (!in_array(strtolower($url_items['scheme']), $acceptable_protocols))
        {
            $msg="Invalid protocol: {$url_items['scheme']} in url: $url";
            $hl_errors[]=$msg;
            error_file ("validate_url: $msg", "error");
            return false;
        }

        if (!validate_domain_name($url_items['host']))
        {
            $msg="Invalid host name: {$url_items['host']} in url: $url";
            $hl_errors[]=$msg;
            error_file ("validate_url: $msg", "error");
            return false;
        }

        return true;
    }

    function validate_date ($date)
    {
        if (!preg_match('/^\d{4}[\-\/]\d{1,2}[\-\/]\d{1,2}$/', $date))
        {
            global $hl_errors;
            $msg="Invalid date: $date";
            $hl_errors[]=$msg;
            error_file ("validate_date: $msg", "error");
            return false;
        }

        return true;
    }

    function validate_mailbox_quota($quota)
    {
        if (!preg_match('/^\d+s?$/i', $quota))
        {
            global $hl_errors;
            $msg="Invalid mailbox quota value: $quota";
            $hl_errors[]=$msg;
            error_file ("validate_mailbox_quota: $msg", "error");
            return false;
        }

        return true;
    }

    /**
     * make sure the folder is inside a subdomain.
     */
    function validate_subdomain_folder (&$folder)
    {
        global $hl_errors;

        clean_relative_path($folder);
        $folder = '/'.$folder.'/';

        /**
         * capture[1] = username
         * capture[2] = subdomain
         * capture[3] = relative path
         */
        if (!preg_match('/^\/home\/(.+?)\/public_html\/(.+?)\/(.*)$/', $folder, $capture))
        {
            $msg="Invalid subdomain folder: $folder";
            $hl_errors[]=$msg;
            error_file ("validate_subdomain_folder: $msg", "error");
            return false;
        }

        $the_username      = $capture[1];
        $the_subdomain     = $capture[2];
        $the_relative_path = $capture[3];

        if (!validate_unix_username($the_username)) return false;
        if (!validate_subdomain_name($the_subdomain)) return false;
        if (!validate_relative_path($the_relative_path)) return false;

        $folder = "/home/{$the_username}/public_html/{$the_subdomain}/{$the_relative_path}";

        return true;
    }
    
    /**
     * make sure the folder is inside a use account, e.g. /home/username[/some/other/path[/]]
     */
    function validate_account_folder (&$folder)
    {
        global $hl_errors;

        clean_relative_path($folder);
        $folder = '/'.$folder.'/';

        /**
         * capture[1] = username
         * capture[2] = relative path
         */
        if (!preg_match('/^\/home\/(.+?)\/(.*)$/', $folder, $capture))
        {
            $msg="Invalid account folder: $folder";
            $hl_errors[]=$msg;
            error_file ("validate_account_folder: $msg", "error");
            return false;
        }

        $the_username      = $capture[1];
        $the_relative_path = $capture[2];

        if (!validate_unix_username($the_username)) return false;
        if (!validate_relative_path($the_relative_path)) return false;

        $folder = "/home/{$the_username}/{$the_relative_path}";

        return true;
    }

    function validate_backup_restore_folder (&$folder)
    {
        global $hl_errors;

        $realfolder = @realpath ($folder);
        if (empty($folder) || $folder{0}!='/' || !$realfolder)
        {
            $msg="Refusing to backup or restore folder: $folder";
            $hl_errors[]=$msg;
            error_file ("validate_backup_restore_folder: $msg", "error");
            return false;
        }

        $entries = split('/', $realfolder);
        if (empty($entries[1]) || $entries[1]!='home')
        {
            $msg="Refusing to backup or restore folder: $realfolder";
            $hl_errors[]=$msg;
            error_file ("validate_backup_restore_folder: $msg", "error");
            return false;
        }

        $entries = split('/', $folder);
        if (in_array('..', $entries))
        {
            $msg="Refusing to backup or restore folder: $folder";
            $hl_errors[]=$msg;
            error_file ("validate_backup_restore_folder: $msg", "error");
            return false;
        }

        $folder = $realfolder;
        return true;
    }

    /**
     * A nice string is a string that:
     * 1) begins with a letter,
     * 2) is at least 2 characters long,
     * 3) can only contain letters, digits and/or underscores: _
     * if small_caps is set then the string can only contain small letters, and not capital letters (e.g. UNIX usernames)
     * otherwise both capital and small letters are allowed.
     */
    function nice_string($str, $small_caps = false)
    {
        global $hl_errors;

        $ignore_case = empty ($small_caps) ? 'i' : '';
        if (!preg_match('/^[a-z][a-z0-9_]+$/'.$ignore_case, $str))
        {
            $msg="Invalid value: $str";
            $hl_errors[]=$msg;
            error_file ("nice_string: not a nice string: $str", "error");
            return false;
        }

        return true;
    }


    function my_cleanpath_testcase ($path)
    {
        validate_backup_restore_folder($path);
        return ($path);
    }

    //it has to have two conseutive newline characters (header/body separator)
    function validate_autoresponse ($autoresponse)
    {
        if (!preg_match('/\n\n/', $autoresponse))
        {
            global $hl_errors;
            $msg = "Invalid autoresponse text: $autoresponse";
            error_file ("validate_autoresponse: $msg", 'error');
            $hl_errors[]=$msg;
            return false;
        }
        return true;
    }
    
    function validate_reseller ($reseller)
    {
        return !empty($reseller);
    }
    
    function validate_ipaddress ($ipaddress)
    {
        $res = true;
        
        $octets = split ('\.', $ipaddress);
        
        if (!is_array($octets) || count($octets)!=4)
        {
            $res = false;
        }
        else
        {
            foreach ($octets as $octet)
            {
                if (!is_numeric($octet) || $octet<0 or $octet>255)
                {
                    $res = false;
                    break;
                }
            }
        }
        
        if (empty($res))
        {
            global $hl_errors;
            $msg = "Invalid ipaddress: $ipaddress";
            error_file ("validate_ipaddress: $msg", 'error');
            $hl_errors[]=$msg;
            return false;
        }
        
        return $res;
    }
    
?>
