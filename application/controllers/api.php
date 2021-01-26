<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	// variable global
	var $apiURLKel		= "http://api.jakarta.go.id/v1/kelurahan"; 
	var $apiURLRSU		= "http://api.jakarta.go.id/v1/rumahsakitumum"; 
	var	$Authorization  = "LdT23Q9rv8g9bVf8v/fQYsyIcuD14svaYL6Bi8f9uGhLBVlHA3ybTFjjqe+cQO8k";
	var $dataKelurahan;

	public function v1()
	{
		// load my_communication helper (curl) 
		$this->load->helper('my_communication');

		// get data kelurahan dari api
		$kelurahan = ApiJakarta($this->apiURLKel, $this->Authorization);

		// pass dan simpan response data kelurahan ke variable global
		$this->dataKelurahan = $kelurahan->data;

		// get data rumah sakit umum dari api
		$rsu = ApiJakarta($this->apiURLRSU, $this->Authorization);
		
		// variable result
		$result = array();

		// perulangan data rumah sakit umum
		foreach($rsu->data as $key => $value){
			// variable data kelurahan berdasarkan kode kelurahan
			$kelurahan = array();

			// variable data kecamatan berdasarkan kode kelurahan
			$kecamatan = array();

			// variable data kota berdasarkan kode kelurahan
			$kota = array();

			// perulangan get data reff kelurahan berdasarkan kode kelurahan
			foreach ($this->dataKelurahan as $k => $v) {
				if ($v->kode_kelurahan == $value->kode_kelurahan) {
					// membuat object kelurahan baru
					$kel['kode'] = $v->kode_kelurahan;
					$kel['nama'] = $v->nama_kelurahan;
					$kelurahan = $kel;

					// membuat object kecamatan baru
					$kec['kode'] = $v->kode_kecamatan;
					$kec['nama'] = $v->nama_kecamatan;
					$kecamatan = $kec;

					// membuat object kota baru
					$kotas['kode'] = $v->kode_kota;
					$kotas['nama'] = $v->nama_kota;
					$kota = $kotas;
				}
			}

			// merge data alamat ke dalam data keseluruhan data rumah sakit umum
			$value->alamat 	= $value->location->alamat;

			// merge data kelurahan ke dalam data keseluruhan data rumah sakit umum
			$value->kelurahan = $kelurahan;
			
			// merge data kecamatan ke dalam data keseluruhan data rumah sakit umum
			$value->kecamatan = $kecamatan;
			
			// merge data kota ke dalam data keseluruhan data rumah sakit umum
			$value->kota = $kota;

			// remove data object / array
			unset($value->kode_kota);
			unset($value->kode_kecamatan);
			unset($value->kode_kelurahan);
			unset($value->latitude);
			unset($value->longitude);
			unset($value->location->alamat);
			
			// simpan ke variable result
			$result[] = $value;
		}
		
		// merge kedalam data hasil dari api, menjadi response baru
		$rsu->data = $result;

		// menampilkan hasil ke dalam browser
		echo "<pre>";
		echo json_encode($result);
		die("<pre>".print_r($rsu,1));
	}
}
