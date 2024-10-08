<?php
class ControllerUpgrade extends Controller {
	private $error = array();

	public function index() {

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] = HTTP_SERVER . 'index.php?route=upgrade/upgrade';

		$this->template = 'upgrade.tpl';
		$this->children = array(
			'header',
			'footer'
		);

		$this->response->setOutput($this->render());

	}

	public function success() {

		$this->template = 'success.tpl';
		$this->children = array(
			'header',
			'footer'
		);

		$this->response->setOutput($this->render());

	}

	public function upgrade() {

		if ($this->validate()) {

			$this->load->model('upgrade');

			$this->model_upgrade->mysql($this->request->post, 'upgrade.sql');
			
			$this->model_upgrade->modifications();

			$this->redirect(HTTP_SERVER . 'index.php?route=upgrade/success');
		} else {
			die($this->error['warning']);
		}

	}

    private function validate() {
        if (!defined('DB_HOSTNAME')) {
            $this->error['warning'] = 'Host required!';
        }

        if (!defined('DB_USERNAME')) {
            $this->error['warning'] = 'User required!';
        }

        if (!defined('DB_DATABASE')) {
            $this->error['warning'] = 'Database Name required!';
        }

        $connection = @mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

        if (!$connection) {
            $this->error['warning'] = 'Error: Could not connect to the database. Please make sure the database server, username, and password are correct in the config.php file!';
        } else {
            if (!@mysqli_select_db($connection, DB_DATABASE)) {
                $this->error['warning'] = 'Error: Database "' . DB_DATABASE . '" does not exist!';
            }

            mysqli_close($connection);
        }

        return empty($this->error);
    }

}
?>