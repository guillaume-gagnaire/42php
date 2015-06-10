<?php

class 					ABController extends Controller {
	public function 	handle() {
		if (isset($_GET['clickOn'], $_GET['p'])) {
			$v = new ABPageView(intval($_GET['clickOn']));
			if ($v->id) {
				$v->clicked = 1;
				$v->click_date = date('Y-m-d H:i:s');
				$v->param = $_GET['p'];
				$v->save();
			}
		}
		if (isset($_GET['redirect'])) {
			Redirect::permanent($_GET['redirect']);
		}
		die();
	}
}

?>