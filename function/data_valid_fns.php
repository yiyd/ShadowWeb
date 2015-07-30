<?php
	function filled_out($form_vars) {
	  // test that each variable has a value
	  foreach ($form_vars as $key => $value) {
	     if ((!isset($key)) || ($value == '')) {
	        throw new Exception("Input Error! No input!");
	     }
	  }
	  return true;
	}

	function valid_email($address) {
	  // check an email address is possibly valid
	  if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address)) {
	    return true;
	  } else {
	    throw new Exception("Wrong Email address!");
	  }
	}


?>
