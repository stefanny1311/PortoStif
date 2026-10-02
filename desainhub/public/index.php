<?php
require_once dirname(__DIR__) . '/config/config.php';

$router = new Router();

/* ============ HALAMAN PUBLIK ============ */
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/services', 'HomeController@services');
$router->get('/portfolio', 'PortfolioController@index');
$router->get('/pricing', 'HomeController@pricing');
$router->get('/designer', 'DesignerController@index');
$router->get('/designer/{id}', 'DesignerController@show');
$router->post('/designer/{id}/order', 'DesignerController@order');
$router->get('/blog', 'HomeController@blog');
$router->get('/blog/{slug}', 'HomeController@blogDetail');
$router->get('/faq', 'HomeController@faq');
$router->get('/contact', 'HomeController@contact');
$router->post('/contact', 'HomeController@contactSubmit');

/* ============ AUTENTIKASI ============ */
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@forgotForm');
$router->post('/forgot-password', 'AuthController@forgotSubmit');
$router->get('/reset-password/{token}', 'AuthController@resetForm');
$router->post('/reset-password/{token}', 'AuthController@resetSubmit');

/* ============ DASHBOARD CUSTOMER ============ */
$router->get('/dashboard', 'UserDashboardController@index');
$router->get('/dashboard/orders', 'UserDashboardController@orders');
$router->get('/dashboard/orders/{id}', 'UserDashboardController@orderDetail');
$router->get('/dashboard/chat', 'UserDashboardController@chat');
$router->get('/dashboard/invoice/{id}', 'UserDashboardController@invoice');
$router->get('/dashboard/profile', 'UserDashboardController@profile');
$router->post('/dashboard/profile', 'UserDashboardController@updateProfile');
$router->get('/dashboard/settings', 'UserDashboardController@settings');

/* ============ DASHBOARD DESIGNER ============ */
$router->get('/designer-dashboard', 'DesignerDashboardController@index');
$router->get('/designer-dashboard/orders/new', 'DesignerDashboardController@newOrders');
$router->get('/designer-dashboard/orders/active', 'DesignerDashboardController@activeOrders');
$router->get('/designer-dashboard/revisions', 'DesignerDashboardController@revisions');
$router->get('/designer-dashboard/portfolio', 'DesignerDashboardController@portfolio');
$router->post('/designer-dashboard/portfolio', 'DesignerDashboardController@storePortfolio');
$router->get('/designer-dashboard/chat', 'DesignerDashboardController@chat');
$router->get('/designer-dashboard/earnings', 'DesignerDashboardController@earnings');
$router->post('/designer-dashboard/withdraw', 'DesignerDashboardController@withdraw');
$router->get('/designer-dashboard/settings', 'DesignerDashboardController@settings');

/* ============ DASHBOARD ADMIN ============ */
$router->get('/admin', 'AdminDashboardController@index');
$router->get('/admin/users', 'AdminDashboardController@users');
$router->get('/admin/designers', 'AdminDashboardController@designers');
$router->get('/admin/designers/verify/{id}', 'AdminDashboardController@verifyDesigner');
$router->get('/admin/portfolio/verify/{id}', 'AdminDashboardController@verifyPortfolio');
$router->get('/admin/orders', 'AdminDashboardController@orders');
$router->get('/admin/chat', 'AdminDashboardController@chat');
$router->get('/admin/payments', 'AdminDashboardController@payments');
$router->get('/admin/reviews', 'AdminDashboardController@reviews');
$router->get('/admin/blog', 'AdminDashboardController@blog');
$router->get('/admin/statistics', 'AdminDashboardController@statistics');

/* ============ CHAT API ============ */
$router->get('/api/chat/conversations', 'ChatController@getConversations');
$router->get('/api/chat/messages/{id}', 'ChatController@getMessages');
$router->post('/api/chat/send', 'ChatController@sendMessage');
$router->get('/api/chat/orders-for-chat', 'ChatController@getAvailableOrders');
$router->get('/api/chat/unread-count', 'ChatController@getUnreadCount');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
