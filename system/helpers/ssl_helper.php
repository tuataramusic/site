<?
   if (!function_exists('ssl_on'))  
   {  
       function ssl_on()  
       {  
           $ci = get_instance();  
           $ci->config->config['base_url']		= str_replace('http://', 'https://',$ci->config->config['base_url']);  
           if ($_SERVER['SERVER_PORT'] != 443)  
           {  
               header('Location: '.$ci->uri->uri_string());  
           }  
       }  
   }  
  function ssl_off()  
  {  
       $ci = get_instance();  
       $ci->config->config['base_url']		= str_replace('https://', 'http://', $ci->config->config['base_url']);  
       if ($_SERVER['SERVER_PORT']		!= 80){  
			header('Location: '.$ci->uri->uri_string());
       }  
  }  
  ?>