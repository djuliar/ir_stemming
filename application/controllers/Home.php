<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		$this->load->model('kamus_model', 'kamus');
		$this->load->library('docxToTextConversion');
		$this->load->library('pdf2Text');
		$this->load->helper('stemming_nazief');
	}

	public function index()
	{
		$data = array(
			'title' => 'Information Retrieval',
			'success' 	=> $this->session->flashdata('success'),
		);
		$this->load->view('home', $data);
	}

	function upload()
	{
		$this->load->library('upload');

		$config['upload_path'] = FCPATH . 'assets/';
        $config['allowed_types'] = 'docx|pdf';
        $config['max_size'] = 2048;
        $config['overwrite'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
		
		$this->upload->initialize($config);
		
		if ($this->upload->do_upload('dokumen')){
			$data = array('upload_data' => $this->upload->data());
			$upload_data = $this->upload->data();
			$file_name = $upload_data['file_name'];
			$file_ext = $upload_data['file_ext'];

			if($file_ext === '.docx'){
				$converter = new DocxToTextConversion(FCPATH . 'assets/'.$file_name);
				$token = $this->tokenisasi($converter->convertToText());
			} elseif($file_ext === '.pdf'){
				$a = new PDF2Text();
				$a->setFilename(FCPATH . 'assets/'.$file_name); 
				$a->decodePDF();
				$token = $this->tokenisasi($a->output());
			}
			unlink(FCPATH . 'assets/'.$file_name);
			echo $token;
		} else {
			$error = array('error' => $this->upload->display_errors());
			echo $error['error'];
		}
	}

	function tokenisasi($text_convert=0)
	{
		$text_no_simbol_number = preg_replace(array('/[^\da-z ]/i','/\d+/u'), '', $text_convert);
		$text_lower = strtolower($text_no_simbol_number);
		$text_array = preg_split("/[\s,.:]+/", $text_lower);

		// STOPWORD REMOVE
		$stopword = $this->kamus->get_where('dictionary', array('stopword' => 'Ya'))->result();
		$json = array();
		foreach ($stopword as $stopword) {
			array_push($json, $stopword->word);
		}
		$arr = json_decode(json_encode($json));
		$numItems = count($arr);
		
		$data_stem = array();
		foreach($text_array as $item)
		{
		    $i = 0;
		    $item_stem = stemming($item);
		    foreach ($arr as $word) {
		    	$i++;
			    if ($item == $word) {
			        break;
			    } else {
			    	if($i === $numItems && $item_stem != '') {
			    		array_push($data_stem, $item_stem);
					}
			    }
			}
		}
		$result_stem = array_count_values($data_stem);
		array_multisort($result_stem, SORT_NUMERIC, SORT_DESC);
		return json_encode(array($result_stem));
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */