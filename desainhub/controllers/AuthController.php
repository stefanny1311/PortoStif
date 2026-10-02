<?php
class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login', [], 'main');
    }

    public function login(): void
    {
        $email = $this->input('email');
        $password = $this->input('password');

        $userModel = new User();
        $user = $userModel->findBy('email', $email);

        if ($user && password_verify($password, $user['password'])) {
            Auth::login($user);
            $redirectMap = [
                'admin' => '/admin',
                'designer' => '/designer-dashboard',
                'customer' => '/dashboard',
            ];
            $this->redirect($redirectMap[$user['role']] ?? '/dashboard');
        }

        setFlash('error', 'Email atau password salah.');
        $this->redirect('/login');
    }

    public function registerForm(): void
    {
        $this->view('auth/register', [], 'main');
    }

    public function register(): void
    {
        $nama = $this->input('nama');
        $email = $this->input('email');
        $password = $this->input('password');
        $role = $this->input('role', 'customer');

        if (empty($nama) || empty($email) || empty($password)) {
            setFlash('error', 'Semua field wajib diisi.');
            $this->redirect('/register');
        }

        $userModel = new User();
        if ($userModel->findBy('email', $email)) {
            setFlash('error', 'Email sudah terdaftar.');
            $this->redirect('/register');
        }

        $userId = $userModel->create([
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        // Jika role designer, buat record designer
        if ($role === 'designer') {
            $designerModel = new Designer();
            $designerModel->create(['user_id' => $userId, 'status' => 'pending']);
        }

        setFlash('success', 'Registrasi berhasil! Silakan login.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }

    public function forgotForm(): void
    {
        $this->view('auth/forgot-password', [], 'main');
    }

    public function forgotSubmit(): void
    {
        setFlash('success', 'Jika email terdaftar, link reset password telah dikirim.');
        $this->redirect('/login');
    }

    public function resetForm(string $token): void
    {
        $this->view('auth/reset-password', ['token' => $token], 'main');
    }

    public function resetSubmit(string $token): void
    {
        setFlash('success', 'Password berhasil direset. Silakan login.');
        $this->redirect('/login');
    }
}