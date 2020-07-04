<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Downloadable extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Downloadable_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'downloadable/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'downloadable/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'downloadable/index.html';
            $config['first_url'] = base_url() . 'downloadable/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Downloadable_model->total_rows($q);
        $downloadable = $this->Downloadable_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'downloadable_data' => $downloadable,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('downloadable/downloadable_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Downloadable_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'file_modul' => $row->file_modul,
		'id_modul' => $row->id_modul,
	    );
            $this->load->view('downloadable/downloadable_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('downloadable'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('downloadable/create_action'),
	    'id' => set_value('id'),
	    'file_modul' => set_value('file_modul'),
	    'id_modul' => set_value('id_modul'),
	);
        $this->load->view('downloadable/downloadable_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'file_modul' => $this->input->post('file_modul',TRUE),
		'id_modul' => $this->input->post('id_modul',TRUE),
	    );

            $this->Downloadable_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('downloadable'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Downloadable_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('downloadable/update_action'),
		'id' => set_value('id', $row->id),
		'file_modul' => set_value('file_modul', $row->file_modul),
		'id_modul' => set_value('id_modul', $row->id_modul),
	    );
            $this->load->view('downloadable/downloadable_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('downloadable'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'file_modul' => $this->input->post('file_modul',TRUE),
		'id_modul' => $this->input->post('id_modul',TRUE),
	    );

            $this->Downloadable_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('downloadable'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Downloadable_model->get_by_id($id);

        if ($row) {
            $this->Downloadable_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('downloadable'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('downloadable'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('file_modul', 'file modul', 'trim|required');
	$this->form_validation->set_rules('id_modul', 'id modul', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Downloadable.php */
/* Location: ./application/controllers/Downloadable.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2020-07-04 02:28:51 */
/* http://harviacode.com */