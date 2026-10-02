<?php
class PortfolioController extends Controller
{
    public function index(): void
    {
        try {
            $portfolioModel = new Portfolio();
            $galeri = $portfolioModel->gallery(null, 20);
        } catch (\Throwable $e) {
            $galeri = [];
        }
        $this->view('landing/portfolio', ['galeri' => $galeri], 'main');
    }
}