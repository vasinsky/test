<?php
    //must be included_once in files that use the mt_rand() function
    mt_srand ((double)microtime()*1000000);
    
    function random_string($length=8)
    {
        $rnd = mt_rand(0, 25);
        $res = chr(ord('a')+$rnd);
        for ($i=1; $i<$length; $i++)
        {
            $rnd = mt_rand(0, 35);
            if ($rnd<10)
            {
                $res .= chr(ord('0')+$rnd);
            }
            else
            {
                $res .= chr(ord('a')+$rnd-10);
            }
        }
        
        return $res;
    }
?>
