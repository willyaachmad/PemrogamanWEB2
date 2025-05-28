<?php

namespace App\Controllers;

use App\Models\BookModel;

class Books extends BaseController
{
    protected $bukuModel;

    public function __construct()
    {
        $this->bukuModel = new BookModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Buku',
            'buku' => $this->bukuModel->findAll()
        ];

        return view('books/index', $data);
    }

    public function detail($slug)
    {
        $buku = $this->bukuModel->getBuku($slug);
        if (empty($buku)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Judul buku '$slug' tidak ditemukan.");
        }

        $data = [
            'title' => 'Detail Buku',
            'buku' => $buku
        ];

        return view('books/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Tambah Buku',
            'validation' => \Config\Services::validation()
        ];
        return view('books/create', $data);
    }

    public function save()
    {
        if (
            !$this->validate([
                'judul' => 'required|is_unique[books.judul]',
                'penulis' => 'required|is_unique[books.penulis]',
                'penerbit' => 'required|is_unique[books.penerbit]',
            ])
        ) {
            return redirect()->to('/books/create')->withInput()->with('validation', $this->validator);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->bukuModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul') ?: 'default.jpg'
        ]);

        session()->setFlashdata('pesan', 'Data buku berhasil ditambahkan.');
        return redirect()->to('/books');
    }

    public function delete($id)
    {
        $buku = $this->bukuModel->find($id);

        if ($buku['sampul'] != 'default.jpg') {
            $path = FCPATH . 'img/' . $buku['sampul'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $this->bukuModel->delete($id);

        session()->setFlashdata('pesan', 'Data buku berhasil dihapus.');
        return redirect()->to('/books');
    }
    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Buku',
            'validation' => \Config\Services::validation(),
            'buku' => $this->bukuModel->getBuku($slug)
        ];
        return view('books/edit', $data);
    }

    public function update($id)
    {
        $bukuLama = $this->bukuModel->getBuku($this->request->getVar('slug'));

        $judulRule = ($bukuLama['judul'] == $this->request->getVar('judul')) ? 'required' : 'required|is_unique[books.judul]';
        $penulisRule = ($bukuLama['penulis'] == $this->request->getVar('penulis')) ? 'required' : 'required|is_unique[books.penulis]';
        $penerbitRule = ($bukuLama['penerbit'] == $this->request->getVar('penerbit')) ? 'required' : 'required|is_unique[books.penerbit]';

        if (
            !$this->validate([
                'judul' => $judulRule,
                'penulis' => $penulisRule,
                'penerbit' => $penerbitRule,
            ])
        ) {
            return redirect()->to('/books/edit/' . $this->request->getVar('slug'))->withInput()->with('validation', $this->validator);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->bukuModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashdata('pesan', 'Data buku berhasil diubah.');
        return redirect()->to('/books');
    }

}