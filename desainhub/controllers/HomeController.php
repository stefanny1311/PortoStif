<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $data = [
            'categories'   => [],
            'topDesigners' => [],
            'galeri'       => [],
        ];

        try {
            $categoryModel  = new Category();
            $data['categories'] = $categoryModel->all('nama ASC');
        } catch (\Throwable $e) {
            // database belum siap — fallback ke data view hardcoded
        }

        try {
            $designerModel = new Designer();
            $data['topDesigners'] = $designerModel->allWithUser(4);
        } catch (\Throwable $e) {
        }

        try {
            $portfolioModel = new Portfolio();
            $data['galeri'] = $portfolioModel->gallery(null, 8);
        } catch (\Throwable $e) {
        }

        $this->view('landing/home', $data, 'main');
    }

    public function about(): void
    {
        $this->view('landing/about', [], 'main');
    }

    public function services(): void
    {
        $categoryModel = new Category();
        $this->view('landing/services', ['categories' => $categoryModel->all('nama ASC')], 'main');
    }

    public function pricing(): void
    {
        $this->view('landing/pricing', [], 'main');
    }

    public function blog(): void
    {
        $this->view('landing/blog', [], 'main');
    }

    public function blogDetail(string $slug): void
    {
        $this->view('landing/blog-detail', ['slug' => $slug], 'main');
    }

    public function faq(): void
    {
        $this->view('landing/faq', [], 'main');
    }

    public function contact(): void
    {
        $this->view('landing/contact', [], 'main');
    }

    public function contactSubmit(): void
    {
        // TODO: simpan pesan kontak / kirim email
        setFlash('success', 'Pesan kamu berhasil dikirim. Tim kami akan segera menghubungi kamu.');
        $this->redirect('/contact');
    }
}
