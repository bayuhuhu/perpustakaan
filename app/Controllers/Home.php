<?php

namespace App\Controllers;

use Exception;
use Dompdf\Dompdf;
use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\RackModel;
use App\Models\MemberModel;
use App\Models\AbsensiModel;
use App\Models\CategoryModel;
use App\Libraries\QRGenerator;
use App\Models\BookStockModel;
use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Home extends ResourceController
{
    protected FineModel $fineModel;
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected CategoryModel $categoryModel;
    protected RackModel $rackModel;
    protected BookStockModel $bookStockModel;
    protected LoanModel $loanModel;

    public function __construct()
    {
        $this->bookModel = new BookModel;
        $this->memberModel = new MemberModel;
        $this->categoryModel = new CategoryModel;
        $this->rackModel = new RackModel;
        $this->bookStockModel = new BookStockModel;
        $this->loanModel = new LoanModel;

        helper('upload');
    }

    public function index(): string
    {
        return view('home/register-member', [
            'validation' => \Config\Services::validation()
        ]);
    }

    public function book(): string
    {
        $itemPerPage = 20;


        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->like('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->orLike('author', $keyword, insensitiveSearch: true)
                ->orLike('publisher', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'books');

            $books = array_filter($books, function ($book) {
                return $book['deleted_at'] == null;
            });
        } else {
            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->paginate($itemPerPage, 'books');

            if ($this->request->isAJAX()) {
                $param = $this->request->getVar('param');
                if (empty($param)) {
                    return json_encode(['error' => 'Parameter "kategori" tidak boleh kosong']);
                }
                $books = $this->bookModel
                    ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                    ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                    ->join('categories', 'books.category_id = categories.id', 'LEFT')
                    ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                    ->where('categories.name', $param)
                    ->paginate($itemPerPage, 'books');
                $books = array_filter($books, function ($book) {
                    return $book['deleted_at'] == null;
                });
                $data = [
                    'books'         => $books,
                    'pager'         => $this->bookModel->pager,
                    'currentPage'   => $this->request->getVar('page_books') ?? 1,
                    'itemPerPage'   => $itemPerPage,
                ];

                return view('home/book', $data);
            }
        }

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search')
        ];

        return view('home/book', $data);
    }
    public function detail($slug = null)
    {
        $book = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->where('slug', $slug)->first();

        if (empty($book)) {
            throw new PageNotFoundException('Book with slug \'' . $slug . '\' not found');
        }

        $loans = $this->loanModel->where([
            'book_id' => $book['id'],
            'return_date' => null
        ])->findAll();

        $loanCount = array_reduce(
            array_map(function ($loan) {
                return $loan['quantity'];
            }, $loans),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $bookStock = $book['quantity'] - $loanCount;

        $data = [
            'book'      => $book,
            'loanCount' => $loanCount ?? 0,
            'bookStock' => $bookStock
        ];

        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');
            if (empty($param)) return;

            $loans = $this->loanModel
                ->select('members.*, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->Where('books.slug', $param)
                ->findAll();

            $loans = array_filter($loans, function ($loan) {
                return !empty($loan['attributes']);
            });

            if (empty($loans)) {
                return view('home/return_loans_member/ulasan', ['msg' => 'Review not found']);
            }

            return view('home/return_loans_member/ulasan', ['loans' => $loans]);
        }

        return view('home/detail', $data);
    }



    public function absensiMember()
    {
        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            if (empty($param)) return;

            $members = $this->memberModel
                ->Where('uid', $param)
                ->findAll();

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });

            if (empty($members)) {
                return view('home/members/absensi', ['msg' => 'Member not found']);
            }

            return view('home/members/absensi', ['members' => $members]);
        }
        return view('home/members/absensi_member');
    }
    public function absensiMemberCreate()
    {
        $memberId = $this->request->getPost('member_id');
        $absensiModel = new AbsensiModel();

        // Siapkan data untuk disimpan
        $data = [
            'member_id' => $memberId,
            // Tambahkan data lainnya jika diperlukan
        ];

        // Simpan data absensi
        $result = $absensiModel->insert($data);

        if ($result) {
            session()->setFlashdata(['msg' => 'Absensi Was Succefully']);
            return redirect()->to('/absensi_member');
        } else {
            session()->setFlashdata(['msg' => 'Failed to Absensi    ', 'error' => true]);
            return redirect()->back();
        }
    }


    public function print($uid = null)
    {

        $member = $this->memberModel->where('uid', $uid)->first();

        if (empty($member)) {
            throw new PageNotFoundException('Member not found');
        }

        $data = [
            'member'            => $member,
        ];
        return view('home/print', $data);

        // $dompdf = new Dompdf();
        // $html = view('home/print', $data);
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'landscape');
        // $dompdf->render();
        // $dompdf->stream('member card.pdf', array(
        //     "Attachment" => false
        // ));
    }


    public function create()
    {
        // Validasi input
        if (!$this->validate([
            'first_name'        => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'         => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'             => 'required|valid_email|max_length[255]',
            'phone'             => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'address'           => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth'     => 'required|valid_date',
            'gender'            => 'required|alpha_numeric_punct',
            'type'              => 'required|alpha_numeric_punct',
            'profile_picture'   => 'is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[profile_picture,5120]'
        ])) {
            // Jika validasi gagal, kembalikan view dengan pesan kesalahan dan input sebelumnya
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('home/register_member', $data);
        }

        // Generate UID untuk member baru
        $uid = sha1(
            $this->request->getVar('first_name') .
                $this->request->getVar('email') .
                $this->request->getVar('phone') .
                rand(0, 1000) .
                md5($this->request->getVar('gender'))
        );

        // Buat QR Code untuk member baru
        $qrGenerator = new QRGenerator();
        $qrCodeLabel = $this->request->getVar('first_name') .
            ($this->request->getVar('last_name') ? ' ' . $this->request->getVar('last_name') : '');
        $qrCode = $qrGenerator->generateQRCode(
            data: $uid,
            labelText: $qrCodeLabel,
            dir: MEMBERS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        // Upload gambar profil jika tersedia
        $coverImage = $this->request->getFile('profile_picture');
        $coverImageFileName = null;

        if ($coverImage->getError() != 4) {
            $coverImageFileName = uploadUSerProfile($coverImage);
        }

        // Simpan data member baru ke database
        $memberData = [
            'uid'               => $uid,
            'first_name'        => $this->request->getVar('first_name'),
            'last_name'         => $this->request->getVar('last_name'),
            'email'             => $this->request->getVar('email'),
            'phone'             => $this->request->getVar('phone'),
            'address'           => $this->request->getVar('address'),
            'type'              => $this->request->getVar('type'),
            'date_of_birth'     => $this->request->getVar('date_of_birth'),
            'gender'            => $this->request->getVar('gender'),
            'profile_picture'   => $coverImageFileName,
            'qr_code'           => $qrCode
        ];

        if (!$this->memberModel->save($memberData)) {
            // Jika penyimpanan gagal, kembalikan view dengan pesan kesalahan dan input sebelumnya
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('home/register_member', $data);
        }

        // Jika penyimpanan berhasil, arahkan ke halaman cetak dengan UID member
        return redirect()->to("register-member/$uid/print");
    }
}
