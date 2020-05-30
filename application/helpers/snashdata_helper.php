<?php   


if ( ! function_exists('snashdata') ) 
{
    /**
     * A quick alias for CI flashdata
     */     
    function snashdata($key = NULL)
    {
        if (isset($key))
        { 
            $snashdata = (isset($_SESSION['__snash_vars'], $_SESSION['__snash_vars'][$key]) && ! is_int($_SESSION['__snash_vars'][$key]))
                ? $_SESSION['__snash_vars'][$key]
                : NULL;
            unmark_snash($key);
            return $snashdata;
        }
        $snashdata = array();

        if ( ! empty($_SESSION['__snash_vars']))
        {
            foreach ($_SESSION['__snash_vars'] as $key => &$value)
            {
                is_int($value) OR $snashdata[$key] = $_SESSION['__snash_vars'][$key];
            }
        }
        unmark_snash($key);
        return $snashdata;
    }
} 


if ( ! function_exists('set_snashdata') ) 
{
    /**
     * A quick alias for CI set_flashdata
     */     
    function set_snashdata($data, $value = NULL)
    {
        $_SESSION['__snash_vars'][$data] = $value;

        $key = is_array($data) ? array_keys($data) : $data;
        if (is_array($key))
        {
            for ($i = 0, $c = count($key); $i < $c; $i++)
            {
                if ( ! isset($_SESSION[$key[$i]]))
                {
                    return FALSE;
                }
            }

            $new = array_fill_keys($key, 'new');

            $_SESSION['__snash_vars'] = isset($_SESSION['__snash_vars'])
                ? array_merge($_SESSION['__snash_vars'], $new)
                : $new;

            return TRUE;
        }

        if ( ! isset($_SESSION[$key]))
        {
            return FALSE;
        }

        $_SESSION['__snash_vars'][$key] = 'new';
        return TRUE;
    }
} 


if ( ! function_exists('unmark_snash') ) 
{
    /**
     * A quick alias for CI unmark_flash
     */  
    function unmark_snash($key)
    {
        if (empty($_SESSION['__snash_vars']))
        {
            return;
        }

        is_array($key) OR $key = array($key);

        foreach ($key as $k)
        {
            if (isset($_SESSION['__snash_vars'][$k]) && ! is_int($_SESSION['__snash_vars'][$k]))
            {
                unset($_SESSION['__snash_vars'][$k]);
            }
        }

        if (empty($_SESSION['__snash_vars']))
        {
            unset($_SESSION['__snash_vars']);
        }
    }
}
