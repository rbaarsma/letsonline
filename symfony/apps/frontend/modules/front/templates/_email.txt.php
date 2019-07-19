<?php // I'm not getting why newlines are removed by the template...
echo trim($message)."\n";
echo str_repeat("_", strlen(__("This message was sent by %user% through the LETSOnline Contact Us Form", array("%user%"=>$from))));
echo "\n";
echo __("This message was sent by %user% through the LETSOnline Contact Us Form", array("%user%"=>$sf_context->getRaw('user')->getObject()));
?>