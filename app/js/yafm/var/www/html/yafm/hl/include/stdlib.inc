<?php
    $cvs_id='$Id: stdlib.inc,v 1.3 2004/07/29 09:39:20 andrew Exp $';
    
    //server name, e.g. gmpsoft.gmp
    if (empty($srvname)) 
    {
        if (empty($_SERVER['SERVER_NAME']))
        {
            $srvname="standalone"; //we've been run from the commandline
        }
        else
        {
            $srvname=$_SERVER['SERVER_NAME'];
            $srvname=preg_replace('/^(www\.)/', '', $srvname);
        }
    }
    define ("REAL_SERVER_NAME", $srvname); //a constant to always contain the real server name
    
    $http_protocol = empty ($_SERVER['HTTPS']) ? 'http' : 'https';
    
    //$bin_path is the absolute path to the executable binaries of the hostlib, see /etc/sudoers
    $bin_path=$absolute_path;
    
    //an absolute url for the currently executing php
    if (empty($PHP_SELF))
    {
        if (empty($_SERVER['PHP_SELF']))
        {
            $PHP_SELF = $_SERVER['argv'][0];
        }
        else
        {
            $PHP_SELF = $_SERVER['PHP_SELF'];
        }
    }
    
    //the date/time format used by the some (but not all) log files
    define ("LOGGING_TIME_FORMAT", "M.d H:i:s - ");
    $debug_levels = array ("debug", "info", "warn", "error", "fatal"); //error levels to display
    
    if (empty($hostname)||empty($hostip)) determine_hostname_ip();

    $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';

    error_file ("stdlib.inc: my version is $cvs_id", 'debug');
        
    function error_file($s, $level=false)
    {
        global $absolute_path, $debug_levels;
        
        $now=date(LOGGING_TIME_FORMAT, time());
        if ($level && (!in_array($level, $debug_levels))) return;
        if (!$level) $level="";
        else $level="($level) ";
        error_log("{$now}{$level}{$s}\n", 3, $absolute_path . "/logs/err.log"); //log to the err.log file
    }  

    //determines the current network's node hostname and ip address.
    //modifies the global $hostname and $hostip variables
    function determine_hostname_ip ()
    {
        global $hostname, $hostip;

        $uname = posix_uname();
        

        
        $hostname = $uname['nodename'];
        if (empty($hostname)) return false; //should never happen
        
        $hostip = @gethostbyname($hostname);
        $hostname = @gethostbyaddr($hostip);
        
        if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $hostip) && $hostip!=$hostname)
        {
            return true;
        }
        else
        {
            $hostname = $uname['nodename'];
            error_file ("determine_hostname_ip: dns lookup failed, will attempt to extract IP address from /etc/hosts", 'warn');
            $hosts = file ("/etc/hosts");
            foreach ($hosts as $hostline)
            {
                if (empty($hostline) || trim($hostline)==''  || preg_match('/^\s*\#/', $hostline)) continue;
                $data = preg_split ('/\s/', $hostline);
                //print_r($data);
                $ip_address = array_shift($data);
                foreach ($data as $hname)
                {
                    if ($hname==$hostname) 
                    {
                        $hostip=$ip_address; //found it!
                        error_file ("determine_hostname_ip: retreived hostinfo from /etc/hosts: $hostname $hostip", 'debug');
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * You must always output to HTML thru this function!!!
     * Never echo or print ("<tr><td>$myvar</td></tr>");
     * Always print ("<tr><td>".htmlesc($myvar)."</td></tr>");
     */
    function htmlesc ($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

?>
