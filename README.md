# Sistema ERP – Gestão de Produtos e Pedidos

Este sistema ERP foi desenvolvido para gerenciar o cadastro de **produtos**, **pedidos**, **cupons de desconto**, **regras de frete** e **controle de estoque**.

---

## 🔐 Login

Para acessar o sistema, é necessário realizar o login com um usuário previamente cadastrado.

### Criar um usuário manualmente (via Tinker):

```bash
php artisan tinker

>>> \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@erp.test',
    'password' => bcrypt('senha_segura')
]);
```

---

## ✅ Funcionalidades Principais

### 💼 Cadastro de Produtos

* Suporte a variantes
* Controle de estoque (mínimo e atual)
* Preço original e promocional
* Upload de imagem

### 🛒 Pedidos

* Cadastro de cliente no momento do pedido
* Escolha de método de pagamento
* Aplicação de cupons com regras (validade e valor mínimo)
* Cálculo de frete com base no estado e subtotal
* Armazenamento dos pedidos com seus itens

### 🎉 Cupons

* Criação com valor de desconto fixo
* Regras de valor mínimo de carrinho
* Validade por data

### 🛘 Carrinho

* Adição e remoção de itens
* Atualização de quantidade
* Aplicação de cupom
* Cálculo automático de subtotal, frete e total

---

## 🌐 Tecnologias Utilizadas

* Laravel 10+
* Blade + Bootstrap
* PHPUnit para testes
* MySQL

---

## ⚙️ Instalação Local

```bash
git clone https://github.com/seu-usuario/seu-repo.git
cd seu-repo
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

---

## 🎓 Testes

Para rodar os testes unitários:

```bash
php artisan test
```

---

## 📅 Licença

Este projeto está licenciado sob a Licença MIT.
