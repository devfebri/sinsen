<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Phinx extends CI_Controller
{
    public $wrap;

    public function __construct()
    {
        parent::__construct();

        $app = require sprintf('%s%s', FCPATH, 'vendor/robmorgan/phinx/app/phinx.php');
        $this->wrap = new Phinx\Wrapper\TextWrapper($app);
    }

    public function index()
    {
        // Execute the command and determine if it was successful.
        $output = call_user_func([$this->wrap, 'getStatus'], null, null);
        $error = $this->wrap->getExitCode() > 0;

        // Finally, display the output of the command.
        header('Content-Type: text/plain', true, $error ? 500 : 200);
        echo $output;
    }

    public function migrate()
    {
        // Execute the command and determine if it was successful.
        $output = call_user_func([$this->wrap, 'getMigrate'], $this->input->get('e'), $this->input->get('t'));
        $error = $this->wrap->getExitCode() > 0;

        // Finally, display the output of the command.
        header('Content-Type: text/plain', true, $error ? 500 : 200);
        echo $output;
    }

    public function rollback()
    {
        // Execute the command and determine if it was successful.
        $output = call_user_func([$this->wrap, 'getRollback'], $this->input->get('e'), $this->input->get('t'));
        $error = $this->wrap->getExitCode() > 0;

        // Finally, display the output of the command.
        header('Content-Type: text/plain', true, $error ? 500 : 200);
        echo $output;
    }

    public function seed()
    {
        // Execute the command and determine if it was successful.
        $output = call_user_func([$this->wrap, 'getSeed'], $this->input->get('e'), $this->input->get('t'), $this->input->get('s'));
        $error = $this->wrap->getExitCode() > 0;

        // Finally, display the output of the command.
        header('Content-Type: text/plain', true, $error ? 500 : 200);
        echo $output;
    }
}