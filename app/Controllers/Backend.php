<?php
namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\DivisionModel;
use App\Models\PositionModel;
use App\Models\CompanyDatatable;
use App\Models\DivisionDatatable;
use App\Models\PositionDatatables;
use Config\Services;

class Backend extends BaseController
{
    protected $model_company;
    protected $model_division;
    protected $model_position;

    public function __construct()
        {
            $this->model_company  = new CompanyModel();
            $this->model_division = new DivisionModel();
            $this->model_position = new PositionModel();
        }

    private function template($variables)
    {
        if (!$variables) {
            return false;
        }
        echo view('backend/index', $variables);
    }
    
    function index()
    {
        $variables = [
            'title'     =>  'Dashboard Page',
            'module'    =>  'module/dashboard/index.php' # must attach the extentions name
        ];
        $this->template($variables);
    }

    function home()
    {
        $variables = [
            'title'     =>  'Page Home',
            'module'    =>  'module/home/index.php' # must attach the extentions name
        ];
        $this->template($variables);   
    }
    function company()
    {
        $variables = [
            'title'     =>  'Forms',
            'module'    =>  'module/company/index.php' # must attach the extentions name
        ];
        $this->template($variables);   
    }
        function submit_company()
    {

        $rules = [
            'name' => [
                'label' => 'Name Company',
                'rules' => 'required|is_unique[company.name]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
             'contact' => [
                'label' => 'Contact Company',
                'rules' => 'required|is_unique[company.contact]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
             'email' => [
                'label' => 'Email Company',
                'rules' => 'required|is_unique[company.email]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
             'address' => [
                'label' => 'Address Company',
                'rules' => 'required|is_unique[company.address]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
            
        ];

        // $validation = $this->validate($rules);
        $validation = \Config\Services::validation()->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
            // Form tidak valid, tampilkan halaman form dengan error message
            $response['errors'] = $validation->getErrors();
            echo $this->response(false, 400, $response);
            return;
        }

        $create = $this->model_company->save([
            'name'      => $this->request->getVar('name'),
            'contact'   => $this->request->getVar('contact'),
            'email'     => $this->request->getVar('email'),
            'address'   => $this->request->getVar('address'),
        ]);

        if (!$create) {
            echo $this->response(false, 500, 'internal server error');
        }

        echo $this->response(true, 200, 'success');
    }
    function edit_company()
    {
        # data untuk diupdate
        $data = [
            "name"      =>  $this->request->getPost('name'),
            "contact"   =>  $this->request->getPost('contact'),
            "email"     =>  $this->request->getPost('email'),
            "address"   =>  $this->request->getPost('address'),
        ];

        # kondisi where, diupdate berdasarkan apa ?
        $where = [
            'id'    =>  $this->request->getPost('id')
        ];

        # menggunakna fungsi built-in codeigniter
        $update = $this->model_company->update($where, $data);
        if (!$update) {
            echo $this->response(false, 500, 'failed update');
        }
        echo $this->response(true, 200, 'success update');
    }

    function list_company()
    {
        $request = Services::request();
        $datatable = new CompanyDatatable($request);

        if ($request->getMethod(true) === 'POST') {
            $lists = $datatable->getDatatables();
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->name;
                $row[] = $list->contact;
                $row[] = $list->email;
                $row[] = $list->address;
                $row[] = "<button id='button-edit' data-id='$list->id' class='btn btn-warning btn-sm'> Edit </button>
                <button id='button-delete' data-id='$list->id' class='btn btn-danger btn-sm'> Hapus </button>";
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $datatable->countAll(),
                'recordsFiltered' => $datatable->countFiltered(),
                'data' => $data,
            ];

            echo json_encode($output);
        }
    }

    function delete_company()
    {
        $id = $this->request->getVar('id');
        $delete = $this->model_company->delete($id);
        if (!$delete) {
            echo $this->response(false, 500, 'delete company failed');
        }
        echo $this->response(true, 200, 'delete company success');
    }

    function get_company()
    {
        header('Content-Type: application/json');
        $id = $this->request->getVar('id');
        $data = $this->model_company->find($id);
        if (!$data) {
            echo $this->response(false, 500, 'internal server error');
        }
        echo $this->response(true, 200, 'success', $data);
    }

    function data_company()
    {
        // json data array company
        // ambil data id, nama dari table company
        header('Content-Type: application/json');
        $data = $this->model_company->data();
        echo $this->response(true, 200, 'success', $data);
    }
    function division()
    {
        $variables = [
            'title'     =>  'Forms',
            'module'    =>  'module/division/index.php' # must attach the extentions name
        ];
        $this->template($variables);   
    }
        function submit_division()
    {

        $rules = [
            'name' => [
                'label' => 'Name Division',
                'rules' => 'required|is_unique[division.name]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
             'email' => [
                'label' => 'Email Division',
                'rules' => 'required|is_unique[division.email]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
            'contact' => [
                'label' => 'Contact Division',
                'rules' => 'required|is_unique[division.contact]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
            
        ];

        // $validation = $this->validate($rules);
        $validation = \Config\Services::validation()->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
            // Form tidak valid, tampilkan halaman form dengan error message
            $response['errors'] = $validation->getErrors();
            echo $this->response(false, 400, $response);
            return;
        }

        $create = $this->model_division->save([
            'company'   => $this->request->getVar('company'),
            'name'      => $this->request->getVar('name'),
            'email'     => $this->request->getVar('email'),
            'contact'   => $this->request->getVar('contact'),
        ]);

        if (!$create) {
            echo $this->response(false, 500, 'internal server error');
        }

        echo $this->response(true, 200, 'success');
    }
    function edit_division()
    {
        # data untuk diupdate
        $data = [
            "company"   =>  $this->request->getPost('company'),
            "name"      =>  $this->request->getPost('name'),
            "email"     =>  $this->request->getPost('email'),
            "contact"   =>  $this->request->getPost('contact'),
        ];

        # kondisi where, diupdate berdasarkan apa ?
        $where = [
            'id'    =>  $this->request->getPost('id')
        ];

        # menggunakna fungsi built-in codeigniter
        $update = $this->model_division->update($where, $data);
        if (!$update) {
            echo $this->response(false, 500, 'failed update');
        }
        echo $this->response(true, 200, 'success update');
    }

    function list_division()
    {
        $request = Services::request();
        $datatable = new DivisionDatatable($request);

        if ($request->getMethod(true) === 'POST') {
            $lists = $datatable->getDatatables();
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->company;
                $row[] = $list->name;
                $row[] = $list->email;
                $row[] = $list->contact;
                $row[] = "<button id='button-edit' data-id='$list->id' class='btn btn-warning btn-sm'> Edit </button>
                <button id='button-delete' data-id='$list->id' class='btn btn-danger btn-sm'> Hapus </button>";
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $datatable->countAll(),
                'recordsFiltered' => $datatable->countFiltered(),
                'data' => $data,
            ];

            echo json_encode($output);
        }
    }

    function delete_division()
    {
        $id = $this->request->getVar('id');
        $delete = $this->model_division->delete($id);
        if (!$delete) {
            echo $this->response(false, 500, 'delete division failed');
        }
        echo $this->response(true, 200, 'delete division success');
    }

    function get_division()
    {
        header('Content-Type: application/json');
        $id = $this->request->getVar('id');
        $data = $this->model_division->find($id);
        if (!$data) {
            echo $this->response(false, 500, 'internal server error');
        }
        echo $this->response(true, 200, 'success', $data);
    }

    function data_division()
    {
        // json data array company
        // ambil data id, nama dari table company
        header('Content-Type: application/json');
        $data = $this->model_division->data();
        echo $this->response(true, 200, 'success', $data);
    }

    function position()
    {
        $variables = [
            'title'     =>  'Forms',
            'module'    =>  'module/position/index.php' # must attach the extentions name
        ];
        $this->template($variables);   
    }
        function submit_position()
    {

        $rules = [
            'name' => [
                'label' => 'Name Position',
                'rules' => 'required|is_unique[position.name]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
             'status' => [
                'label' => 'Contact Company',
                'rules' => 'required|is_unique[position.status]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
        ];

        // $validation = $this->validate($rules);
        $validation = \Config\Services::validation()->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
            // Form tidak valid, tampilkan halaman form dengan error message
            $response['errors'] = $validation->getErrors();
            echo $this->response(false, 400, $response);
            return;
        }

        $create = $this->model_position->save([
            'name'      => $this->request->getVar('name'),
            'status'   => $this->request->getVar('status'),
        ]);

        if (!$create) {
            echo $this->response(false, 500, 'internal server error');
        }

        echo $this->response(true, 200, 'success');
    }
    function edit_position()
    {
        # data untuk diupdate
        $data = [
            "division"  =>  $this->request->getPost('division'),
            "company"   =>  $this->request->getPost('company'),
            "name"      =>  $this->request->getPost('name'),
            "status"    =>  $this->request->getPost('status'),
        ];

        # kondisi where, diupdate berdasarkan apa ?
        $where = [
            'id'    =>  $this->request->getPost('id')
        ];

        # menggunakna fungsi built-in codeigniter
        $update = $this->model_position->update($where, $data);
        if (!$update) {
            echo $this->response(false, 500, 'failed update');
        }
        echo $this->response(true, 200, 'success update');
    }

    function list_position()
    {
        $request = Services::request();
        $datatable = new PositionDatatable($request);

        if ($request->getMethod(true) === 'POST') {
            $lists = $datatable->getDatatables();
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->division;
                $row[] = $list->company;
                $row[] = $list->name;
                $row[] = $list->status;
                $row[] = "<button id='button-edit' data-id='$list->id' class='btn btn-warning btn-sm'> Edit </button>
                <button id='button-delete' data-id='$list->id' class='btn btn-danger btn-sm'> Hapus </button>";
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $datatable->countAll(),
                'recordsFiltered' => $datatable->countFiltered(),
                'data' => $data,
            ];

            echo json_encode($output);
        }
    }

    function delete_position()
    {
        $id = $this->request->getVar('id');
        $delete = $this->model_position->delete($id);
        if (!$delete) {
            echo $this->response(false, 500, 'delete position failed');
        }
        echo $this->response(true, 200, 'delete position success');
    }

    function get_position()
    {
        header('Content-Type: application/json');
        $id = $this->request->getVar('id');
        $data = $this->model_position->find($id);
        if (!$data) {
            echo $this->response(false, 500, 'internal server error');
        }
        echo $this->response(true, 200, 'success', $data);
    }

    function data_position()
    {
        // json data array company
        // ambil data id, nama dari table company
        header('Content-Type: application/json');
        $data = $this->model_position->data();
        echo $this->response(true, 200, 'success', $data);
    }
}