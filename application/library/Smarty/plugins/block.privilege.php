<?php
function smarty_block_privilege($params, $content, &$smarty)
{
    if (is_null($content)) {
        return;
    }

	$require = @$params['role'];
	if (empty($require)) {
		return $content;
	}
	$user    = $smarty->_tpl_vars['gUser'];
	$satisfy = false;
	switch ($require) {
		case Uc_Auth::CRADLE_ROLE_ADM:
			if ($user->isAdmin()) {
				$satisfy = true;
			}
			break;
		case Uc_Auth::CRADLE_ROLE_OP:
			if ($user->isAdmin()
				|| $user->isOp()) {
				$satisfy = true;
			}
			break;
	}


    return $satisfy? $content : '';
}
?>
