<?php
    //unix process handling routines
    
    /**
     * the most common type of UNIX (noninteractive) piping:
     * first write then read.
     * 1. we write something to the kid's stdin
     * 2. we close the stdin stream
     * 3. read from kid's stdout until it closes it
     * 4. same thing for kid's stderr
     * 5. we wait on the kid to get its exit code
     * TODO: check all calls for false returns
     */
    function write_read_pipe ($command, $to_stdin)
    {    
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("pipe", "w")   // stderr is a pipe to write to
        );
        
        $process = @proc_open($command, $descriptorspec, $pipes);
        if (is_resource($process)) {
            // $pipes now looks like this:
            // 0 => writeable handle connected to child stdin
            // 1 => readable handle connected to child stdout
            // Any error output will be appended to /tmp/error-output.txt

            fwrite($pipes[0], $to_stdin);
            fclose($pipes[0]);

            $stdout="";
            while(!feof($pipes[1])) 
            {
                $stdout.=fgets($pipes[1], 1024);
            }
            @fclose($pipes[1]);
            
            $stderr="";
            while(!feof($pipes[2])) 
            {
                $stderr.=fgets($pipes[2], 1024);
            }
            @fclose($pipes[2]);
            
            // It is important that you close any pipes before calling
            // proc_close in order to avoid a deadlock
            $return_value = proc_close($process);
            
            return array (
                'code'   => $return_value,
                'stdout' => $stdout,
                'stderr' => $stderr,
            );
        }
        return false;
    }
    
    function my_die($message, $exitcode=1)
    {
        global $hl_errors, $hl_codes;
        
        print ("$message\n");
        if (!empty($hl_errors)) dump_hl_errors($hl_errors);
        if (!empty($hl_codes)) dump_hl_codes($hl_codes);
        exit ($exitcode);
    }
    
    /**
     * $command - the command that will be run
     */
    function my_exec ($command)
    {
        $output=array();
        error_file ("my_exec: $command", "debug");
        exec ($command, $output, $ret_val);
        return array ('ret_val' => $ret_val, 'output' => $output);
    }
    
    function shell_output_handler ($result)
    {
        if (empty($result['ret_val']))
        {
            //zero ret_val means success
            return true;
        }
        else
        {
            $stdout = join ("\\", $result['output']);
            /*
            return hl_log(false, 'shell_output_handler', 'error', 'suspicious_return_code', 
                array($result['ret_val'], $stdout));
            */
            //!!!issue a warning!
            return false;
        }
    }
    
    /**
    * A wrapper for /usr/bin/sudo
    * @return array - two keys: 'ret_val' and 'output'
    * @param command sting - the command to execute as another user
    * @param asuser string - the user to execute command as, if ommitted then 'root' is used
    * @desc Enter description here...
    */
    function sudo ($command, $asuser='root')
    {
        $cmd  = "/usr/bin/sudo ";
        if ($asuser != 'root') $cmd .= "-u '$asuser' ";
        $cmd .= $command;
        $output=array();
        error_file ("sudo: $cmd", "debug");
        exec ($cmd, $output, $ret_val);
        error_file("sudo: output:\n".join ("\n", $output), "debug");
        return array ('ret_val' => $ret_val, 'output' => $output);
    }
?>
