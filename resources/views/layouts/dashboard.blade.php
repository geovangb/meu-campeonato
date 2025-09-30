<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */
?>

    <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="text-center py-3">Admin</h4>
    <a href="{{ route('products.index') }}">📦 Produtos</a>
    <a href="{{ route('products.create') }}">➕ Novo Produto</a>
    <a href="{{ route('orders.index') }}"><i class="bi bi-clipboard-check"></i> Pedidos</a>
    <a href="{{ route('coupons.index') }}"><i class="bi bi-ticket-perforated"></i> Cupons</a>
</div>

<div class="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
