# MeuCampeonato 1.0

Sistem desenvolvido para gerenciar os campeonatos

---

## ⚙️ Instalação Local

```bash
git clone https://github.com/geovangb/meu-campeonato.git
cd meu-campeonato
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

## 🔐 Login

Para acessar o sistema, é necessário realizar o login com um usuário previamente cadastrado.

### Criar um usuário manualmente (via Tinker):

```bash
php artisan tinker

>>> \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@campeonato.test',
    'password' => bcrypt('senha_segura')
]);
```

---

## ✅ Funcionalidades Principais

### 💼 Cadastrar Campeonatos

* Cadastrar e configurar Campeonatos
* Sortear Jogos e faze final

### 🛒 Times

* Cadastrar Times;
* Gerenciar escalações dos jogos


### Melhorias
* Implementar o front com Angular
* Melhorar a organização e tipo do campenato


## 🌐 Tecnologias Utilizadas

* Laravel 10+
* Blade + Bootstrap
* PHPUnit para testes
* MySQL

---

## 🎓 Testes

Para rodar os testes unitários:

```bash
php artisan test
```

---

## 📅 Licença

GB Developer
